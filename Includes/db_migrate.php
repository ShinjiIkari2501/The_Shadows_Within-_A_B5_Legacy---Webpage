<?php
/**
 * db_migrate.php - brings the SQLite database up to date (a "migration")
 *
 * WHY THIS FILE EXISTS
 * The database file is created once (and on Render it lives on a persistent disk). When the game
 * gets new features, the existing file needs new columns, tables and story scenes. Deleting the
 * file is not an option, because all users and save games would be lost. A migration changes the
 * existing database step by step and keeps all data.
 *
 * HOW IT KNOWS WHAT IS ALREADY DONE
 * SQLite has a built-in number called "user_version" (PRAGMA user_version). A new database has 0.
 * After step 1 has run it is set to 1. On the next start the number says "step 1 is done" and
 * nothing happens. To add a step 2 later: write b5_migration_2(), call it below and raise
 * B5_SCHEMA_VERSION to 2.
 *
 * It is safe to call b5_migrate() on EVERY request: when nothing is to do it costs one tiny query.
 */

const B5_SCHEMA_VERSION = 9;

/** Entry point, called by db_connect.php right after the connection is open. */
function b5_migrate(PDO $pdo)
{
    if ($pdo->getAttribute(PDO::ATTR_DRIVER_NAME) !== 'sqlite') {
        return; // MySQL: the database is created by importing Database/b5_mysql_import.sql
    }
    $version = (int)$pdo->query('PRAGMA user_version')->fetchColumn();
    if ($version >= B5_SCHEMA_VERSION) {
        return; // nothing to do
    }

    // BEGIN IMMEDIATE locks the database for writing right away. If two visitors arrive at the
    // same moment on a fresh deploy, the second one waits and then sees "already done".
    $pdo->exec('BEGIN IMMEDIATE');
    try {
        $version = (int)$pdo->query('PRAGMA user_version')->fetchColumn(); // read again inside the lock
        if ($version < 1) {
            b5_migration_1($pdo);
            $pdo->exec('PRAGMA user_version = 1');
        }
        // The story content (migration 2) is applied again whenever act3_content.php changed. Every time the
        // content file is edited, raise this number by one (and B5_SCHEMA_VERSION above), so that databases which
        // already ran an older content file get the new texts. Applying the content twice does no harm.
        if ($version < 9) {
            b5_migration_2($pdo);
            $pdo->exec('PRAGMA user_version = 9');
        }
        $pdo->exec('COMMIT');
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->exec('ROLLBACK'); // undo everything: the database stays as it was
        }
        throw $e;
    }
}

/** Returns true if the table already has a column with this name. */
function b5_has_column(PDO $pdo, $table, $column)
{
    foreach ($pdo->query("PRAGMA table_info($table)")->fetchAll() as $info) {
        if ($info['name'] === $column) {
            return true;
        }
    }
    return false;
}

/** Turns a PHP list into JSON text for the database. An empty list is stored as NULL ("no rules"). */
function b5_json_or_null($list)
{
    if (!is_array($list) || count($list) === 0) {
        return null;
    }
    return json_encode(array_values($list));
}

/**
 * Migration 1: the new database structure of the rules engine.
 *  - new column player_slots.story_flags
 *  - new columns story_choices.requires / sets / locked_hint / route
 *  - new table story_node_text (text variants)
 */
function b5_migration_1(PDO $pdo)
{
    // ---- 1. new columns (only if they do not exist yet) ----
    if (!b5_has_column($pdo, 'player_slots', 'story_flags')) {
        $pdo->exec("ALTER TABLE player_slots ADD COLUMN story_flags TEXT DEFAULT '{}'");
    }
    foreach (['requires', 'sets', 'locked_hint', 'route'] as $column) {
        if (!b5_has_column($pdo, 'story_choices', $column)) {
            $pdo->exec("ALTER TABLE story_choices ADD COLUMN $column TEXT DEFAULT NULL");
        }
    }

    // ---- 2. new table for scene text variants ----
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS story_node_text (
            text_id  INTEGER PRIMARY KEY,
            node_id  INTEGER NOT NULL,
            priority INTEGER NOT NULL DEFAULT 0,
            requires TEXT DEFAULT NULL,
            body     TEXT NOT NULL,
            mode     TEXT NOT NULL DEFAULT 'replace',
            FOREIGN KEY (node_id) REFERENCES story_nodes(node_id) ON DELETE CASCADE
        )"
    );
}

/**
 * Migration 2: the story content (Act III scenes, choices, text variants) from act3_content.php.
 * It can safely run on a database that already holds an older version of the same content,
 * because every row is written with "update if it exists" and the variants are rewritten as a whole.
 */
