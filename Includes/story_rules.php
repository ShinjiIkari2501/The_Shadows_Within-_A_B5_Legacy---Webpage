<?php
/**
 * story_rules.php
 * The "rule book" of the story engine. It is loaded by Game-Bridge.php.
 *
 * It answers four questions:
 *  1. Which choices and database columns are missing so the story can be played to the end?
 *     -> b5_ensure_story_content()  (adds them once; running it again changes nothing)
 *  2. What does a choice change? (morality, Drakh influence, flags, HP ...)
 *     -> B5_CHOICE_EFFECTS + b5_apply_story_effects()
 *  3. Which choices may the player see right now? (e.g. Narn only AFTER Centauri Prime)
 *     -> b5_choice_visible()
 *  4. Which of the 7 endings did the player earn?
 *     -> b5_ending_number()
 *
 * WHY IS THE DATABASE FILLED FROM HERE?
 * The database that is delivered with the project only contains the choices up to the hub (node 114)
 * and the last scenes. The branches behind the hub (Centauri, Narn, Mars, Lyta) had no choices, so the
 * player could never leave them and the endings 2, 3, 4 and 7 were unreachable.
 * "INSERT OR IGNORE" adds the missing choices without touching existing ones, so this also repairs
 * databases that are already deployed (for example on a Render persistent disk).
 */

const B5_NODE_CENTAURI = 115;
const B5_NODE_NARN     = 116;
const B5_NODE_LYTA     = 118;
const B5_NODE_MARS_END = 122; // last Mars scene: when the player has left it, the Mars mission is done

// A Drakh influence of at least this value means "the Londo pact was signed".
const B5_DRAKH_PACT = 25;

// Mars Section 13 data secured (the final approach to Earth requires this).
const B5_PROGRESS_MARS_DONE = 999;

// Columns of player_slots that the story rules are allowed to change.
const B5_STATE_COLUMNS = [
    'current_node_id', 'credits', 'analysis_focus', 'character_origin', 'player_hp',
    'alignment_score', 'drakh_influence', 'bester_threat_level', 'control_decryption_progress',
    'ship_calibration_path', 'is_narn_avantgarde_recruited', 'is_narn_mercenaries_hired',
    'lyta_recruited', 'talias_trauma_resolved', 'constructor_rescued', 'visited_nodes', 'final_strike',
];

// Missing choices: [choice_id, from_node, next_node, text, effect_credits, effect_analysis_focus]
const B5_EXTRA_CHOICES = [
    // Scene 16 - Centauri Prime (node 115)
    [40, 115, 114, "Choice 1: Accept Londo's aggressive military pact - sacrifice Narn sovereignty for his battle cruisers.", 0, 0],
    [41, 115, 114, "Choice 2: Reject Londo and follow Vir Cotto - use the secret humanitarian rescue fleet instead.", 0, 0],
    // Scene 17 - Narn Homeworld (node 116)
    [42, 116, 114, "Choice 1: Spend 20,000 Credits to hire criminal Narn mercenaries.", -20000, 0],
    [43, 116, 114, "Choice 2: Refuse the underground option - the Narn abandon your cause.", 0, 0],
    [44, 116, 114, "Press Enter to accept the Kha'Ri decree - the elite Narn Avantgarde is mobilized to protect Earth.", 0, 0],
    // Scene 18 - Stranded Alliance Wreck (node 117), the first stop of the Mars mission
    [45, 117, 120, "Choice 1: Shove Sha'In out of the way of the falling girder (Suffer -10 HP damage).", 0, 0],
    [46, 117, 120, "Choice 2: Cut the girder loose with your PPG and salvage the wreck's cargo (+2,000 Credits).", 2000, 0],
    // Scene 23 - The Mars Coup (node 120)
    [47, 120, 121, "Choice 1: Trust Garibaldi's black-ops override codes (shady, but fast).", 0, 0],
    [48, 120, 121, "Choice 2: Run your own deep analysis to crack the Section 13 encryption (+5 Analysis Focus).", 0, 5],
    // Scene 24 - Sector 13 (node 121)
    [49, 121, 122, "Choice 1: Download the Section 13 logs and leave before the alarms peak.", 0, 0],
    [50, 121, 122, "Choice 2: Download the logs and free the other test subjects (Suffer -10 HP damage).", 0, 0],
    // Scene 25 - Escape from Tharsis (node 122)
    [51, 122, 114, "Press Enter to force the propulsion bypass and break out of Tharsis.", 0, 0],
    // Scene 22 - Lyta's Refuge (node 118)
    [52, 118, 114, "Path of Light: Show Lyta the Section 13 evidence and speak of Talia Winters as a victim.", 0, 0],
    [53, 118, 114, "Path of Revenge: Embrace Lyta's rage - the Corps will burn, every single cell.", 0, 0],
    [54, 118, 114, "Argue with dry military metrics and the needs of the alliance.", 0, 0],
];

