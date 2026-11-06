<?php
/**
 * Logout.php
 * Emergency Purge Sequence for 'The Shadows Within - A B5 Legacy'
 * Destroys active session arrays and severs the tactical connection link.
 */

session_start();

// 1. Clear game pipeline state variables out of memory
unset($_SESSION['input_history']);
unset($_SESSION['active_save_slot']);
unset($_SESSION['fallback_slots']);
unset($_SESSION['b5_current_scene']);
unset($_SESSION['b5_player_origin']);
unset($_SESSION['eingeloggt']);
unset($_SESSION['username']);
unset($_SESSION['user_id']);

// 2. Flush the global session array matrix
$_SESSION = array();

// 3. Destroy session cookie tracking parameters inside browser cache
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Terminate data container context completely on the server node
session_destroy();

// Redirect back to index terminal hub anchor
header("Location: Index.php");
exit();
?>
