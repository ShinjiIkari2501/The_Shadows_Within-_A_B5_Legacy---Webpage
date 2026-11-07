<?php 
// 1. Load layout center (Includes header, nav, and footer)
require_once 'Includes/layout.php'; 

// 2. Activate output buffering for main content
ob_start(); 
?>

<!-- PURE MAIN CONTENT BLOCK START -->
<h2>Data Security & Privacy Policy</h2>
<h3>International Compliance Protocol / Interstellar Alliance Archives</h3>

<p>
    This interface operates with a strict focus on user anonymity and data minimization. Below you will find the 
    legal framework governing how this terminal processes, handles, and secures your gameplay telemetry.
</p>

<div style="margin-top: 25px; border-top: 1px solid rgba(96, 172, 243, 0.2); padding-top: 20px;">
    
    <h4>1. General Framework & Zero-PII Policy</h4>
    <p>
        This system is engineered to protect user privacy by design. It complies with international data privacy principles 
        (such as <strong>GDPR / DSGVO</strong>) by avoiding the collection of any personally identifiable information (PII). 
        No real names, no email addresses, and no tracking coordinates are ever requested or stored on this grid.
    </p>

    <h4>2. Anonymized Telemetry & Profile Statistics</h4>
    <p>
        When you enroll a new entity profile through our recruitment grid, the system only processes custom gameplay metrics 
        to track your progress and generate your station statistics:
    </p>
    <ul>
        <li><strong>Username / Callsign:</strong> A freely chosen, completely anonymous pseudonym used solely to identify your save state and compile public profile statistics.</li>
        <li><strong>Security Token (Password):</strong> Encrypted using secure server-side hashing algorithms and stored within a relational database (MySQL/MariaDB) to secure your anonymous access.</li>
        <li><strong>Gameplay Data:</strong> Internal variables (such as alignment scores, credits, and faction choices) are tracked to dynamically update your dashboard metrics.</li>
        <li><strong>Session Cookies:</strong> Temporary diagnostic tokens stored in your browser's local cache to keep your uplink active while navigating through different station sectors.</li>
    </ul>

    <h4>3. Anti-Bot Defense Measures (Honeypot)</h4>
    <p>
        To protect the communication array from automated spam attacks (Shadow reconnaissance bots), our input grids 
        utilize hidden structural traps ("Honeypots"). These scripts analyze automated input signals. No personal 
        identifiable information is collected or shared during this threat-assessment procedure.
    </p>

    <h4>4. Data Control & Sub-Space Contact</h4>
    <p>
        You possess full control over your anonymous telemetry. By clicking the <strong>LOGOUT</strong> button on the left console, 
        your active local session token is immediately destroyed and purged from the station's server memory. A secure 
        contact vector is established directly through the database, allowing users to interact with the grid administrator 
        without revealing external communication links.
    </p>

    <!-- LEGAL DISCLAIMER & INTELLECTUAL PROPERTY RECONSTRUCTION -->
    <div style="margin-top: 40px; background-color: rgba(255, 153, 0, 0.03); border: 1px solid rgba(255, 153, 0, 0.2); padding: 20px; border-radius: 6px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);">
        <h4 style="color: #ff9900; margin-top: 0; text-shadow: 0 0 4px rgba(255, 153, 0, 0.4); font-family: 'B5Station', Arial, sans-serif;">
            ⚖️ LEGAL DISCLAIMER & INTELLECTUAL PROPERTY NOTICE
        </h4>
        
        <!-- SECTOR A: DANIEL BAEDORF COPYRIGHT -->
        <p style="font-size: 0.95em; line-height: 1.5; color: #ffffff; font-weight: bold; margin-bottom: 12px; text-shadow: 0 0 3px rgba(255, 255, 255, 0.3);">
            🔒 OWNERSHIP OF INTELLECTUAL PROPERTY (DANIEL BAEDORF):
        </p>
        <p style="font-size: 0.9em; line-height: 1.5; color: hsl(0, 9%, 90%); margin-top: 0; margin-bottom: 15px;">
            The entire custom source code (PHP, HTML, CSS architecture), the responsive visual user-interface design, 
            the relational database schema, as well as all original fan-fiction storylines (the sector 2264 blackout crisis) 
            and newly introduced characters created specifically for this project remain the **exclusive intellectual property and copyrighted work of Daniel Baedorf (aka Shinji2501)**. 
            Unauthorized replication, distribution, or commercial use of these custom assets is strictly prohibited under international copyright laws.
        </p>
        
        <div style="border-top: 1px dashed rgba(96, 172, 243, 0.2); margin: 15px 0; padding-top: 15px;"></div>

        <!-- SECTOR B: WARNER BROS. LICENSES -->
        <p style="font-size: 0.95em; line-height: 1.5; color: #60acf3; font-weight: bold; margin-bottom: 12px;">
            🛰️ THIRD-PARTY TRADEMARKS & UNIVERSAL LICENSES:
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
// 3. Store buffer content into variable
$seitenInhalt = ob_get_clean(); 

// 4. Render page template with title and content vectors
renderLayout("B5 Legacy - Data Security & Disclaimer", $seitenInhalt); 
?>
