<?php 
// 1. Die Layout-Zentrale laden (Startet auch die Session)
require_once 'Includes/layout.php'; 

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start(); 

// LOGIN-PRÜFUNG: Sicher deklariert
$isLoggedIn = 0;
if (isset($_SESSION['eingeloggt']) && $_SESSION['eingeloggt'] === true) {
    $isLoggedIn = 1;
}

// ==========================================================================
// NATIVE PYTHON INTERACTION ENGINE (Verarbeitet deinen echten Code)
// ==========================================================================
$gameOutput = "";

if ($isLoggedIn === 1) {
    // Initialisiert die Eingabe-Historie bei Spielstart oder Reset
    if (!isset($_SESSION['b5_history']) || (isset($_POST['game_input']) && trim(strtolower($_POST['game_input'])) === 'restart')) {
        $_SESSION['b5_history'] = [];
    }

    // Fängt den neuen Befehl ab und fügt ihn der Historie hinzu
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['game_input'])) {
        $currentInput = trim($_POST['game_input']);
        if ($currentInput !== "" && strtolower($currentInput) !== 'restart') {
            $_SESSION['b5_history'][] = $currentInput;
        }
    }

    // Pfad zu deiner originalen Python-Datei
    $pythonScript = __DIR__ . '/Python/B5_Project.txt'; 
    $command = "python3 " . escapeshellarg($pythonScript) . " 2>&1";

    $descriptorspec = [
        0 => ["pipe", "r"], // STDIN
        1 => ["pipe", "w"], // STDOUT
        2 => ["pipe", "w"]  // STDERR
    ];

    $process = proc_open($command, $descriptorspec, $pipes);

    if (is_resource($process)) {
        // Füttert Python nacheinander mit all deinen getätigten Schritten
        foreach ($_SESSION['b5_history'] as $pastInput) {
            fwrite($pipes, $pastInput . "\n");
        }
        fclose($pipes); // Schließt die Eingabe, damit Python den Text ausgibt

        // Holt den originalen Print-Text aus deiner B5_Project.txt
        $gameOutput = stream_get_contents($pipes);
        fclose($pipes);
        fclose($pipes);
        proc_close($process);
    } else {
        $gameOutput = "SYSTEM ERROR: Sub-space core execution failed.";
    }
}
?>

<h2>Tactical Simulation Deck</h2>
<h3>Early-Concept-Alpha / Operation: Test</h3>

