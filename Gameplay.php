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
                <input type="text" id="terminal-input" style="flex: 1 !important; background: transparent !important; border: none !important; color: #fff !important; font-family: monospace !important; font-size: 1.1em !important; outline: none !important;" placeholder="Type a command (e.g. 1, 2, look) and press Enter..." autofocus>
            </div>
        </div>

        <!-- 2. DIE TAKTISCHE BEFEHLS-LEGENDE -->
        <div style="flex: 1 !important; min-width: 240px !important; max-width: 300px !important; background-color: rgba(13, 20, 59, 0.5) !important; border: 1px solid rgba(96, 172, 243, 0.3) !important; backdrop-filter: blur(5px) !important; -webkit-backdrop-filter: blur(5px) !important; border-radius: 6px !important; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5) !important; padding: 15px !important; font-family: Arial, sans-serif !important; box-sizing: border-box !important; text-align: left !important; height: fit-content !important; align-self: flex-start !important;">
            <h4 style="color: #ff9900 !important; text-shadow: 0 0 5px rgba(255, 153, 0, 0.5) !important; margin-top: 0 !important; margin-bottom: 12px !important; font-family: 'B5Station', Arial, sans-serif !important; letter-spacing: 0.5px !important; border-bottom: 1px solid rgba(96, 172, 243, 0.2) !important; padding-bottom: 5px !important;">
                🛰️ COMMAND MATRIX
            </h4>
            <p style="font-size: 0.85em !important; color: hsl(0, 9%, 85%) !important; margin-bottom: 15px !important; line-height: 1.4 !important;">
                Nutze die Eingaben deines Adventure-Vektors, um die Story-Simulation zu steuern:
            </p>
            <ul style="list-style-type: none !important; padding: 0 !important; margin: 0 !important; font-size: 0.9em !important; line-height: 1.7 !important;">
                <li style="margin-bottom: 8px !important;"><strong style="color: #60acf3 !important; font-family: monospace !important;">1 / 2</strong><br><span style="color: #aaa !important; font-size: 0.85em !important;">➔ Herkunft & Pfade wählen</span></li>
                <li style="margin-bottom: 8px !important;"><strong style="color: #60acf3 !important; font-family: monospace !important;">look / scan</strong><br><span style="color: #aaa !important; font-size: 0.85em !important;">➔ Umgebung scannen</span></li>
                <li style="margin-bottom: 8px !important;"><strong style="color: #60acf3 !important; font-family: monospace !important;">clear</strong><br><span style="color: #aaa !important; font-size: 0.85em !important;">➔ Bildschirm leeren</span></li>
                <li style="margin-bottom: 8px !important;"><strong style="color: #60acf3 !important; font-family: monospace !important;">restart</strong><br><span style="color: #aaa !important; font-size: 0.85em !important;">➔ Setzt das Spiel komplett zurück</span></li>
                <li style="margin-bottom: 8px !important;"><strong style="color: #60acf3 !important; font-family: monospace !important;">quit / exit</strong><br><span style="color: #aaa !important; font-size: 0.85em !important;">➔ Beendet die Terminal-Sitzung</span></li>
            </ul>
        </div>

    </div>

    <!-- JAVASCRIPT-TERMINAL-LOGIK (UMGEHT JEDEN ABSTURZ) -->
    <script type="text/javascript">
        const outputDiv = document.getElementById("terminal-output");
        const inputField = document.getElementById("terminal-input");
        const current_user = "<?php echo htmlspecialchars($_SESSION['username'] ?? 'Commander'); ?>";

        function printToTerminal(text) {
            const div = document.createElement("div");
            div.textContent = text;
            outputDiv.appendChild(div);
            outputDiv.scrollTop = outputDiv.scrollHeight;
        }

        // Simulierter Spielverlauf basierend auf deiner B5_Project.txt
        function zeigeIntro() {
            // Textfeld wieder freischalten (falls vorher 'quit' eingegeben wurde)
            inputField.disabled = false;
            inputField.placeholder = "Type a command (e.g. 1, 2, look) and press Enter...";
            inputField.focus();

            printToTerminal("=== SIMULATION BOOT SEQUENCE COMPLETE ===");
            printToTerminal("Uplink aktiv. Willkommen im System, Commander " + current_user + ".\n");
            printToTerminal("[CHARAKTER-AUSWAHL: DIE RECHENSCHAFT DER VERGANGENHEIT]");
            printToTerminal("Bevor du in die Schächte eintauchst, wähle deine Herkunft:");
            printToTerminal("1 = GEHEIMDIENST-VETERAN (Hoher Analyse-Fokus, kennt militärische Protokolle)");
            printToTerminal("2 = UNTERWELT-SCHMUGGLER (Kennt illegale Schleusen und unregistrierte Routen)");
        }

        inputField.addEventListener("keydown", function(event) {
            if (event.keyCode === 13) {
                const befehl = inputField.value.trim().toLowerCase();
                inputField.value = "";
                
                printToTerminal("\n> " + befehl);

                // ==========================================================================
                // NEU: RESTART & QUIT FUNKTIONEN (Direkt abgefangen)
                // ==========================================================================
                if (befehl === "restart" || befehl === "reset") {
                    outputDiv.innerHTML = "";
                    printToTerminal("🔄 REBOOTING CORE... Display cache flushed.");
                    // Kleiner Zeitversatz für das Sci-Fi-Feeling
                    setTimeout(zeigeIntro, 600);
                    return;
                }
                
                if (befehl === "quit" || befehl === "exit") {
                    printToTerminal("\n🛑 SHUTDOWN SEQUENCE INITIATED...");
                    printToTerminal("💾 Progress data routed to Interstellar Alliance archives.");
                    printToTerminal("Connection closed. Safe travels, Commander " + current_user + ". 🖖");
                    
                    // Sperrt das Eingabefeld unmissverständlich
                    inputField.disabled = true;
                    inputField.placeholder = "📟 TERMINAL OFFLINE. Type 'restart' to boot again.";
                    return;
                }

                // ==========================================================================
                // DEINE BESTEHENDE SPIELLOGIK
                // ==========================================================================
                if (befehl === "1") {
                    printToTerminal("\n-> Du bist ein Phantom des ehemaligen Earthforce-Geheimdienstes.");
                    printToTerminal("\n=== AKT I: DER FUNKE IM DRECK ===");
                    printToTerminal("Die Luft im Braunen Sektor von Babylon 5 schmeckt nach recyceltem Sauerstoff...");
                    printToTerminal("Plötzlich stolpert eine Gestalt aus einer Wartungsschleuse.");
                    printToTerminal("Ein Mann in der zerfetzten Kluft der Rangers bricht direkt vor dir zusammen!");
                    printToTerminal("Er presst dir einen Kristall in die Hand: 'Nimm ihn... Bring ihn... persönlich zum Kommandostab...'");
                } else if (befehl === "2") {
                    printToTerminal("\n-> Du bist ein Geist des Braunen Sektors, ein Meister unregistrierter Fracht.");
                    printToTerminal("\n=== AKT I: DER FUNKE IM DRECK ===");
                    printToTerminal("Die Luft im Braunen Sektor von Babylon 5 schmeckt nach recyceltem Sauerstoff...");
                    printToTerminal("Ein Ranger bricht vor dir zusammen und übergibt dir einen geheimen Kristall.");
                } else if (befehl === "look" || befehl === "scan") {
                    printToTerminal("Sensoren scannen den Braunen Sektor. Psi-Corps-Agenten patrouillieren in der Nähe.");
                } else if (befehl === "clear") {
                    outputDiv.innerHTML = "";
                    printToTerminal("=== DISPLAY LOG CACHE CLEARED ===");
                } else {
                    printToTerminal("Unbekannter Vektor: '" + befehl + "'. Nutze '1', '2', 'look', 'restart' oder 'quit'.");
                }
            }
        });

        // Sofortzündung beim Laden
        window.onload = function() {
            zeigeIntro();
        }

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
