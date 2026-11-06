<?php
/**
 * story_engine.php - the "rules" of the story for 'The Shadows Within - A B5 Legacy'
 *
 * WHAT THIS FILE DOES
 * Game-Bridge.php moves the player from scene to scene. This file adds the brain on top of that:
 *   1. It remembers what the player did (flags such as "pact=vir" or "lyta_state=light").
 *   2. It checks conditions ("Requires Mars data") before a choice can be used.
 *   3. It applies effects of a choice (alignment +10, drakh influence -15, set a flag ...).
 *   4. It picks different scene texts depending on what happened before.
 *   5. It works out which of the 7 endings the player gets.
 *
 * HOW CONDITIONS AND EFFECTS ARE WRITTEN
 * Both are small text rules stored as JSON lists in the database table story_choices.
 *
 *   Conditions (column "requires"), ALL rules of the list must be true:
 *     "pact=vir"          the value must be equal
 *     "pact!=londo"       the value must not be equal
 *     "credits>=20000"    numbers can be compared with  >=  <=  >  <
 *     "mars_data"         the value must be set (not empty, not 0)
 *     "!wreck_done"       the value must NOT be set
 *
 *   Effects (column "sets"), executed one after the other:
 *     "pact=vir"          set a value
 *     "alignment+=10"     add to a number
 *     "drakh-=15"         subtract from a number
 *
 * WHERE ARE THE VALUES STORED?
 * Some values already have their own column in the table player_slots (credits, alignment_score ...).
 * Everything else lives together in the text column story_flags, written as JSON, for example
 * {"pact":"vir","visited_narn":1}. B5_KEY_COLUMN below tells which key belongs to which column.
 */

// Short key used in the rules  =>  real column name in player_slots.
const B5_KEY_COLUMN = [
    'credits'         => 'credits',
    'hp'              => 'player_hp',
    'focus'           => 'analysis_focus',
    'origin'          => 'character_origin',
    'alignment'       => 'alignment_score',   // -100 (ruthless) ... +100 (compassionate)
    'drakh'           => 'drakh_influence',   // 0 ... 100
    'threat'          => 'bester_threat_level', // 0 ... 100, grows slowly with every hyperspace jump
    'lyta'            => 'lyta_recruited',
    'narn_avantgarde' => 'is_narn_avantgarde_recruited',
    'narn_mercs'      => 'is_narn_mercenaries_hired',
    'talia_saved'     => 'talias_trauma_resolved',
    'calibration'     => 'ship_calibration_path',   // 1 = binary, 2 = organic, 3 = hybrid
    'crystal'         => 'has_delenns_data_crystal',
    'constructor'     => 'constructor_rescued',
];

// Allowed range of numeric values: key => [minimum, maximum]. A value can never leave its range.
const B5_KEY_RANGE = [
    'hp'        => [0, 100],
    'alignment' => [-100, 100],
    'drakh'     => [0, 100],
    'threat'    => [0, 100],
];

// Game over limit for the "total threat" (see b5_total_threat()).
const B5_GAME_OVER_THREAT = 60;


// =============================================================================================
// STATE: all values of one save slot in one array
// =============================================================================================

