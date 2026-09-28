<?php
// ==========================================================================
// THE SHADOWS WITHIN: A B5 LEGACY - SECURE LOGOUT CORE
// ==========================================================================

// REAKTOR-SYNCHRONISIERUNG: Nutzt exakt denselben temporären Speicherpfad wie Login und Layout!
if (!is_dir('/tmp/php_sessions')) {
    mkdir('/tmp/php_sessions', 0777, true);
}
ini_set('session.save_path', '/tmp/php_sessions');

// Startet die Verbindung, um sie danach sauber aufzulösen
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Löscht alle Session-Variablen restlos aus dem Speicher
$_SESSION = array();

// Vernichtet das Session-Cookie im Browser des Commanders vollständig
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Zerstört die Sitzung auf dem Server
session_destroy();

// SLEIPNIR-ROUTE: Schickt dich schleufensicher zurück auf die anonyme Hauptseite
header("Location: ./");
exit();
?>
