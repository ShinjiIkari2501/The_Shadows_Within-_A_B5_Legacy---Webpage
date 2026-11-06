<?php
/**
 * Login.php
 * Core Security Authentication Gateway for 'The Shadows Within - A B5 Legacy'
 * Verifies credentials against the MySQL database with a local static fallback.
 */

// SECURITY PHALANX: Enforce secure session cookie attributes over the network
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.use_only_cookies', '1');

session_start();

if (isset($_POST['loginSubmit'])) {

    // Anti-Spam Bot Protection (Honeypot)
    if (!empty($_POST['eMail'])) {
        die("Transmission blocked: Automated bot activity detected.");
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Static crew database registry as a secure offline fallback
    $crewDatabase = [
        'Shinji2501' => 'Test1234',
        'Tiny1983'   => 'Weed2022',
        'Test'       => '1234'
    ];

    $loginSuccess = false;
    $dbVerified   = false; // true ONLY when the password was checked against the database hash
    $authenticatedUserId = 1; // Default test ID fallback placeholder

    // 1. DATABASE CHECK: Verify credentials against live production tables
    try {
        if (file_exists('Includes/db_connect.php')) {
            require_once 'Includes/db_connect.php';

            // Fetch secure hash and user identity from the database
            $stmt = $pdo->prepare("SELECT user_id, password_hash FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $userRecord = $stmt->fetch();

            // Verify cryptographically hashed password matrix
            if ($userRecord && password_verify($password, $userRecord['password_hash'])) {
                $loginSuccess = true;
                $dbVerified   = true;
                $authenticatedUserId = (int)$userRecord['user_id'];
            }
        }
    } catch (Exception $e) {
        // Fallback triggers automatically if the database link is disrupted
    }

    // 2. STATIC FALLBACK: Check local memory array if database profile missed
    if (!$loginSuccess) {
        if (isset($_SESSION['registrierter_user']) && $_SESSION['registrierter_user'] === $username) {
            if (isset($_SESSION['registriertes_passw']) && $_SESSION['registriertes_passw'] === $password) {
                $loginSuccess = true;
            }
        }
        elseif (array_key_exists($username, $crewDatabase) && $crewDatabase[$username] === $password) {
            $loginSuccess = true;
        }
    }

    // 3. AUTHENTICATION EVALUATION AND REDIRECTION ROUTING
    if ($loginSuccess === true) {
        session_regenerate_id(true);
        $_SESSION['eingeloggt'] = true;
        // Admin rights (Statistics.php) are only possible for DB-verified logins, never for the static fallback list.
        $_SESSION['db_verified'] = $dbVerified;
        $_SESSION['username']   = $username;
        $_SESSION['user_id']    = $authenticatedUserId; // Crucial mapping parameter for player_slots sync

        $_SESSION['flash_message'] = "🔒 UPLINK ESTABLISHED: Security Clearance Granted. Terminal Sync Complete.";
        header("Location: Index.php");
        exit();
    } else {
        $_SESSION['flash_message_error'] = "⚠️ ACCESS DENIED: Invalid Security Credentials or Unknown Sector Entity.";
        header("Location: Index.php");
        exit();
    }

} else {
    header("Location: Index.php");
    exit();
}
?>