/** Reads the JSON text of story_flags into an array. Broken or empty text gives an empty array. */
function b5_flags_decode($json)
{
    if (!is_string($json) || $json === '') {
        return [];
    }
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

/**
 * Builds ONE array with every value the rules can look at: the columns (renamed to the short keys)
 * plus all flags. Example result: ['credits' => 10000, 'alignment' => 5, 'pact' => 'vir', ...]
 */
function b5_state(array $slot)
{
    $state = b5_flags_decode($slot['story_flags'] ?? '');
    foreach (B5_KEY_COLUMN as $key => $column) {
        $state[$key] = $slot[$column] ?? '';
    }
    return $state;
}


// =============================================================================================
// CONDITIONS ("requires")
// =============================================================================================

/** Checks ONE rule text such as "alignment>=15" against the state. Returns true or false. */
function b5_check_rule(array $state, $rule)
{
    $rule = trim((string)$rule);
    // Split into: optional "!", the key, optional operator, the rest (= the compare value).
    if (!preg_match('/^(!?)([a-z_0-9]+)\s*(>=|<=|!=|=|>|<)?\s*(.*)$/', $rule, $m)) {
        return false; // an unreadable rule never opens a door
    }
    $negate = ($m[1] === '!');
    $key    = $m[2];
    $op     = $m[3];
    $value  = $m[4];
    $current = $state[$key] ?? '';

    if ($op === '') {
        // No operator: just ask "is it set?"
        $isSet = ($current !== '' && $current !== 0 && $current !== '0' && $current !== false && $current !== null);
        return $negate ? !$isSet : $isSet;
    }
    if ($negate) {
        return false; // "!" is only allowed without an operator
    }

    if (is_numeric($value)) {
        // Numeric compare. A value that was never set counts as 0.
        $a = is_numeric($current) ? (float)$current : 0.0;
        $b = (float)$value;
        switch ($op) {
            case '>=': return $a >= $b;
            case '<=': return $a <= $b;
            case '>':  return $a >  $b;
            case '<':  return $a <  $b;
            case '!=': return $a != $b;
            default:   return $a == $b;
        }
    }

    // Text compare (only = and != make sense for text).
    if ($op === '!=') {
        return (string)$current !== $value;
    }
    if ($op === '=') {
        return (string)$current === $value;
    }
    return false;
}

/** Checks a whole JSON list of rules. No rules (NULL or empty) means: always allowed. */
function b5_conditions_met(array $state, $json)
{
    if ($json === null || $json === '') {
        return true;
    }
    $rules = json_decode($json, true);
    if (!is_array($rules)) {
        return false;
    }
    foreach ($rules as $rule) {
        if (!b5_check_rule($state, $rule)) {
            return false; // one failed rule is enough
        }
    }
    return true;
}


// =============================================================================================
// EFFECTS ("sets")
// =============================================================================================

/**
 * Executes the effect rules. $slot (the columns) and $flags (the JSON values) are changed directly,
 * that is what the "&" in front of the parameters means ("by reference").
 */
function b5_apply_effects(array &$slot, array &$flags, $json)
{
    if ($json === null || $json === '') {
        return;
    }
    $rules = json_decode($json, true);
    if (!is_array($rules)) {
        return;
    }

    foreach ($rules as $rule) {
        if (!preg_match('/^([a-z_0-9]+)\s*(\+=|-=|=)\s*(.*)$/', trim((string)$rule), $m)) {
            continue; // skip unreadable rules
        }
        $key = $m[1];
        $op  = $m[2];
        $raw = $m[3];

        $column  = B5_KEY_COLUMN[$key] ?? null;
        $current = ($column !== null) ? ($slot[$column] ?? 0) : ($flags[$key] ?? 0);

        if ($op === '=') {
            $new = is_numeric($raw) ? (int)$raw : $raw;
        } else {
            $delta = is_numeric($raw) ? (int)$raw : 0;
            $base  = is_numeric($current) ? (int)$current : 0;
            $new   = ($op === '+=') ? $base + $delta : $base - $delta;
        }

        if (is_int($new) && isset(B5_KEY_RANGE[$key])) {
            $new = max(B5_KEY_RANGE[$key][0], min(B5_KEY_RANGE[$key][1], $new));
        }

        if ($column !== null) {
            $slot[$column] = $new;
        } else {
            $flags[$key] = $new;
        }
    }
}


// =============================================================================================
// CHOICES: which buttons does the player see?
// =============================================================================================

/** True if the FIRST rule of a requires list is met (or there is only one rule). */
function b5_first_rule_met(array $state, $json)
{
    $rules = json_decode((string)$json, true);
    if (!is_array($rules) || count($rules) < 2) {
        return true;
    }
    return b5_check_rule($state, $rules[0]);
}

/**
 * Returns the buttons of a scene as a list. Every entry has the keys
 *   choice_id, choice_text, locked (true/false), hint (only for locked buttons)
 *  - condition met            -> normal button
 *  - condition NOT met + hint -> greyed out button that shows the hint ("Requires ...")
 *  - condition NOT met, no hint -> the button is hidden completely
 * Rule of thumb for the greyed out buttons: the FIRST rule of "requires" decides whether the button
 * is shown at all (for example "mars_gate=1": only after Mars). The other rules are the ones
 * that are explained by the hint.
 */
function b5_visible_choices(PDO $pdo, $nodeId, array $state)
{
    $stmt = $pdo->prepare(
        "SELECT choice_id, choice_text, requires, locked_hint FROM story_choices WHERE from_node_id = ? ORDER BY choice_id"
    );
    $stmt->execute([$nodeId]);

    $list = [];
    foreach ($stmt->fetchAll() as $row) {
        if (b5_conditions_met($state, $row['requires'])) {
            $list[] = ["choice_id" => (int)$row['choice_id'], "choice_text" => $row['choice_text'], "locked" => false];
        } elseif ($row['locked_hint'] !== null && $row['locked_hint'] !== ''
                 && b5_first_rule_met($state, $row['requires'])) {
            $list[] = ["choice_id" => (int)$row['choice_id'], "choice_text" => $row['choice_text'],
                       "locked" => true, "hint" => $row['locked_hint']];
        }
    }
    return $list;
}


// =============================================================================================
// SCENE TEXT: variants and placeholders
// =============================================================================================

/**
 * Takes the basic text of a node and changes it depending on the state.
 * Table story_node_text holds the variants. Each variant has a mode:
 *   'replace' = the whole text is swapped (the variant with the highest priority that matches wins)
 *   'append'  = the text is added below (all matching variants are added, highest priority first)
 * Afterwards the placeholders {{STATUS}} and {{LOST_LIST}} are filled in.
 */
function b5_node_text(PDO $pdo, $nodeId, $baseText, array $state)
{
    $stmt = $pdo->prepare("SELECT requires, body, mode FROM story_node_text WHERE node_id = ? ORDER BY priority DESC, text_id");
    $stmt->execute([$nodeId]);

    $text = $baseText;
    $replaced = false;
    $appendix = [];
    foreach ($stmt->fetchAll() as $v) {
        if (!b5_conditions_met($state, $v['requires'])) {
            continue;
        }
        if ($v['mode'] === 'replace') {
            if (!$replaced) {
                $text = $v['body'];
                $replaced = true;
            }
        } else {
            $appendix[] = $v['body'];
        }
    }
    if (count($appendix) > 0) {
        $text .= "\n\n" . implode("\n\n", $appendix);
    }

    // Placeholders are only replaced if they are really in the text.
    if (strpos($text, '{{STATUS}}') !== false) {
        $text = str_replace('{{STATUS}}', b5_status_block($state), $text);
    }
    if (strpos($text, '{{LOST_LIST}}') !== false) {
        $text = str_replace('{{LOST_LIST}}', b5_lost_list($state), $text);
    }
    return $text;
}

/** Human readable name of the Narn situation. */
function b5_narn_label(array $s)
{
    switch ($s['narn_state'] ?? '') {
        case 'allied':   return 'Kha\'Ri fleet pledged';
        case 'wary':     return 'wary, fleet pledged';
        case 'mercs':    return 'regular fleet lost, mercenaries hired';
        case 'betrayed': return 'betrayed - only mercenaries (20,000 cr) are left';
        default:         return empty($s['visited_narn']) ? 'not contacted yet' : 'unresolved';
    }
}

/** Status lines shown in the hub, so the player can see where the alliance stands. */
function b5_status_block(array $s)
{
    $pact = $s['pact'] ?? '';
    if ($pact === 'londo') {
        $centauri = 'military pact signed (heavy warships, Drakh influence rising)';
    } elseif ($pact === 'vir') {
        $centauri = 'Vir\'s rescue fleet pledged';
    } elseif ($pact === 'double') {
        $centauri = 'warships pledged through a bribe (Drakh influence slightly up)';
    } else {
        $centauri = 'not contacted yet';
    }

    $lyta = 'not found yet';
    if (!empty($s['lyta'])) {
        $lyta = (($s['lyta_state'] ?? '') === 'light') ? 'aboard (path of light)' : 'aboard (path of vengeance)';
    } elseif ((int)($s['lyta_visits'] ?? 0) >= 2) {
        $lyta = 'refused you';
    } elseif ((int)($s['lyta_visits'] ?? 0) === 1) {
        $lyta = 'met once - she wants proof';
    }

    $line  = "ALLIANCE STATUS\n";
    $line .= " - Narn Homeworld: " . b5_narn_label($s) . "\n";
    $line .= " - Centauri Prime: " . $centauri . "\n";
    $line .= " - Lyta Alexander: " . $lyta . "\n";
    $line .= " - Sleeper network pressure: " . (int)($s['threat'] ?? 0) . "%";
    return $line;
}

/** Lists what the player gives up by crossing the Mars point of no return. */
function b5_lost_list(array $s)
{
    $lines = [];
    $narnOk = in_array($s['narn_state'] ?? '', ['allied', 'wary', 'mercs'], true);
    if (!$narnOk) {
        if (($s['narn_state'] ?? '') === 'betrayed') {
            $lines[] = " - Narn: you only have the option to hire mercenaries. After Mars that chance is gone.";
        } else {
            $lines[] = " - Narn: no fleet will answer your call.";
        }
    }
    if (empty($s['pact'])) {
        $lines[] = " - Centauri Prime: neither Londo's warships nor Vir's rescue fleet will join you.";
    }
    if (empty($s['lyta']) && (int)($s['lyta_visits'] ?? 0) === 0) {
        $lines[] = " - Lyta: you have not met her. After Mars she will demand much more from you.";
    }
    $lines[] = " - Medlab Alpha: no more treatment before the assault.";

    if (count($lines) === 1) {
        return "Everything that can be secured has been secured:\n" . $lines[0];
    }
    return "Once you commit to Mars the following doors close behind you:\n" . implode("\n", $lines);
}


// =============================================================================================
// ENDINGS
// =============================================================================================

/**
 * The "total threat" is the slowly growing pressure of the sleeper network plus a penalty for
 * every ally the player failed to win. If it reaches B5_GAME_OVER_THREAT the assault fails.
 */
function b5_total_threat(array $s)
{
    $total = (int)($s['threat'] ?? 0);
    $narnOk = in_array($s['narn_state'] ?? '', ['allied', 'wary', 'mercs'], true);
    if (!$narnOk) {
        $total += 35;   // no Narn fleet (never contacted, or betrayed and no mercenaries)
    }
    if (empty($s['pact'])) {
        $total += 25;   // Centauri Prime was never visited
    }
    if (empty($s['lyta'])) {
        $total += 20;   // no telepath on the strike team
    }
    return $total;
}

/**
 * Decides which ending node the player gets. The FIRST matching rule wins, so the order matters.
 * Returns the node id of the ending.
 *
 *   195 Ending 6  Game Over - "The Silence"
 *   194 Ending 5  The Shadow Director
 *   193 Ending 4  Firestorm, the canon echo (Lyta's vengeance, HQ explodes)
 *   128 Ending 7  Canon Resonance (best ending, Vir pact or the smuggler's bribe deal)
 *   191 Ending 2  The Price of the Empire
 *   190 Ending 1  Sad canon ending (Lennier's sacrifice)  (Vir pact OR the smuggler's bribe deal)
 *   192 Ending 3  The Silent Sacrifice (everything else)
 */
function b5_resolve_ending(array $slot)
{
    $s = b5_state($slot);
    $lytaState = $s['lyta_state'] ?? '';
    $final     = $s['final'] ?? '';
    $pact      = $s['pact'] ?? '';
    $narnAllied = in_array($s['narn_state'] ?? '', ['allied', 'wary'], true);
    // 'double' is the smuggler's way: Centauri warships bought with a bribe, Narn stay loyal.
    $pactNoBetrayal = ($pact === 'vir' || $pact === 'double');

    if ((int)$s['hp'] <= 0 || b5_total_threat($s) >= B5_GAME_OVER_THREAT) {
        return 195;
    }
    if ($final === 'execute') {
        return 194;
    }
    if ($lytaState === 'vengeance') {
        return 193;
    }
    if ($pactNoBetrayal && $narnAllied && $lytaState === 'light') {
        return 128;
    }
    if ($pact === 'londo') {
        return 191;
    }
    if ($pactNoBetrayal && $narnAllied && empty($s['lyta']) && $final === 'meldor') {
        return 190;
    }
    return 192;
}
