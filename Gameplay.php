<?php
// 1. Die Layout-Zentrale laden (Startet auch die Session)
require_once 'Includes/layout.php';

// ZENTRALE DATENBANK-VERBINDUNG (PDO)
if (!isset($pdo)) {
    try {
        // Passe hier bei Bedarf Host, DB-Name, User und Passwort an
        $pdo = new PDO("mysql:host=localhost;dbname=deine_datenbank;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("[CRITICAL ERROR]: Secure database link failure.");
    }
}

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start();

// LOGIN-PRÜFUNG
$isLoggedIn = 0;
$userId = 0;
if (isset($_SESSION['eingeloggt']) && $_SESSION['eingeloggt'] === true) {
    $isLoggedIn = 1;
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1; 
}

// -------------------------------------------------------------------------
// DATENBANK-FUNKTIONEN (Direkt integriert)
// -------------------------------------------------------------------------
function getSaveSlots($pdo, $userId) {
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
}

function saveSlotData($pdo, $userId, $slot, $history) {
    $jsonData = json_encode($history);
    $stmt = $pdo->prepare("INSERT INTO game_saves (user_id, slot_number, save_data) 
                           VALUES (?, ?, ?) 
                           ON DUPLICATE KEY UPDATE save_data = ?, updated_at = CURRENT_TIMESTAMP");
    $stmt->execute([$userId, $slot, $jsonData, $jsonData]);
}

// SLOT INTERCEPTOR (Laden und Löschen von Speicherständen)
if ($isLoggedIn === 1) {
    if (isset($_GET['select_slot'])) {
        $slot = (int)$_GET['select_slot'];
        $_SESSION['active_save_slot'] = $slot;
        $saves = getSaveSlots($pdo, $userId);
        $_SESSION['input_history'] = $saves[$slot]['data'] ?? [];
        header("Location: Gameplay.php");
        exit;
    }
    
    if (isset($_GET['delete_slot'])) {
        $slot = (int)$_GET['delete_slot'];
        $stmt = $pdo->prepare("DELETE FROM game_saves WHERE user_id = ? AND slot_number = ?");
        $stmt->execute([$userId, $slot]);
        
        if (isset($_SESSION['active_save_slot']) && $_SESSION['active_save_slot'] === $slot) {
            unset($_SESSION['active_save_slot']);
            unset($_SESSION['input_history']);
        }
        header("Location: Gameplay.php");
        exit;
    }
}

// LOGIK-HUB FÜR ASYNCHRONEN LIVE-INPUT (AJAX)
if ($isLoggedIn === 1 && isset($_GET['ajax_action']) && $_GET['ajax_action'] === 'submit_input') {
    if (!isset($_SESSION['input_history'])) {
        $_SESSION['input_history'] = [];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rawInput = isset($_POST['player_input']) ? $_POST['player_input'] : '';
        $_SESSION['input_history'][] = $rawInput;
        
        if (isset($_SESSION['active_save_slot'])) {
            saveSlotData($pdo, $userId, $_SESSION['active_save_slot'], $_SESSION['input_history']);
        }
    }

    $jarPath = __DIR__ . '/B5_Project.jar';
    $output = "";

    if (file_exists($jarPath)) {
        $inputString = "";
        foreach ($_SESSION['input_history'] as $pastInput) {
            $inputString .= $pastInput . "\n";
        }
        $command = "echo " . escapeshellarg($inputString) . " | java -jar " . escapeshellarg($jarPath) . " 2>&1";
        $output = shell_exec($command);
    } else {
        $output = "[ERROR]: B5_Project.jar wurde im Webverzeichnis nicht gefunden!";
    }

    echo htmlspecialchars($output);
    exit;
}

?>
<h2>Tactical Simulation Deck</h2>
<h3>Early-Concept-Alpha / Operation: Database-Linked Multi-Slot Matrix</h3>

<?php if ($isLoggedIn === 1): ?>

    <!-- PANEL FÜR DIE 3 DATENBANK-SLOTS -->
    <div style="max-width: 850px; margin: 15px auto; display: flex; gap: 15px; justify-content: space-between;">
        <?php 
        $currentSaves = getSaveSlots($pdo, $userId);
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
                <span>[DATABASE SLOT ACTIVE: 0<?php echo $_SESSION['active_save_slot']; ?>]</span>
                <span>Steuerung: [➔] / [Bild-Ab] Nächste Seite | [➔] / [Bild-Auf] Zurück</span>
            </div>

            <pre id="terminal-screen" style="width: 100%; height: 450px; overflow-y: hidden; color: #00ffcc; font-family: 'Courier New', monospace; font-size: 16px; white-space: pre-wrap; margin: 0 0 20px 0; background: #000; padding: 15px; border-radius: 4px; border: 1px solid #111;">[SYSTEM]: Verbinde mit lokaler Java-Engine...</pre>

            <form id="terminal-form" style="display: flex; gap: 10px;">
                <span style="color: #00ffcc; font-family: 'Courier New', monospace; font-size: 18px; line-height: 40px;">&gt;</span>
                <input type="text" name="player_input" style="flex-grow: 1; background: #000; border: 1px solid #00ffcc; color: #00ffcc; padding: 10px; font-family: 'Courier New', monospace; font-size: 16px; border-radius: 4px;" placeholder="Zahl eingeben oder Enter drücken..." autocomplete="off" id="terminal-input">
                <button type="submit" style="background: transparent; border: 1px solid #00ffcc; color: #00ffcc; padding: 0 20px; font-family: 'Courier New', monospace; font-size: 14px; cursor: pointer; border-radius: 4px;">Senden</button>
            </form>
        </div>

        <script>
            const form = document.getElementById('terminal-form');
            const input = document.getElementById('terminal-input');
            const screen = document.getElementById('terminal-screen');
            
            let allPages = [];
            let currentPageIndex = 0;

            // Teilt den Text blockweise in Seiten auf, passend zur Display-Größe
            function paginateText(text) {
                const lines = text.split('\n');
                const linesPerPage = 17; // Exakt kalibriert für 450px Höhe ohne Scrollbalken
                const pages = [];
                for (let i = 0; i < lines.length; i += linesPerPage) {
                    pages.push(lines.slice(i, i + linesPerPage).join('\n'));
                }
                return pages;
            }

            // Rendert die aufgerufene Seite
            function showPage(pageIndex) {
                if (pageIndex < 0 || pageIndex >= allPages.length) return;
                currentPageIndex = pageIndex;
                screen.innerHTML = allPages[pageIndex];
                
                // Fügt Navigations-Indikator hinzu, falls es noch Folgeseiten gibt
                if (currentPageIndex < allPages.length - 1) {
                    screen.innerHTML += "\n\n\n[--- DRÜCKE PFEIL-RECHTS (➔) ODER BILD-AB FÜR NÄCHSTE SEITE (Seite " + (currentPageIndex + 1) + "/" + allPages.length + ") ---]";
                }
            }

            // Klick-Fokus-Halter für die Tastatur
            window.addEventListener('click', () => {
                if(input) input.focus();
            });

            // AJAX-Übertragung
            function transmitData(inputValue) {
                const formData = new FormData();
                formData.append('player_input', inputValue);

                fetch('?ajax_action=submit_input', { method: 'POST', body: formData })
                .then(response => response.text())
                .then(data => {
                    allPages = paginateText(data);
                    // Springt nach einer Aktion immer direkt auf die letzte Seite zum neuen Menü
                    showPage(allPages.length - 1);
                    input.value = '';
                    input.focus();
                });
            }

            window.addEventListener('DOMContentLoaded', () => { transmitData(''); });
            form.addEventListener('submit', (e) => { e.preventDefault(); transmitData(input.value); });

            // NATIVE TASTEN-STEUERUNG ZUM BLÄTTERN
            window.addEventListener('keydown', (e) => {
                if (e.key === "ArrowRight" || e.key === "PageDown") {
                    if (currentPageIndex < allPages.length - 1) { 
                        e.preventDefault(); 
                        showPage(currentPageIndex + 1); 
                    }
                }
                if (e.key === "ArrowLeft" || e.key === "PageUp") {
                    if (currentPageIndex > 0) { 
                        e.preventDefault(); 
                        showPage(currentPageIndex - 1); 
                    }
                }
            });
            input.focus();
        </script>
    <?php else: ?>
        <div style="max-width: 850px; margin: 40px auto; text-align: center; font-family: 'Courier New', monospace; color: #888; border: 1px dashed rgba(96, 172, 243, 0.3); padding: 30px; border-radius: 4px; background: #050508;">
            [SYSTEM ALERT]: Keine aktive Verbindung zur Simulationsmatrix.<br><br>
            <span style="color: #00ffcc;">Bitte wähle oben einen Datenbank-SLOT aus, um ein Savegame parallel zu laden oder neu zu starten!</span>
        </div>
    <?php endif; ?>

<?php
endif; // Schließt das if ($isLoggedIn === 1) aus Teil 1

// 4. Holt den HTML-Code aus dem Puffer und baut das Layout final zusammen
$content = ob_get_clean();
renderLayout("Tactical Simulation Deck", $content);
?>
