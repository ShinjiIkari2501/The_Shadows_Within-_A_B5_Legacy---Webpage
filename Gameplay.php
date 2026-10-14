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

// SPIELZUSTAND INITIALISIEREN: Setzt das Start-Szenario fest
if (!isset($_SESSION['b5_scene_id'])) {
    $_SESSION['b5_scene_id'] = 0; // 0 = Charaktererstellung
    $_SESSION['b5_log'] = "=== THE SHADOWS WITHIN - CORE MATRIX ===\r\n\r\nSELECT YOUR BACKGROUND SYSTEM:\r\n1 = Intelligence (Ex-Agent)\r\n2 = Underworld (Smuggler)\r\n";
}

// INPUT-VERARBEITUNG: Steuert chronologisch durch deine Kampagne
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['player_input'])) {
    $input = trim($_POST['player_input']);
    
    switch ($_SESSION['b5_scene_id']) {
        case 0: // Charaktererstellung abgeschlossen
            if ($input === '1' || $input === '2') {
                $_SESSION['b5_scene_id'] = 1;
                $_SESSION['b5_log'] .= "\n> " . htmlspecialchars($input) . "\n\n[SYSTEM]: Character Matrix confirmed.\n\nSCENE 1: PROLOGUE – THE ECHO OF THE PAST\nEXT. MOON IN NO MAN'S LAND - NIGHT\n\nA lonely, dying campfire crackles in the cold wind. CITIZEN G'KAR sits motionlessly by the fire. His deep, rough voice breaks the absolute silence of the night.\n\nG'KAR: 'The universe is in a constant change, unstoppable and blind to the suffering of those who cross its paths. We believed we had won the light of peace... but the darkness does not sleep.'\n\n[Press Enter to proceed to Babylon 5]";
            } else {
                $_SESSION['b5_log'] .= "\n> " . htmlspecialchars($input) . "\n[INVALID COMMAND]: Please select 1 or 2.";
            }
            break;

        case 1: // Prolog -> Wechsel zu Babylon 5 (Szene 14 Medlab)
            $_SESSION['b5_scene_id'] = 14;
            $_SESSION['b5_log'] .= "\n> " . htmlspecialchars($input) . "\n\nSCENE 14: INT. BABYLON 5 - MEDLAB WAKING\n\nYour eyes flutter open. The harsh, sterile fluorescent lights of Medlab hum softly overhead. Dr. Stephen Franklin stands over your bio-bed, checking a monitor.\n\nDR. FRANKLIN: 'Easy now. You\'ve been out for a while. Your neural readings were completely off the charts, but your vitals are stabilizing.'\n\nSuddenly, an urgent comm-alert echoes through the room!\n\nCHOOSE YOUR ACTION:\n1 = Ask about the comm-alert\n2 = Try to stand up immediately";
            break;

        case 14: // Medlab Entscheidungsbaum
            if ($input === '1') {
                $_SESSION['b5_scene_id'] = 15;
                $_SESSION['b5_log'] .= "\n> " . htmlspecialchars($input) . "\n\nDr. Franklin frowns and taps his link. The alert signals incoming hostile targets near the sector boundary...";
            } elseif ($input === '2') {
                $_SESSION['b5_scene_id'] = 15;
                $_SESSION['b5_log'] .= "\n> " . htmlspecialchars($input) . "\n\nYou push yourself up, but dizziness hits you instantly. Franklin forces you back down onto the bed...";
            } else {
                $_SESSION['b5_log'] .= "\n> " . htmlspecialchars($input) . "\n[INVALID COMMAND]: Please select 1 or 2.";
            }
            break;

        default:
            $_SESSION['b5_log'] .= "\n> " . htmlspecialchars($input) . "\n\nThe chronological stream moves into deep space...";
            break;
    }
}

// Logik für den Reset-Befehl
if (isset($_GET['reset_game'])) {
    unset($_SESSION['b5_scene_id']);
    unset($_SESSION['b5_log']);
    header("Location: Gameplay.php");
    exit;
}
?>

<?php if ($isLoggedIn === 1): ?>

    <!-- Das reinrassige Retro-Text-Terminal (Unzerstörbar, lädt sofort) -->
    <div style="border: 1px solid rgba(96, 172, 243, 0.3); border-radius: 4px; box-shadow: 0 0 20px rgba(0,0,0,0.7); background: #010203; max-width: 850px; margin: 15px auto; padding: 20px;">
        
        <!-- Gibt den Textpuffer flüssig aus -->
        <pre id="terminal-screen" style="width: 100%; height: 450px; overflow-y: auto; color: #00ffcc; font-family: 'Courier New', monospace; font-size: 16px; white-space: pre-wrap; margin: 0 0 20px 0; background: #000; padding: 15px; border-radius: 4px; border: 1px solid #111;"><?php echo $_SESSION['b5_log']; ?></pre>

        <!-- Die interaktive Input-Leiste -->
        <form method="POST" style="display: flex; gap: 10px;">
            <span style="color: #00ffcc; font-family: 'Courier New', monospace; font-size: 18px; line-height: 40px;">&gt;</span>
            <input type="text" 
                   name="player_input" 
                   style="flex-grow: 1; background: #000; border: 1px solid #00ffcc; color: #00ffcc; padding: 10px; font-family: 'Courier New', monospace; font-size: 16px; border-radius: 4px;" 
                   placeholder="Type option and press Enter..." 
                   autocomplete="off"
                   id="terminal-input"
                   required>
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
endif; // Schließt die Login-Prüfung

// 3. Holt den HTML-Code aus dem Puffer
$content = ob_get_clean();

// 4. Ruft das Layout auf
renderLayout("Tactical Simulation Deck", $content);
?>
