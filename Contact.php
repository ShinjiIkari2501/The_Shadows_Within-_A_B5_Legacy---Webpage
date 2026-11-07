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
        die("Transmission blocked: Automated bot activity detected by BabCom-Grid.");
    }

    // DATEN AUSLESEN UND SÄUBERN
    $senderName    = trim($_POST['contact-name'] ?? '');
    $senderEmail   = trim($_POST['contact-email'] ?? '');
    $senderSubject = trim($_POST['contact-subject'] ?? 'No Subject');
    $senderMessage = trim($_POST['contact-message'] ?? '');

    // VALIDIERUNG: Sind die Pflichtfelder ausgefüllt?
    if (!empty($senderName) && !empty($senderEmail) && !empty($senderMessage)) {
        
        try {
            // Verbindung über dein bestehendes PDO-Framework herstellen
            $pdo = b5_pdo();
            $stmt = $pdo->prepare("INSERT INTO contact_messages (sender_name, sender_email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$senderName, $senderEmail, $senderSubject, $senderMessage]);

            // DYNAMISCHE FLASH-MESSAGE IN DIE SESSION SPEICHERN
            $_SESSION['flash_message'] = "📡 TRANSMISSION SUCCESSFUL: Secure sub-space uplink established. Message routed to Command Deck.";
        } catch (Exception $e) {
            $_SESSION['flash_message_error'] = "⚠️ TRANSMISSION CRITICAL: Sub-space array offline. DB-Error.";
        }
        
        header("Location: Contact.php");
        exit();
        
    } else {
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
        
        <form action="Contact.php" method="post">
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
            
            <div class="form-row">
                <div class="form-group full-width">
                    <label for="contact-subject">Subject:</label>
                    <input type="text" name="contact-subject" id="contact-subject" placeholder="What is this transmission about?">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group full-width">
                    <label for="contact-message">Message: *</label>
                    <textarea name="contact-message" id="contact-message" rows="5" placeholder="Enter your message sequence..." required></textarea>
                </div>
            </div>
            
            <input type="text" id="zusatzinfo_kontakt" name="zusatzinfo_kontakt" tabindex="-1" autocomplete="off">
            <button type="submit" name="contactSubmit" class="formButton">Transmit</button>
        </form>
    </fieldset>
</section>

<?php 
$seitenInhalt = ob_get_clean(); 
renderLayout("B5 Legacy - Secure Contact Uplink", $seitenInhalt); 
?>
