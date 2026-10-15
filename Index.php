<?php 
// 1. Die Layout-Zentrale laden (Sie startet auch automatisch die Session)
require_once 'Includes/layout.php'; 

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start(); 

// LOGIN-PRÜFUNG UND VARIABLEN-SETUP
$isLoggedIn = 0;
$userId = 0;
$currentSceneInfo = "[Kein aktiver Spielstand]";

if (isset($_SESSION['eingeloggt']) && $_SESSION['eingeloggt'] === true) {
    $isLoggedIn = 1;
    // Fallback auf Standard-ID 1, falls dein Login-System noch keine IDs verteilt
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1; 

    // =========================================================================
    // AUTOMATISCHER SPEICHERSTANDS-DETEKTOR FÜR DEN INDEX
    // =========================================================================
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=deine_datenbank;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Holt den neuesten Speicherstand des angemeldeten Benutzers aus der SQL-Datenbank
        $stmt = $pdo->prepare("SELECT slot_number, updated_at, save_data FROM game_saves WHERE user_id = ? ORDER BY updated_at DESC LIMIT 1");
        $stmt->execute([$userId]);
        $latestSave = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($latestSave) {
            $saveData = json_decode($latestSave['save_data'], true);
            // Zählt die Einträge im Array, um zu wissen, bei welchem Schritt der Spieler ist
            $steps = is_array($saveData) ? count($saveData) : 0;
            $currentSceneInfo = "Slot 0" . $latestSave['slot_number'] . " (Schritt: " . $steps . " | Letztes Update: " . date('d.m.Y H:i', strtotime($latestSave['updated_at'])) . ")";
        }
    } catch (PDOException $e) {
        // FALLBACK: Falls wir auf Render.com ohne SQL sind, scannen wir den Session-Zwischenspeicher aus
        if (isset($_SESSION['fallback_slots'][$userId])) {
            $slots = $_SESSION['fallback_slots'][$userId];
            // Sortiert nach dem neuesten Eintrag im Session-Array
            krsort($slots);
            foreach ($slots as $slotNum => $slotContent) {
                $steps = is_array($slotContent['data']) ? count($slotContent['data']) : 0;
                $currentSceneInfo = "Slot 0" . $slotNum . " (Schritt: " . $steps . " | Stand: " . $slotContent['date'] . " - Session-Mode)";
                break; 
            }
        }
    }
}
?>
<?php
// PRÜFEN: Ist der Commander eingeloggt?
if ($isLoggedIn === 1): 
?>

    <!-- ==========================================================================
         STATUS A: COMMANDER INTERNES CORE-TERMINAL (EINGELOGGT)
         ========================================================================== -->
    <h2>Welcome to the ISA-Network</h2>
    <h3>Welcome back, Commander <?php echo htmlspecialchars($_SESSION['username']); ?></h3>
    
    <p>
        🔒 Secure sub-space connection fully established with Tuzanor. 
        All local tactical feeds, crew logs, and tactical gameplay matrices are decrypted and at your disposal.
    </p>

    <!-- NEU: ECHTZEIT-ÜBERBLICK ÜBER DEN SPEICHERSTAND -->
    <div style="margin: 20px 0; background-color: rgba(0, 255, 204, 0.05); border: 1px solid rgba(0, 255, 204, 0.3); padding: 15px; border-radius: 6px;">
        <h4 style="color: #00ffcc; margin-top: 0; font-family: 'Courier New', monospace;">🛰️ CURRENT MATRIX LEVEL & ACCESS RIGHTS</h4>
        <ul style="list-style: none; padding: 0; margin: 0; font-family: 'Courier New', monospace; font-size: 0.95em; color: #e0e0e0; line-height: 1.6;">
            <li><strong>Clearance Level:</strong> Level 1 Security Clearance (Ranger Command)</li>
            <li><strong>Active Entity ID:</strong> Verified Account Database Node</li>
            <li><strong>Current Savegame Node:</strong> <span style="color: #00ffcc; font-weight: bold;"><?php echo htmlspecialchars($currentSceneInfo); ?></span></li>
        </ul>
        <p style="font-size: 0.85em; color: #888; margin: 8px 0 0 0;">
            *Hinweis: Wenn du dein Abenteuer fortsetzen möchtest, wähle im Navigationsmenü (rechts) das 'Tactical Simulation Deck'.
        </p>
    </div>

    <div class="char-container" style="margin-top: 25px;">
        <!-- Taktische Systemoption 1 -->
        <div class="char-card">
            <div class="char-info">
                <h4>🛰️ Fleet Com-Array Sub-System</h4>
                <p class="char-meta">Status: Operational / Encrypted</p>
                <p>Access active reconnaissance frequencies and monitor the planetary jam-wall surrounding the Sol-System. Direct link to Ranger strike teams is on standby.</p>
                <a href="#" style="color: #ff9900; text-decoration: none; font-weight: bold; display: inline-block; margin-top: 10px;">➔ Open Transmission Deck</a>
            </div>
        </div>

        <!-- Taktische Systemoption 2 -->
        <div class="char-card">
            <div class="char-info">
                <h4>🧬 Biomechanical Core Diagnostics</h4>
                <p class="char-meta">Status: Stability at 94%</p>
                <p>Review the telemetry of the Liburnia’s Earth-Ipsha hybrid engines. Engineering bay logs from Sha'In are updated in real-time.</p>
                <a href="#" style="color: #ff9900; text-decoration: none; font-weight: bold; display: inline-block; margin-top: 10px;">➔ Calibrate Power Grid</a>
            </div>
        </div>
    </div>

