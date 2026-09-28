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
// INTERSTELLAR PYTHON BRIDGE (Verarbeitet die Terminal-Eingaben live)
// ==========================================================================
if (isset($_POST['execute_command']) && $isLoggedIn === 1) {
    header('Content-Type: application/json');
    $input = trim($_POST['execute_command']);
    
    // Pfad zu deinem originalen Python-Skript im Repository
    $pythonScript = __DIR__ . '/Python/B5_Project.txt'; 
    
    // Befehl für die Linux-Konsole auf Render.com vorbereiten
    // Nutzt escapeshellarg für maximale Serversicherheit
    $command = "python3 " . escapeshellarg($pythonScript) . " 2>&1";
    
    // Öffnet den Prozess, um Befehle an Python zu senden und die Antwort zu lesen
    $descriptorspec = [
        0 => ["pipe", "r"], // STDIN (Eingabe an Python)
        1 => ["pipe", "w"], // STDOUT (Ausgabe von Python)
        2 => ["pipe", "w"]  // STDERR
    ];
    
    $process = proc_open($command, $descriptorspec, $pipes);
    
    if (is_resource($process)) {
        // Falls ein Befehl eingegeben wurde, senden wir ihn an Pythons input()
        if ($input !== "" && $input !== "start_game") {
            fwrite($pipes[0], $input . "\n");
        }
        fclose($pipes[0]);
        
        // Liest die Antwort des Python-Skripts
        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);
        
        echo json_encode(["status" => "success", "output" => $output]);
    } else {
        echo json_encode(["status" => "error", "output" => "SYSTEM ERROR: Failed to boot Python sub-space core."]);
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
                Nutze die originalen Eingaben deiner Python-Projektdatei, um die Simulation zu steuern:
            </p>
            <ul style="list-style-type: none !important; padding: 0 !important; margin: 0 !important; font-size: 0.9em !important; line-height: 1.7 !important;">
                <li style="margin-bottom: 8px !important;"><strong style="color: #60acf3 !important; font-family: monospace !important;">1 / 2</strong><br><span style="color: #aaa !important; font-size: 0.85em !important;">➔ Pfade & Auswahl treffen</span></li>
                <li style="margin-bottom: 8px !important;"><strong style="color: #60acf3 !important; font-family: monospace !important;">clear / cls</strong><br><span style="color: #aaa !important; font-size: 0.85em !important;">➔ Bildschirm leeren</span></li>
                <li style="margin-bottom: 8px !important;"><strong style="color: #60acf3 !important; font-family: monospace !important;">restart</strong><br><span style="color: #aaa !important; font-size: 0.85em !important;">➔ Startet die Simulation neu</span></li>
            </ul>
        </div>

    </div>    <!-- JAVASCRIPT: ECHTE PYTHON-BRÜCKE MIT AUTONOMEM SCROLL-RELAIS -->
    <script type="text/javascript">
        const outputDiv = document.getElementById("terminal-output");
        const inputField = document.getElementById("terminal-input");

        // 🛰️ Sendet die Befehle per AJAX an den Server, um Python zu triggern
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
                    // Schreibt den originalen Print-Output aus deiner B5_Project.txt ins Feld
                    printToTerminal(data.output);
                } else {
                    printToTerminal("\n⚠ TRANSMISSION ERROR: " + data.output);
                }
            })
            .catch(error => {
                printToTerminal("\n⚠ UPLINK CRITICAL: Connection to Python core lost.");
            });
        }

        function printToTerminal(text) {
            const pre = document.createElement("pre");
            pre.style.margin = "0";
            pre.style.whiteSpace = "pre-wrap";
            pre.style.fontFamily = "monospace";
            pre.textContent = text;
            outputDiv.appendChild(pre);
            
            // 🛰️ AUTONOMES SCROLL-RELAIS: Verhält sich exakt wie dein Seitenraster!
            // Zwingt das Sichtfenster nach unten, während das Gehäuse stabil bleibt
            outputDiv.scrollTop = outputDiv.scrollHeight;
        }

        // Fängt den Enter-Tastendruck des Commanders ab
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

                // Zeigt die eigene Eingabe im Terminal an
                printToTerminal("\ncmd_vector> " + befehl);
                
                // Schickt den echten Befehl (1, 2, etc.) direkt an das Python-Skript
                sendCommandToPython(befehl);
            }
        });

        // Zündet das originale Python-Intro beim ersten Laden der Seite
        window.onload = function() {
            printToTerminal("🔄 INITIALIZING SUB-SPACE CHANNELS...");
            setTimeout(function() {
                sendCommandToPython("start_game");
            }, 500);
        };
    </script>

<?php else: ?>
    <p style="color: #ff3333; text-shadow: 0 0 4px rgba(255, 51, 51, 0.4); text-align: center;">
        ⛔ ACCESS DENIED: Active crew credentials required to access the Simulation Deck. Please login via the left console.
    </p>
<?php endif; ?>

<?php 
// 4. Den Inhalt aus dem Zwischenspeicher holen
$seitenInhalt = ob_get_clean(); 

// 5. Das Layout mit dem taktischen Titel rendern
renderLayout("B5 Legacy - Tactical Deck", $seitenInhalt); 
?>

