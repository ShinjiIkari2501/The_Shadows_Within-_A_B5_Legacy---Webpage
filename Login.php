<?php
// HTTPS-SICHERHEITS-PHALANX: Zwingt den Server, Sessions sicher über HTTPS zu übertragen
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.use_only_cookies', '1');

// Startet die Session-Zentrale
session_start();

if (isset($_POST['loginSubmit'])) {
    
    // Spam-Bot Schutz (Honeypot)
    if (!empty($_POST['eMail'])) {
        die("Transmission blocked: Automated bot activity detected.");
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Die feste Crew-Datenbank
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
        
        // Zwingt den Browser, den Cache zu leeren und leitet sicher weiter
        header("Location: index.php");
        exit();
    } else {
        header("Location: index.php");
        exit();
    }

} else {
    header("Location: index.php");
    exit();
}
?>
