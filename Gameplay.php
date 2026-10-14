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
<h3>Early-Concept-Alpha / Operation: Persistent Matrix</h3>

<?php 
if ($isLoggedIn === 1): 
    
    // Initialisiert die dauerhafte Historie falls komplett neu
    if (!isset($_SESSION['input_history'])) {
        $_SESSION['input_history'] = [];
    }

    $jarPath = __DIR__ . '/B5_Project.jar';

    // Wenn der Spieler eine neue Entscheidung abschickt
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['player_input'])) {
        $_SESSION['input_history'][] = trim($_POST['player_input']);
    }

    // --- EXECUTION ENGINE: Startet Java frisch und simuliert den Zustand ---
    $output = "";
    if (file_exists($jarPath)) {
        $descriptorspec = [
            0 => ["pipe", "r"], // stdin (Schreiben an Java)
            1 => ["pipe", "w"], // stdout (Lesen aus Java)
            2 => ["pipe", "w"]  // stderr
        ];

        $process = proc_open("java -jar " . escapeshellarg($jarPath), $descriptorspec, $pipes);

        if (is_resource($process)) {
            // KORREKTUR: Wir schreiben direkt in den Eingangskanal $pipes[0]
            foreach ($_SESSION['input_history'] as $pastInput) {
                @fwrite($pipes[0], $pastInput . "\n");
            }
            
            // Ein abschließender Zeilenumbruch triggert wartende Scanner
            @fwrite($pipes[0], "\n");
            @fflush($pipes[0]);
            fclose($pipes[0]); // Signalisiert Java: Eingabe beendet. Bitte verarbeiten!

            // KORREKTUR: Wir lesen die Story direkt aus dem Ausgangskanal $pipes[1]
            $output = stream_get_contents($pipes[1]);
            
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);
        }
    } else {
        $output = "[ERROR]: B5_Project.jar wurde im Verzeichnis nicht gefunden!\nPfad: " . $jarPath;
    }
?>

    <!-- Das reinrassige Retro-Text-Terminal -->
    <div style="border: 1px solid rgba(96, 172, 243, 0.3); border-radius: 4px; box-shadow: 0 0 20px rgba(0,0,0,0.7); background: #010203; max-width: 850px; margin: 15px auto; padding: 20px;">
        
        <!-- Gibt die aktuelle Story aus Java aus -->
        <pre id="terminal-screen" style="width: 100%; height: 450px; overflow-y: auto; color: #00ffcc; font-family: 'Courier New', monospace; font-size: 16px; white-space: pre-wrap; margin: 0 0 20px 0; background: #000; padding: 15px; border-radius: 4px; border: 1px solid #111;"><?php echo htmlspecialchars($output); ?></pre>

        <!-- Die interaktive Input-Leiste -->
        <form method="POST" style="display: flex; gap: 10px;">
            <span style="color: #00ffcc; font-family: 'Courier New', monospace; font-size: 18px; line-height: 40px;">&gt;</span>
            <input type="text" 
                   name="player_input" 
                   style="flex-grow: 1; background: #000; border: 1px solid #00ffcc; color: #00ffcc; padding: 10px; font-family: 'Courier New', monospace; font-size: 16px; border-radius: 4px;" 
                   placeholder="Type option (e.g. 1, 2) or press Enter to continue..." 
                   autocomplete="off"
                   id="terminal-input">
            <button type="submit" style="background: transparent; border: 1px solid #00ffcc; color: #00ffcc; padding: 0 20px; font-family: 'Courier New', monospace; font-size: 14px; cursor: pointer; border-radius: 4px;">Transmit</button>
        </form>

        <div style="text-align: right; margin-top: 15px;">
            <a href="?reset_game=1" style="color: #ff3366; font-family: 'Courier New', monospace; font-size: 12px; text-decoration: none; border: 1px solid #ff3366; padding: 5px 10px; border-radius: 4px;">⚡ Reset Core Matrix</a>
        </div>
    </div>

    <script>
        document.getElementById('terminal-input').focus();
        const screen = document.getElementById('terminal-screen');
        screen.scrollTop = screen.scrollHeight;
    </script>

<?php
    // Logik für den Reset-Befehl
    if (isset($_GET['reset_game'])) {
        unset($_SESSION['input_history']);
        header("Location: Gameplay.php");
        exit;
    }
endif;

// 3. Holt den HTML-Code aus dem Puffer
$content = ob_get_clean();

// 4. Renders the layout title and body
renderLayout("Tactical Simulation Deck", $content);
?>
