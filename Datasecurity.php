<?php 
// 1. Die Layout-Zentrale laden (Startet auch automatisch die Session)
require_once 'Includes/layout.php'; 

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start(); 
?>

<!-- AB HIER SCHREIBST DU DEN REINEN INHALT FÜR DEN MAIN-BEREICH -->
<h2>Data Security & Privacy Policy</h2>
<h3>International Compliance Protocol / Interstellar Alliance Archives</h3>

<p>
    This interface operates in accordance with global data protection standards. Below you will find the 
    legal framework governing how this terminal processes, handles, and secures transmission telemetry 
    and crew entity profiles.
</p>

<div style="margin-top: 25px; border-top: 1px solid rgba(96, 172, 243, 0.2); padding-top: 20px;">
    
    <h4>1. General Framework & Jurisdiction</h4>
    <p>
        As a modern web application, this system is designed to comply with major international data privacy laws, 
        ensuring a safe environment for all command personnel. This includes the European General Data Protection 
        Regulation (<strong>GDPR / DSGVO</strong>) for users within the European Sector, as well as the California 
        Consumer Privacy Act (<strong>CCPA</strong>) for users accessing the grid from Western Alliance sectors.
    </p>

    <h4>2. Collection of Personal Telemetry (Registration & Session)</h4>
    <p>
        When you enroll a new entity profile through our recruitment grid, the system temporarily saves data to 
        authenticate your clearance level:
    </p>
    <ul>
        <li><strong>Username / Callsign:</strong> Used exclusively to identify your terminal session.</li>
        <li><strong>Security Token (Password):</strong> Evaluated locally via server-side routines to grant access.</li>
        <li><strong>Session Cookies:</strong> Temporary diagnostic tokens stored in your browser's local cache to keep your uplink active while navigating through different station sectors.</li>
    </ul>

    <h4>3. Anti-Bot Defense Measures (Honeypot)</h4>
    <p>
        To protect the communication array from automated spam attacks (Shadow reconnaissance bots), our input grids 
        utilize hidden structural traps ("Honeypots"). These scripts analyze automated input signals. No personal 
        identifiable information is collected or shared during this threat-assessment procedure.
    </p>

    <h4>4. Your Tactical Data Rights</h4>
    <p>
        Under international regulations (GDPR), you possess full control over your telemetry. You have the right to inspect 
        your saved data profile, request modifications, or trigger a complete erasure of your record. By clicking the 
        <strong>LOGOUT</strong> button on the left console, your active local session token is immediately destroyed 
        and purged from the station's server memory.
    </p>

    <!-- LEGAL DISCLAIMER & INTELLECTUAL PROPERTY RECONSTRUCTION -->
    <div style="margin-top: 40px; background-color: rgba(255, 153, 0, 0.03); border: 1px solid rgba(255, 153, 0, 0.2); padding: 20px; border-radius: 6px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);">
        <h4 style="color: #ff9900; margin-top: 0; text-shadow: 0 0 4px rgba(255, 153, 0, 0.4); font-family: 'B5Station', Arial, sans-serif;">
            ⚖️ LEGAL DISCLAIMER & INTELLECTUAL PROPERTY NOTICE
        </h4>
        
        <!-- SEKTOR A: DEIN GEISTIGES EIGENTUM (Vollständiger Urheberschutz) -->
        <p style="font-size: 0.95em; line-height: 1.5; color: #ffffff; font-weight: bold; margin-bottom: 12px; text-shadow: 0 0 3px rgba(255, 255, 255, 0.3);">
            🔒 OWNERSHIP OF INTELLECTUAL PROPERTY (DANIEL BAEDORF):
        </p>
        <p style="font-size: 0.9em; line-height: 1.5; color: hsl(0, 9%, 90%); margin-top: 0; margin-bottom: 15px;">
            The entire custom source code (PHP, HTML, CSS architecture), the responsive visual user-interface design, 
            the proprietary Python text-adventure game logic, as well as all original fan-fiction storylines (the sector 2264 blackout crisis) 
            and newly introduced characters created specifically for this project remain the **exclusive intellectual property and copyrighted work of Daniel Baedorf (aka Shinji2501)**. 
            Unauthorized replication, distribution, or commercial use of these custom assets is strictly prohibited under international copyright laws.
        </p>
        
        <div style="border-top: 1px dashed rgba(96, 172, 243, 0.2); margin: 15px 0; padding-top: 15px;"></div>

        <!-- SEKTOR B: WARNER BROS. LIZENZEN -->
        <p style="font-size: 0.95em; line-height: 1.5; color: #60acf3; font-weight: bold; margin-bottom: 12px;">
            🛰️ THIRD-PARTY TRADEMARKS & UNIVERSAL LICIENCES:
        </p>
        <p style="font-size: 0.9em; line-height: 1.5; color: hsl(0, 9%, 85%); margin-top: 0; margin-bottom: 10px;">
            This website is a non-commercial, non-profit fan project created solely for educational and entertainment purposes. 
            **Babylon 5**, including all officially established names, canon starship concepts, factions, background lore, and imagery 
            referenced to embed this custom story into the universe, are the exclusive legal trademark and copyrighted property of 
            **Warner Bros. Entertainment Inc.** and J. Michael Straczynski. 
        </p>
        
        <p style="font-size: 0.9em; line-height: 1.5; color: hsl(0, 9%, 85%); margin-top: 10px; margin-bottom: 0;">
            No copyright infringement is intended towards the original license holders. All custom-coded assets and original fictional sub-plots 
            remain unaffected by this reference and are protected under independent authorship.
        </p>
    </div>


</div>

<p style="text-align: center; margin-top: 30px;">
    <a href="Index.php" style="color: #ff9900; text-decoration: none; font-weight: bold;">➔ Return to Main Terminal</a>
</p>

<?php 
// 3. Den Inhalt aus dem Zwischenspeicher holen und in eine Variable packen
$seitenInhalt = ob_get_clean(); 

// 4. Das Layout aufrufen und den Titel + Inhalt übergeben
renderLayout("B5 Legacy - Data Security & Disclaimer", $seitenInhalt); 
?>
