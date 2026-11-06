<?php
/**
 * Game-Bridge.php - the story engine of 'The Shadows Within - A B5 Legacy'
 *
 * WHAT THIS FILE DOES
 * Gameplay.php (the page in the browser) sends a small request here, for example
 * "the player clicked choice 5". This file looks up the story in the SQLite database,
 * updates the player's save slot and answers with JSON. The JavaScript in Gameplay.php
 * then draws the new scene on the page.
 *
 * ACTIONS THE BROWSER CAN SEND
 *   INIT / FETCH_NODE  show the scene the player is currently on (progress is NOT reset)
 *   EXECUTE_CHOICE     the player clicked a choice: apply its effects and move to the next scene
 *   RESTART            start the active save slot again at the prologue (node 100)
 *
 * IMPORTANT RULE
 * Who the player is (user id) and which save slot is active are read from the PHP SESSION,
 * never from the request. A request can be faked by anyone, the session cannot.
 */

// Never print PHP warnings into the page. They would end up in front of the JSON
// and the JavaScript in the browser could not read the answer any more.
ini_set('display_errors', '0');

// Tell the browser that this answer is JSON and not HTML.
header('Content-Type: application/json; charset=UTF-8');

// Constants are values that never change while the script runs.
const B5_START_NODE    = 100;  // node 100 = prologue, every new game starts here
const B5_SETUP_NODE    = 10;   // node 10 = character setup (choose Intelligence or Underworld), comes after the prologue
const B5_HUB_NODE      = 114;  // node 114 = tactical hub of Act III
const B5_GAME_OVER_NODE = 195; // node 195 = game over ending (also used by the ending resolver)
const B5_LAST_WINDOW   = 181;  // node 181 = war cabinet after Mars (the hub is closed from then on)
const B5_MEDLAB_NODE   = 113;  // node 113 = Medlab scene ("fully restoring your HP")
const B5_RETURN_CHOICE = -1;   // made-up choice id for scenes that have no choices in the database yet

// How much HP a choice costs: choice_id => damage (taken from the texts of the choices).
// Newer choices carry their damage in their own "sets" rule (for example "hp-=30") instead.
const B5_HP_DAMAGE = [3 => 25, 8 => 10, 31 => 15, 34 => 10, 37 => 35];

// The rules engine: flags, conditions, effects, text variants and the ending resolver.
require_once __DIR__ . '/Includes/story_engine.php';


// =============================================================================================
// HELPER FUNCTIONS
// =============================================================================================

/**
 * Sends the answer as JSON and stops the script.
 * $payload  = the data (an array) that is sent to the browser
 * $httpCode = 200 means "ok", 4xx/5xx mean "error"
 */
function b5_reply($payload, $httpCode = 200)
{
    http_response_code($httpCode);
    echo json_encode($payload);
    exit; // nothing else may run (or be printed) after the answer
}

/** Shortcut: sends an error message as JSON. */
function b5_error($message, $httpCode = 400)
{
    b5_reply(["status" => "ERROR", "message" => $message], $httpCode);
}

/** Loads one story node (title, text, sector, ending flag) from the database. Returns false if it does not exist. */
function b5_fetch_node($pdo, $nodeId)
{
    // The "?" is a placeholder. It is filled with $nodeId by execute().
    // That is called a prepared statement and protects against SQL injection.
    $stmt = $pdo->prepare("SELECT title, description, active_sector, is_ending FROM story_nodes WHERE node_id = ?");
    $stmt->execute([$nodeId]);
    return $stmt->fetch(); // one row as an array, or false if nothing was found
}

/**
 * Loads the save slot of a player. If this slot does not exist yet, it is created first
 * and starts at the prologue (node 100).
 */
function b5_load_slot($pdo, $userId, $slotNumber)
{
    $stmt = $pdo->prepare("SELECT * FROM player_slots WHERE user_id = ? AND slot_number = ?");
    $stmt->execute([$userId, $slotNumber]);
    $slot = $stmt->fetch();

    if (!$slot) { // no row found, so the slot is new
        $insert = $pdo->prepare("INSERT INTO player_slots (user_id, slot_number, current_node_id) VALUES (?, ?, ?)");
        $insert->execute([$userId, $slotNumber, B5_START_NODE]);

        $stmt->execute([$userId, $slotNumber]); // read the new row so we have all default values
        $slot = $stmt->fetch();
    }
    return $slot;
}

