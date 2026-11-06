<?php
/**
 * Statistics.php - evaluations of the game and the database.
 *   every logged-in player: own save games + anonymous totals of all players
 *   admins:                 additionally users, database browser, SQL console, backup (Includes/stats_admin.php)
 * Admin rights are checked on the server for every request (Includes/auth.php), not only hidden in the menu.
 */
require_once 'Includes/layout.php';
require_once 'Includes/auth.php';
require_once 'Includes/stats_admin.php';

$loggedIn = b5_is_logged_in();
$isAdmin  = $loggedIn && b5_is_admin();
$pdo      = null;

if ($loggedIn) {
    $pdo = b5_pdo();
    if ($isAdmin) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['op'] ?? '') !== 'sql') {
            b5_handle_admin_post($pdo);          // ends with a redirect
        }
        if (($_GET['download'] ?? '') === 'backup') {
            b5_send_backup($pdo);                // ends the request
        }
    }
}

/** Horizontal bar chart in plain HTML/CSS: one hue, value written at the bar, table view below. */
function b5_bars(string $title, array $rows, string $unit = ''): string {
    $max = 0;
    foreach ($rows as $r) { $max = max($max, (float)$r[1]); }
    $o = '<section class="panel chart"><h4>' . b5_h($title) . '</h4>';
    if (!$rows) {
        return $o . '<p class="muted">No data yet.</p></section>';
    }
    $o .= '<div class="bars" role="list">';
    foreach ($rows as [$label, $value]) {
        $w = $max > 0 ? round(100 * (float)$value / $max, 1) : 0;
        $o .= '<div class="bar-row" role="listitem" title="' . b5_h($label) . ': ' . b5_h($value) . b5_h($unit) . '">'
            . '<span class="bar-label">' . b5_h($label) . '</span>'
            . '<span class="bar-track"><span class="bar-fill" style="width:' . $w . '%"></span></span>'
            . '<span class="bar-value">' . b5_h($value) . b5_h($unit) . '</span></div>';
    }
    $o .= '</div><details><summary>Table view</summary><div class="table-wrap"><table class="data"><tbody>';
    foreach ($rows as [$label, $value]) {
        $o .= '<tr><td>' . b5_h($label) . '</td><td>' . b5_h($value) . b5_h($unit) . '</td></tr>';
    }
    return $o . '</tbody></table></div></details></section>';
}

function b5_tile(string $label, $value): string {
    return '<div class="stat-card"><div class="stat-value">' . b5_h($value) . '</div><div class="stat-label">' . b5_h($label) . '</div></div>';
}

ob_start();

