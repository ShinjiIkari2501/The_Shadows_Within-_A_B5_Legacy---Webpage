<?php
/**
 * Gameplay.php - PART 1/3 (DIRECT ARCHITECTURE INTEGRATION)
 * BabCom Tactical Terminal Interface - Series IV v2264 (MAX-VIEW)
 */

require_once 'Includes/layout.php';
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = 0;
$userId = 0;
if (isset($_SESSION['eingeloggt']) && $_SESSION['eingeloggt'] === true) {
    $isLoggedIn = 1;
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
}

if (!isset($_SESSION['active_save_slot'])) {
    $_SESSION['active_save_slot'] = 1;
}

$activeSlot = $_SESSION['active_save_slot'];

// AJAX endpoint: includes the Game-Bridge directly (no cURL loopback)
if ($isLoggedIn === 1 && isset($_GET['ajax_action']) && $_GET['ajax_action'] === 'submit_input') {
    // PHP warnings must never be printed in front of the JSON (the browser would show "NETWORK DECOIL")
    ini_set('display_errors', '0');
    header('Content-Type: application/json; charset=UTF-8');

    // FIX: this line used to read "$POST" instead of "$_POST". A variable called $POST does not
    // exist in PHP, so the choice id got lost and always became 0. That is why the game never
    // moved on. $_POST is the built-in array that holds the data the JavaScript sends via FormData.
    // Only known actions are accepted (a "whitelist"); anything else is treated as INIT.
    $action = isset($_POST['action']) ? strtoupper(trim((string)$_POST['action'])) : 'INIT';
    if (!in_array($action, ['INIT', 'FETCH_NODE', 'EXECUTE_CHOICE', 'RESTART'], true)) {
        $action = 'INIT';
    }

    // The bridge only receives the action and the choice id. It reads user and save slot from the session itself.
    $bridgeRequest = ["action" => $action];
    if (isset($_POST['choice_id'])) {
        $bridgeRequest["choice_id"] = (int)$_POST['choice_id'];
    }
    // This flag tells Game-Bridge.php: "I was started by Gameplay.php, so $bridgeRequest can be used."
    define('B5_BRIDGE_INTERNAL', true);

    // __DIR__ is the folder of this file, so the path works no matter where the script is started from.
    if (file_exists(__DIR__ . '/Game-Bridge.php')) {
        include __DIR__ . '/Game-Bridge.php';
    } else {
        echo json_encode([
                "status" => "ERROR",
                "message" => "SYSTEM CRITICAL: Game-Bridge.php was not found on the server."
        ]);
    }
    exit;
}

if ($isLoggedIn === 1 && isset($_GET['select_slot'])) {
    $selectedSlot = (int)$_GET['select_slot'];
    if ($selectedSlot >= 1 && $selectedSlot <= 3) { // only the three existing save slots are allowed
        $_SESSION['active_save_slot'] = $selectedSlot;
    }
    header("Location: Gameplay.php");
    exit;
}
?>



