<?php
/**
 * check.php - one-time server check for the move to a new host (e.g. InfinityFree).
 * Open it once in the browser, read the result, then DELETE this file.
 */
header('Content-Type: text/plain; charset=UTF-8');
function line($ok, $text) { echo ($ok ? '[ OK ] ' : '[FAIL] ') . $text . "\n"; }

echo "=== B5 server check ===\n\n";
line(version_compare(PHP_VERSION, '8.0.0', '>='), 'PHP version: ' . PHP_VERSION . ' (8.0 or newer needed)');

$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
line($https, 'Page is opened over https (if FAIL: login still works, but activate the free SSL certificate in the panel)');
line(is_file(__DIR__ . '/Includes/storyscript.php'), 'Includes/storyscript.php exists');

$configFile = __DIR__ . '/Includes/config.php';
echo "\n";
if (is_file($configFile)) {
    echo "--- MySQL (Includes/config.php found) ---\n";
    line(extension_loaded('pdo_mysql'), 'PDO MySQL driver loaded');
    try {
        $cfg = require $configFile;
        $pdo = new PDO('mysql:host=' . $cfg['host'] . ';dbname=' . $cfg['name'] . ';charset=utf8mb4', $cfg['user'], $cfg['pass'],
                       [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        line(true, 'Connection works. Server version: ' . $pdo->query('SELECT VERSION()')->fetchColumn());

        $needed = ['users', 'story_nodes', 'story_choices', 'story_node_text', 'player_slots', 'player_history', 'player_crew', 'player_visited_locations', 'items'];
        $have = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
        $missing = array_diff($needed, $have);
        line(count($missing) === 0, 'All 9 tables exist' . (count($missing) ? ' - missing: ' . implode(', ', $missing) . ' (import Database/b5_mysql_import.sql in phpMyAdmin)' : ''));
        if (!count($missing)) {
            echo "      story scenes:  " . $pdo->query('SELECT COUNT(*) FROM story_nodes')->fetchColumn() . " (expected 54)\n";
            echo "      story choices: " . $pdo->query('SELECT COUNT(*) FROM story_choices')->fetchColumn() . " (expected 99)\n";
            echo "      text variants: " . $pdo->query('SELECT COUNT(*) FROM story_node_text')->fetchColumn() . " (expected 37)\n";
            echo "      users:         " . $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() . "\n";
            $engine = $pdo->query("SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'player_slots'")->fetchColumn();
            line(strtoupper((string)$engine) === 'INNODB', "Table engine of player_slots: $engine (InnoDB needed for saving)");
        }
    } catch (Throwable $e) {
        line(false, 'MySQL test failed: ' . $e->getMessage());
    }
} else {
    echo "--- SQLite (no Includes/config.php) ---\n";
    line(extension_loaded('pdo_sqlite'), 'PDO SQLite driver loaded');
    $dbDir  = __DIR__ . '/Database';
    $dbFile = $dbDir . '/b5_project.sqlite';
    line(is_file($dbFile), 'Database file exists');
    line(is_writable($dbDir), 'Database folder is writable');
    line(is_writable($dbFile), 'Database file is writable');
    echo "Hint: for MySQL copy Includes/config.sample.php to Includes/config.php and fill in the data.\n";
}
echo "\nDelete check.php now.\n";
