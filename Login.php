<?php
/**
 * Login.php
 * Login gateway for 'The Shadows Within - A B5 Legacy'.
 * The password is checked ONLY against the hash stored in the database (no fallback list, no demo users).
 * Why no fallback: a fallback login can log in a user who does not exist in the database. The game then
 * has no save slots for this user and crashes. It would also put passwords into the public GitHub repo.
 */

// The "secure" cookie flag only works on https:// pages. On plain http:// the browser would throw the
// session cookie away and nobody could log in. So switch it on only when the page is really on https.
$onHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
ini_set('session.cookie_secure', $onHttps ? '1' : '0');
ini_set('session.cookie_httponly', '1');
ini_set('session.use_only_cookies', '1');

session_start();

if (isset($_POST['loginSubmit'])) {

    // Anti-spam honeypot: real users never fill this hidden field
    if (!empty($_POST['eMail'])) {
        die("Transmission blocked: Automated bot activity detected.");
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $loginSuccess = false;
    $authenticatedUserId = null;

    try {
        require_once __DIR__ . '/Includes/db_connect.php'; // creates $pdo

        $stmt = $pdo->prepare("SELECT user_id, username, password_hash FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $userRecord = $stmt->fetch();

        // password_verify compares the typed password with the stored hash
        if ($userRecord && password_verify($password, $userRecord['password_hash'])) {
            $loginSuccess = true;
            $authenticatedUserId = (int)$userRecord['user_id'];
            $username = $userRecord['username']; // spelling as stored (MySQL ignores upper/lower case when searching)
        }
    } catch (Throwable $e) {
        // The technical reason goes only to the server log, not to the browser
        error_log('[B5 Auth Error] ' . $e->getMessage());
    }

    if ($loginSuccess) {
        session_regenerate_id(true); // new session id after login (protects against session fixation)
        $_SESSION['eingeloggt']  = true;
        $_SESSION['username']    = $username;
        $_SESSION['user_id']     = $authenticatedUserId; // links the player to the rows in player_slots
        $_SESSION['db_verified'] = true;                 // needed by auth.php for admin rights

        $_SESSION['flash_message'] = "🔒 UPLINK ESTABLISHED: Security Clearance Granted. Terminal Sync Complete.";
    } else {
        $_SESSION['flash_message_error'] = "⚠️ ACCESS DENIED: Invalid Security Credentials or Unknown Sector Entity.";
    }
    header("Location: Index.php");
    exit();

} else {
    header("Location: Index.php");
    exit();
}
?>
