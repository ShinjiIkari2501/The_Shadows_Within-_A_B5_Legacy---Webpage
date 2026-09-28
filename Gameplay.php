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
// INTERSTELLAR PYTHON PIPELINE ENGINE (Führt deine B5_Project.txt direkt aus)
// ==========================================================================
if (isset($_POST['execute_command']) && $isLoggedIn === 1) {
    header('Content-Type: application/json');
    $input = trim($_POST['execute_command']);
    
    // Initialisiert den Spielstand im Session-Speicher bei Neustart
    if (!isset($_SESSION['b5_game_inputs']) || $input === "restart") {
        $_SESSION['b5_game_inputs'] = [];
    }
    
    // Fügt die neue Eingabe der Historie hinzu (außer beim ersten Laden)
    if ($input !== "" && $input !== "start_game" && $input !== "restart") {
        $_SESSION['b5_game_inputs'][] = $input;
    }
    
    // Pfad zu deinem originalen Python-Skript im Repository
    $pythonScript = __DIR__ . '/Python/B5_Project.txt'; 
    
    // Bereitet den Shell-Befehl vor und leitet Fehler um
    $command = "python3 " . escapeshellarg($pythonScript) . " 2>&1";
    
    // Öffnet den bidirektionalen Prozess zum Python-Interpreter
    $descriptorspec = [
        0 => ["pipe", "r"], // STDIN (Eingaben an Python übergeben)
        1 => ["pipe", "w"], // STDOUT (Ausgaben von Python abfangen)
        2 => ["pipe", "w"]  // STDERR
    ];
    
    $process = proc_open($command, $descriptorspec, $pipes);
    
    if (is_resource($process)) {
        // Füttert Python nacheinander mit allen bisherigen Entscheidungen
        foreach ($_SESSION['b5_game_inputs'] as $pastInput) {
            fwrite($pipes, $pastInput . "\n");
        }
        fclose($pipes); // Schließt den Eingabekanal, damit Python weiterrechnet
        
        // Holt die generierte Text-Ausgabe deines Skripts ab
        $output = stream_get_contents($pipes);
        fclose($pipes);
        fclose($pipes);
        proc_close($process);
        
        echo json_encode(["status" => "success", "output" => $output]);
    } else {
        echo json_encode(["status" => "error", "output" => "SYSTEM ERROR: Sub-space interpreter offline."]);
    }
    exit();
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
            <div id="terminal-output" style="height: 420px !important; overflow-y: auto !important; color: #60acf3 !important; font-size: 1.05em !important; line-height: 1.5 !important; text-align: left !important; padding-right: 10px !important; margin-bottom: 15px !important; white-space: pre-wrap !important;"></div>

            <!-- Eingabezeile für den Spieler -->
            <div style="display: flex !important; align-items: center !important; border-top: 1px solid rgba(255, 153, 0, 0.2) !important; padding-top: 10px !important;">
                <span style="color: #ff9900 !important; font-weight: bold !important; margin-right: 10px !important;">cmd_vector></span>
                <input type="text" id="terminal-input" style="flex: 1 !important; background: transparent !important; border: none !important; color: #fff !important; font-family: monospace !important; font-size: 1.1em !important; outline: none !important;" placeholder="Type a command and press Enter..." autofocus>
            </div>
        </div>

        <!-- 2. DIE TAKTISCHE BEFEHLS-LEGENDE -->
        <div style="flex: 1 !important; min-width: 240px !important; max-width: 300px !important; background-color: rgba(13, 20, 59, 0.5) !important; border: 1px solid rgba(96, 172, 243, 0.3) !important; backdrop-filter: blur(5px) !important; -webkit-backdrop-filter: blur(5px) !important; border-radius: 6px !important; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5) !important; padding: 15px !important; font-family: Arial, sans-serif !important; box-sizing: border-box !important; text-align: left !important; height: fit-content !important; align-self: flex-start !important;">
            <h4 style="color: #ff9900 !important; text-shadow: 0 0 5px rgba(255, 153, 0, 0.5) !important; margin-top: 0 !important; margin-bottom: 12px !important; font-family: 'B5Station', Arial, sans-serif !important; letter-spacing: 0.5px !important; border-bottom: 1px solid rgba(96, 172, 243, 0.2) !important; padding-bottom: 5px !important;">
                🛰️ COMMAND MATRIX
            </h4>
            <p style="font-size: 0.85em !important; color: hsl(0, 9%, 85%) !important; margin-bottom: 15px !important; line-height: 1.4 !important;">
                Nutze die Eingaben deiner originalen Python-Datei, um die Simulation direkt zu steuern:
            </p>
            <ul style="list-style-type: none !important; padding: 0 !important; margin: 0 !important; font-size: 0.9em !important; line-height: 1.7 !important;">
                <li style="margin-bottom: 8px !important;"><strong style="color: #60acf3 !important; font-family: monospace !important;">1 / 2 / 3</strong><br><span style="color: #aaa !important; font-size: 0.85em !important;">➔ Pfade wählen / Entscheidungen treffen</span></li>
                <li style="margin-bottom: 8px !important;"><strong style="color: #60acf3 !important; font-family: monospace !important;">clear / cls</strong><br><span style="color: #aaa !important; font-size: 0.85em !important;">➔ Bildschirm leeren</span></li>
                <li style="margin-bottom: 8px !important;"><strong style="color: #60acf3 !important; font-family: monospace !important;">restart</strong><br><span style="color: #aaa !important; font-size: 0.85em !important;">➔ Setzt die Simulation komplett zurück</span></li>
            </ul>
        </div>

    </div>    <!-- JAVASCRIPT: ECHTE PYTHON-BRÜCKE MIT PIPELINE-STREAMING -->
    <script type="text/javascript">
        const outputDiv = document.getElementById("terminal-output");
        const inputField = document.getElementById("terminal-input");

        function sendCommandToPython(commandText) {
            const formData = new FormData();
            formData.append('execute_command', commandText);

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    // Löscht das Fenster bei einem kompletten Neustart
                    if (commandText === "restart") {
                        outputDiv.innerHTML = "";
                    }
                    // Gibt den exakten, echten Print-Text deines Python-Skripts aus
                    printToTerminal(data.output);
                } else {
                    printToTerminal("\n⚠ TRANSMISSION ERROR: " + data.output);
                }
            })
            .catch(error => {
                printToTerminal("\n⚠ UPLINK CRITICAL: Core communication lost.");
            });
        }

        function printToTerminal(text) {
            const pre = document.createElement("pre");
            pre.style.margin = "0";
            pre.style.whiteSpace = "pre-wrap";
            pre.style.fontFamily = "monospace";
            pre.style.color = "#60acf3";
            pre.style.fontSize = "1.05em";
            pre.textContent = text;
            outputDiv.appendChild(pre);
            
            // 🛰️ AUTO-SCROLL-RELAIS: Schiebt das Sichtfenster perfekt mit, während die Inputzeile steht!
            outputDiv.scrollTop = outputDiv.scrollHeight;
        }

        // Fängt den Enter-Tastendruck ab
        inputField.addEventListener("keydown", function(event) {
            if (event.keyCode === 13) {
                const befehl = inputField.value.trim();
                inputField.value = "";
                
                if (befehl === "") return;
                
                // Lokaler Bildschirm-Leerer
                if (befehl.toLowerCase() === "clear" || befehl.toLowerCase() === "cls") {
                    outputDiv.innerHTML = "";
                    printToTerminal("[Display cache cleared]");
                    return;
                }

                // Sendet den echten Befehl (1, 2, 3 etc.) direkt an das Python-Skript
                sendCommandToPython(befehl);
            }
        });

        // Startet dein originales Python-Skript direkt beim Laden der Seite
        window.onload = function() {
            printToTerminal("🔄 INITIALIZING CORE INTERPRETER...");
            setTimeout(function() {
                sendCommandToPython("start_game");
            }, 400);
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
$seitenInhalt = ob_get_clean(); 
renderLayout("B5 Legacy - Simulation Deck", $seitenInhalt); 
?>

