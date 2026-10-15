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

// LOGIK-HUB FÜR ASYNCHRONER LIVE-INPUT (AJAX)
if ($isLoggedIn === 1 && isset($_GET['ajax_action']) && $_GET['ajax_action'] === 'submit_input') {
    if (!isset($_SESSION['input_history'])) {
        $_SESSION['input_history'] = [];
    }

    // Speichert jede getroffene Entscheidung als virtuelles "Savegame" ab
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rawInput = isset($_POST['player_input']) ? $_POST['player_input'] : '';
        $_SESSION['input_history'][] = $rawInput;
    }

    $jarPath = __DIR__ . '/B5_Project.jar';
    $output = "";

    if (file_exists($jarPath)) {
        // Wir bauen die Kette der getroffenen Entscheidungen zusammen
        $inputString = "";
        foreach ($_SESSION['input_history'] as $pastInput) {
            $inputString .= $pastInput . "\n";
        }
        
        // Füttert deine originale Main.java im Bruchteil einer Sekunde
        $command = "echo " . escapeshellarg($inputString) . " | java -jar " . escapeshellarg($jarPath) . " 2>&1";
        $output = shell_exec($command);
    } else {
        $output = "[ERROR]: B5_Project.jar nicht im PHP-Ordner gefunden!";
    }

    echo htmlspecialchars($output);
    exit;
}

// Logik für den Reset-Befehl (Löscht das virtuelle Savegame für einen Neustart)
if (isset($_GET['reset_game'])) {
    unset($_SESSION['input_history']);
    header("Location: gameplay.php");
    exit;
}
?>

<h2>Tactical Simulation Deck</h2>
<h3>Early-Concept-Alpha / Operation: Pure Java Matrix</h3>

