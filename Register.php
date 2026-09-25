<?php 
// 1. Die Layout-Zentrale laden (Sie startet auch automatisch die Session)
require_once 'Includes/layout.php'; 

// ==========================================================================
// BACKEND-LOGIK: VERARBEITUNG DES REGISTRIERUNGS-BUTTONS
// ==========================================================================
if (isset($_POST['registerSubmit'])) {
    
    // Spam-Bot Schutz (Honeypot)
    if (!empty($_POST['eMail'])) {
        die("Transmission blocked: Bot activity detected by security phalanx.");
    }

    $regUser = trim($_POST['reg_username'] ?? '');
    $regPass = $_POST['reg_password'] ?? '';
    $regPassConfirm = $_POST['reg_password_confirm'] ?? '';

    // DIE ZENTRALE CREW-DATENBANK (Muss exakt mit der login.php übereinstimmen)
    $existierendeCrew = [
        'Shinji2501' => 'Test1234',
        'Sheridan'   => 'Tuzanor2261',
        'Lochley'    => 'Babcom5'
    ];

    // 1. UNSCHLÄGBARE SICHERHEITS-PRÜFUNG: Existiert der Username bereits?
    if (array_key_exists($regUser, $existierendeCrew)) {
        $_SESSION['flash_message_error'] = "⚠️ SECURITY VIOLATION: Callsign '" . htmlspecialchars($regUser) . "' is a permanent crew entity. Overwriting credentials denied.";
        header("Location: Register.php");
        exit();
    }

    // Zusätzlicher Check: Hat sich in dieser laufenden Session bereits jemand so genannt?
    elseif (isset($_SESSION['registrierter_user']) && $_SESSION['registrierter_user'] === $regUser) {
        $_SESSION['flash_message_error'] = "⚠️ TRANSMISSION FAILURE: Identity is already allocated in temporary buffer.";
        header("Location: Register.php");
        exit();
    }

    // 2. VALIDIERUNG: Längenprüfung für den Benutzernamen (Min: 8, Max: 20)
    elseif (strlen($regUser) < 8 || strlen($regUser) > 20) {
        $_SESSION['flash_message_error'] = "⚠️ REGISTRATION ERROR: Username must be between 8 and 20 characters.";
        header("Location: Register.php");
        exit();
    }

    // 3. VALIDIERUNG: Längenprüfung für das Passwort (Min: 8, Max: 20)
    elseif (strlen($regPass) < 8 || strlen($regPass) > 20) {
        $_SESSION['flash_message_error'] = "⚠️ REGISTRATION ERROR: Password must be between 8 and 20 characters.";
        header("Location: Register.php");
        exit();
    }

    // 4. VALIDIERUNG: Stimmen die Passwörter übereinstimmen?
    elseif ($regPass !== $regPassConfirm) {
        $_SESSION['flash_message_error'] = "⚠️ REGISTRATION ERROR: Passwords do not match. Re-enter credentials.";
        header("Location: Register.php");
        exit();
    } 
    
    else {
        // ERFOLG: Wir simulieren das Speichern in der Session
        $_SESSION['registrierter_user'] = $regUser;
        $_SESSION['registriertes_passw'] = $regPass;

        // Grüne Erfolgsmeldung zünden
        $_SESSION['flash_message'] = "✅ REGISTRATION COMPLETE: Entity $regUser secured. Transmission routed to Command Deck.";
        header("Location: Index.php");
        exit();
    }
}

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start(); 
?>

<!-- HIER KOMMT DER REINE INHALT FÜR DEN MAIN-BEREICH -->
<h2>Alliance Recruitment Grid</h2>
<h3>Database Enrollment / Security Phalanx</h3>

<section id="contact-section">
    <fieldset class="contact-fieldset" style="max-width: 500px; margin: 0 auto; background-color: rgba(13, 20, 59, 0.4); border: 1px solid rgba(96, 172, 243, 0.3); padding: 20px; border-radius: 6px;">
        <legend>New Entity Enrollment:</legend>
        
        <form action="Register.php" method="post">
            
            <!-- Benutzername mit Min/Max-Sicherung im HTML -->
            <div class="form-row" style="margin-bottom: 15px;">
                <div class="form-group full-width" style="text-align: left;">
                    <label for="reg_username" style="color: #ff9900; font-weight: bold; display: block; margin-bottom: 5px;">Desired Username: * (8-20 Chars)</label>
                    <input type="text" name="reg_username" id="reg_username" placeholder="Choose your callsign" minlength="8" maxlength="20" style="width: 100%; padding: 8px; background: rgba(0,0,0,0.5); border: 1px solid rgba(96, 172, 243, 0.4); color: #fff; border-radius: 4px;" required>
                </div>
            </div>
            
            <!-- Passwort 1 mit Min/Max-Sicherung im HTML -->
            <div class="form-row" style="margin-bottom: 15px;">
                <div class="form-group full-width" style="text-align: left;">
                    <label for="reg_password" style="color: #ff9900; font-weight: bold; display: block; margin-bottom: 5px;">Password: * (8-20 Chars)</label>
                    <input type="password" name="reg_password" id="reg_password" placeholder="Enter secure password" minlength="8" maxlength="20" style="width: 100%; padding: 8px; background: rgba(0,0,0,0.5); border: 1px solid rgba(96, 172, 243, 0.4); color: #fff; border-radius: 4px;" required>
                </div>
            </div>
            
            <!-- Passwort 2 (Verifizierung) mit Min/Max-Sicherung im HTML -->
            <div class="form-row" style="margin-bottom: 20px;">
                <div class="form-group full-width" style="text-align: left;">
                    <label for="reg_password_confirm" style="color: #ff9900; font-weight: bold; display: block; margin-bottom: 5px;">Confirm Password: *</label>
                    <input type="password" name="reg_password_confirm" id="reg_password_confirm" placeholder="Repeat your password" minlength="8" maxlength="20" style="width: 100%; padding: 8px; background: rgba(0,0,0,0.5); border: 1px solid rgba(96, 172, 243, 0.4); color: #fff; border-radius: 4px;" required>
                </div>
            </div>
            
            <!-- Unsichtbarer Honeypot gegen Spam-Bots -->
            <input type="text" id="eMail" name="eMail" style="display:none !important;" tabindex="-1" autocomplete="off">
            
            <!-- Absendebutton im B5-Design -->
            <button type="submit" name="registerSubmit" class="formButton" style="background-color: rgba(26, 47, 122, 0.4); border: 1px solid rgba(255, 153, 0, 0.4); border-radius: 4px; color: #ff9900; padding: 8px 25px; font-weight: bold; cursor: pointer; text-transform: uppercase;">Request Clearance</button>
        </form>
    </fieldset>
</section>

<p style="text-align: center; margin-top: 20px;">
    Already have clearance? <a href="Index.php" style="color: #ff9900; text-decoration: none;">Return to Main Terminal</a>
</p>

<?php 
// 3. Den Inhalt aus dem Zwischenspeicher holen
$seitenInhalt = ob_get_clean(); 

// 4. Das Layout aufrufen und den Titel übergeben
renderLayout("B5 Legacy - Database Enrollment", $seitenInhalt); 
?>
