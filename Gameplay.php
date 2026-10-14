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

    <!-- Xterm.js Web-Terminal direkt über ein stabiles CDN laden -->
    <link rel="stylesheet" href="https://jsdelivr.net" />
    <script src="https://jsdelivr.net"></script>

    <!-- Der Terminal-Container fügt sich nahtlos in dein Sci-Fi-Design ein -->
    <div style="border: 1px solid rgba(96, 172, 243, 0.3); border-radius: 4px; box-shadow: 0 0 20px rgba(0,0,0,0.7); padding: 15px; background: #010203; max-width: 850px; margin: 15px auto;">
        <div id="terminal-display" style="width: 100%; height: 500px; background-color: #010203;"></div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Initialisiert ein echtes Retro-Terminal auf deiner Seite
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

            const container = document.getElementById('terminal-display');
            term.open(container);
            term.write("[SYSTEM]: Establishing local hyperdrive matrix...\r\n");
            
            // Simulierter, stabiler Text-Stream deines Java-Cores über das native JS-Interface
            // Da das Terminal lokal läuft, blockiert der Browser die Tastatur hier NIEMALS!
            term.write("\r\n======================================================\r\n");
            term.write("=== THE SHADOWS WITHIN - CORE TERMINAL             ===\r\n");
            term.write("======================================================\r\n");
            term.write("SELECT YOUR BACKGROUND SYSTEM:\r\n\r\n");
            term.write("1 = Intelligence (Ex-Agent)\r\n");
            term.write("2 = Underworld (Smuggler)\r\n\r\n");
            term.write("Your choice: ");

            // Fängt deine Tastatureingaben direkt und flüssig ab
            let inputBuffer = "";
            term.onData(e => {
                switch (e) {
                    case "\r": // Enter-Taste gedrückt
                        term.write("\r\n[SYSTEM]: Processing vector matrix key " + inputBuffer + "...\r\n");
                        // Hier wird deine Spiellogik nativ getriggert!
                        inputBuffer = "";
                        break;
                    case "\u007F": // Backspace
                        if (inputBuffer.length > 0) {
                            inputBuffer = inputBuffer.slice(0, -1);
                            term.write("\b \b");
                        }
                        break;
                    default:
                        if (e >= " " && e <= "~") {
                            inputBuffer += e;
                            term.write(e);
                        }
                }
            });

            setTimeout(() => { term.focus(); }, 300);
        });
    </script>

<?php
endif; // Schließt die Login-Prüfung

// 3. Holt den angesammelten HTML-Code aus dem Zwischenspeicher
$content = ob_get_clean();

// 4. Ruft die Funktion mit Titel und Inhalt passend zu deiner Layout.php auf
renderLayout("Tactical Simulation Deck", $content);
?>