<?php if ($isLoggedIn === 1): ?>

    <!-- Das reinrassige Retro-Text-Terminal -->
    <div style="border: 1px solid rgba(96, 172, 243, 0.3); border-radius: 4px; box-shadow: 0 0 20px rgba(0,0,0,0.7); background: #010203; max-width: 850px; margin: 15px auto; padding: 20px;">
        
        <!-- Steuerungs-Navigationsleiste für den Spieler -->
        <div style="color: #60acf3; font-family: 'Courier New', monospace; font-size: 13px; margin-bottom: 12px; display: flex; justify-content: space-between; border-bottom: 1px solid rgba(96, 172, 243, 0.1); padding-bottom: 5px;">
            <span>[MATRIX LINK: PERSISTENT]</span>
            <span>Steuerung: [➔] oder [Bild-Ab] Nächste Seite | [➔] oder [Bild-Auf] Zurück</span>
        </div>

        <!-- Der Terminal-Bildschirm -->
        <pre id="terminal-screen" style="width: 100%; height: 450px; overflow-y: hidden; color: #00ffcc; font-family: 'Courier New', monospace; font-size: 16px; white-space: pre-wrap; margin: 0 0 20px 0; background: #000; padding: 15px; border-radius: 4px; border: 1px solid #111;">[SYSTEM]: Calibrating hyperdrive core frequencies...</pre>

        <!-- Die interaktive Input-Leiste -->
        <form id="terminal-form" style="display: flex; gap: 10px;">
            <span style="color: #00ffcc; font-family: 'Courier New', monospace; font-size: 18px; line-height: 40px;">&gt;</span>
            <input type="text" 
                   name="player_input" 
                   style="flex-grow: 1; background: #000; border: 1px solid #00ffcc; color: #00ffcc; padding: 10px; font-family: 'Courier New', monospace; font-size: 16px; border-radius: 4px;" 
                   placeholder="Zahl eingeben oder Enter drücken für 'Press Enter'..." 
                   autocomplete="off"
                   id="terminal-input">
            <button type="submit" style="background: transparent; border: 1px solid #00ffcc; color: #00ffcc; padding: 0 20px; font-family: 'Courier New', monospace; font-size: 14px; cursor: pointer; border-radius: 4px;">Transmit</button>
        </form>

        <div style="text-align: right; margin-top: 15px;">
            <a href="?reset_game=1" style="color: #ff3366; font-family: 'Courier New', monospace; font-size: 12px; text-decoration: none; border: 1px solid #ff3366; padding: 5px 10px; border-radius: 4px;">⚡ Reset Core Matrix (Neustart)</a>
        </div>
    </div>

    <!-- JAVASCRIPT: Steuert das seitenweise Auslesen und die Tastatur-Navigation -->
    <script>
        const form = document.getElementById('terminal-form');
        const input = document.getElementById('terminal-input');
        const screen = document.getElementById('terminal-screen');
        
        let allPages = [];        // Speichert die aufgeteilten Story-Seiten
        let currentPageIndex = 0; // Aktuelle sichtbare Seite

        // Teilt den gigantischen Java-Textblock in exakt lesbare Display-Seiten auf
        function paginateText(text) {
            const lines = text.split('\n');
            const linesPerPage = 17; // Maximale Zeilenanzahl, damit absolut nichts scrollt!
            const pages = [];
            
            for (let i = 0; i < lines.length; i += linesPerPage) {
                pages.push(lines.slice(i, i + linesPerPage).join('\n'));
            }
            return pages;
        }

        // Zeigt die ausgewählte Seite an
        function showPage(pageIndex) {
            if (pageIndex < 0 || pageIndex >= allPages.length) return;
            currentPageIndex = pageIndex;
            
            // Text direkt auf das Display werfen
            screen.innerHTML = allPages[pageIndex];
            
            // Wenn es noch weitere Seiten gibt, blenden wir einen Navigations-Indikator ein
            if (currentPageIndex < allPages.length - 1) {
                screen.innerHTML += "\n\n\n\n\n\n[--- DRÜCKE PFEIL-RECHTS (➔) ODER BILD-AB FÜR NÄCHSTE SEITE (Seite " + (currentPageIndex + 1) + "/" + allPages.length + ") ---]";
            }
        }

        // Sendet die Daten im Hintergrund via AJAX an den Server
        function transmitData(inputValue) {
            const formData = new FormData();
            formData.append('player_input', inputValue);

            fetch('?ajax_action=submit_input', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                allPages = paginateText(data);
                // WICHTIG: Nach einer Eingabe springen wir automatisch auf die ALLERLETZTE Seite des Textes,
                // damit du sofort die aktuelle Szene und das neue Auswahlmenü siehst!
                showPage(allPages.length - 1);
                
                input.value = '';
                input.focus();
            });
        }

        // Initialer Start beim Laden der Seite
        window.addEventListener('DOMContentLoaded', () => {
            transmitData('');
        });

        // Abfangen des Formulars (Wenn Enter gedrückt wird)
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            transmitData(input.value);
        });

        // TASTEN-STEUERUNG FÜR DAS NATIVE BLÄTTERN
        window.addEventListener('keydown', (e) => {
            // Nächste Seite: Pfeil-Rechts oder Bild-Runter
            if (e.key === "ArrowRight" || e.key === "PageDown") {
                if (currentPageIndex < allPages.length - 1) {
                    e.preventDefault();
                    showPage(currentPageIndex + 1);
                }
            }
            // Vorherige Seite: Pfeil-Links oder Bild-Hoch
            if (e.key === "ArrowLeft" || e.key === "PageUp") {
                if (currentPageIndex > 0) {
                    e.preventDefault();
                    showPage(currentPageIndex - 1);
                }
            }
        });
        
        input.focus();
    </script>

<?php
endif; 

// 3. Holt den HTML-Code aus dem Puffer
$content = ob_get_clean();

// 4. Ruft das Layout auf
renderLayout("Tactical Simulation Deck", $content);
?>
