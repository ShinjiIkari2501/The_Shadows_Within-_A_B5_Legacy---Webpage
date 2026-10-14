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
<h3>Early-Concept-Alpha / Operation: Native Pipe</h3>

<?php 
if ($isLoggedIn === 1): 
    
    // Initialisiert den Spiel-Textpuffer im Session-Speicher falls noch leer
    if (!isset($_SESSION['game_log'])) {
        $_SESSION['game_log'] = "[SYSTEM]: Booting localized Java core...\n";
    }

    // Pfad zur JAR-Datei direkt im selben Hauptordner festlegen
    $jarPath = __DIR__ . '/B5_Project.jar';

    // Wenn der Spieler eine Eingabe abschickt
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['player_input'])) {
        $input = trim($_POST['player_input']) . "\n";

        // Fehlervermeidung: Falls die JAR fehlt, geben wir eine Warnung aus
        if (!file_exists($jarPath)) {
            $_SESSION['game_log'] .= "\n[ERROR]: B5_Project.jar not found in main directory!\n";
        } else {
            // Deskriptoren für die Prozess-Kommunikation festlegen (Stdin, Stdout, Stderr)
            $descriptorspec = [
                0 => ["pipe", "r"], // stdin (Schreiben an Java)
                1 => ["pipe", "w"], // stdout (Lesen aus Java)
                2 => ["pipe", "w"]  // stderr
            ];

            // Startet das Java-Spiel als nativen Hintergrundprozess
            $process = proc_open("java -jar " . escapeshellarg($jarPath), $descriptorspec, $pipes);

            if (is_resource($process)) {
                // Wenn bereits vorherige Inputs existierten, simulieren wir die Chronologie
                // und jagen die Eingabe direkt in Javas Standard-Eingabekanal (Scanner)
                fwrite($pipes[0], $input);
                fclose($pipes[0]);

                // Liest die Antwort direkt aus Javas System.out.println
                $output = stream_get_contents($pipes[1]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);

                // Fügt den neuen Text dem Logbuch hinzu
                $_SESSION['game_log'] .= "\n> " . htmlspecialchars(trim($input)) . "\n" . htmlspecialchars($output);
            }
        }
    }
?>

    <!-- Das reinrassige Retro-Text-Terminal (Keine Scripte, Keine iFrames) -->
    <div style="border: 1px solid rgba(96, 172, 243, 0.3); border-radius: 4px; box-shadow: 0 0 20px rgba(0,0,0,0.7); background: #010203; max-width: 850px; margin: 15px auto; padding: 20px;">
        
        <!-- Das Logbuch gibt den Text chronologisch aus -->
        <pre style="width: 100%; height: 400px; overflow-y: auto; color: #00ffcc; font-family: 'Courier New', monospace; font-size: 16px; white-space: pre-wrap; margin: 0 0 20px 0; background: #000; padding: 10px; border-radius: 4px; border: 1px solid #111;"><?php echo $_SESSION['game_log']; ?></pre>

        <!-- Das Eingabefeld schickt Daten direkt an Javas nextLine-Scanner -->
        <form method="POST" style="display: flex; gap: 10px;">
            <span style="color: #00ffcc; font-family: 'Courier New', monospace; font-size: 18px; line-height: 40px;">&gt;</span>
            <input type="text" 
                   name="player_input" 
                   style="flex-grow: 1; background: #000; border: 1px solid #00ffcc; color: #00ffcc; padding: 10px; font-family: 'Courier New', monospace; font-size: 16px; border-radius: 4px; autofocus" 
                   placeholder="Type option (e.g. 1, 2) and press Enter..." 
                   autocomplete="off"
                   required>
            <button type="submit" style="background: transparent; border: 1px solid #00ffcc; color: #00ffcc; padding: 0 20px; font-family: 'Courier New', monospace; font-size: 14px; cursor: pointer; border-radius: 4px;">Transmit</button>
        </form>

        <!-- Kleiner Reset-Button, falls man die Simulation von vorne beginnen will -->
        <div style="text-align: right; margin-top: 15px;">
            <a href="?reset_game=1" style="color: #ff3366; font-family: 'Courier New', monospace; font-size: 12px; text-decoration: none; border: 1px solid #ff3366; padding: 5px 10px; border-radius: 4px;">⚡ Reset Core Matrix</a>
        </div>
    </div>

<?php
    // Logik für den Reset-Button
    if (isset($_GET['reset_game'])) {
        unset($_SESSION['game_log']);
        header("Location: gameplay.php");
        exit;
    }
endif;

// 3. Holt den angesammelten HTML-Code aus dem Zwischenspeicher
$content = ob_get_clean();

// 4. Ruft die Funktion mit Titel und Inhalt passend zu deiner Layout.php auf
renderLayout("Tactical Simulation Deck", $content);
?>
