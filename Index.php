<?php 


// 1. Die Layout-Zentrale laden (Sie startet auch automatisch die Session)
require_once 'Includes/layout.php'; 

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start(); 
?>

<?php
// PRÜFEN: Ist der Commander eingeloggt?
if (isset($_SESSION['eingeloggt']) && $_SESSION['eingeloggt'] === true): 
?>

    <!-- ==========================================================================
         STATUS A: COMMANDER INTERNES CORE-TERMINAL (EINGELOGGT)
         ========================================================================== -->
        <!-- KORREKTUR: Text läuft in Serpentine, die 5 schaltet dank 'Logo5' auf B5Station um! -->
    <h2>The Shadows within: A B<span class="Logo5">5</span> Legacy</h2>
    <h3>Main Terminal / Sector <span class="Logo5">2264</span> Overview</h3>

    
    <p>
        🔒 Secure sub-space connection fully established with Tuzanor. 
        All local tactical feeds and crew logs are decrypted and at your disposal.
    </p>

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
    <h2>The Shadows within: A B&copy; Legacy</h2>
    <h3>Main Terminal / Sector 2264 Overview</h3>
    
    <p>
        Welcome to the official communication array of the hybrid prototype starship <em>Liburnia</em>. 
        This tactical interface serves as the primary data hub for Interstellar Alliance operations 
        within the isolated Earth Alliance borders during the tense blackout of 2264.
    </p>

    <p>
        Current Station Situation: The galaxy remains in a fragile peace. Ranger teams report unusual sub-space anomalies near the Minbari border, 
        while diplomatic channels to Babylon 5 are heavily encrypted. Browse the tactical archives via the left console to review the Timeline, 
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