<?php else: ?>

    <!-- ==========================================================================
         STATUS B: ANONYMER WILLKOMMENS-BILDSCHIRM (NICHT EINGELOGGT)
         ========================================================================== -->
    <h2>Welcome to the ISA-Network</h2>
    <h3>Main Terminal / Sector 2264 Overview</h3>
    
    <p>
        Welcome to the official communication array of the hybrid prototype starship <em>Liburnia</em>. 
        This tactical interface serves as the primary data hub for Interstellar Alliance operations 
        within the isolated Earth Alliance borders during the tense blackout of 2264.
    </p>

    <!-- GEWÜNSCHTE HINWEISE FÜR ANONYME BESUCHER -->
    <div style="margin: 20px 0; background-color: rgba(96, 172, 243, 0.05); border: 1px solid rgba(96, 172, 243, 0.3); padding: 15px; border-radius: 6px; font-family: Arial, sans-serif; line-height: 1.5;">
        <h4 style="color: #60acf3; margin-top: 0; font-family: 'B5Station', Arial, sans-serif;">📢 STRATEGIC ROUTING NOTICE</h4>
        <p style="margin: 5px 0; font-size: 0.95em;">
            Über das **Terminalmenü auf der rechten Seite** hast du freien Zugriff auf die verschiedenen öffentlichen Informationskanäle der Allianz, wie den historischen Zeitstrahl und die Flotten-Manifeste.
        </p>
        <p style="margin: 10px 0 0 0; font-size: 0.95em; color: #ff9900; font-weight: bold;">
            ⚠️ ACHTUNG: Der taktische Gameplay-Kanal (Simulation Deck) ist streng verschlüsselt und erfordert zwingend eine gültige ID-Berechtigung (Login für registrierte Nutzer)!
        </p>
    </div>

    <p>
        Current Station Situation: The galaxy remains in a fragile peace. Ranger teams report unusual sub-space anomalies near the Minbari border, 
        while diplomatic channels to Babylon 5 are heavily encrypted. Browse the tactical archives via the console to review the Timeline, 
        Gameplay grids, and current Fleet Manifests.
    </p>

    <!-- Der sauber kalibrierte Registrierungs-Bereich für Rekruten -->
    <div style="margin-top: 35px; background-color: rgba(96, 172, 243, 0.05); border: 1px solid rgba(96, 172, 243, 0.2); padding: 20px; border-radius: 6px; text-align: center;">
        <h4 style="color: #ff9900; margin-top: 0; text-shadow: 0 0 4px rgba(255, 153, 0, 0.4); font-family: 'B5Station', Arial, sans-serif;">NEW RECRUIT? REQUEST SECURITY CLEARANCE</h4>
        <p style="font-size: 0.9em; margin-bottom: 20px; font-family: Arial, sans-serif; color: hsl(0, 9%, 90%);">If you do not possess active credentials, you must register your entity in the Alliance database first.</p>
        
        <a href="Register.php" style="text-decoration: none; display: inline-block;">
            <button type="button" style="
                display: block !important;
                margin: 0 auto !important;
                padding: 6px 20px !important;           
                cursor: pointer !important; 
                border-radius: 4px !important;
                border: 1px solid rgba(255, 153, 0, 0.4) !important; 
                background-color: rgba(60, 60, 60, 0.4) !important; 
                color: #ffffff !important; 
                font-family: 'B5Station', Arial, sans-serif !important;
                font-size: 0.9em !important;
                font-weight: normal !important;
                text-transform: uppercase !important;   
                letter-spacing: 1px !important;         
                text-shadow: 0 0 4px rgba(255, 255, 255, 0.6) !important;
                transition: all 0.2s ease !important;    
            "
            onmouseover="this.style.backgroundColor='rgba(26, 47, 122, 0.6)'; this.style.color='#ff9900'; this.style.textShadow='0 0 4px rgba(255, 153, 0, 0.5)'; this.style.borderColor='#ff9900'; this.style.boxShadow='0 0 10px rgba(255, 153, 0, 0.8)';"
            onmouseout="this.style.backgroundColor='rgba(60, 60, 60, 0.4)'; this.style.color='#ffffff'; this.style.textShadow='0 0 4px rgba(255, 255, 255, 0.6)'; this.style.borderColor='rgba(255, 153, 0, 0.4)'; this.style.boxShadow='none';">
                CREATE ACCOUNT
            </button>
        </a>
    </div>

<?php endif; ?>

<?php 
// 4. Den Inhalt aus dem Zwischenspeicher holen
$seitenInhalt = ob_get_clean(); 

// 5. Das Layout mit individuellem Titel rendern
renderLayout("B5 Legacy - Main Terminal", $seitenInhalt); 
?>
