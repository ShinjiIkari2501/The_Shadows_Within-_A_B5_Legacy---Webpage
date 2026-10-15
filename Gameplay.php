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

// LOGIK-HUB FÜR HINTERGRUND-ABFRAGEN (AJAX)
if ($isLoggedIn === 1 && isset($_GET['ajax_action']) && $_GET['ajax_action'] === 'submit_input') {
    if (!isset($_SESSION['input_history'])) {
        $_SESSION['input_history'] = [];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rawInput = isset($_POST['player_input']) ? $_POST['player_input'] : '';
        $_SESSION['input_history'][] = $rawInput;
    }

    $jarPath = __DIR__ . '/B5_Project.jar';
    $output = "";

    if (file_exists($jarPath)) {
        $inputString = "";
        foreach ($_SESSION['input_history'] as $pastInput) {
            $inputString .= $pastInput . "\n";
        }
        
        $command = "echo " . escapeshellarg($inputString) . " | java -jar " . escapeshellarg($jarPath) . " 2>&1";
        $output = shell_exec($command);
    } else {
        $output = "[ERROR]: B5_Project.jar wurde im Webverzeichnis nicht gefunden!";
    }

    echo htmlspecialchars($output);
    exit;
}

// Logik für den Reset-Befehl
if (isset($_GET['reset_game'])) {
    unset($_SESSION['input_history']);
    header("Location: gameplay.php");
    exit;
}
?>

<h2>Tactical Simulation Deck</h2>
<h3>Early-Concept-Alpha / Operation: Ultra-Fluid Engine</h3>

<?php if ($isLoggedIn === 1): ?>

    <!-- Das reinrassige Retro-Text-Terminal -->
    <div style="border: 1px solid rgba(96, 172, 243, 0.3); border-radius: 4px; box-shadow: 0 0 20px rgba(0,0,0,0.7); background: #010203; max-width: 850px; margin: 15px auto; padding: 20px;">
        
        <!-- Der Terminal-Bildschirm -->
        <pre id="terminal-screen" style="width: 100%; height: 450px; overflow-y: auto; color: #00ffcc; font-family: 'Courier New', monospace; font-size: 16px; white-space: pre-wrap; margin: 0 0 20px 0; background: #000; padding: 15px; border-radius: 4px; border: 1px solid #111;">[SYSTEM]: Initializing secure interface link...</pre>

        <!-- Die interaktive Input-Leiste -->
        <form id="terminal-form" style="display: flex; gap: 10px;">
            <span style="color: #00ffcc; font-family: 'Courier New', monospace; font-size: 18px; line-height: 40px;">&gt;</span>
            <input type="text" 
                   name="player_input" 
                   style="flex-grow: 1; background: #000; border: 1px solid #00ffcc; color: #00ffcc; padding: 10px; font-family: 'Courier New', monospace; font-size: 16px; border-radius: 4px;" 
                   placeholder="Type option or press Enter to continue..." 
                   autocomplete="off"
                   id="terminal-input">
            <button type="submit" style="background: transparent; border: 1px solid #00ffcc; color: #00ffcc; padding: 0 20px; font-family: 'Courier New', monospace; font-size: 14px; cursor: pointer; border-radius: 4px;">Transmit</button>
        </form>

        <div style="text-align: right; margin-top: 15px;">
            <a href="?reset_game=1" style="color: #ff3366; font-family: 'Courier New', monospace; font-size: 12px; text-decoration: none; border: 1px solid #ff3366; padding: 5px 10px; border-radius: 4px;">⚡ Reset Core Matrix</a>
        </div>
    </div>

    <!-- JAVASCRIPT: Steuert das Terminal mit Typewriter-Effekt und Auto-Scroll -->
    <script>
        const form = document.getElementById('terminal-form');
        const input = document.getElementById('terminal-input');
        const screen = document.getElementById('terminal-screen');
        
        let typeInterval = null; // Speichert den aktiven Schreib-Timer

        // Funktion für den flüssigen Schreibmaschinen-Effekt
        function typeWriter(text) {
            // Falls noch ein alter Text getippt wird, stoppen wir ihn sofort
            if (typeInterval) clearInterval(typeInterval);
            
            screen.innerHTML = ''; // Bildschirm leeren
            let index = 0;
            const speed = 15; // Geschwindigkeit in Millisekunden pro Buchstabe

            typeInterval = setInterval(() => {
                if (index < text.length) {
                    // Verarbeitet Zeilenumbrüche korrekt
                    screen.innerHTML += text.charAt(index);
                    index++;
                    
                    // Zwingt den Bildschirm, synchron mit dem Tippen nach unten zu scrollen
                    screen.scrollTop = screen.scrollHeight;
                } else {
                    clearInterval(typeInterval);
                }
            }, speed);
        }

        // Funktion, um die Daten asynchron vom Server zu holen
        function sendInput(inputValue) {
            const formData = new FormData();
            formData.append('player_input', inputValue);

            fetch('?ajax_action=submit_input', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                typeWriter(data); // Startet den Schreibmaschinen-Effekt mit den neuen Daten
                input.value = ''; // Eingabefeld leeren
                input.focus();    // Fokus zurücksetzen
            });
        }

        // Erstmaliges Laden beim Seitenstart triggern
        window.addEventListener('DOMContentLoaded', () => {
            sendInput('');
        });

        // Abfangen des Formular-Absendens
        form.addEventListener('submit', (e) => {
            e.preventDefault(); 
            sendInput(input.value);
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
