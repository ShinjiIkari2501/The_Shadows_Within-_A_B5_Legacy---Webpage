<?php
/**
 * auth.php - who is allowed to do what (used by Statistics.php and the navigation).
 *
 * ROLES
 *   user  = every logged-in player. Sees their own statistics and anonymous totals.
 *   admin = full access to the database (Statistics.php, admin tabs).
 *
 * WHO IS ADMIN?
 *   1. users.is_admin = 1 in the database (column is created automatically).
 *   2. Environment variable B5_ADMIN_USERS="Shinji2501,Other" (comma separated user names)
 *      always counts as admin. Recommended for the live server.
 *   (There is deliberately NO automatic first admin: the seed accounts of the shipped database
 *   have placeholder password hashes, so first register a real account on the site, then make it
 *   admin with B5_ADMIN_USERS or with:  UPDATE users SET is_admin = 1 WHERE username = 'YourName';)
 *   An admin must have logged in with the password from the DATABASE (not with the static
 *   fallback list in Login.php), see $_SESSION['db_verified']. The admin flag is re-read
 *   from the database on every request, so removing admin rights takes effect immediately.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** The shared database connection (db_connect.php creates $pdo). */
function b5_pdo(): PDO {
    global $pdo;
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        require __DIR__ . '/db_connect.php';
    }
    return $pdo;
}

function b5_is_logged_in(): bool {
    return isset($_SESSION['eingeloggt']) && $_SESSION['eingeloggt'] === true;
}

/** Adds the column users.is_admin if it is missing (cheap check, runs only for admin features). */
function b5_ensure_admin_column(PDO $pdo): void {
    foreach ($pdo->query("PRAGMA table_info(users)") as $col) {
        if ($col['name'] === 'is_admin') {
            return;
        }
    }
    $pdo->exec("ALTER TABLE users ADD COLUMN is_admin INTEGER NOT NULL DEFAULT 0");
}

function b5_is_admin(): bool {
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $cache = false;
    if (!b5_is_logged_in() || empty($_SESSION['db_verified']) || empty($_SESSION['user_id'])) {
        return false;
    }
    try {
        $pdo = b5_pdo();
        b5_ensure_admin_column($pdo);
        $stmt = $pdo->prepare("SELECT user_id, username, is_admin FROM users WHERE user_id = ?");
        $stmt->execute([(int)$_SESSION['user_id']]);
        $me = $stmt->fetch();
        // the session user name must still match the database row (a deleted/renamed account loses access)
        if (!$me || $me['username'] !== ($_SESSION['username'] ?? null)) {
            return false;
        }
        if ((int)$me['is_admin'] === 1) {
            return $cache = true;
        }
        $env = getenv('B5_ADMIN_USERS');
        if ($env !== false && $env !== '') {
            $names = array_map('trim', explode(',', $env));
            if (in_array($me['username'], $names, true)) {
                return $cache = true;
            }
        }
    } catch (Throwable $e) {
        error_log('[auth] ' . $e->getMessage());
    }
    return $cache;
}

/* --- CSRF protection for every admin form ----------------------------------------------- */
function b5_csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function b5_csrf_field(): string {
    return '<input type="hidden" name="csrf" value="' . htmlspecialchars(b5_csrf_token()) . '">';
}

function b5_csrf_ok(): bool {
    return isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string)$_POST['csrf']);
}