// What a choice changes. 'add' = add a number (can be negative), 'set' = overwrite with a fixed value.
// Choices that are not listed here only move the story on. HP damage and credit costs are handled
// elsewhere (B5_HP_DAMAGE in Game-Bridge.php and effect_credits in the database).
const B5_CHOICE_EFFECTS = [
    // Scene 4 - the alliance contract
    6  => ['add' => ['alignment_score' => -5]],
    7  => ['add' => ['alignment_score' => 10]],
    // Scene 5 - the liberation of Sha'In (every way frees the constructor)
    8  => ['add' => ['alignment_score' => -10], 'set' => ['constructor_rescued' => 1]],
    9  => ['add' => ['alignment_score' => 5],   'set' => ['constructor_rescued' => 1]],
    10 => ['add' => ['alignment_score' => 5],   'set' => ['constructor_rescued' => 1]],
    11 => ['set' => ['constructor_rescued' => 1]],
    // Scene 6 - linguistic calibration of the ship
    12 => ['set' => ['ship_calibration_path' => 1]],
    13 => ['set' => ['ship_calibration_path' => 2]],
    14 => ['set' => ['ship_calibration_path' => 3]],
    // Scene 16 - Centauri Prime
    40 => ['add' => ['alignment_score' => -10, 'drakh_influence' => 25, 'bester_threat_level' => 15]],
    41 => ['add' => ['alignment_score' => 10,  'drakh_influence' => -30]],
    // Scene 17 - Narn Homeworld
    42 => ['add' => ['alignment_score' => -10], 'set' => ['is_narn_mercenaries_hired' => 1]],
    43 => ['add' => ['bester_threat_level' => 35]],
    44 => ['add' => ['alignment_score' => 5, 'bester_threat_level' => -20], 'set' => ['is_narn_avantgarde_recruited' => 1]],
    // Scene 18 - wreck
    45 => ['add' => ['player_hp' => -10]],
    // Scene 23 - Mars
    47 => ['add' => ['alignment_score' => -5]],
    48 => ['add' => ['analysis_focus' => 5]],
    // Scene 24 - Sector 13: the data logs are secured (progress 999)
    49 => ['set' => ['control_decryption_progress' => B5_PROGRESS_MARS_DONE]],
    50 => ['add' => ['alignment_score' => 10, 'player_hp' => -10], 'set' => ['control_decryption_progress' => B5_PROGRESS_MARS_DONE]],
    // Scene 22 - Lyta
    52 => ['set' => ['lyta_recruited' => 1, 'talias_trauma_resolved' => 1]],
    53 => ['add' => ['alignment_score' => -10], 'set' => ['lyta_recruited' => 1]],
    54 => ['set' => ['lyta_recruited' => 0]],
    // Scene 26 - briefing (+5 focus comes from the database), Scene 27 - stratospheric entry
    31 => ['add' => ['alignment_score' => -5]],
    32 => ['add' => ['analysis_focus' => 5]],
    // Scene 29 - infiltration
    35 => ['add' => ['analysis_focus' => 5]],
    // Scene 30 - the final strike (final_strike decides between the endings 1, 4 and 5)
    36 => ['add' => ['alignment_score' => 10], 'set' => ['final_strike' => 1]],
    37 => ['set' => ['final_strike' => 2]],
];

/**
 * Adds the missing columns and choices to the database. Safe to call on every request.
 */
