<?php
/**
 * stats_admin.php - admin tools of Statistics.php: user management, database browser,
 * SQL console, backup. Only ever loaded after b5_is_admin() returned true.
 * Safety: CSRF token on every POST, table/column names only from a whitelist read from the
 * database itself, values always bound as parameters, password hashes never shown.
 */

function b5_q(string $identifier): string {
    return '"' . str_replace('"', '""', $identifier) . '"';
}

function b5_h($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function b5_tables(PDO $pdo): array {
    return $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
}

function b5_columns(PDO $pdo, string $table): array {
    return $pdo->query('PRAGMA table_info(' . b5_q($table) . ')')->fetchAll();
}

function b5_flash(string $msg, bool $error = false): void {
    $_SESSION[$error ? 'flash_message_error' : 'flash_message'] = b5_h($msg);
}

function b5_redirect(string $query): void {
    header('Location: Statistics.php' . $query);
    exit;
}

/** A cell for display: hashes are masked, long values shortened. */
function b5_cell(string $column, $value): string {
    if ($value === null) {
        return '<em>NULL</em>';
    }
    if (stripos($column, 'password') !== false) {
        return '••••••••';
    }
    $v = (string)$value;
    return b5_h(strlen($v) > 80 ? substr($v, 0, 80) . '...' : $v);
}

/** Handles all admin POST requests. Always ends with a redirect. */
function b5_handle_admin_post(PDO $pdo): void {
    if (!b5_csrf_ok()) {
        b5_flash('Security token invalid. Please try again.', true);
        b5_redirect('');
    }
    $op = (string)($_POST['op'] ?? '');
    $me = (int)$_SESSION['user_id'];
    $view = (string)($_POST['view'] ?? 'users');
    $back = '?view=' . urlencode($view);

    try {
        switch ($op) {
            case 'toggle_admin':
                $uid = (int)$_POST['user_id'];
                $isAdminNow = (int)$pdo->query("SELECT is_admin FROM users WHERE user_id = " . $uid)->fetchColumn();
                if ($isAdminNow === 1) {
                    $admins = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE is_admin = 1")->fetchColumn();
                    if ($admins <= 1) {
                        b5_flash('The last admin can not be demoted.', true);
                        break;
                    }
                }
                $pdo->prepare("UPDATE users SET is_admin = ? WHERE user_id = ?")->execute([$isAdminNow === 1 ? 0 : 1, $uid]);
                b5_flash('Role updated.');
                break;

            case 'reset_pw':
                $uid = (int)$_POST['user_id'];
                $new = substr(bin2hex(random_bytes(8)), 0, 12);
                $pdo->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?")->execute([password_hash($new, PASSWORD_DEFAULT), $uid]);
                b5_flash("New password for user #$uid (shown once): $new");
                break;

            case 'delete_user':
                $uid = (int)$_POST['user_id'];
                if ($uid === $me) {
                    b5_flash('You can not delete your own account here.', true);
                    break;
                }
                $pdo->prepare("DELETE FROM users WHERE user_id = ?")->execute([$uid]);
                b5_flash("User #$uid deleted (save games removed by cascade).");
                break;

            case 'row_delete':
            case 'row_update':
            case 'row_insert':
                $table = (string)($_POST['t'] ?? '');
                if (!in_array($table, b5_tables($pdo), true)) {
                    b5_flash('Unknown table.', true);
                    break;
                }
                $cols = b5_columns($pdo, $table);
                $names = array_column($cols, 'name');
                $back = '?view=db&t=' . urlencode($table) . '&page=' . (int)($_POST['page'] ?? 1);

                if ($op === 'row_delete') {
                    $pdo->prepare('DELETE FROM ' . b5_q($table) . ' WHERE rowid = ?')->execute([(int)$_POST['rowid']]);
                    b5_flash('Row deleted.');
                    break;
                }

                $set = [];
                $vals = [];
                foreach ($names as $name) {
                    $key = 'f_' . $name;
                    if (!array_key_exists($key, $_POST)) {
                        continue;
                    }
                    $val = (string)$_POST[$key];
                    $isHash = stripos($name, 'password') !== false;
                    if ($isHash) {
                        if ($val === '') {
                            continue;                       // empty = keep / not set
                        }
                        $val = password_hash($val, PASSWORD_DEFAULT);   // plain password typed in -> stored hashed
                    } elseif ($val === '' && !empty($_POST['null_' . $name])) {
                        $val = null;
                    }
                    $set[] = $name;
                    $vals[] = $val;
                }
                if (!$set) {
                    b5_flash('Nothing to save.', true);
                    break;
                }
                if ($op === 'row_update') {
                    $sql = 'UPDATE ' . b5_q($table) . ' SET ' . implode(', ', array_map(fn($n) => b5_q($n) . ' = ?', $set)) . ' WHERE rowid = ?';
                    $vals[] = (int)$_POST['rowid'];
                    $pdo->prepare($sql)->execute($vals);
                    b5_flash('Row saved.');
                } else {
                    $sql = 'INSERT INTO ' . b5_q($table) . ' (' . implode(', ', array_map('b5_q', $set)) . ') VALUES (' . implode(', ', array_fill(0, count($set), '?')) . ')';
                    $pdo->prepare($sql)->execute($vals);
                    b5_flash('Row inserted.');
                }
                break;

            default:
                b5_flash('Unknown action.', true);
        }
    } catch (Throwable $e) {
        b5_flash('Database error: ' . $e->getMessage(), true);
    }
    b5_redirect($back);
}

/** Sends a consistent copy of the database as a download. */
function b5_send_backup(PDO $pdo): void {
    $tmp = sys_get_temp_dir() . '/b5_backup_' . bin2hex(random_bytes(6)) . '.sqlite';
    try {
        $pdo->exec('VACUUM INTO ' . $pdo->quote($tmp));
    } catch (Throwable $e) {
        $src = $pdo->query('PRAGMA database_list')->fetch()['file'] ?? '';
        if ($src === '' || !copy($src, $tmp)) {
            http_response_code(500);
            exit('Backup failed.');
        }
    }
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="b5_backup_' . date('Ymd_His') . '.sqlite"');
    header('Content-Length: ' . filesize($tmp));
    readfile($tmp);
    @unlink($tmp);
    exit;
}

/** Admin tab: users. */
function b5_admin_users(PDO $pdo): string {
    $rows = $pdo->query("SELECT u.user_id, u.username, u.created_at, u.is_admin,
                                (SELECT COUNT(*) FROM player_slots s WHERE s.user_id = u.user_id) AS slots,
                                (SELECT COUNT(*) FROM player_history h WHERE h.user_id = u.user_id) AS choices
                         FROM users u ORDER BY u.user_id")->fetchAll();
    $o = '<div class="table-wrap"><table class="data"><thead><tr><th>ID</th><th>User</th><th>Role</th><th>Slots</th><th>Choices</th><th>Created</th><th>Actions</th></tr></thead><tbody>';
    foreach ($rows as $r) {
        $id = (int)$r['user_id'];
        $o .= '<tr><td>' . $id . '</td><td>' . b5_h($r['username']) . '</td><td>' . ($r['is_admin'] ? '<span class="badge-admin">ADMIN</span>' : 'user') . '</td>'
            . '<td>' . (int)$r['slots'] . '</td><td>' . (int)$r['choices'] . '</td><td>' . b5_h($r['created_at']) . '</td><td class="actions">';
        foreach ([['toggle_admin', $r['is_admin'] ? 'Demote' : 'Promote', ''],
                  ['reset_pw', 'New password', 'Set a new random password for this user?'],
                  ['delete_user', 'Delete', 'Delete this user and ALL their save games?']] as [$op, $label, $confirm]) {
            $o .= '<form method="post" class="inline"' . ($confirm ? ' onsubmit="return confirm(\'' . b5_h($confirm) . '\')"' : '') . '>'
                . b5_csrf_field() . '<input type="hidden" name="op" value="' . $op . '"><input type="hidden" name="view" value="users">'
                . '<input type="hidden" name="user_id" value="' . $id . '"><button type="submit" class="btn-small">' . $label . '</button></form>';
        }
        $o .= '</td></tr>';
    }
    return $o . '</tbody></table></div>';
}

/** Admin tab: database browser with edit / delete / insert. */
function b5_admin_db(PDO $pdo): string {
    $tables = b5_tables($pdo);
    $t = (string)($_GET['t'] ?? $tables[0] ?? '');
    if (!in_array($t, $tables, true)) {
        $t = $tables[0] ?? '';
    }
    $o = '<div class="chips">';
    foreach ($tables as $name) {
        $n = (int)$pdo->query('SELECT COUNT(*) FROM ' . b5_q($name))->fetchColumn();
        $o .= '<a class="chip' . ($name === $t ? ' active' : '') . '" href="?view=db&t=' . urlencode($name) . '">' . b5_h($name) . ' <small>' . $n . '</small></a>';
    }
    $o .= '</div>';
    if ($t === '') {
        return $o . '<p>No tables.</p>';
    }

    $cols = b5_columns($pdo, $t);
    $perPage = 25;
    $total = (int)$pdo->query('SELECT COUNT(*) FROM ' . b5_q($t))->fetchColumn();
    $pages = max(1, (int)ceil($total / $perPage));
    $page = min(max(1, (int)($_GET['page'] ?? 1)), $pages);
    $rows = $pdo->query('SELECT rowid AS __rowid, * FROM ' . b5_q($t) . ' ORDER BY rowid LIMIT ' . $perPage . ' OFFSET ' . (($page - 1) * $perPage))->fetchAll();

    $hidden = fn(string $op, int $rowid = 0) => b5_csrf_field() . '<input type="hidden" name="op" value="' . $op . '"><input type="hidden" name="view" value="db">'
        . '<input type="hidden" name="t" value="' . b5_h($t) . '"><input type="hidden" name="page" value="' . $page . '">'
        . ($rowid ? '<input type="hidden" name="rowid" value="' . $rowid . '">' : '');
    $fields = function (array $row = null) use ($cols, $t) {
        $f = '<div class="form-row">';
        foreach ($cols as $c) {
            $name = $c['name'];
            $isPw = stripos($name, 'password') !== false;
            $val = $row[$name] ?? '';
            $f .= '<div class="form-group"><label>' . b5_h($name) . ' <small>' . b5_h($c['type']) . ($c['pk'] ? ' · PK' : '') . '</small></label>'
                . '<input type="' . ($isPw ? 'password' : 'text') . '" name="f_' . b5_h($name) . '" value="' . ($isPw ? '' : b5_h($val)) . '"'
                . ($isPw ? ' placeholder="new password (stored hashed), empty = keep" autocomplete="new-password"' : '') . '>'
                . (!$isPw && !$c['notnull'] ? '<label class="check"><input type="checkbox" name="null_' . b5_h($name) . '" value="1"' . ($row !== null && $val === null ? ' checked' : '') . '> empty = NULL</label>' : '')
                . '</div>';
        }
        return $f . '</div>';
    };

    $o .= '<p class="muted">' . $total . ' rows · page ' . $page . ' of ' . $pages . '</p><div class="table-wrap"><table class="data"><thead><tr><th></th>';
    foreach ($cols as $c) {
        $o .= '<th>' . b5_h($c['name']) . '</th>';
    }
    $o .= '</tr></thead><tbody>';
    foreach ($rows as $r) {
        $rid = (int)$r['__rowid'];
        $o .= '<tr><td class="actions"><details class="row-edit"><summary class="btn-small">Edit</summary></details></td>';
        foreach ($cols as $c) {
            $o .= '<td>' . b5_cell($c['name'], $r[$c['name']]) . '</td>';
        }
        $o .= '</tr><tr class="edit-row"><td colspan="' . (count($cols) + 1) . '"><details><summary>Edit / delete row ' . $rid . '</summary>'
            . '<form method="post" class="stack">' . $hidden('row_update', $rid) . $fields($r) . '<button type="submit">Save</button></form>'
            . '<form method="post" class="inline" onsubmit="return confirm(\'Delete this row?\')">' . $hidden('row_delete', $rid) . '<button type="submit" class="btn-danger">Delete row</button></form>'
            . '</details></td></tr>';
    }
    $o .= '</tbody></table></div>';

    $o .= '<div class="pager">';
    if ($page > 1) { $o .= '<a class="btn" href="?view=db&t=' . urlencode($t) . '&page=' . ($page - 1) . '">&laquo; Prev</a>'; }
    if ($page < $pages) { $o .= '<a class="btn" href="?view=db&t=' . urlencode($t) . '&page=' . ($page + 1) . '">Next &raquo;</a>'; }
    $o .= '</div><details class="panel"><summary>Insert new row into ' . b5_h($t) . '</summary><form method="post" class="stack">'
        . $hidden('row_insert') . $fields() . '<button type="submit">Insert</button></form></details>';
    return $o;
}

/** Admin tab: SQL console (read-only unless "allow changes" is ticked). */
function b5_admin_sql(PDO $pdo): string {
    $sql = '';
    $out = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['op'] ?? '') === 'sql') {
        if (!b5_csrf_ok()) {
            $out = '<p class="network-error">Security token invalid.</p>';
        } else {
            $sql = trim((string)($_POST['sql'] ?? ''));
            $stmtText = rtrim($sql, "; \t\r\n");
            $write = !empty($_POST['allow_write']);
            if ($stmtText === '') {
                $out = '';
            } elseif (strpos($stmtText, ';') !== false) {
                $out = '<p class="network-error">Only ONE statement at a time (no semicolons inside).</p>';
            } else {
                try {
                    $pdo->exec('PRAGMA query_only = ' . ($write ? 'OFF' : 'ON'));
                    $stmt = $pdo->query($stmtText);
                    if ($stmt->columnCount() > 0) {
                        $rows = $stmt->fetchAll();
                        $shown = array_slice($rows, 0, 200);
                        $out = '<p class="muted">' . count($rows) . ' rows' . (count($rows) > 200 ? ' (first 200 shown)' : '') . '</p>';
                        if ($shown) {
                            $out .= '<div class="table-wrap"><table class="data"><thead><tr>';
                            foreach (array_keys($shown[0]) as $h) { $out .= '<th>' . b5_h($h) . '</th>'; }
                            $out .= '</tr></thead><tbody>';
                            foreach ($shown as $r) {
                                $out .= '<tr>';
                                foreach ($r as $k => $v) { $out .= '<td>' . b5_cell((string)$k, $v) . '</td>'; }
                                $out .= '</tr>';
                            }
                            $out .= '</tbody></table></div>';
                        }
                    } else {
                        $out = '<p class="ok">Done. Rows affected: ' . (int)$stmt->rowCount() . '</p>';
                    }
                } catch (Throwable $e) {
                    $out = '<p class="network-error">' . b5_h($e->getMessage()) . '</p>';
                } finally {
                    $pdo->exec('PRAGMA query_only = OFF');
                }
            }
        }
    }
    return '<form method="post" class="stack">' . b5_csrf_field() . '<input type="hidden" name="op" value="sql"><input type="hidden" name="view" value="sql">'
        . '<label for="sql">SQL statement (one at a time)</label><textarea id="sql" name="sql" rows="5" spellcheck="false" placeholder="SELECT * FROM player_slots LIMIT 10">' . b5_h($sql) . '</textarea>'
        . '<label class="check"><input type="checkbox" name="allow_write" value="1"> Allow changes (INSERT / UPDATE / DELETE / ALTER). Without this tick the console is read-only.</label>'
        . '<button type="submit">Run</button></form>' . $out;
}
