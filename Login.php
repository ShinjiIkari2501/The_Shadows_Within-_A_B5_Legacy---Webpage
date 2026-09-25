<?php
// Session starten, damit sich der Server merkt, wer eingeloggt ist
session_start();

// Prüfen, ob das Formular überhaupt über den Login-Button abgeschickt wurde
if (isset($_POST['loginSubmit'])) {
    
    // 1. SPAM-BOT SCHUTZ: Honeypot-Feld prüfen
    if (!empty($_POST['eMail'])) {
        die("Transmission blocked: Automated bot activity detected.");
    }

    // 2. DATEN AUSLESEN UND BEREINIGEN
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // 3. CREW-DATENBANK SIMULIEREN (Assoziatives Array mit Test-Usern)
    $crewDatenbank = [
        'Shinji2501' => 'Test1234', // Username => Passwort
        'Sheridan'   => 'Tuzanor2261',
        'Lochley'    => 'Babcom5'
    ];

    // ==========================================================================
    // 4. LOGIN VALIDIEREN (KORREKTUR: Registrierte User überschreiben feste Crew)
    // ==========================================================================
    $loginErfolgreich = false;

    // WEICHE B) SCHUL-UPGRADE ZUERST: Hat sich dieser User gerade frisch registriert?
    if (isset($_SESSION['registrierter_user']) && $_SESSION['registrierter_user'] === $username) {
        // Wenn ja, muss er zwingend das NEUE Passwort nutzen!
        if ($_SESSION['registriertes_passw'] === $password) {
            $loginErfolgreich = true;
        }
    } 
    // WEICHE A) FALLBACK: Wenn keine frische Registrierung vorliegt, in der Crew-Datenbank suchen
    elseif (array_key_exists($username, $crewDatenbank) && $crewDatenbank[$username] === $password) {
        $loginErfolgreich = true;
    }

    // Die finale Auswertung und Session-Zuweisung
    if ($loginErfolgreich === true) {
        
        $_SESSION['eingeloggt'] = true;
        $_SESSION['username'] = $username;
        
        // Zündet das grüne Erfolgssignal für das Terminal
        $_SESSION['flash_message'] = "🔒 UPLINK ESTABLISHED: Security Clearance Granted. Terminal Sync Complete.";
        header("Location: index.php");
        exit();
        
    } else {
        // Zündet den roten Alarm bei falschen Daten
        $_SESSION['flash_message_error'] = "⚠️ ACCESS DENIED: Invalid Security Credentials or Unknown Sector Entity.";
        header("Location: index.php");
        exit();
    }

} else {
    // Falls jemand versucht, die Datei direkt über die Adresszeile aufzurufen
    header("Location: index.php");
    exit();
}
?>
