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
    
    // Initialisiert das Logbuch falls noch leer
    if (!isset($_SESSION['game_log'])) {
        $_SESSION['game_log'] = "[SYSTEM]: Establishing persistent sub-space matrix...\n";
    }

    $jarPath = __DIR__ . '/B5_Project.jar';

    // Wenn der Spieler eine Eingabe abschickt
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['player_input'])) {
        $input = trim($_POST['player_input']) . "\n";
        $_SESSION['game_log'] .= "\n> " . htmlspecialchars(trim($input)) . "\n";

        if (file_exists($jarPath)) {
            $descriptorspec = [
                0 => ["pipe", "r"], // stdin
                1 => ["pipe", "w"], // stdout
                2 => ["pipe", "w"]  // stderr
            ];

            // Wir starten den Prozess und füttern ihn mit der GESAMTEN bisherigen Historie des Spielers!
            // Da PHP bei jedem Seitenaufruf neu lädt, simulieren wir den Zustand, indem wir alle
            // bisherigen Entscheidungen nacheinander in Javas Scanner jagen.
            if (!isset($_SESSION['input_history'])) {
                $_SESSION['input_history'] = [];
            }
            $_SESSION['input_history'][] = trim($_POST['player_input']);

            $process = proc_open("java -jar " . escapeshellarg($jarPath), $descriptorspec, $pipes);

            if (is_resource($process)) {
                // Deaktiviert das Blockieren beim Lesen, damit PHP nicht einfriert
                stream_set_blocking($pipes[1], 0);

                // 1. Alle bisherigen Entscheidungen nacheinander einspielen, um Java auf den aktuellen Stand zu bringen
                foreach ($_SESSION['input_history'] as $pastInput) {
                    fwrite($pipes[0], $pastInput . "\n");
                    fflush($pipes[0]);
                    usleep(50000); // 50ms Puffer, damit der Java-Scanner Zeit zum Verarbeiten hat
                }

                fclose($pipes[0]); // Schließt den Input, damit Java weiß, dass die Eingabe fertig ist

                // 2. Die gesamte Antwort bis zu diesem Punkt auslesen
                $output = "";
                // Wir geben Java ein kurzes Zeitfenster, um alle Szenen-Texte zu generieren
                usleep(200000); 
                while ($frame = fgets($pipes[1])) {
                    $output .= $frame;
                }

                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);

                // KORREKTUR: Wir überschreiben das Logbuch mit der sauberen, vollständigen Chronologie aus Java!
                $_SESSION['game_log'] = "[SYSTEM]: Persistent tactical link active.\n\n" . htmlspecialchars($output);
            }
        } else {
            $_SESSION['game_log'] .= "[ERROR]: B5_Project.jar not found!\n";
        }
    } else {
        // Erstmaliges Laden: Wir triggern das Spiel einmal nackt ohne Input, um das Hauptmenü anzuzeigen
        if (file_exists($jarPath) && count($_SESSION['input_history'] ?? []) === 0) {
            $descriptorspec = [0 => ["pipe", "r"], 1 => ["pipe", "w"], 2 => ["pipe", "w"]];
            $process = proc_open("java -jar " . escapeshellarg($jarPath), $descriptorspec, $pipes);
            if (is_resource($process)) {
                fclose($pipes[0]);
                usleep(100000);
                $_SESSION['game_log'] = "[SYSTEM]: Core initialized.\n\n" . htmlspecialchars(stream_get_contents($pipes[1]));
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);
            }
        }
    }
?>

    <!-- Das reinrassige Retro-Text-Terminal -->
    <div style="border: 1px solid rgba(96, 172, 243, 0.3); border-radius: 4px; box-shadow: 0 0 20px rgba(0,0,0,0.7); background: #010203; max-width: 850px; margin: 15px auto; padding: 20px;">
        
        <!-- Das Textfenster scrollt jetzt automatisch immer nach ganz unten zum neuesten Text -->
        <pre id="terminal-screen" style="width: 100%; height: 450px; overflow-y: auto; color: #00ffcc; font-family: 'Courier New', monospace; font-size: 16px; white-space: pre-wrap; margin: 0 0 20px 0; background: #000; padding: 15px; border-radius: 4px; border: 1px solid #111;"><?php echo $_SESSION['game_log']; ?></pre>

        <!-- Die interaktive Input-Leiste -->
        <form method="POST" style="display: flex; gap: 10px;">
            <span style="color: #00ffcc; font-family: 'Courier New', monospace; font-size: 18px; line-height: 40px;">&gt;</span>
            <input type="text" 
                   name="player_input" 
                   style="flex-grow: 1; background: #000; border: 1px solid #00ffcc; color: #00ffcc; padding: 10px; font-family: 'Courier New', monospace; font-size: 16px; border-radius: 4px;" 
                   placeholder="Type option (e.g. 1, 2) and press Enter..." 
                   autocomplete="off"
                   id="terminal-input"
                   required>
            <button type="submit" style="background: transparent; border: 1px solid #00ffcc; color: #00ffcc; padding: 0 20px; font-family: 'Courier New', monospace; font-size: 14px; cursor: pointer; border-radius: 4px;">Transmit</button>
        </form>

        <div style="text-align: right; margin-top: 15px;">
            <a href="?reset_game=1" style="color: #ff3366; font-family: 'Courier New', monospace; font-size: 12px; text-decoration: none; border: 1px solid #ff3366; padding: 5px 10px; border-radius: 4px;">⚡ Reset Core Matrix</a>
        </div>
    </div>

    <!-- Minimale JS-Komfortunterstützung: Zwingt den Fokus ins Feld und scrollt nach unten -->
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
        header("Location: gameplay.php");
        exit;
    }
endif;

// 3. Holt den HTML-Code aus dem Puffer
$content = ob_get_clean();

// 4. Renders the layout title and body
renderLayout("Tactical Simulation Deck", $content);
?>
