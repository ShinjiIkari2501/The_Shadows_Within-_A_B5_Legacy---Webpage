<?php 
// 1. Load the core layout center (Automatically initializes the running session)
require_once 'Includes/layout.php';

// ==========================================================================
// BACKEND LOGIC: PROCESSING THE ALLIANCE REGISTRATION
// ==========================================================================
if (isset($_POST['registerSubmit'])) {

    // Anti-Spam Bot Protection (Honeypot)
    if (!empty($_POST['eMail'])) {
        die("Transmission blocked: Bot activity detected by security phalanx.");
    }

    $regUser = trim($_POST['reg_username'] ?? '');
    $regPass = $_POST['reg_password'] ?? '';
    $regPassConfirm = $_POST['reg_password_confirm'] ?? '';

    // 1. DATA VALIDATION: Length checks matching game framework profiles
    if (strlen($regUser) < 8 || strlen($regUser) > 20) {
        $_SESSION['flash_message_error'] = "⚠️ REGISTRATION ERROR: Username must be between 8 and 20 characters.";
        header("Location: Register.php");
        exit();
    }

    if (strlen($regPass) < 8 || strlen($regPass) > 20) {
        $_SESSION['flash_message_error'] = "⚠️ REGISTRATION ERROR: Password must be between 8 and 20 characters.";
        header("Location: Register.php");
        exit();
    }

    if ($regPass !== $regPassConfirm) {
        $_SESSION['flash_message_error'] = "⚠️ REGISTRATION ERROR: Passwords do not match. Re-enter credentials.";
        header("Location: Register.php");
        exit();
    }

    // 2. DATABASE INTEGRATION: Inject live SQL sequence using centralized PDO manager
    try {
        // Load secure connection engine
        require_once 'Includes/db_connect.php';

        // Check if the username already exists in the persistent table
        $checkStmt = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
        $checkStmt->execute([':username' => $regUser]);

        if ($checkStmt->fetch()) {
            $_SESSION['flash_message_error'] = "⚠️ SECURITY VIOLATION: Callsign '" . htmlspecialchars($regUser) . "' is already allocated in permanent crew database.";
            header("Location: Register.php");
            exit();
        }

        // Securely hash the plain-text password using native standard PHP cryptography
        $securePasswordHash = password_hash($regPass, PASSWORD_DEFAULT);

        // Transaction Block: Ensure both the user profile and all 3 slots generate atomically
        $pdo->beginTransaction();

        // Transaction Step A: Write the user profile record
        $insertUserStmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)");
        $insertUserStmt->execute([
            ':username'      => $regUser,
            ':password_hash' => $securePasswordHash
        ]);

        // Grab the newly generated database auto-increment identity
        $newUserId = $pdo->lastInsertId();

        // Transaction Step B: Initialize exactly 3 fresh, empty save slots.
        // New slots start at node 100 (the prologue). The prologue leads to node 10 (the choice between
        // Intelligence and Underworld), so the character selection comes AFTER the prologue.
        $insertSlotsStmt = $pdo->prepare("INSERT INTO player_slots (user_id, slot_number, current_node_id) VALUES (:user_id, :slot_number, 100)");

        // Populate Slots 1, 2, and 3 sequentially
        for ($slotNum = 1; $slotNum <= 3; $slotNum++) {
            $insertSlotsStmt->execute([
                ':user_id'     => $newUserId,
                ':slot_number' => $slotNum
            ]);
        }

        // Commit transaction data securely to the disk
        $pdo->commit();

        // Establish session variables for unified web login matching successful registration
        $_SESSION['registrierter_user'] = $regUser;

        $_SESSION['flash_message'] = "✅ REGISTRATION COMPLETE: Entity $regUser secured. 3 tactical save slots allocated. Transmission routed to Command Deck.";
        header("Location: Index.php");
        exit();

    } catch (\Exception $exception) {
        // Rollback active structural inserts if an unexpected database core failure drops out
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }

        $_SESSION['flash_message_error'] = "🚨 CRITICAL ENGINE RECOIL: Registration transaction failed. Error: " . htmlspecialchars($exception->getMessage());
        header("Location: Register.php");
        exit();
    }
}

// 3. Initiate internal content layout buffering
ob_start();
?>
<!-- REINE INHALT FÜR DEN MAIN-BEREICH -->
<h2>Alliance Recruitment Grid</h2>
<h3>Database Enrollment / Security Phalanx</h3>

<section id="contact-section">
    <fieldset class="contact-fieldset" style="max-width: 500px; margin: 0 auto; background-color: rgba(13, 20, 59, 0.4); border: 1px solid rgba(96, 172, 243, 0.3); padding: 20px; border-radius: 6px;">
        <legend>New Entity Enrollment:</legend>

        <form action="Register.php" method="post">

            <div class="form-row" style="margin-bottom: 15px;">
                <div class="form-group full-width" style="text-align: left;">
                    <label for="reg_username" style="color: #ff9900; font-weight: bold; display: block; margin-bottom: 5px;">Desired Username: * (8-20 Chars)</label>
                    <input type="text" name="reg_username" id="reg_username" placeholder="Choose your callsign" minlength="8" maxlength="20" style="width: 100%; padding: 8px; background: rgba(0,0,0,0.5); border: 1px solid rgba(96, 172, 243, 0.4); color: #fff; border-radius: 4px;" required>
                </div>
            </div>

            <div class="form-row" style="margin-bottom: 15px;">
                <div class="form-group full-width" style="text-align: left;">
                    <label for="reg_password" style="color: #ff9900; font-weight: bold; display: block; margin-bottom: 5px;">Password: * (8-20 Chars)</label>
                    <input type="password" name="reg_password" id="reg_password" placeholder="Enter secure password" minlength="8" maxlength="20" style="width: 100%; padding: 8px; background: rgba(0,0,0,0.5); border: 1px solid rgba(96, 172, 243, 0.4); color: #fff; border-radius: 4px;" required>
                </div>
            </div>

            <div class="form-row" style="margin-bottom: 20px;">
                <div class="form-group full-width" style="text-align: left;">
                    <label for="reg_password_confirm" style="color: #ff9900; font-weight: bold; display: block; margin-bottom: 5px;">Confirm Password: *</label>
                    <input type="password" name="reg_password_confirm" id="reg_password_confirm" placeholder="Repeat your password" minlength="8" maxlength="20" style="width: 100%; padding: 8px; background: rgba(0,0,0,0.5); border: 1px solid rgba(96, 172, 243, 0.4); color: #fff; border-radius: 4px;" required>
                </div>
            </div>

            <input type="text" id="eMail" name="eMail" style="display:none !important;" tabindex="-1" autocomplete="off">

            <button type="submit" name="registerSubmit" class="formButton" style="background-color: rgba(26, 47, 122, 0.4); border: 1px solid rgba(255, 153, 0, 0.4); border-radius: 4px; color: #ff9900; padding: 8px 25px; font-weight: bold; cursor: pointer; text-transform: uppercase;">Request Clearance</button>
        </form>
    </fieldset>
</section>

<p style="text-align: center; margin-top: 20px;">
    Already have clearance? <a href="Index.php" style="color: #ff9900; text-decoration: none;">Return to Main Terminal</a>
</p>

<?php
$seitenInhalt = ob_get_clean();

renderLayout("B5 Legacy - Database Enrollment", $seitenInhalt);
?>
