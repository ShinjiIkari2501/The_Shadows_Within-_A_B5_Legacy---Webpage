<?php
// ==========================================================================
// THE SHADOWS WITHIN: A B5 LEGACY - SECURE LOGIN INTERFACE
// ==========================================================================

// LIVE-PROXYSCHUTZ: Erkennt die HTTPS-Verschlüsselung von Render.com vollautomatisch
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $_SERVER['HTTPS'] = 'on';
}

// Zwingt den Server, Sessions stabil und sicher zu verarbeiten
ini_set('session.cookie_httponly', '1');
ini_set('session.use_only_cookies', '1');

// Falls die Verbindung sicher ist, aktivieren wir das Secure-Flag passend für den Browser
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', '1');
}

// Startet die Session-Zentrale
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['loginSubmit'])) {
    
    // Spam-Bot Schutz (Honeypot)
    if (!empty($_POST['eMail'])) {
        die("Transmission blocked: Automated bot activity detected.");
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Die feste, unzerstörbare Crew-Datenbank
    $crewDatenbank = [
        'Shinji2501' => 'Test1234',
        'Sheridan'   => 'Tuzanor2261',
        'Lochley'    => 'Babcom5'
    ];

    $loginErfolgreich = false;

    // 1. Prüfung: Hat sich der User gerade frisch registriert?
    if (isset($_SESSION['registrierter_user']) && $_SESSION['registrierter_user'] === $username) {
        if ($_SESSION['registriertes_passw'] === $password) {
            $loginErfolgreich = true;
        }
    } 
    // 2. Prüfung: Fallback auf die permanente Crew-Datenbank
    elseif (array_key_exists($username, $crewDatenbank) && $crewDatenbank[$username] === $password) {
        $loginErfolgreich = true;
    }

    // Auswertung der Sicherheits-Credentials
    if ($loginErfolgreich === true) {
        $_SESSION['eingeloggt'] = true;
        $_SESSION['username'] = $username;
        unset($_SESSION['login_error']); // Löscht alte Fehlermeldungen bei Erfolg
        
        // Führt den Browser sicher zurück auf das Hauptterminal
        header("Location: ./");
        exit();
    } else {
        // Setzt ein Fehlersignal im Speicher bei falschen Daten
        $_SESSION['login_error'] = "ACCESS DENIED: Invalid Security Credentials.";
        header("Location: ./");
        exit();
    }

} else {
    header("Location: ./");
    exit();
}
?>