<?php if ($isLoggedIn === 1): ?>

    <p style="color: #00c850; text-shadow: 0 0 4px rgba(0, 200, 80, 0.4); margin-bottom: 20px;">
        ✔ Credentials verified. Access granted to Sub-Space Alpha Core.
    </p>

    <!-- CONTAINER: Setzt Terminal und Legende sauber nebeneinander -->
    <div style="display: flex !important; flex-direction: row !important; flex-wrap: wrap !important; gap: 20px !important; max-width: 950px !important; margin: 0 auto !important; justify-content: center !important; box-sizing: border-box !important;">
        
        <!-- 1. DAS TERMINAL-GEHÄUSE -->
        <div style="flex: 2 !important; min-width: 450px !important; background-color: #050714 !important; border: 2px solid #ff9900 !important; border-radius: 6px !important; box-shadow: 0 0 15px rgba(255, 153, 0, 0.3) !important; padding: 15px !important; font-family: monospace !important; box-sizing: border-box !important;">
            
            <!-- Terminal Kopfzeile -->
            <div style="border-bottom: 1px solid rgba(255, 153, 0, 0.3) !important; padding-bottom: 8px !important; margin-bottom: 12px !important; color: #ff9900 !important; font-size: 0.85em !important; display: flex !important; justify-content: space-between !important;">
                <span>[SUBLINK_CORE_TERMINAL_v2.0]</span>
                <span style="color: #00c850;">● ACTIVE_FEED</span>
            </div>

            <!-- Ausgabefenster des Spiels -->
            <div id="terminal-output" style="height: 420px !important; overflow-y: auto !important; color: #60acf3 !important; font-size: 1.05em !important; line-height: 1.5 !important; text-align: left !important; padding-right: 10px !important; margin-bottom: 15px !important; white-space: pre-wrap !important;">
                <pre style="margin: 0; white-space: pre-wrap; font-family: monospace; color: #60acf3; font-size: 1.05em;"><?php echo htmlspecialchars($gameOutput); ?></pre>
            </div>

            <!-- Eingabezeile für den Spieler (Formular-gesteuert für 100% Serversicherheit) -->
            <form method="post" action="" style="margin: 0; padding: 0;">
                <div style="display: flex !important; align-items: center !important; border-top: 1px solid rgba(255, 153, 0, 0.2) !important; padding-top: 10px !important;">
                    <span style="color: #ff9900 !important; font-weight: bold !important; margin-right: 10px !important;">cmd_vector></span>
                    <input type="text" name="game_input" id="terminal-input" style="flex: 1 !important; background: transparent !important; border: none !important; color: #fff !important; font-family: monospace !important; font-size: 1.1em !important; outline: none !important;" placeholder="Type a command and press Enter..." autofocus autocomplete="off">
                </div>
            </form>
        </div>        <!-- 2. DIE TAKTISCHE BEFEHLS-LEGENDE -->
        <div style="flex: 1 !important; min-width: 240px !important; max-width: 300px !important; background-color: rgba(13, 20, 59, 0.5) !important; border: 1px solid rgba(96, 172, 243, 0.3) !important; backdrop-filter: blur(5px) !important; -webkit-backdrop-filter: blur(5px) !important; border-radius: 6px !important; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5) !important; padding: 15px !important; font-family: Arial, sans-serif !important; box-sizing: border-box !important; text-align: left !important; height: fit-content !important; align-self: flex-start !important;">
            <h4 style="color: #ff9900 !important; text-shadow: 0 0 5px rgba(255, 153, 0, 0.5) !important; margin-top: 0 !important; margin-bottom: 12px !important; font-family: 'B5Station', Arial, sans-serif !important; letter-spacing: 0.5px !important; border-bottom: 1px solid rgba(96, 172, 243, 0.2) !important; padding-bottom: 5px !important;">
                🛰️ COMMAND MATRIX
            </h4>
            <p style="font-size: 0.85em !important; color: hsl(0, 9%, 85%) !important; margin-bottom: 15px !important; line-height: 1.4 !important;">
                Nutze die Eingaben deiner originalen Python-Datei, um die Simulation direkt zu steuern:
            </p>
            <ul style="list-style-type: none !important; padding: 0 !important; margin: 0 !important; font-size: 0.9em !important; line-height: 1.7 !important;">
                <li style="margin-bottom: 8px !important;"><strong style="color: #60acf3 !important; font-family: monospace !important;">1 / 2 / 3</strong><br><span style="color: #aaa !important; font-size: 0.85em !important;">➔ Pfade wählen / Entscheidungen treffen</span></li>
                <li style="margin-bottom: 8px !important;"><strong style="color: #60acf3 !important; font-family: monospace !important;">clear</strong><br><span style="color: #aaa !important; font-size: 0.85em !important;">➔ Leert den lokalen Verlauf</span></li>
                <li style="margin-bottom: 8px !important;"><strong style="color: #60acf3 !important; font-family: monospace !important;">restart</strong><br><span style="color: #aaa !important; font-size: 0.85em !important;">➔ Setzt die Simulation komplett zurück</span></li>
            </ul>
        </div>

    </div>

    <!-- AUTO-SCROLL-RELAIS: Hält das Textfenster synchron mit deinem Seitenraster -->
    <script type="text/javascript">
        window.onload = function() {
            const outputDiv = document.getElementById("terminal-output");
            const inputField = document.getElementById("terminal-input");
            
            if (outputDiv) {
                // Zwingt die Box nach dem Neuladen sofort zum neuesten Text ganz unten zu springen
                outputDiv.scrollTop = outputDiv.scrollHeight;
            }
            if (inputField) {
                inputField.focus();
            }
        };
    </script>

<?php else: ?>
    <div style="background-color: rgba(255, 51, 51, 0.1) !important; border: 1px solid #ff3333 !important; padding: 20px !important; border-radius: 6px !important; text-align: center !important; margin: 30px auto !important; max-width: 600px !important;">
        <h4 style="color: #ff3333 !important; text-shadow: 0 0 5px #ff3333 !important; margin-bottom: 10px !important; font-family: 'B5Station', Arial, sans-serif !important;">
            🔒 RESTRICTED SECTOR: EARLY-CONCEPT-ALPHA-TEST CLOSED
        </h4>
        <p style="font-size: 0.95em !important;">The test simulation deck is currently running under a private alpha phalanx. Please log in.</p>
    </div>
<?php endif; ?>

<?php 
// 4. Den Inhalt aus dem Zwischenspeicher holen
$seitenInhalt = ob_get_clean(); 

// 5. Das Layout mit dem taktischen Titel rendern
renderLayout("B5 Legacy - Simulation Deck", $seitenInhalt); 
?>

