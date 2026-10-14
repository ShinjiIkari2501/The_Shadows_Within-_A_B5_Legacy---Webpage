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
    
    // Initialisiert das Logbuch und die Historie falls komplett neu
    if (!isset($_SESSION['game_log'])) {
        $_SESSION['game_log'] = "[SYSTEM]: Establishing persistent sub-space matrix...\n";
    }
    if (!isset($_SESSION['input_history'])) {
        $_SESSION['input_history'] = [];
    }

    $jarPath = __DIR__ . '/B5_Project.jar';

    // Wenn der Spieler eine Eingabe abschickt
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['player_input'])) {
        // Füge die neue Eingabe der dauerhaften Historie hinzu
        $_SESSION['input_history'][] = trim($_POST['player_input']);
    }

    // --- SYNCHRONISIERTER PROZESS-START ---
    if (file_exists($jarPath)) {
        $descriptorspec = [
            0 => ["pipe", "r"], // stdin
            1 => ["pipe", "w"], // stdout
            2 => ["pipe", "w"]  // stderr
        ];

        // Starte das Java-Spiel frisch
        $process = proc_open("java -jar " . escapeshellarg($jarPath), $descriptorspec, $pipes);

        if (is_resource($process)) {
            // Blockieren kurzzeitig aktivieren für stabiles Schreiben
            stream_set_blocking($pipes[0], 1);
            stream_set_blocking($pipes[1], 1);

            // Jage alle bisherigen Entscheidungen nacheinander in Javas Scanner
            foreach ($_SESSION['input_history'] as $pastInput) {
                // Prüft ob die Pipe noch offen ist, um Broken Pipes zu verhindern
                if (is_resource($process)) {
                    fwrite($pipes[0], $pastInput . "\n");
                    fflush($pipes[0]);
                    usleep(10000); // 10ms Puffer
                }
            }

            // Wir schicken einen finalen leeren Enter-Schlag hinterher, falls Java im Prolog wartet
            fwrite($pipes[0], "\n");
            fflush($pipes[0]);

            // Schließt den Input-Kanal, damit Java fertig liest und den gesamten Text ausgibt
            fclose($pipes[0]);

            // Liest die komplette, generierte Textantwort aus Javas System.out
            $output = stream_get_contents($pipes[1]);
            
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);

            // Aktualisiere das grüne Textfenster mit der echten Story-Chronologie
            $_SESSION['game_log'] = "[SYSTEM]: Tactical Link Stable.\n\n" . htmlspecialchars($output);
        }
    } else {
        $_SESSION['game_log'] = "[ERROR]: B5_Project.jar not found in directory: " . htmlspecialchars($jarPath);
    }
?>

    <!-- Das reinrassige Retro-Text-Terminal -->
    <div style="border: 1px solid rgba(96, 172, 243, 0.3); border-radius: 4px; box-shadow: 0 0 20px rgba(0,0,0,0.7); background: #010203; max-width: 850px; margin: 15px auto; padding: 20px;">
        
        <!-- Das Textfenster scrollt immer automatisch mit -->
        <pre id="terminal-screen" style="width: 100%; height: 450px; overflow-y: auto; color: #00ffcc; font-family: 'Courier New', monospace; font-size: 16px; white-space: pre-wrap; margin: 0 0 20px 0; background: #000; padding: 15px; border-radius: 4px; border: 1px solid #111;"><?php echo $_SESSION['game_log']; ?></pre>

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
        unset($_SESSION['game_log']);
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