if (!$loggedIn) {
    echo '<h2>Statistics</h2><h3>Access restricted</h3><div class="notice"><p>Please log in with the ID-Card-Check on the left to see the statistics.</p></div>';
} else {
    $uid  = (int)$_SESSION['user_id'];
    $view = (string)($_GET['view'] ?? ($_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['view'] ?? 'overview') : 'overview'));
    $adminViews = ['users' => 'Users', 'db' => 'Database', 'sql' => 'SQL console'];
    if (!$isAdmin || !isset($adminViews[$view])) {
        $view = 'overview';
    }

    echo '<h2>Statistics</h2><h3>' . ($isAdmin ? 'Administrator access: full database' : 'Your game and the Alliance totals') . '</h3>';

    if ($isAdmin) {
        echo '<div class="chips"><a class="chip' . ($view === 'overview' ? ' active' : '') . '" href="Statistics.php">Overview</a>';
        foreach ($adminViews as $k => $label) {
            echo '<a class="chip' . ($view === $k ? ' active' : '') . '" href="?view=' . $k . '">' . $label . '</a>';
        }
        echo '<a class="chip" href="?download=backup">Backup download</a></div>';
    }

    if ($view === 'users') {
        echo b5_admin_users($pdo);
    } elseif ($view === 'db') {
        echo b5_admin_db($pdo);
    } elseif ($view === 'sql') {
        echo b5_admin_sql($pdo);
    } else {
        /* ---------------- overview (everyone) ---------------- */
        $one = fn(string $sql, array $p = []) => (function () use ($pdo, $sql, $p) { $s = $pdo->prepare($sql); $s->execute($p); return $s->fetchColumn(); })();
        $all = fn(string $sql, array $p = []) => (function () use ($pdo, $sql, $p) { $s = $pdo->prepare($sql); $s->execute($p); return $s->fetchAll(PDO::FETCH_NUM); })();

        // ---- own save games ----
        $mine = $pdo->prepare("SELECT s.slot_number, s.character_origin, s.player_hp, s.credits, s.last_updated, n.title, n.active_sector, n.is_ending,
                                      (SELECT COUNT(*) FROM player_crew c WHERE c.slot_id = s.slot_id AND c.is_alive = 1) AS crew
                               FROM player_slots s LEFT JOIN story_nodes n ON n.node_id = s.current_node_id
                               WHERE s.user_id = ? ORDER BY s.slot_number");
        $mine->execute([$uid]);
        $mine = $mine->fetchAll();
        $myChoices = (int)$one("SELECT COUNT(*) FROM player_history WHERE user_id = ?", [$uid]);

        echo '<h4>Your save games</h4><div class="stat-grid">'
            . b5_tile('Save slots in use', count($mine)) . b5_tile('Choices you made', $myChoices)
            . b5_tile('Endings reached', count(array_filter($mine, fn($r) => $r['is_ending']))) . '</div>';
        if ($mine) {
            echo '<div class="table-wrap"><table class="data"><thead><tr><th>Slot</th><th>Class</th><th>Position</th><th>HP</th><th>Credits</th><th>Crew alive</th><th>Last played</th></tr></thead><tbody>';
            foreach ($mine as $r) {
                echo '<tr><td>' . (int)$r['slot_number'] . '</td><td>' . b5_h($r['character_origin']) . '</td><td>' . b5_h($r['title']) . ($r['is_ending'] ? ' <strong>(ENDING)</strong>' : '') . '</td>'
                    . '<td>' . (int)$r['player_hp'] . '</td><td>' . number_format((int)$r['credits']) . '</td><td>' . (int)$r['crew'] . '</td><td>' . b5_h($r['last_updated']) . '</td></tr>';
            }
            echo '</tbody></table></div>';
        } else {
            echo '<div class="notice"><p>You have not started a game yet. Open the Gameplay terminal to begin.</p></div>';
        }

        $recent = $all("SELECT strftime('%Y-%m-%d %H:%M', h.selected_at), c.choice_text FROM player_history h JOIN story_choices c ON c.choice_id = h.chosen_choice_id WHERE h.user_id = ? ORDER BY h.history_id DESC LIMIT 8", [$uid]);
        if ($recent) {
            echo '<details class="panel"><summary>Your last choices</summary><div class="table-wrap"><table class="data"><tbody>';
            foreach ($recent as [$t, $txt]) { echo '<tr><td>' . b5_h($t) . '</td><td>' . b5_h($txt) . '</td></tr>'; }
            echo '</tbody></table></div></details>';
        }

        // ---- anonymous totals of all players ----
        $players = (int)$one("SELECT COUNT(*) FROM users");
        $slots   = (int)$one("SELECT COUNT(*) FROM player_slots");
        $choices = (int)$one("SELECT COUNT(*) FROM player_history");
        $ends    = (int)$one("SELECT COUNT(*) FROM player_slots s JOIN story_nodes n ON n.node_id = s.current_node_id WHERE n.is_ending = 1");
        echo '<h4>All players, anonymous</h4><div class="stat-grid">' . b5_tile('Registered players', $players) . b5_tile('Save games', $slots)
            . b5_tile('Choices made', $choices) . b5_tile('Endings reached', $ends)
            . b5_tile('Average credits', number_format((float)$one("SELECT COALESCE(AVG(credits),0) FROM player_slots"))) . '</div><div class="chart-grid">';

        echo b5_bars('Endings reached', $all("SELECT n.title, COUNT(*) FROM player_slots s JOIN story_nodes n ON n.node_id = s.current_node_id WHERE n.is_ending = 1 GROUP BY n.node_id ORDER BY 2 DESC"));
        echo b5_bars('Character classes', $all("SELECT character_origin, COUNT(*) FROM player_slots GROUP BY 1 ORDER BY 2 DESC"));
        echo b5_bars('Where the save games are now (sector)', $all("SELECT n.active_sector, COUNT(*) FROM player_slots s JOIN story_nodes n ON n.node_id = s.current_node_id GROUP BY 1 ORDER BY 2 DESC LIMIT 10"));
        echo b5_bars('Most chosen options', $all("SELECT c.choice_text, COUNT(*) FROM player_history h JOIN story_choices c ON c.choice_id = h.chosen_choice_id GROUP BY c.choice_id ORDER BY 2 DESC, 1 LIMIT 8"));
        echo b5_bars('Crew survival', $all("SELECT CASE is_alive WHEN 1 THEN 'alive' ELSE 'lost' END, COUNT(*) FROM player_crew GROUP BY 1"));
        echo '</div>';

        if ($isAdmin) {
            // ---- admin extras ----
            echo '<h4>Database, admin</h4><div class="chart-grid">';
            $tbl = [];
            foreach (b5_tables($pdo) as $t) { $tbl[] = [$t, (int)$pdo->query('SELECT COUNT(*) FROM ' . b5_q($t))->fetchColumn()]; }
            echo b5_bars('Rows per table', $tbl);
            echo b5_bars('Choices per player', $all("SELECT u.username, COUNT(h.history_id) FROM users u LEFT JOIN player_history h ON h.user_id = u.user_id GROUP BY u.user_id ORDER BY 2 DESC"));
            echo '</div>';
            $file = $pdo->query('PRAGMA database_list')->fetch()['file'] ?? '';
            echo '<p class="muted">Database file: ' . b5_h(basename($file)) . ' · ' . (is_file($file) ? number_format(filesize($file) / 1024, 1) . ' KB' : '?') . ' · SQLite ' . b5_h($pdo->query('SELECT sqlite_version()')->fetchColumn()) . '</p>';
        }
    }
}

$content = ob_get_clean();
renderLayout('B5 Legacy - Statistics', $content);