<main class="game-main">
    <div class="babcom-viewport">
        <div class="babcom-header-bar">
            <span>[BABCOM MULTI-VECTOR COGNITIVE TERMINAL v4.2 - LIVE UPLINK]</span>
            <span>SYS-STATUS: COGNITIVE LINK SYNCHRONIZED</span>
            <span>TACTICAL ARRAY SLOT: 0<?= $activeSlot; ?></span>
        </div>

        <?php if ($isLoggedIn === 1): ?>
            <div class="slot-tabs">
                <?php for ($i = 1; $i <= 3; $i++):
                    $isActive = ($activeSlot === $i);
                    ?>
                    <a href="?select_slot=<?= $i; ?>" class="slot-tab<?= $isActive ? ' active' : ''; ?>">
                        TACTICAL COORD SLOT 0<?= $i; ?> <?= $isActive ? '• ACTIVE' : ''; ?>
                    </a>
                <?php endfor; ?>
            </div>

            <div class="babcom-hud">
                <div class="hud-stat-box" id="box-origin">
                    <div class="hud-label">Ident-Origin (Klasse)</div>
                    <div class="hud-value" id="hud-origin">Scanning...</div>
                </div>
                <div class="hud-stat-box" id="box-hp">
                    <div class="hud-label">Vital-Sign (Leben)</div>
                    <div class="hud-value"><span id="hud-hp">100</span>/100 HP</div>
                </div>
                <div class="hud-stat-box" id="box-credits">
                    <div class="hud-label">Capital (Geld)</div>
                    <div class="hud-value hud-gold"><span id="hud-credits">0</span> cr</div>
                </div>
                <div class="hud-stat-box" id="box-focus">
                    <div class="hud-label">Analysis (Taktik-Fokus)</div>
                    <div class="hud-value" id="hud-focus">0</div>
                </div>
            </div>

            <div class="terminal-display-area">
                <!-- Der neue, scrollbare Bereich für Titel und Beschreibung -->
                <div class="terminal-text-scroll-container">
                    <div id="terminal-location">[GRID POSITION: LINKING...]</div>
                    <h2 id="terminal-title">Uplink Sequence Initiated...</h2>
                    <div id="terminal-description">Awaiting secure data packet transit from terminal bridge root...</div>
                    <div id="terminal-notice" hidden></div>
                </div>

                <!-- Das Choices-Fenster bleibt fest am unteren Rand verankert -->
                <div id="choices-container"></div>
            </div>

            <script>
                const hudOrigin = document.getElementById('hud-origin');
                const hudHp = document.getElementById('hud-hp');
                const hudCredits = document.getElementById('hud-credits');
                const hudFocus = document.getElementById('hud-focus');
                const boxHp = document.getElementById('box-hp');
                const termLocation = document.getElementById('terminal-location');
                const termTitle = document.getElementById('terminal-title');
                const termDesc = document.getElementById('terminal-description');
                const termNotice = document.getElementById('terminal-notice');
                const choicesContainer = document.getElementById('choices-container');

                function updateBabComTerminal(actionType, choiceId = null) {
                    const formData = new FormData();
                    formData.append('action', actionType);
                    if (choiceId) formData.append('choice_id', choiceId);

                    choicesContainer.classList.add('busy');

                    fetch('?ajax_action=submit_input', { method: 'POST', body: formData })
                        .then(res => res.json())
                        .then(data => {
                            choicesContainer.classList.remove('busy');
                            if (data.status === "SUCCESS") {
                                termLocation.innerText = `[GRID POSITION: ${data.active_sector.toUpperCase()}]`;
                                termTitle.innerText = data.title;
                                termDesc.innerText = String(data.description).replace(/\\n/g, '\n'); // texts that contain a literal \n become real line breaks
                                termDesc.parentElement.scrollTop = 0; // new scene: start reading at the top

                                // Show notices from the story engine (e.g. "Insufficient credits")
                                if (data.message) {
                                    termNotice.innerText = data.message;
                                    termNotice.hidden = false;
                                } else {
                                    termNotice.hidden = true;
                                }

                                if (data.character_stats) {
                                    hudOrigin.innerText = data.character_stats.origin || 'Standard';
                                    hudHp.innerText = data.character_stats.hp !== undefined ? data.character_stats.hp : 100;

                                    let creditsVal = data.character_stats.credits !== undefined ? data.character_stats.credits : 10000;
                                    hudCredits.innerText = Number(creditsVal).toLocaleString();

                                    hudFocus.innerText = data.character_stats.analysis_focus !== undefined ? data.character_stats.analysis_focus : 10;

                                    boxHp.classList.toggle('low', data.character_stats.hp <= 30);
                                }

                                choicesContainer.innerHTML = '';
                                if (data.choices && data.choices.length > 0) {
                                    data.choices.forEach(choice => {
                                        const btn = document.createElement('button');

                                        // Locked choices (requirements not met) are greyed out and show why.
                                        // The server checks the requirements again, so this is only for display.
                                        if (choice.locked) {
                                            btn.innerText = `[LOCKED] > ${choice.choice_text}\n    ${choice.hint || 'Not available'}`;
                                            btn.disabled = true;
                                            btn.className = 'choice-btn locked';
                                            choicesContainer.appendChild(btn);
                                            return; // next choice
                                        }

                                        btn.innerText = `[VECTOR KEY] > ${choice.choice_text}`;
                                        btn.className = 'choice-btn';

                                        btn.onclick = () => updateBabComTerminal('EXECUTE_CHOICE', choice.choice_id);
                                        choicesContainer.appendChild(btn);
                                    });
                                } else if (data.is_ending) {
                                    const endMsg = document.createElement('div');
                                    endMsg.className = 'end-msg';
                                    endMsg.innerText = "[MISSION TERMINATED / TIMELINE CONCLUDED]";
                                    choicesContainer.appendChild(endMsg);

                                    // Without a restart button the save slot would stay stuck on the ending forever
                                    const restartBtn = document.createElement('button');
                                    restartBtn.innerText = "[NEW TIMELINE] > Restart this save slot from the prologue";
                                    restartBtn.className = 'choice-btn';
                                    restartBtn.onclick = () => {
                                        if (confirm('Restart this save slot? The current progress of this slot will be reset.')) {
                                            updateBabComTerminal('RESTART');
                                        }
                                    };
                                    choicesContainer.appendChild(restartBtn);
                                }
                            } else {
                                // textContent instead of innerHTML: server messages are never interpreted as HTML
                                termDesc.innerHTML = '';
                                const errSpan = document.createElement('span');
                                errSpan.className = 'network-error';
                                errSpan.textContent = `[INCOMING STREAM ERROR]: ${data.message}`;
                                termDesc.appendChild(errSpan);
                            }
                        })
                        .catch(err => {
                            termDesc.innerHTML = "<span class='network-error'>[🚨 NETWORK DECOIL]: Failed to align secure sync matrix with bridge endpoint.</span>";
                        });
                }

                window.addEventListener('DOMContentLoaded', () => { updateBabComTerminal('INIT'); });
            </script>

        <?php else: ?>
            <div class="game-denied">
                [SECURITY ALERT]: ACCESS DENIED. AUTHORIZATION KEY REQUIRED TO MOUNT BABCOM TERMINAL CHANNELS.
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
$content = ob_get_clean();
renderLayout("Tactical Simulation Deck", $content);
?>
