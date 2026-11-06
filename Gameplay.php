<?php
/**
 * Gameplay.php - PART 1/2 (DIRECT ARCHITECTURE INTEGRATION)
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
    ini_set('display_errors', '0');
    header('Content-Type: application/json; charset=UTF-8');

    $action = isset($_POST['action']) ? strtoupper(trim((string)$_POST['action'])) : 'INIT';
    if (!in_array($action, ['INIT', 'FETCH_NODE', 'EXECUTE_CHOICE', 'RESTART'], true)) {
        $action = 'INIT';
    }

    $bridgeRequest = ["action" => $action];
    if (isset($_POST['choice_id'])) {
        $bridgeRequest["choice_id"] = (int)$_POST['choice_id'];
    }
    define('B5_BRIDGE_INTERNAL', true);

    if (file_exists(__DIR__ . '/Game-Bridge.php')) {
        // Wir fangen die JSON-Ausgabe der Brücke ab, um die Texte mit storyscript.php zu synchronisieren
        ob_start();
        include __DIR__ . '/Game-Bridge.php';
        $bridgeJson = ob_get_clean();

        $data = json_decode($bridgeJson, true);

        // Wenn die Brücke erfolgreich war, überschreiben wir die Texte mit den Daten aus der storyscript.php
        if (isset($data['status']) && $data['status'] === 'SUCCESS' && isset($data['node_id'])) {
            $storyscriptPath = __DIR__ . '/storyscript.php';
            if (file_exists($storyscriptPath)) {
                $storyScript = include $storyscriptPath;
                $currentNodeId = $data['node_id'];

                // Falls Node-ID im Array existiert (z.B. SETUP oder numerischer Key)
                $scriptKey = ($currentNodeId === 10) ? 'SETUP' : $currentNodeId;

                if (isset($storyScript[$scriptKey])) {
                    $data['title'] = $storyScript[$scriptKey]['title'] ?? $data['title'];
                    $data['description'] = $storyScript[$scriptKey]['script'] ?? $data['description'];
                    if (isset($storyScript[$scriptKey]['location'])) {
                        $data['active_sector'] = $storyScript[$scriptKey]['location'];
                    }
                }
            }
        }
        echo json_encode($data);
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
    if ($selectedSlot >= 1 && $selectedSlot <= 3) {
        $_SESSION['active_save_slot'] = $selectedSlot;
    }
    header("Location: Gameplay.php");
    exit;
}
?>

<main class="game-main">
    <!-- Inline-CSS um die Terminalgröße einzufrieren und das Scrollen zu erzwingen -->
    <style>
        .terminal-display-area {
            height: 550px !important; /* Verhindert das Ändern der Terminalgröße permanent */
            max-height: 550px !important;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-sizing: border-box;
        }
        .terminal-text-scroll-container {
            flex: 1;
            overflow-y: auto !important; /* Erzwingt die vertikale Scrollleiste */
            padding-right: 10px;
            margin-bottom: 15px;
            white-space: pre-wrap; /* Behält die exakten Zeilenumbrüche des Drehbuchs bei */
        }
        #choices-container {
            flex-shrink: 0; /* Verhindert, dass das Entscheidungsfenster zusammengedrückt wird */
        }
                #choices-container {
            flex-shrink: 0; /* Verhindert, dass das Entscheidungsfenster zusammengedrückt wird */
        }

        /* === NEUE CUSTOM SCROLLBAR HIER HINZUFÜGEN === */
        .terminal-text-scroll-container::-webkit-scrollbar {
            width: 10px;
            background: rgba(13, 20, 59, 0.3);
        }

        .terminal-text-scroll-container::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.4);
            border-radius: 4px;
            margin-top: 5px;
            margin-bottom: 5px;
            border: 1px solid rgba(96, 172, 243, 0.1);
        }

        .terminal-text-scroll-container::-webkit-scrollbar-thumb {
            background: rgba(96, 172, 243, 0.4);
            border-radius: 4px;
            border: 1px solid rgba(13, 20, 59, 0.6);
        }

        .terminal-text-scroll-container::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 153, 0, 0.6);
            border: 1px solid rgba(255, 153, 0, 0.2);
        }

        .terminal-text-scroll-container {
            scrollbar-width: thin;
            scrollbar-color: rgba(96, 172, 243, 0.4) rgba(0, 0, 0, 0.4);
        }
    </style>

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
                <div class="terminal-text-scroll-container" id="terminal-scroll-box">
                    <div id="terminal-location">[GRID POSITION: LINKING...]</div>
                    <h2 id="terminal-title">Uplink Sequence Initiated...</h2>
                    <div id="terminal-description">Awaiting secure data packet transit from terminal bridge root...</div>
                    <div id="terminal-notice" hidden></div>
                </div>

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
                const terminalScrollBox = document.getElementById('terminal-scroll-box');

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
                                termDesc.innerText = String(data.description).replace(/\\n/g, '\n');

                                // Setzt die Scrollleiste der Textbox bei jedem Szenenwechsel ganz nach oben
                                terminalScrollBox.scrollTop = 0;

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

                                        if (choice.locked) {
                                            btn.innerText = `[LOCKED] > ${choice.choice_text}\n    ${choice.hint || 'Not available'}`;
                                            btn.disabled = true;
                                            btn.className = 'choice-btn locked';
                                            choicesContainer.appendChild(btn);
                                            return;
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