/**
 * Builds the complete answer for the scene the slot is currently on:
 * text, buttons (choices) and the character stats for the HUD.
 * $message is an optional hint for the player, for example "Insufficient credits".
 */
function b5_scene($pdo, $slot, $message = '')
{
    $nodeId = (int)$slot['current_node_id']; // (int) makes sure we really have a number
    $node   = b5_fetch_node($pdo, $nodeId);

    // Old save games can point to a node that does not exist (the node numbers were changed
    // once). Instead of crashing, such a save is moved back to the character setup.
    if (!$node) {
        $fix = $pdo->prepare("UPDATE player_slots SET current_node_id = ? WHERE slot_id = ?");
        $fix->execute([B5_START_NODE, $slot['slot_id']]);

        $slot['current_node_id'] = B5_START_NODE;
        $nodeId = B5_START_NODE;
        $node   = b5_fetch_node($pdo, $nodeId);

        if (!$node) { // even node 10 is missing: the database itself is broken
            throw new Exception("Start node " . B5_START_NODE . " is missing in story_nodes.");
        }
    }

    // All values of the player (columns + story flags) in one array. The rules look at this array.
    $state = b5_state($slot);

    // The buttons. Choices whose requirements are not met are hidden or shown greyed out with a hint.
    $choices = b5_visible_choices($pdo, $nodeId, $state);

    // Scene text: the basic text of the node, changed by variants that depend on earlier decisions.
    $description = b5_node_text($pdo, $nodeId, $node['description'], $state);

    // A scene without any usable choice that is not an ending would be a dead end: the player could
    // not click anything. A placeholder button leads back (to the hub, or to the war cabinet after
    // Mars). It never appears in the normal story, only as a safety net.
    $isEnding = (bool)$node['is_ending'];
    $usable = 0;
    foreach ($choices as $c) {
        if (!$c['locked']) {
            $usable++;
        }
    }
    if ($usable === 0 && !$isEnding) {
        $choices[] = [
            "choice_id"   => B5_RETURN_CHOICE,
            "choice_text" => "Return to the Tactical Hub (this storyline is not available yet)",
            "locked"      => false
        ];
    }

    $response = [
        "status"        => "SUCCESS",
        "node_id"       => $nodeId,
        "title"         => $node['title'],
        "description"   => $description,
        "active_sector" => $node['active_sector'],
        "is_ending"     => $isEnding,
        "choices"       => $choices,
        "character_stats" => [
            // "??" means: use the value on the left, or the one on the right if it is missing/NULL
            "origin"         => $slot['character_origin'] ?? 'Standard',
            "hp"             => (int)$slot['player_hp'],
            "credits"        => (int)$slot['credits'],
            "analysis_focus" => (int)$slot['analysis_focus'],
            "psi_level"      => (int)$slot['psi_level'],
            "threat"         => (int)$slot['bester_threat_level']
        ]
    ];
    if ($message !== '') {
        $response["message"] = $message;
    }
    return $response;
}


// =============================================================================================
// STEP 1: Who is playing? (login check)
// =============================================================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // layout.php normally started the session already, this is only a safety net
}

// 'eingeloggt' is set by Login.php after a successful login.
if (!isset($_SESSION['eingeloggt']) || $_SESSION['eingeloggt'] !== true) {
    b5_error("Authorization required.", 401); // 401 = not logged in
}

$userId     = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$slotNumber = isset($_SESSION['active_save_slot']) ? (int)$_SESSION['active_save_slot'] : 1;

if ($userId < 1 || $slotNumber < 1 || $slotNumber > 3) { // there are exactly 3 save slots
    b5_error("Invalid session state.", 400);
}


// =============================================================================================
// STEP 2: What does the browser want? (read the request)
// =============================================================================================
if (defined('B5_BRIDGE_INTERNAL') && isset($bridgeRequest) && is_array($bridgeRequest)) {
    // Normal case: Gameplay.php included this file and prepared $bridgeRequest.
    $request = $bridgeRequest;
} else {
    // Direct call of Game-Bridge.php with a JSON body (only works for logged-in users).
    $request = json_decode(file_get_contents('php://input'), true);
}

if (!is_array($request) || !isset($request['action']) || !is_string($request['action'])) {
    b5_error("Invalid request payload or missing action parameter.");
}
$action = strtoupper(trim($request['action']));


