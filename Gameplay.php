<?php
// 1. Die Layout-Zentrale laden (Startet auch die Session)
require_once 'Includes/layout.php';

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start();

// LOGIN-PRÜFUNG: Korrekt deklariert
$isLoggedIn = 0;
if (isset($_SESSION['eingeloggt']) && $_SESSION['eingeloggt'] === true) {
    $isLoggedIn = 1;
}
?>

<h2>Tactical Simulation Deck</h2>
<h3>Early-Concept-Alpha / Operation: Core Nexus</h3>

<?php if ($isLoggedIn === 1): ?>

    <!-- Das native Steuerungsdeck im astreinen Sci-Fi-Look -->
    <div style="border: 1px solid rgba(96, 172, 243, 0.3); border-radius: 4px; box-shadow: 0 0 20px rgba(0,0,0,0.7); background: #050508; max-width: 650px; margin: 30px auto; padding: 40px; text-align: center;">
        
        <p style="color: #888; font-family: 'Courier New', monospace; font-size: 14px; margin-bottom: 30px; line-height: 1.6;">
            [SYSTEM ALERT]: Direct browser integration restricted by orbital cross-origin firewalls. 
            To ignite the hybrid engines and establish a secure, fully interactive command link 
            to the EAS Liburnia, you must boot the subsystem in a dedicated sandbox terminal.
        </p>

        <!-- DER MASSIVE, LEUCHTENDE ABSCHUSS-BUTTON -->
        <!-- target="_blank" zwingt den Browser, das Terminal in einem neuen, freien Tab zu öffnen! -->
        <a href="https://the-shadows-within-a-b5-legacy-java.onrender.com" 
           target="_blank" 
           style="display: inline-block; background: transparent; border: 2px solid #00ffcc; color: #00ffcc; padding: 15px 30px; font-family: 'Courier New', monospace; font-size: 16px; font-weight: bold; text-decoration: none; border-radius: 4px; box-shadow: 0 0 15px rgba(0, 255, 204, 0.2); transition: all 0.3s; cursor: pointer;"
           onmouseover="this.style.background='#00ffcc'; this.style.color='#0d0d13'; this.style.boxShadow='0 0 25px #00ffcc';"
           onmouseout="this.style.background='transparent'; this.style.color='#00ffcc'; this.style.boxShadow='0 0 15px rgba(0, 255, 204, 0.2)';">
           ⚡ LAUNCH INTERSTALLAR TERMINAL
        </a>

        <div style="margin-top: 30px; color: #ff3366; font-family: 'Courier New', monospace; font-size: 12px;">
            [SECURE CONNECTION STATUS: READY TO IGNITE]
        </div>
    </div>

<?php
endif; // Schließt die Login-Prüfung

// 3. Holt den angesammelten HTML-Code aus dem Zwischenspeicher
$content = ob_get_clean();

// 4. Ruft die Funktion mit Titel und Inhalt passend zu deiner Layout.php auf
renderLayout("Tactical Simulation Deck", $content);
?>
