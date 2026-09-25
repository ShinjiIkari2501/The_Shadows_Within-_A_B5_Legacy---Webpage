<?php
// ==========================================================================
// THE SHADOWS WITHIN: A B5 LEGACY - CENTRAL LAYOUT ENGINE
// Verarbeitet die Inhalts-Injektionen und steuert das Haupt-Grid
// ==========================================================================

function renderLayout($seitenTitel, $seitenInhalt) {
?>
<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="description" content="The Shadows within: A B5 Legacy">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Daniel Baedorf aka Shinji2501">
    
    <title><?php echo htmlspecialchars($seitenTitel); ?></title>
    
    <!-- Pfad zur zentralen CSS für Farben und Schriften -->
    <link rel="stylesheet" href="CSS/Babcom_Style.css">

    <!-- 🛰️ SYSTEM-WIDE FONTS DEFINITION -->
    <style>
        @font-face {
            font-family: 'B5Station';
            src: url('Fonts/babylon5_station_bold.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        @font-face {
            font-family: 'SerpentineB5';
            src: url('Fonts/Serpentine_Medium.otf') format('opentype');
            font-weight: normal;
            font-style: normal;
        }
    </style>
    
    <style>
        /* UNZERSTÖRBARES DESKTOP-LAYOUT (SCROLL-ISOLIERT) */
        @media (min-width: 48em) {
            body {
                display: grid !important;
                /* Links 240px für die Nav, rechts der Rest für den Inhalt */
                grid-template-columns: 240px 1fr !important; 
                grid-template-rows: auto 1fr auto !important; 
                column-gap: 20px !important; 
                row-gap: 0px !important;     
                margin: 0 !important;
                padding: 0 !important;
                width: 100vw !important;
                
                /* FIXIERUNG AUF MONITORHÖHE: Verhindert das Mitwandern */
                height: 100vh !important;      
                max-height: 100vh !important;  
                overflow: hidden !important; 
            }

            header {
                grid-column: span 2 !important;
                margin-bottom: 25px !important; 
            }

            /* DIE NAVIGATION BLEIBT FELSENFEST EINGEFROREN */
            mainNav {
                grid-column: 1 !important;           
                grid-row: 2 !important;              
                width: 210px !important;               
                height: 520px !important;   
                box-sizing: border-box !important;
                
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;         
                justify-content: flex-start !important; 
                
                padding-top: 25px !important; 
                padding-bottom: 25px !important;
                padding-left: 12px !important;
                padding-right: 12px !important;
                
                justify-self: center !important;
                align-self: center !important; 
                position: relative !important;
                
                margin: -55px auto 25px auto !important; 

                background-color: rgba(13, 20, 59, 0.75) !important; 
                backdrop-filter: blur(8px) !important;
                -webkit-backdrop-filter: blur(8px) !important;
                border: 1px solid rgba(96, 172, 243, 0.2) !important;
                border-radius: 8px !important;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5), 0 0 10px rgba(26, 47, 122, 0.2) !important;
            }

            /* UNZERSTÖRBARER NAV-SCHRIFTEN-VEKTOR */
            mainNav ul li a, mainNav a, .main-nav a {
                font-family: 'B5Station', Arial, sans-serif !important;
                text-transform: uppercase !important;
                letter-spacing: 0.5px !important;
                color: #ffffff !important; 
                text-decoration: none !important;
                font-weight: bold !important;
                text-shadow: 0 0 6px rgba(96, 172, 243, 0.5) !important;
                transition: all 0.2s ease !important;
            }

            mainNav ul li a:hover {
                color: #ff9900 !important;
                text-shadow: 0 0 8px rgba(255, 153, 0, 0.8) !important;
            }

            mainNav fieldset {
                width: 100% !important;
                box-sizing: border-box !important;
                margin-top: 0px !important;
                margin-bottom: 20px !important;
            }

            mainNav ul {
                width: 100% !important;
                text-align: center !important;
                list-style-type: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            mainNav ul li {
                width: 100% !important;
                text-align: center !important;
                margin-top: 12px !important; 
            }

            mainNav ul li a {
                display: inline-block !important;
                width: 100% !important;
                text-align: center !important;
            }

            /* NUR DAS RECHTE FENSTER DARF SCROLLEN */
            main {
                grid-column: 2 !important;           
                grid-row: 2 !important;              
                width: 100% !important;              
                height: 100% !important;
                overflow-y: auto !important; 
                scroll-behavior: smooth !important;  
                
                padding-left: 10px !important;
                padding-right: 25px !important;
                margin-top: 0px !important;
                margin-bottom: 25px !important; 
                padding-bottom: 60px !important; 
                box-sizing: border-box !important;
            }

            /* ==========================================================================
               UNTERER KONSOLEN-RAHMEN (Footer) - NARRATIVE FLUSS-MATRIX
               ========================================================================== */
            footer {
                grid-column: span 2 !important;      
                display: flex !important;                  
                flex-direction: row !important;
                justify-content: space-between !important; /* Drückt Links nach links, Text nach rechts */
                align-items: center !important;            
                padding: 0 20px !important;
                height: 45px !important; /* Hauchdünne Originalgröße reaktiviert */
                box-sizing: border-box !important;
                background-color: rgba(13, 20, 59, 0.75) !important;
            }

            /* SYSTEMWEITES SCHRIFTEN-UPGRADE FÜR DEN GANZEN FOOTER-BEREICH */
            footer, footer p, footer span, footer a, body footer, footer .footer-links a {
                font-family: 'B5Station', Arial, sans-serif !important;
                text-transform: uppercase !important;
                font-weight: bold !important;
                font-size: 0.95em !important; /* Perfekte, harmonische Lesegröße */
                text-shadow: 0 0 6px rgba(96, 172, 243, 0.5) !important;
            }

            /* DIE LINKS-FLANKE IM FOOTER: Werden felsenfest links nebeneinander aufgereiht */
            footer .footer-links {
                display: flex !important;
                flex-direction: row !important;
                gap: 25px !important; 
                margin: 0 !important;
                padding: 0 !important;
            }

            footer .footer-links a {
                color: #ffffff !important;
                text-decoration: none !important;
                transition: all 0.2s ease !important;
            }

            footer .footer-links a:hover {
                color: #ff9900 !important;
                text-shadow: 0 0 8px rgba(255, 153, 0, 0.8) !important;
            }

            /* DIE RECHTE COPY-FLANKE IM FOOTER: Bleibt starr rechtsbündig */
            footer p {
                margin: 0 !important;
                padding: 0 !important;
                text-align: right !important;
                color: rgba(255, 255, 255, 0.6) !important;
                flex-grow: 0 !important;
            }
            
        }

        /* GLOBALE WEICHE FÜR DIE SYMBOL-UMWANDLUNG */
        .Logo5, .LogoB, h1 .Logo5, h1 .LogoB, h2 span.Logo5, h2 span.LogoB {
            font-family: 'B5Station', Arial, sans-serif !important;
            font-weight: bold !important;
            display: inline-block !important;
        }
    </style>
</head>
<body>
    <header>
        <div>
            <a href="index.php">
                <img src="Images/Test1.jpg" width="1450" height="300" alt="Logo">
            </a>
        </div>
    </header>

    <!-- Lädt die linke Flanke (mainNavigation.php) nach -->
    <?php require_once 'Includes/mainNavigation.php'; ?>

    <!-- Hauptfenster für den Inhalt -->
    <main>
        <?php echo $seitenInhalt; ?>
    </main>

    <!-- Der unzerstörbare Konsolen-Footer (LISTENFREI OPTIMIERT!) -->
    <footer style="display: flex !important; justify-content: space-between !important; align-items: center !important; flex-direction: row !important; height: 45px !important; padding: 0 20px !important; box-sizing: border-box !important;">
        <div class="footer-links" style="display: flex !important; flex-direction: row !important; gap: 25px !important; margin: 0 !important; padding: 0 !important;">
            <a href="Contact.php" style="color: #ffffff !important; text-decoration: none !important;">📡 Com-Array</a>
            <a href="Impressum.php" style="color: #ffffff !important; text-decoration: none !important;">Impressum</a>
            <a href="Datasecurity.php" style="color: #ffffff !important; text-decoration: none !important;">Data Security</a>
        </div>
        <p style="margin: 0 !important; padding: 0 !important; text-align: right !important; color: rgba(255, 255, 255, 0.6) !important; flex-grow: 0 !important;">&copy; 2026 Shinji2501. All rights reserved. Babylon 5 and all related indicia are trademarks of Warner Bros. Entertainment Inc.</p>
    </footer>
</body>
</html>
<?php
}
?>
