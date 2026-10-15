<?php
// 1. Die Layout-Zentrale laden (Startet auch die Session)
require_once 'Includes/layout.php';

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start();

// LOGIN-PRÜFUNG
$isLoggedIn = 0;
$userId = 0;
if (isset($_SESSION['eingeloggt']) && $_SESSION['eingeloggt'] === true) {
    $isLoggedIn = 1;
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1; 
}

// =========================================================================
// INTELLIGENTER DATENBANK-DETEKTOR & FALLBACK-SYSTEM
// =========================================================================
$useDatabase = false;
$pdo = null;

try {
    $pdo = new PDO("mysql:host=localhost;dbname=deine_datenbank;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $useDatabase = true;
} catch (PDOException $e) {
    $useDatabase = false; // Geräuschloser Fallback auf Session-Sicherung (z.B. auf Render)
}

// -------------------------------------------------------------------------
// SLOT-STEUERUNG (Verwaltet den festen Szenen-Zustand des Spielers)
// -------------------------------------------------------------------------
function getSaveSlots($pdo, $userId, $useDatabase) {
    if ($useDatabase && $pdo !== null) {
        try {
            $stmt = $pdo->prepare("SELECT slot_number, updated_at, save_data FROM game_saves WHERE user_id = ?");
            $stmt->execute([$userId]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $slots = [];
            foreach ($results as $row) {
                $slots[$row['slot_number']] = [
                    'date' => date('d.m.Y H:i', strtotime($row['updated_at'])),
                    'data' => json_decode($row['save_data'], true)
                ];
            }
            return $slots;
        } catch (Exception $e) {}
    }
    return $_SESSION['fallback_slots'][$userId] ?? [];
}

function saveSlotData($pdo, $userId, $slot, $stateData, $useDatabase) {
    if ($useDatabase && $pdo !== null) {
        try {
            $jsonData = json_encode($stateData);
            $stmt = $pdo->prepare("INSERT INTO game_saves (user_id, slot_number, save_data) 
                                   VALUES (?, ?, ?) 
                                   ON DUPLICATE KEY UPDATE save_data = ?, updated_at = CURRENT_TIMESTAMP");
            $stmt->execute([$userId, $slot, $jsonData, $jsonData]);
            return;
        } catch (Exception $e) {}
    }
    $_SESSION['fallback_slots'][$userId][$slot] = [
        'date' => date('d.m.Y H:i'),
        'data' => $stateData
    ];
}

// SLOT INTERCEPTOR (Verarbeitet das Laden und Zurücksetzen)
if ($isLoggedIn === 1) {
    if (isset($_GET['select_slot'])) {
        $slot = (int)$_GET['select_slot'];
        $_SESSION['active_save_slot'] = $slot;
        $saves = getSaveSlots($pdo, $userId, $useDatabase);
        
        // Lädt den exakten Zustand aus der Datenbank-Matrix
        $_SESSION['b5_current_scene'] = $saves[$slot]['data']['scene'] ?? "CREATION";
        $_SESSION['b5_player_origin'] = $saves[$slot]['data']['origin'] ?? "Standard";
        header("Location: Gameplay.php");
        exit;
    }
    
    if (isset($_GET['delete_slot'])) {
        $slot = (int)$_GET['delete_slot'];
        if ($useDatabase && $pdo !== null) {
            try { $stmt = $pdo->prepare("DELETE FROM game_saves WHERE user_id = ? AND slot_number = ?"); $stmt->execute([$userId, $slot]); } catch (Exception $e) {}
        }
        unset($_SESSION['fallback_slots'][$userId][$slot]);
        if (isset($_SESSION['active_save_slot']) && $_SESSION['active_save_slot'] === $slot) {
            unset($_SESSION['active_save_slot']);
            unset($_SESSION['b5_current_scene']);
            unset($_SESSION['b5_player_origin']);
        }
        header("Location: Gameplay.php");
        exit;
    }
}

// =========================================================================
// ASYNCHRONER LIVE-INPUT (DIREKTES SZNEN-ARRAY STATT FEHLERHAFTER PIPES)
// =========================================================================
if ($isLoggedIn === 1 && isset($_GET['ajax_action']) && $_GET['ajax_action'] === 'submit_input') {
    if (!isset($_SESSION['b5_current_scene'])) {
        $_SESSION['b5_current_scene'] = "CREATION";
        $_SESSION['b5_player_origin'] = "Standard";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rawInput = isset($_POST['player_input']) ? trim($_POST['player_input']) : '';
        
        // DAS COMPREHENSIVE ROUTING-ARRAY (Alle Szenen 1 bis 32 lückenlos abgedeckt!)
        switch ($_SESSION['b5_current_scene']) {
            case "CREATION":
                if ($rawInput === "1") { $_SESSION['b5_player_origin'] = "Intelligence"; $_SESSION['b5_current_scene'] = "PROLOGUE"; }
                if ($rawInput === "2") { $_SESSION['b5_player_origin'] = "Underworld"; $_SESSION['b5_current_scene'] = "PROLOGUE"; }
                break;
            case "PROLOGUE":   $_SESSION['b5_current_scene'] = "SCENE_2"; break;
            case "SCENE_2":    $_SESSION['b5_current_scene'] = "SCENE_3"; break;
            case "SCENE_3":    $_SESSION['b5_current_scene'] = "SCENE_4"; break;
            case "SCENE_4":    $_SESSION['b5_current_scene'] = "SCENE_5"; break;
            case "SCENE_5":    $_SESSION['b5_current_scene'] = "SCENE_6"; break;
            case "SCENE_6":    $_SESSION['b5_current_scene'] = "SCENE_7"; break;
            case "SCENE_7":    $_SESSION['b5_current_scene'] = "SCENE_8"; break;
            case "SCENE_8":    $_SESSION['b5_current_scene'] = "SCENE_9"; break;
            case "SCENE_9":    $_SESSION['b5_current_scene'] = "SCENE_10"; break;
            case "SCENE_10":   $_SESSION['b5_current_scene'] = "SCENE_11"; break;
            case "SCENE_11":   $_SESSION['b5_current_scene'] = "SCENE_12"; break;
            case "SCENE_12":   $_SESSION['b5_current_scene'] = "SCENE_13"; break;
            case "SCENE_13":   $_SESSION['b5_current_scene'] = "SCENE_14"; break;
            case "SCENE_14":   $_SESSION['b5_current_scene'] = "SCENE_15"; break;
            
            // LÜCKENLOSE WEICHEN FÜR DIE ZWEITE HÄLFTE
            case "SCENE_15":   $_SESSION['b5_current_scene'] = "SCENE_16"; break;
            case "SCENE_16":   $_SESSION['b5_current_scene'] = "SCENE_17"; break;
            case "SCENE_17":   $_SESSION['b5_current_scene'] = "SCENE_18"; break;
            case "SCENE_18":   $_SESSION['b5_current_scene'] = "SCENE_19"; break;
            case "SCENE_19":   $_SESSION['b5_current_scene'] = "SCENE_20"; break;
            case "SCENE_20":   $_SESSION['b5_current_scene'] = "SCENE_21"; break;
            case "SCENE_21":   $_SESSION['b5_current_scene'] = "SCENE_22"; break;
            case "SCENE_22":   $_SESSION['b5_current_scene'] = "SCENE_23"; break;
            case "SCENE_23":   $_SESSION['b5_current_scene'] = "SCENE_24"; break;
            case "SCENE_24":   $_SESSION['b5_current_scene'] = "SCENE_25"; break;
            case "SCENE_25":   $_SESSION['b5_current_scene'] = "REUNION"; break;
            
            // ACT V GENEVA SHOWDOWN CORRIDOR
            case "REUNION":    $_SESSION['b5_current_scene'] = "GENEVA"; break;
            case "GENEVA":     $_SESSION['b5_current_scene'] = "CEILING"; break;
            case "CEILING":    $_SESSION['b5_current_scene'] = "BATTLE"; break;
            case "BATTLE":     $_SESSION['b5_current_scene'] = "CONCLUSION"; break;
            case "CONCLUSION": $_SESSION['b5_current_scene'] = "EPILOGUE"; break;
        }
        
        // Zustand im aktuellen SQL-Slot sichern
        if (isset($_SESSION['active_save_slot'])) {
            $saveState = ['scene' => $_SESSION['b5_current_scene'], 'origin' => $_SESSION['b5_player_origin']];
            saveSlotData($pdo, $userId, $_SESSION['active_save_slot'], $saveState, $useDatabase);
        }
    }

    // Java als saubere API aufrufen – Blitzschnell, sicher und ohne Pipes!
    $jarPath = __DIR__ . '/B5_Project.jar';
    $output = "";

    if (file_exists($jarPath)) {
        $scene = escapeshellarg($_SESSION['b5_current_scene']);
        $origin = escapeshellarg($_SESSION['b5_player_origin']);
        
        // Die Parameter werden als sichere Start-Argumente übergeben
        $command = "java -jar " . escapeshellarg($jarPath) . " $scene $origin 2>&1";
        $output = shell_exec($command);
    } else {
        $output = "[ERROR]: B5_Project.jar wurde im Webverzeichnis nicht gefunden!";
    }

    echo htmlspecialchars($output);
    exit;
}
?>
<h2>Tactical Simulation Deck</h2>
<h3>Early-Concept-Alpha / Operation: Pure Terminal Matrix</h3>

<?php if ($isLoggedIn === 1): ?>

    <!-- PANEL FÜR DIE 3 SAVE-SLOTS -->
    <div style="max-width: 850px; margin: 15px auto; display: flex; gap: 15px; justify-content: space-between;">
        <?php 
        $currentSaves = getSaveSlots($pdo, $userId, $useDatabase);
        for ($i = 1; $i <= 3; $i++): 
            $hasSave = isset($currentSaves[$i]);
            $isActive = (isset($_SESSION['active_save_slot']) && $_SESSION['active_save_slot'] === $i);
            $border = $isActive ? '2px solid #00ffcc' : '1px solid rgba(96, 172, 243, 0.3)';
            $bg = $isActive ? '#0a1a15' : '#050508';
        ?>
            <div style="flex: 1; background: <?php echo $bg; ?>; border: <?php echo $border; ?>; border-radius: 4px; padding: 15px; text-align: center; box-shadow: 0 0 10px rgba(0,0,0,0.5);">
                <div style="font-family: 'Courier New', monospace; font-size: 14px; font-weight: bold; color: #60acf3; margin-bottom: 5px;">SLOT 0<?php echo $i; ?></div>
                <div style="font-family: 'Courier New', monospace; font-size: 11px; color: #888; margin-bottom: 15px;">
                    <?php echo $hasSave ? "Stand: " . $currentSaves[$i]['date'] : "[LEERER SLOT]"; ?>
                </div>
                
                <a href="?select_slot=<?php echo $i; ?>" style="display: block; background: transparent; border: 1px solid #00ffcc; color: #00ffcc; padding: 5px 10px; font-family: 'Courier New', monospace; font-size: 12px; text-decoration: none; border-radius: 4px; margin-bottom: 8px;">
                    <?php echo $isActive ? "⚡ AKTIV" : ($hasSave ? " Laden" : " Starten"); ?>
                </a>
                
                <?php if ($hasSave): ?>
                    <a href="?delete_slot=<?php echo $i; ?>" onclick="return confirm('Slot wirklich löschen?');" style="display: block; color: #ff3366; font-family: 'Courier New', monospace; font-size: 11px; text-decoration: none; border: 1px solid #ff3366; padding: 3px 5px; border-radius: 4px;">✕ Löschen</a>
                <?php endif; ?>
            </div>
        <?php endfor; ?>
    </div>

    <?php if (isset($_SESSION['active_save_slot'])): ?>
        <!-- Das interaktive Retro-Text-Terminal -->
        <div style="border: 1px solid rgba(96, 172, 243, 0.3); border-radius: 4px; box-shadow: 0 0 20px rgba(0,0,0,0.7); background: #010203; max-width: 850px; margin: 20px auto; padding: 20px;">
            
            <div style="color: #60acf3; font-family: 'Courier New', monospace; font-size: 13px; margin-bottom: 12px; display: flex; justify-content: space-between; border-bottom: 1px solid rgba(96, 172, 243, 0.1); padding-bottom: 5px;">
                <span>[SLOT ACTIVE: 0<?php echo $_SESSION['active_save_slot']; ?>] <?php echo $useDatabase ? '<span style="color:#00ffcc;">[SQL MODE]</span>' : '<span style="color:#ffcc00;">[SESSION MODE]</span>'; ?></span>
                <span>Nutze das Mausrad zum Scrollen der Historie</span>
            </div>

            <pre id="terminal-screen" style="width: 100%; height: 450px; overflow-y: auto; color: #00ffcc; font-family: 'Courier New', monospace; font-size: 16px; white-space: pre-wrap; margin: 0 0 20px 0; background: #000; padding: 15px; border-radius: 4px; border: 1px solid #111;">[SYSTEM]: Initialisiere Matrix-Verbindung...</pre>

            <form id="terminal-form" style="display: flex; gap: 10px;">
                <span style="color: #00ffcc; font-family: 'Courier New', monospace; font-size: 18px; line-height: 40px;">&gt;</span>
                <input type="text" name="player_input" style="flex-grow: 1; background: #000; border: 1px solid #00ffcc; color: #00ffcc; padding: 10px; font-family: 'Courier New', monospace; font-size: 16px; border-radius: 4px;" placeholder="Option eintippen und Enter drücken..." autocomplete="off" id="terminal-input">
                <button type="submit" style="background: transparent; border: 1px solid #00ffcc; color: #00ffcc; padding: 0 20px; font-family: 'Courier New', monospace; font-size: 14px; cursor: pointer; border-radius: 4px;">Senden</button>
            </form>
        </div>

        <script>
            const form = document.getElementById('terminal-form');
            const input = document.getElementById('terminal-input');
            const screen = document.getElementById('terminal-screen');

            window.addEventListener('click', () => {
                if(input) input.focus();
            });

            function transmitData(inputValue) {
                const formData = new FormData();
                formData.append('player_input', inputValue);

                fetch('?ajax_action=submit_input', { method: 'POST', body: formData })
                .then(response => response.text())
                .then(data => {
                    screen.innerHTML = data;
                    setTimeout(() => {
                        screen.scrollTop = screen.scrollHeight;
                    }, 30);
                    input.value = '';
                    input.focus();
                });
            }

            window.addEventListener('DOMContentLoaded', () => { transmitData(''); });
            form.addEventListener('submit', (e) => { e.preventDefault(); transmitData(input.value); });
            input.focus();
        </script>
    <?php else: ?>
        <div style="max-width: 850px; margin: 40px auto; text-align: center; font-family: 'Courier New', monospace; color: #888; border: 1px dashed rgba(96, 172, 243, 0.3); padding: 30px; border-radius: 4px; background: #050508;">
            [SYSTEM matrix]: Verbindung zur Simulationsmatrix bereit.<br><br>
            <span style="color: #00ffcc;">Bitte wähle oben einen SLOT aus, um eine parallele Simulation zu starten!</span>
        </div>
    <?php endif; ?>

<?php else: ?>
    <div style="max-width: 850px; margin: 40px auto; text-align: center; font-family: 'Courier New', monospace; color: #888; border: 1px dashed rgba(96, 172, 243, 0.3); padding: 30px; border-radius: 4px; background: #050508;">
        [SYSTEM matrix]: Verbindung zur Simulationsmatrix bereit.<br><br>
        <span style="color: #00ffcc;">Bitte logge dich ein, um das Tactical Simulation Deck zu initialisieren.</span>
    </div>
<?php endif; ?>

<?php
// 4. Holt den HTML-Code aus dem Puffer und baut das Layout final zusammen
$content = ob_get_clean();
renderLayout("Tactical Simulation Deck", $content);
?>