function b5_ensure_story_content($pdo)
{
    static $done = false; // only once per request
    if ($done) {
        return;
    }
    $done = true;

    // 1) Alle benötigten Spalten für player_slots prüfen und ggf. hinzufügen
    $existing = [];
    foreach ($pdo->query("PRAGMA table_info(player_slots)") as $col) {
        $existing[$col['name']] = true;
    }

    $newColumns = [
        'visited_nodes'               => "TEXT DEFAULT ''",
        'final_strike'                => "INTEGER DEFAULT 0",
        'alignment_score'             => "INTEGER DEFAULT 0",
        'drakh_influence'             => "INTEGER DEFAULT 0",
        'bester_threat_level'         => "INTEGER DEFAULT 0",
        'control_decryption_progress' => "INTEGER DEFAULT 0",
        'ship_calibration_path'       => "INTEGER DEFAULT 0",
        'is_narn_avantgarde_recruited'=> "INTEGER DEFAULT 0",
        'is_narn_mercenaries_hired'   => "INTEGER DEFAULT 0",
        'lyta_recruited'              => "INTEGER DEFAULT 0",
        'talias_trauma_resolved'      => "INTEGER DEFAULT 0",
        'constructor_rescued'         => "INTEGER DEFAULT 0",
        'psi_level'                   => "INTEGER DEFAULT 0",
        'character_origin'            => "TEXT DEFAULT 'Standard'",
        'player_hp'                   => "INTEGER DEFAULT 100",
        'credits'                     => "INTEGER DEFAULT 10000",
        'analysis_focus'              => "INTEGER DEFAULT 10",
    ];

    foreach ($newColumns as $name => $definition) {
        if (!isset($existing[$name])) {
            try {
                $pdo->exec("ALTER TABLE player_slots ADD COLUMN $name $definition");
            } catch (Exception $e) {
                // Falls es zeitgleich durch einen anderen Request angelegt wurde
            }
        }
    }

    // 2) Missing choices einfügen
    $insert = $pdo->prepare(
        "INSERT OR IGNORE INTO story_choices
            (choice_id, from_node_id, next_node_id, choice_text, effect_credits, effect_analysis_focus)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    foreach (B5_EXTRA_CHOICES as $choice) {
        $insert->execute($choice);
    }
}

/** Has the player already left this story node? (stored in player_slots.visited_nodes) */
function b5_has_visited($slot, $nodeId)
{
    $list = explode(',', (string)($slot['visited_nodes'] ?? ''));
    return in_array((string)$nodeId, $list, true);
}

/**
 * Is this choice shown to the player right now? Choices that are not listed always are.
 */
function b5_choice_visible($choiceId, $slot)
{
    $focus    = (int)($slot['analysis_focus'] ?? 10);
    $drakh    = (int)($slot['drakh_influence'] ?? 0);
    $align    = (int)($slot['alignment_score'] ?? 0);
    $progress = (int)($slot['control_decryption_progress'] ?? 0);
    $marsDone = b5_has_visited($slot, B5_NODE_MARS_END);

    switch ((int)$choiceId) {
        case 14: return $focus >= 80;                                  // hybrid calibration needs high analysis
        case 23: return !$marsDone;                                    // Medlab only until the Mars mission is done
        case 24: return !b5_has_visited($slot, B5_NODE_CENTAURI);      // every vector can be flown once
        case 25: return b5_has_visited($slot, B5_NODE_CENTAURI)        // Narn comes after Centauri Prime,
                      && !b5_has_visited($slot, B5_NODE_NARN);         // because the pact changes the Narn scene
        case 26: return !$marsDone;
        case 27: return $marsDone && !b5_has_visited($slot, B5_NODE_LYTA); // Lyta needs the Mars evidence
        case 28: return $marsDone;                                     // final approach: Section 13 data required
        case 42:
        case 43: return $drakh >= B5_DRAKH_PACT;                       // Narn betrayed by the Londo pact
        case 44: return $drakh < B5_DRAKH_PACT;                        // Narn honorably allied
        case 52: return $progress === B5_PROGRESS_MARS_DONE && $align >= 75; // Path of Light
        case 53: return $align <= 40;                                  // Path of Revenge
    }
    return true;
}

/**
 * Does a choice cost credits for THIS player? The database says -15,000 for the "class-specific strike"
 * (choice 11), but only the Smuggler pays; the ex-agent bluffs with forged documents for free.
 */
function b5_credit_cost($choiceId, $slot, $effectCredits)
{
    if ((int)$choiceId === 11 && strtolower((string)($slot['character_origin'] ?? '')) === 'intelligence') {
        return 0;
    }
    return $effectCredits;
}

/**
 * Applies the story effects of a choice to $new (the column values that will be saved).
 * $notes receives short messages for the player (HP loss and so on).
 */
function b5_apply_story_effects($choiceId, $slot, array &$new, array &$notes)
{
    $choiceId = (int)$choiceId;
    $current  = function ($col) use (&$new, $slot) {
        return (int)($new[$col] ?? $slot[$col] ?? 0);
    };

    $fx = B5_CHOICE_EFFECTS[$choiceId] ?? [];
    foreach (($fx['add'] ?? []) as $col => $amount) {
        $new[$col] = $current($col) + $amount;
    }
    foreach (($fx['set'] ?? []) as $col => $value) {
        $new[$col] = $value;
    }

    $origin = strtolower((string)($new['character_origin'] ?? $slot['character_origin'] ?? ''));

    switch ($choiceId) {
        case 17: // Scene 9: deep scan - success with Analysis >= 90, otherwise the console shocks you
            if ($current('analysis_focus') >= 90) {
                $new['control_decryption_progress'] = $current('control_decryption_progress') + 100;
                $notes[] = "Scan successful: an intact biocrystal fragment was extracted.";
            } else {
                $new['player_hp'] = $current('player_hp') - 15;
                $notes[] = "Scan failed: electrostatic discharge, -15 HP.";
            }
            break;

        case 18: // Scene 10: plasma leak - the damage depends on the ship calibration of Scene 6
            $damage = [1 => 30, 2 => 25, 3 => 10][$current('ship_calibration_path')] ?? 30;
            $new['player_hp'] = $current('player_hp') - $damage;
            $notes[] = "Plasma burns: -$damage HP.";
            break;

        case 51: // Scene 25: escape from Tharsis - again depends on the ship calibration
            $damage = [1 => 20, 2 => 15, 3 => 0][$current('ship_calibration_path')] ?? 20;
            if ($damage > 0) {
                $new['player_hp'] = $current('player_hp') - $damage;
                $notes[] = "The defense lasers hit the hull: -$damage HP.";
            } else {
                $notes[] = "Perfect hybrid calibration: no damage.";
            }
            break;

        case 33: // Scene 28: Garibaldi's broadcast works differently for each background
            if ($origin === 'intelligence') {
                $new['bester_threat_level'] = $current('bester_threat_level') - 15;
            } elseif ($origin === 'underworld') {
                $new['credits'] = $current('credits') + 5000;
                $notes[] = "Resistance groups rise up: +5,000 Credits.";
            }
            break;
    }

    // Keep every value in a sensible range.
    $limits = [
        'alignment_score'     => [0, 100],
        'drakh_influence'     => [0, 1000],
        'bester_threat_level' => [0, 1000],
        'player_hp'           => [1, 100], // the player is never dead, "barely alive" at worst
    ];
    foreach ($limits as $col => [$min, $max]) {
        if (isset($new[$col])) {
            $new[$col] = max($min, min($max, (int)$new[$col]));
        }
    }
}

/**
 * Which of the 7 endings did the player earn? (first rule that fits wins)
 *  7 True ending ........ Lyta joined via the Path of Light, Mars data secured, morality >= 75
 *  2 Centauri Shadow .... the Londo pact was signed
 *  3 Narn Citadel ....... the Narn Avantgarde was recruited
 *  5 Shadow Director .... ex-agent (Analysis >= 80) who fought Bester by hand
 *  4 Resistance Triumph . smuggler who fought Bester by hand (morality >= 40)
 *  1 Path of Light ...... morality >= 50
 *  6 Absolute Silence ... everything else (game over)
 */
function b5_ending_number($slot)
{
    $align    = (int)($slot['alignment_score'] ?? 0);
    $focus    = (int)($slot['analysis_focus'] ?? 10);
    $drakh    = (int)($slot['drakh_influence'] ?? 0);
    $progress = (int)($slot['control_decryption_progress'] ?? 0);
    $strike   = (int)($slot['final_strike'] ?? 0);
    $origin   = strtolower(trim((string)($slot['character_origin'] ?? '')));

    if (!empty($slot['lyta_recruited']) && !empty($slot['talias_trauma_resolved'])
        && $progress === B5_PROGRESS_MARS_DONE && $align >= 75) {
        return 7;
    }
    if ($drakh >= B5_DRAKH_PACT) {
        return 2;
    }
    if (!empty($slot['is_narn_avantgarde_recruited'])) {
        return 3;
    }
    if ($strike === 2 && $origin === 'intelligence' && $focus >= 80) {
        return 5;
    }
    if ($strike === 2 && $origin === 'underworld' && $align >= 40) {
        return 4;
    }
    if ($align >= 50) {
        return 1;
    }
    return 6;
}
