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
// DATENBANK-DETEKTOR & FALLBACK-SYSTEM
// =========================================================================
$useDatabase = false;
$pdo = null;

try {
    $pdo = new PDO("mysql:host=localhost;dbname=deine_datenbank;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $useDatabase = true;
} catch (PDOException $e) {
    $useDatabase = false; // Fallback auf Session-Sicherung für Render.com
}

// -------------------------------------------------------------------------
// SLOT-STEUERUNG
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

function saveSlotData($pdo, $userId, $slot, $history, $useDatabase) {
    if ($useDatabase && $pdo !== null) {
        try {
            $jsonData = json_encode($history);
            $stmt = $pdo->prepare("INSERT INTO game_saves (user_id, slot_number, save_data) 
                                   VALUES (?, ?, ?) 
                                   ON DUPLICATE KEY UPDATE save_data = ?, updated_at = CURRENT_TIMESTAMP");
            $stmt->execute([$userId, $slot, $jsonData, $jsonData]);
            return;
        } catch (Exception $e) {}
    }
    $_SESSION['fallback_slots'][$userId][$slot] = [
        'date' => date('d.m.Y H:i'),
        'data' => $history
    ];
}

// SLOT INTERCEPTOR (Laden und Löschen von Speicherständen)
if ($isLoggedIn === 1) {
    if (isset($_GET['select_slot'])) {
        $slot = (int)$_GET['select_slot'];
        $_SESSION['active_save_slot'] = $slot;
        $saves = getSaveSlots($pdo, $userId, $useDatabase);
        $_SESSION['input_history'] = $saves[$slot]['data'] ?? [];
        header("Location: Gameplay.php");
        exit;
    }
    
    if (isset($_GET['delete_slot'])) {
        $slot = (int)$_GET['delete_slot'];
        if ($useDatabase && $pdo !== null) {
            try { $stmt = $pdo->prepare("DELETE FROM game_saves WHERE user_id = ? AND slot_number = ?"); $stmt->execute([$userId, $slot]); } catch (Exception $e) {}
        }
        if (isset($_SESSION['fallback_slots'][$userId][$slot])) {
            unset($_SESSION['fallback_slots'][$userId][$slot]);
        }
        if (isset($_SESSION['active_save_slot']) && $_SESSION['active_save_slot'] === $slot) {
            unset($_SESSION['active_save_slot']);
            unset($_SESSION['input_history']);
        }
        header("Location: Gameplay.php");
        exit;
    }
}

// =========================================================================
// ASYNCHRONER LIVE-INPUT HUB
// =========================================================================
if ($isLoggedIn === 1 && isset($_GET['ajax_action']) && $_GET['ajax_action'] === 'submit_input') {
    if (!isset($_SESSION['input_history'])) {
        $_SESSION['input_history'] = [];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rawInput = isset($_POST['player_input']) ? trim($_POST['player_input']) : '';
        $_SESSION['input_history'][] = $rawInput;
        
        if (isset($_SESSION['active_save_slot'])) {
            saveSlotData($pdo, $userId, $_SESSION['active_save_slot'], $_SESSION['input_history'], $useDatabase);
        }
    }

    $jarPath = __DIR__ . '/B5_Project.jar';
    $output = "";

    if (file_exists($jarPath)) {
        $descriptorspec = [
            0 => ["pipe", "r"], // stdin
            1 => ["pipe", "w"], // stdout
            2 => ["pipe", "w"]  // stderr
        ];

        $process = proc_open("java -jar " . escapeshellarg($jarPath), $descriptorspec, $pipes);

        if (is_resource($process)) {
            $inputString = "";
            
            if (count($_SESSION['input_history']) > 0) {
                foreach ($_SESSION['input_history'] as $pastInput) {
                    $inputString .= $pastInput . "\n";
                }
            }
            
            // Ein Puffer füttert nachfolgende Scanner-Abfragen der aktuellen Szene
            for ($p = 0; $p < 100; $p++) {
                $inputString .= "\n";
            }

            // Korrektur: Wir schreiben dediziert in den Kanal-Index [0]
            @fwrite($pipes[0], $inputString);
            @fflush($pipes[0]);
            @fclose($pipes[0]);

            // Synchrones Auslesen des stdout-Kanals [1]
            stream_set_blocking($pipes[1], 1);
            usleep(150000);

            while ($line = fgets($pipes[1])) {
                $output .= $line;
                $read = [$pipes[1]];
                $write = null;
                $except = null;
                if (stream_select($read, $write, $except, 0, 50000) === 0) {
                    break; 
                }
            }

            @fclose($pipes[1]);
            @fclose($pipes[2]);
            @proc_close($process);
        } else {
            $output = "[ERROR]: Das Java-Triebwerk konnte nicht gestartet werden.";
        }
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