function b5_migration_2(PDO $pdo)
{
    $pdo->exec("DELETE FROM story_node_text"); // the table only holds data from act3_content.php, so a clean start is safe

    $content = require __DIR__ . '/act3_content.php';

    // Choices that older versions of the content file created and that were removed since then.
    if (!empty($content['removed'])) {
        $remove = $pdo->prepare("DELETE FROM story_choices WHERE choice_id = ?");
        foreach ($content['removed'] as $oldChoiceId) {
            $remove->execute([$oldChoiceId]);
        }
    }

    // ---- 3. scenes ----
    // "ON CONFLICT ... DO UPDATE" changes an existing scene IN PLACE. (A plain REPLACE would delete the
    // old row first, and because of the foreign keys that would delete its choices and save games too!)
    $nodeStmt = $pdo->prepare(
        "INSERT INTO story_nodes (node_id, title, description, active_sector, is_ending)
         VALUES (?, ?, ?, ?, ?)
         ON CONFLICT(node_id) DO UPDATE SET
             title = excluded.title, description = excluded.description,
             active_sector = excluded.active_sector, is_ending = excluded.is_ending"
    );
    foreach ($content['nodes'] as $n) {
        $nodeStmt->execute([$n[0], $n[1], $n[4], $n[2], $n[3]]);
    }

    // ---- 4. new choices ----
    $choiceStmt = $pdo->prepare(
        "INSERT INTO story_choices
            (choice_id, from_node_id, next_node_id, choice_text, effect_credits, effect_analysis_focus,
             effect_origin, requires, sets, locked_hint, route)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
         ON CONFLICT(choice_id) DO UPDATE SET
             from_node_id = excluded.from_node_id, next_node_id = excluded.next_node_id,
             choice_text = excluded.choice_text, effect_credits = excluded.effect_credits,
             effect_analysis_focus = excluded.effect_analysis_focus, effect_origin = excluded.effect_origin,
             requires = excluded.requires, sets = excluded.sets,
             locked_hint = excluded.locked_hint, route = excluded.route"
    );
    foreach ($content['choices'] as $c) {
        $choiceStmt->execute([
            $c[0], $c[1], $c[2], $c[3], $c[4], $c[5], $c[6],
            b5_json_or_null($c[7]), b5_json_or_null($c[8]), $c[9], $c[10],
        ]);
    }

    // ---- 5. changes to choices that already exist ----
    $allowed = ['from_node_id', 'next_node_id', 'choice_text', 'requires', 'sets', 'locked_hint', 'route'];
    foreach ($content['updates'] as $choiceId => $changes) {
        $parts  = [];
        $values = [];
        foreach ($changes as $column => $value) {
            if (!in_array($column, $allowed, true)) {
                throw new Exception("Migration: column $column must not be changed.");
            }
            if ($column === 'requires' || $column === 'sets') {
                $value = b5_json_or_null($value);
            }
            $parts[]  = "$column = ?";
            $values[] = $value;
        }
        $values[] = $choiceId;
        $pdo->prepare("UPDATE story_choices SET " . implode(', ', $parts) . " WHERE choice_id = ?")->execute($values);
    }

    // ---- 6. text variants ----
    $variantStmt = $pdo->prepare("INSERT INTO story_node_text (node_id, priority, requires, mode, body) VALUES (?, ?, ?, ?, ?)");
    foreach ($content['variants'] as $v) {
        $variantStmt->execute([$v[0], $v[1], b5_json_or_null($v[2]), $v[3], $v[4]]);
    }

    // ---- 7. the long story texts from storyscript.php ----
    // storyscript.php holds the written film-script texts. Its keys are SCENE numbers (1, 2, 3 ... and 'SETUP'),
    // but the database nodes have other ids (scene 1 = node 100). This list says which script entry belongs to
    // which node: node id => key in storyscript.php.
    // Only nodes WITHOUT an own text in act3_content.php are listed here. The other scenes (hub, Centauri, Narn,
    // Lyta, Mars ...) were rewritten for the Act III decisions and keep the text from act3_content.php.
    // The text variants from step 6 are still added on top of the script text when the game runs.
    $script = require __DIR__ . '/storyscript.php';
    $scriptNodes = [
        10  => 'SETUP',
        100 => 1,  101 => 2,  102 => 3,  103 => 4,  104 => 5,
        105 => 6,  106 => 7,  107 => 8,  108 => 9,  109 => 10,
        113 => 14,
        123 => 27, 124 => 28, 125 => 29, 126 => 30, 127 => 31,
    ];
    $scriptStmt = $pdo->prepare("UPDATE story_nodes SET description = ?, active_sector = ? WHERE node_id = ?");
    foreach ($scriptNodes as $nodeId => $scriptKey) {
        if (!isset($script[$scriptKey])) {
            throw new Exception("Migration: storyscript.php has no entry '$scriptKey' (node $nodeId).");
        }
        $entry = $script[$scriptKey];
        // the location line is optional; keep the old sector if the script has none
        $sector = $entry['location'] ?? $pdo->query("SELECT active_sector FROM story_nodes WHERE node_id = " . (int)$nodeId)->fetchColumn();
        $scriptStmt->execute([trim($entry['script']), $sector, $nodeId]);
    }

    // ---- 8. small text fixes in old scenes ----
    // mixed German/English wording in scene 13
    $pdo->exec("UPDATE story_nodes SET description = replace(description, 'gewaltige,', 'massive,') WHERE node_id = 112");
    // Lore: the Drakh plague belongs to 2267 (Crusade). In 2264 Bester uses Drakh biotechnology. That it goes
    // back to a Shadow device is a secret that is only revealed on Mars (scene 24b), so scene 14 only hints at it.
    $pdo->exec("UPDATE story_nodes SET description = replace(description, 'variant of the Drakh plague', 'variant of Drakh biotechnology') WHERE node_id = 113");

    // The original scenes were saved with the two characters backslash + n instead of a real line break,
    // so the player would see a visible "\n". Turn them into real line breaks (char(92) = backslash, char(10) = line break).
    $pdo->exec("UPDATE story_nodes SET description = replace(description, char(92) || 'n', char(10))");
    $pdo->exec("UPDATE story_choices SET choice_text = replace(choice_text, char(92) || 'n', char(10))");
}
