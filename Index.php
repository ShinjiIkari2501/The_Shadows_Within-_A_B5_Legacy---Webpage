<?php
/**
 * Index.php
 * Main Command Deck & Tactical Overview for 'The Shadows Within - A B5 Legacy'
 * Dynamically detects active saves from player_slots and handles the layout states.
 */

// 1. Load the core layout center (Automatically initializes the running session)
require_once 'Includes/layout.php';

// 2. Activate output buffering for core content injection
ob_start();

// USER AUTHENTICATION INTEGRATION
$isLoggedIn = 0;
$userId = 0;
$currentSceneInfo = "[No active simulation save detected]";

if (isset($_SESSION['eingeloggt']) && $_SESSION['eingeloggt'] === true) {
    $isLoggedIn = 1;
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;

    // =========================================================================
    // AUTOMATED SAVEGAME MATRIX DETECTOR (Aligned with player_slots)
    // =========================================================================
    try {
        require_once 'Includes/db_connect.php';

        // Fetch the newest active save node matching your normaliced database schema
        $stmt = $pdo->prepare("SELECT slot_number, last_updated, current_node_id FROM player_slots WHERE user_id = ? ORDER BY last_updated DESC LIMIT 1");
        $stmt->execute([$userId]);
        $latestSave = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($latestSave) {
            $currentSceneInfo = "Slot 0" . $latestSave['slot_number'] . " (Node ID: " . $latestSave['current_node_id'] . " | Telemetry Sync: " . date('d.m.Y H:i', strtotime($latestSave['last_updated'])) . ")";
        }
    } catch (PDOException $e) {
        // FALLBACK: Scan transient session array records if production database links decouple
        if (isset($_SESSION['fallback_slots'][$userId])) {
            $slots = $_SESSION['fallback_slots'][$userId];
            krsort($slots);
            foreach ($slots as $slotNum => $slotContent) {
                $steps = is_array($slotContent['data']) ? count($slotContent['data']) : 0;
                $currentSceneInfo = "Slot 0" . $slotNum . " (Steps: " . $steps . " | Status: " . $slotContent['date'] . " - Session Fallback Mode)";
                break;
            }
        }
    }
}
?>

<?php if ($isLoggedIn === 1): ?>

    <!-- ==========================================================================
         STATUS A: COMMANDER INTERNAL CORE TERMINAL (LOGGED IN)
         ========================================================================== -->
    <h2>Welcome to the ISA-Network</h2>
    <h3>Welcome back, Commander <?php echo htmlspecialchars($_SESSION['username']); ?></h3>

    <p>
        🔒 Secure sub-space connection fully established with Tuzanor.
        All local tactical feeds, crew logs, and tactical gameplay matrices are decrypted and at your disposal.
    </p>

    <!-- TACTICAL MONITOR: REAL-TIME RUNTIME METRICS OVERVIEW -->
    <div class="notice">
        <h4>🛰️ CURRENT MATRIX LEVEL & ACCESS RIGHTS</h4>
        <ul class="plain-list">
            <li><strong>Clearance Level:</strong> Level 1 Security Clearance (Ranger Command Core)</li>
            <li><strong>Active Entity ID:</strong> Verified Account Database Node Link</li>
            <li><strong>Current Savegame Node:</strong> <strong><?php echo htmlspecialchars($currentSceneInfo); ?></strong></li>
        </ul>
        <p>
            *Notice: To resume your adventure matrix coordinates, select the 'Tactical Simulation Deck' from the right navigation panel.
        </p>
    </div>

<?php else: ?>

    <!-- ==========================================================================
         STATUS B: ANONYMOUS WELCOME SCREEN (NOT LOGGED IN)
         ========================================================================== -->
    <h2>Welcome to the ISA-Network</h2>
    <h3>Main Terminal / Sector 2264 Overview</h3>

    <p>
        Welcome to the official communication array of the hybrid prototype starship <em>Liburnia</em>.
        This tactical interface serves as the primary data hub for Interstellar Alliance operations
        within the isolated Earth Alliance borders during the tense blackout of 2264.
    </p>

    <!-- ROUTING DISPATCH ALERT FOR ANONYMOUS ATTRIBUTES -->
    <div class="notice">
        <h4>📢 STRATEGIC ROUTING NOTICE</h4>
        <p>
            Via the **Command Matrix Terminal Menu on the right side** you have clear access to public data archives, including the historical Timeline and Fleet Manifest channels.
        </p>
        <p>
            ⚠️ ATTENTION: The tactical Gameplay channel (Simulation Deck Matrix) is strictly encrypted and requires a validated entity ID verification clearance (Registered Login Required)!
        </p>
    </div>

    <p>
        Current Station Situation: The galaxy remains in a fragile peace. Ranger teams report unusual sub-space anomalies near the Minbari border,
        while diplomatic channels to Babylon 5 are heavily encrypted. Browse the tactical archives via the console to review the Timeline,
        Gameplay grids, and current Fleet Manifests.
    </p>

    <!-- RECRUIT CLEARANCE HUB GATEWAY LINK -->
    <div class="notice" style="text-align:center">
        <h4>NEW RECRUIT? REQUEST SECURITY CLEARANCE</h4>
        <p>If you do not possess active credentials, you must register your entity in the Alliance database first.</p>

        <a href="Register.php">
            <button type="button">
                CREATE ACCOUNT
            </button>
        </a>
    </div>

<?php endif; ?>

<?php
$seitenInhalt = ob_get_clean();
renderLayout("B5 Legacy - Main Terminal", $seitenInhalt);
?>
