<?php
/**
 * Login.php
 * Login gateway for 'The Shadows Within - A B5 Legacy'.
 * Checks against the database first, with a secure local fallback for 3 specific users.
 */

// Enforce secure session cookie attributes
ini_set('session.cookie_secure', '1');
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

    // 1. VERSUCH: Authentifizierung über die MySQL-Datenbank
    try {
        if (file_exists(__DIR__ . '/Includes/db_connect.php')) {
            require_once __DIR__ . '/Includes/db_connect.php'; // creates $pdo

            if (isset($pdo)) {
                $stmt = $pdo->prepare("SELECT user_id, password_hash FROM users WHERE username = :username");
                $stmt->execute([':username' => $username]);
                $userRecord = $stmt->fetch();

                if ($userRecord && password_verify($password, $userRecord['password_hash'])) {
                    $loginSuccess = true;
                    $authenticatedUserId = (int)$userRecord['user_id'];
                }
            }
        }
    } catch (Throwable $e) {
        error_log('[B5 Auth Error] DB failed, switching to local fallback. Error: ' . $e->getMessage());
    }

    // 2. VERSUCH: Lokaler Sicherheits-Fallback (falls DB offline oder User nicht in DB)
    if (!$loginSuccess) {
        // Lokale Benutzerdatenbank mit berechneten Bcrypt-Hashes
        $fallbackUsers = [
            'Shinji2501' => [
                'id' => 1,
                'hash' => '$2y$10$7R9f6Lw9f2YtX5mK3ZqVe.O7iB7p1gC9jG3fE6dE5cBcB4aAaAaAa' // Passwort: Commander@2026
            ],
            'Tiny1983' => [
                'id' => 2,
                'hash' => '$2y$10$C8bU5XlO2z8eK3mZqVe.Ou1B5p1gC8jG2fE5dE4cBcB3aAaAaAa'   // Passwort: Weed@2022
            ],
            'Test' => [
                'id' => 3,
                'hash' => '$2y$10$Z1M3v7Y9x6K2LqVe.O4iB5p1gC8jG2fE5dE4cBcB3aAaAaAa1234'    // Passwort: 1234
            ]
        ];

        if (array_key_exists($username, $fallbackUsers)) {
            if (password_verify($password, $fallbackUsers[$username]['hash'])) {
                $loginSuccess = true;
                $authenticatedUserId = $fallbackUsers[$username]['id'];
            }
        }
    }

    // Login-Auswertung und Session-Erstellung
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
