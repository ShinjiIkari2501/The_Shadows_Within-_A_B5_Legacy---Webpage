<?php
/**
 * db_connect.php
 * Central Database Connection Manager for 'The Shadows Within - A B5 Legacy'
 * Every page that needs the database includes this file and then simply uses the variable $pdo.
 *
 * WHICH DATABASE IS USED?
 *  1. MySQL, if the file Includes/config.php exists. You create this file ON THE SERVER (copy
 *     Includes/config.sample.php and fill in the data from the hosting panel). It is not part of the
 *     GitHub repository, so the database password never becomes public.
 *  2. Otherwise SQLite (the file Database/b5_project.sqlite), like before. This is handy on your own
 *     computer, because you do not need a MySQL server for testing.
 *
 * MYSQL: the tables and the story are created once by importing Database/b5_mysql_import.sql
 * (in phpMyAdmin). The automatic updater (db_migrate.php) only works for SQLite.
 */

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // database errors become exceptions we can catch
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // rows come back as arrays like $row['username']
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $configFile = __DIR__ . '/config.php';

    if (is_file($configFile)) {
        // ---------------------------------------------------------------- MySQL
        $cfg = require $configFile; // returns an array: host, name, user, pass
        $dsn = 'mysql:host=' . $cfg['host'] . ';dbname=' . $cfg['name'] . ';charset=utf8mb4';
        $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], $options);

        // Friendly hint in the server log if the import was forgotten.
        $tableCheck = $pdo->query("SHOW TABLES LIKE 'story_nodes'")->fetchColumn();
        if ($tableCheck === false) {
            throw new Exception('Tables are missing. Import Database/b5_mysql_import.sql in phpMyAdmin first.');
        }
    } else {
        // ---------------------------------------------------------------- SQLite
        // dirname(__DIR__) = the folder one level above this file (the project root).
        $seedFile = dirname(__DIR__) . '/Database/b5_project.sqlite';

        // getenv() reads an environment variable. It returns false if the variable is not set.
        $envPath = getenv('B5_DB_PATH');
        $sqlite_file = ($envPath !== false && $envPath !== '') ? $envPath : $seedFile;

        // If the database file is missing, SQLite would quietly create an empty one.
        // Prevent that: copy the real database from the project instead.
        if (!is_file($sqlite_file)) {
            if (!is_file($seedFile)) {
                throw new Exception("Seed database not found: $seedFile");
            }
            $targetDir = dirname($sqlite_file);
            if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true)) {
                throw new Exception("Cannot create database directory: $targetDir");
            }
            if (!copy($seedFile, $sqlite_file)) {
                throw new Exception("Cannot initialise database file: $sqlite_file");
            }
        }

        $pdo = new PDO("sqlite:$sqlite_file", null, null, $options);

        // SQLite ignores FOREIGN KEY rules (like ON DELETE CASCADE) unless this is switched on.
        $pdo->exec('PRAGMA foreign_keys = ON');
        // If another request is writing at the same moment, wait up to 5 seconds instead of failing.
        $pdo->exec('PRAGMA busy_timeout = 5000');

        // Bring the database up to date (new columns, tables and story scenes). This does nothing
        // if the database is already current. See db_migrate.php for how it works.
        require_once __DIR__ . '/db_migrate.php';
        b5_migrate($pdo);
    }

} catch (Throwable $e) {
    // The technical reason only goes to the server log, not to the browser.
    error_log('[db_connect] ' . $e->getMessage());

    header("Content-Type: application/json; charset=UTF-8");
    http_response_code(500);
    echo json_encode([
        "status"        => "error",
        "error_message" => "SECURITY PHALANX ERROR: Connection link to database backbone decoupled."
    ]);
    exit;
}
?>
