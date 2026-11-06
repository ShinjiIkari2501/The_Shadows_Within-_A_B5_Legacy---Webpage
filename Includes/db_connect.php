<?php
/**
 * db_connect.php
 * Central Database Connection Manager for 'The Shadows Within - A B5 Legacy'
 * Every page that needs the database includes this file and then simply uses the variable $pdo.
 *
 * WHERE IS THE DATABASE?
 *  - Normally here: <project folder>/Database/b5_project.sqlite
 *  - On Render you can set the environment variable B5_DB_PATH, for example to
 *    /var/data/b5_project.sqlite (a "persistent disk"). This matters because the normal file
 *    system of a Render container is wiped on every deploy/restart: a database stored there
 *    loses all registered users and save games each time.
 *  - If the file in B5_DB_PATH does not exist yet (fresh disk), it is created as a copy of
 *    Database/b5_project.sqlite. An existing file is never overwritten.
 *
 * BUG THAT WAS FIXED HERE
 * The old version looked for 'Includes/b5_project.sqlite'. That file does not exist, so SQLite
 * silently created an EMPTY one and every query failed with "no such table: users"
 * (this broke the registration and the database part of the login).
 */

// dirname(__DIR__) = the folder one level above this file (the project root).
$seedFile = dirname(__DIR__) . '/Database/b5_project.sqlite';

// getenv() reads an environment variable. It returns false if the variable is not set.
$envPath = getenv('B5_DB_PATH');
if ($envPath !== false && $envPath !== '') {
    $sqlite_file = $envPath;
} else {
    $sqlite_file = $seedFile;
}

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // database errors become exceptions we can catch
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // rows come back as arrays like $row['username']
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
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

} catch (Exception $e) {
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
