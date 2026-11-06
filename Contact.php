<?php 
// 1. Die Layout-Zentrale laden (Startet auch die Session)
require_once 'Includes/layout.php'; 

// ==========================================================================
// BACKEND-LOGIK: VERARBEITUNG DES SENDEN-BUTTONS (TRANSMIT)
// ==========================================================================
if (isset($_POST['contactSubmit'])) {
    
    // SPAM-BOT SCHUTZ: Prüfen, ob das unsichtbare Honeypot-Feld befüllt wurde
    $honeypot = $_POST['zusatzinfo_kontakt'] ?? '';
    if (!empty($honeypot)) {
        // Ein Bot hat reingetappt -> Übertragung unkommentiert abbrechen
        die("Transmission blocked: Automated bot activity detected by BabCom-Grid.");
    }

    // DATEN AUSLESEN UND SÄUBERN
    $senderName    = trim($_POST['contact-name'] ?? '');
    $senderEmail   = trim($_POST['contact-email'] ?? '');
    $senderSubject = trim($_POST['contact-subject'] ?? 'No Subject');
    $senderMessage = trim($_POST['contact-message'] ?? '');

    // VALIDIERUNG: Sind die Pflichtfelder ausgefüllt?
    if (!empty($senderName) && !empty($senderEmail) && !empty($senderMessage)) {
        
        /* 
           HINWEIS FÜR SPÄTER / SCHULE:
           Hier könnte jetzt Code stehen, um die Nachricht in einer Datenbank zu speichern
           oder eine echte E-Mail per mail() oder PHPMailer an dich zu senden.
        */

        // DYNAMISCHE FLASH-MESSAGE IN DIE SESSION SPEICHERN
        $_SESSION['flash_message'] = "📡 TRANSMISSION SUCCESSFUL: Secure sub-space uplink established. Message routed to Command Deck.";
        
        // Seite neu laden, um die Formulardaten aus dem Speicher zu löschen (PRG-Pattern)
        header("Location: Contact.php");
        exit();
        
    } else {
        // Falls im Backend doch ein Pflichtfeld gefehlt hat (Sicherheitsnetz)
        $_SESSION['flash_message_error'] = "⚠️ TRANSMISSION FAILURE: Required telemetry fields missing. Check Name, Email, and Message.";
        header("Location: Contact.php");
        exit();
    }
}

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start(); 
?>

<!-- HIER KOMMT DER REINE INHALT FÜR DEN MAIN-BEREICH -->
<h2>Contact the Crew</h2>
<h3>Secure Sub-Space Uplink / Gold Channel Communications</h3>

<section id="contact-section">
    <fieldset class="contact-fieldset">
        <legend>Transmission Grid:</legend>
        
        <!-- Das Formular schickt die Daten an sich selbst (Contact.php) zurück -->
        <form action="Contact.php" method="post">
            
            <!-- 1. REIHE: Name und E-Mail nebeneinander -->
            <div class="form-row">
                <div class="form-group">
                    <label for="contact-name">Name: *</label>
                    <input type="text" name="contact-name" id="contact-name" placeholder="Your Name" required>
                </div>
                <div class="form-group">
                    <label for="contact-email">Email-Address: *</label>
                    <input type="email" name="contact-email" id="contact-email" placeholder="Your Email" required>
                </div>
            </div>
            
            <!-- 2. REIHE: Betreff geht über die volle Breite -->
            <div class="form-row">
                <div class="form-group full-width">
                    <label for="contact-subject">Subject:</label>
                    <input type="text" name="contact-subject" id="contact-subject" placeholder="What is this transmission about?">
                </div>
            </div>
            
            <!-- 3. REIHE: Die Nachricht -->
            <div class="form-row">
                <div class="form-group full-width">
                    <label for="contact-message">Message: *</label>
                    <textarea name="contact-message" id="contact-message" rows="5" placeholder="Enter your message sequence..." required></textarea>
                </div>
            </div>
            
            <!-- Unsichtbarer Honeypot gegen Spam-Bots (Muss mit name="zusatzinfo_kontakt" übereinstimmen) -->
            <input type="text" id="zusatzinfo_kontakt" name="zusatzinfo_kontakt" tabindex="-1" autocomplete="off">
            
            <!-- Absendebutton im B5-Design -->
            <button type="submit" name="contactSubmit" class="formButton">Transmit</button>
        </form>
    </fieldset>
</section>

<?php 
// 3. Den Inhalt aus dem Zwischenspeicher in eine Variable packen
$seitenInhalt = ob_get_clean(); 

// 4. Das Layout aufrufen und den Titel der Seite + den Inhalt übergeben
renderLayout("B5 Legacy - Secure Contact Uplink", $seitenInhalt); 
?>