// =============================================================================================
// STEP 3: Do the work
// =============================================================================================
// db_connect.php creates the variable $pdo (the connection to the SQLite database).
require_once __DIR__ . '/Includes/db_connect.php';

try {
    switch ($action) {

        // ---- Show the current scene ------------------------------------------------------
        case 'INIT':
        case 'FETCH_NODE':
            $slot = b5_load_slot($pdo, $userId, $slotNumber);
            b5_reply(b5_scene($pdo, $slot));
            break;

        // ---- Start this slot from the beginning ------------------------------------------
        case 'RESTART':
            $slot = b5_load_slot($pdo, $userId, $slotNumber);
            // Everything that belongs to the story is reset, including the story flags.
            $reset = $pdo->prepare(
                "UPDATE player_slots
                    SET current_node_id = ?, character_origin = 'Standard', analysis_focus = 10,
                        player_hp = 100, credits = 10000, psi_level = 0,
                        alignment_score = 0, drakh_influence = 0, bester_threat_level = 0,
                        lyta_recruited = 0, is_narn_avantgarde_recruited = 0, is_narn_mercenaries_hired = 0,
                        talias_trauma_resolved = 0, ship_calibration_path = 0, has_delenns_data_crystal = 0,
                        constructor_rescued = 0, story_flags = '{}'
                  WHERE slot_id = ?"
            );
            $reset->execute([B5_START_NODE, $slot['slot_id']]);
            b5_reply(b5_scene($pdo, b5_load_slot($pdo, $userId, $slotNumber), "New timeline initiated."));
            break;

        // ---- The player clicked a choice -------------------------------------------------
        case 'EXECUTE_CHOICE':
            $choiceId = isset($request['choice_id']) ? (int)$request['choice_id'] : 0;
            if ($choiceId === 0) {
                b5_error("Missing choice_id for executing choice.");
            }

            // A transaction groups several database changes: either ALL of them are saved
            // (commit) or NONE of them (rollBack), for example if an error happens in between.
            $pdo->beginTransaction();
            $message = '';

            $slot        = b5_load_slot($pdo, $userId, $slotNumber);
            $currentNode = (int)$slot['current_node_id'];
            $state       = b5_state($slot);

            if ($choiceId === B5_RETURN_CHOICE) {
                // The placeholder button from b5_scene(). It only counts on a scene that has really
                // no usable choice and is not an ending.
                $node = b5_fetch_node($pdo, $currentNode);
                $usable = 0;
                foreach (b5_visible_choices($pdo, $currentNode, $state) as $c) {
                    if (!$c['locked']) {
                        $usable++;
                    }
                }

                if ($usable === 0 && $node && !(bool)$node['is_ending']) {
                    $target = !empty($state['mars_gate']) ? B5_LAST_WINDOW : B5_HUB_NODE;
                    $back = $pdo->prepare("UPDATE player_slots SET current_node_id = ? WHERE slot_id = ?");
                    $back->execute([$target, $slot['slot_id']]);
                }

            } else {
                // The choice must start at the node the player is standing on. Otherwise someone could
                // jump to the ending by sending a made-up choice id, or an old browser tab could
                // send a choice that does not fit any more.
                $stmt = $pdo->prepare("SELECT * FROM story_choices WHERE choice_id = ? AND from_node_id = ?");
                $stmt->execute([$choiceId, $currentNode]);
                $choice = $stmt->fetch();

                if (!$choice) {
                    $message = "Choice not available at this position - scene re-synchronized.";
                } elseif (!b5_conditions_met($state, $choice['requires'])) {
                    // The button was greyed out or hidden. A faked request must not open it.
                    $message = $choice['locked_hint'] ? $choice['locked_hint'] : "This option is not available.";
                } else {
                    $nextNode      = (int)$choice['next_node_id'];
                    $effectCredits = (int)$choice['effect_credits'];
                    $credits       = (int)$slot['credits'];

                    // Choices of type "ending" do not have a fixed target: the ending resolver picks
                    // the ending node from everything the player did.
                    if ($choice['route'] === 'ending') {
                        $nextNode = b5_resolve_ending($slot);
                    }

                    // Safety check: the target scene must exist.
                    if (!b5_fetch_node($pdo, $nextNode)) {
                        throw new Exception("Choice $choiceId points to missing node $nextNode.");
                    }

                    if ($effectCredits < 0 && $credits + $effectCredits < 0) {
                        // The choice costs credits the player does not have: nothing changes.
                        $message = "Insufficient credits: this option costs " . number_format(-$effectCredits) . " cr.";
                    } else {
                        // Work on a copy of the slot: all changes are collected in $new and saved in ONE update.
                        $new   = $slot;
                        $flags = b5_flags_decode($slot['story_flags'] ?? '');

                        $new['credits'] = $credits + $effectCredits;

                        // Analysis focus: in the character setup (node 10) the value is the STARTING
                        // value (for example 95 for Intelligence). In all other scenes it is a bonus (+5).
                        $effectFocus = (int)$choice['effect_analysis_focus'];
                        if ((int)$choice['from_node_id'] === B5_SETUP_NODE) {
                            if ($effectFocus > 0) {
                                $new['analysis_focus'] = $effectFocus;
                            }
                        } else {
                            $new['analysis_focus'] = (int)$slot['analysis_focus'] + $effectFocus;
                        }

                        // Origin (character class): only choices of the setup scene set it.
                        if ($choice['effect_origin'] !== null && $choice['effect_origin'] !== '') {
                            $new['character_origin'] = $choice['effect_origin'];
                        }

                        // Hit points: subtract damage (if this choice has any), never go below 0.
                        $damage = isset(B5_HP_DAMAGE[$choiceId]) ? B5_HP_DAMAGE[$choiceId] : 0;
                        $new['player_hp'] = max(0, (int)$slot['player_hp'] - $damage);

                        // The "sets" rules of the choice: flags, alignment, drakh influence, more HP damage ...
                        b5_apply_effects($new, $flags, $choice['sets']);

                        // Scene 14 (Medlab): Dr. Chambers heals the player completely.
                        if ($nextNode === B5_MEDLAB_NODE) {
                            $new['player_hp'] = 100;
                        }

                        // Game over: whoever has no hit points left dies right now and sees the
                        // "game over" ending (node 195), no matter where in the story the choice was made.
                        if ((int)$new['player_hp'] <= 0) {
                            $nextNode = B5_GAME_OVER_NODE;
                        }

                        $new['current_node_id'] = $nextNode;
                        $new['story_flags']     = json_encode($flags);

                        $update = $pdo->prepare(
                            "UPDATE player_slots
                                SET current_node_id = ?, credits = ?, analysis_focus = ?, character_origin = ?,
                                    player_hp = ?, alignment_score = ?, drakh_influence = ?, bester_threat_level = ?,
                                    lyta_recruited = ?, is_narn_avantgarde_recruited = ?, is_narn_mercenaries_hired = ?,
                                    talias_trauma_resolved = ?, ship_calibration_path = ?, has_delenns_data_crystal = ?,
                                    constructor_rescued = ?, story_flags = ?
                              WHERE slot_id = ?"
                        );
                        $update->execute([
                            $new['current_node_id'], $new['credits'], $new['analysis_focus'], $new['character_origin'],
                            $new['player_hp'], $new['alignment_score'], $new['drakh_influence'], $new['bester_threat_level'],
                            $new['lyta_recruited'], $new['is_narn_avantgarde_recruited'], $new['is_narn_mercenaries_hired'],
                            $new['talias_trauma_resolved'], $new['ship_calibration_path'], $new['has_delenns_data_crystal'],
                            $new['constructor_rescued'], $new['story_flags'],
                            $slot['slot_id']
                        ]);

                        // Remember which choices the player took (table player_history).
                        $history = $pdo->prepare("INSERT INTO player_history (user_id, chosen_choice_id) VALUES (?, ?)");
                        $history->execute([$userId, $choiceId]);
                    }
                }
            }

            $pdo->commit(); // everything worked: save all changes for real
            b5_reply(b5_scene($pdo, b5_load_slot($pdo, $userId, $slotNumber), $message));
            break;

        default:
            b5_error("The specified action protocol is unsupported.");
    }

} catch (Exception $e) {
    // Something went wrong: undo the unfinished transaction so the save stays consistent.
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // The technical details only go to the server log (on Render: the "Logs" tab) and are
    // not shown to the player, because they could reveal file paths or table names.
    error_log('[Game-Bridge] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    b5_error("The story engine hit a problem. Please try again.", 500);
}
