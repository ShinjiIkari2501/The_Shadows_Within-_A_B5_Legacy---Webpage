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
<h3>Early-Concept-Alpha / Operation: Test</h3>

<?php if ($isLoggedIn === 1): ?>

    <!-- Xterm.js Terminal-Styles direkt aus dem offiziellen CDN laden -->
    <link rel="stylesheet" href="https://jsdelivr.net" />
    <script src="https://jsdelivr.net"></script>

    <!-- Der Terminal-Container fügt sich nahtlos in dein Design ein -->
    <div style="border: 1px solid rgba(96, 172, 243, 0.3); border-radius: 4px; box-shadow: 0 0 20px rgba(0,0,0,0.7); padding: 10px; background: #010203; max-width: 850px; margin: 15px auto;">
        <div id="terminal-container" style="width: 100%; height: 500px;"></div>
    </div>

    <!-- Das Steuerungs-Skript verbindet deine Seite direkt mit dem Render-Java-Server -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Initialisiert ein echtes Retro-Terminal direkt auf deiner Seite
            const term = new Terminal({
                cursorBlink: true,
                fontFamily: '"Courier New", monospace',
                fontSize: 16,
                theme: {
                    background: '#010203',
                    foreground: '#00ffcc',
                    cursor: '#00ffcc'
                }
            });

            const container = document.getElementById('terminal-container');
            term.open(container);
            term.write("Connecting to EAS Liburnia Core Engine...\r\n");

            // Baut eine direkte, native Websocket-Verbindung zu deiner Java-App auf Render auf
            // WICHTIG: Wir wandeln https:// in wss:// (Sicheres Websocket) um!
            const socketUrl = "wss://://onrender.com";
            const ws = new WebSocket(socketUrl);

            // Wenn Daten vom Java-Server kommen, drucke sie sofort im Terminal aus
            ws.onmessage = (event) => {
                term.write(event.data);
            };

            // Wenn der Spieler eine Taste drückt, schicke sie direkt an das Java-Spiel
            term.onData((data) => {
                ws.send(data);
            });

            ws.onclose = () => {
                term.write("\r\n[SYSTEM]: Connection to tactical deck terminated.");
            };

            ws.onerror = (err) => {
                term.write("\r\n[ERROR]: Com-Link failure. Check Render status logs.");
            };

            // Zwingt den Cursor sofort zum Blinken und Fokussieren
            setTimeout(() => {
                term.focus();
            }, 300);
        });
    </script>

<?php
endif; // Schließt die Login-Prüfung

// 3. Holt den angesammelten HTML-Code aus dem Zwischenspeicher
$content = ob_get_clean();

// 4. Ruft die Funktion mit Titel und Inhalt passend zu deiner Layout.php auf
renderLayout("Tactical Simulation Deck", $content);
?>
