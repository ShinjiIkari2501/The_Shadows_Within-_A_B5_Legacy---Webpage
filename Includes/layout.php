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
                overflow: hidden !important; /* Blockiert das Scrollen des Gesamtframerate-Fensters */
            }

            header {
                grid-column: span 2 !important;
                margin-bottom: 25px !important; 
            }

            /* DIE NAVIGATION BLEIBT FELSENFEST EINGEFROREN */
            /* DIE ERBEUTETE LINKSKONSOLE (Vollständiger Glas-Look reaktiviert!) */
            mainNav {
                grid-column: 1 !important;           
                grid-row: 2 !important;              
                width: 210px !important;               
                height: 520px !important;   /* Feste, eingefrorene Wunschgröße */
                box-sizing: border-box !important;
                
                /* DIE WAFFE FÜR PERFEKTE INNEN-SYMMETRIE: */
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;         /* Zentriert alles absolut exakt von links nach rechts */
                justify-content: flex-start !important; /* Startet sauber oben und verteilt nach unten */
                
                /* EXAKTE INNENABSTÄNDE (Oben und unten perfekt ausbalanciert) */
                padding-top: 25px !important; 
                padding-bottom: 25px !important;
                padding-left: 12px !important;
                padding-right: 12px !important;
                
                /* Horizontale und vertikale Zentrierung im globalen Grid */
                justify-self: center !important;
                align-self: center !important; 
                position: relative !important;
                
                /* Der optische Höhenschubs für die perfekte Achse */
                margin: -55px auto 25px auto !important; 

                /* Der originale Babylon-5 Glasrahmen & Lichtkante */
                background-color: rgba(13, 20, 59, 0.75) !important; 
                backdrop-filter: blur(8px) !important;
                -webkit-backdrop-filter: blur(8px) !important;
                border: 1px solid rgba(96, 172, 243, 0.2) !important;
                border-radius: 8px !important;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5), 0 0 10px rgba(26, 47, 122, 0.2) !important;
            }

            /* ERZWUNGENES SCHRIFTEN-UPGRADE INNEN-NAV */
            mainNav, mainNav ul, mainNav ul li, mainNav ul li a {
                font-family: 'B5Station', Arial, sans-serif !important;
            }

            /* Sorgt dafür, dass das Login-Inlay die Breite perfekt ausnutzt und zentriert bleibt */
            mainNav fieldset {
                width: 100% !important;
                box-sizing: border-box !important;
                margin-top: 0px !important;
                margin-bottom: 20px !important;
            }

            /* Zentriert die Liste und die Links haargenau von links nach rechts */
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
                
                /* DIE RETTUNG: Das Fenster füllt die Reihe und kriegt seine eigene Scrollbar */
                height: 100% !important;
                overflow-y: auto !important; /* Aktiviert die autonome Scrollbar NUR rechts */
                scroll-behavior: smooth !important;  
                
                padding-left: 10px !important;
                padding-right: 25px !important;
                margin-top: 0px !important;
                margin-bottom: 25px !important; 
                padding-bottom: 60px !important; /* Platzhalter, damit der fixierte Footer nix verdeckt */
                box-sizing: border-box !important;
            }

            /* ERZWUNGENES SCHRIFTEN-UPGRADE FOOTER */
            footer, footer p, footer span, footer ul li a, body footer {
                font-family: 'B5Station', Arial, sans-serif !important;
            }

            footer {
                grid-column: span 2 !important;      
            }
        }

        /* GLOBALE WEICHE FÜR DIE SYMBOL-UMWANDLUNG (Logo5 & LogoB) */
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
        <?php 
        // Injiziert den dynamischen Inhalt der jeweiligen Unterseite
        echo $seitenInhalt; 
        ?>
    </main>

    <!-- Der unzerstörbare Konsolen-Footer -->
    <footer>
        <ul>
            <li><a href="Contact.php">📡 Com-Array</a></li>
            <li><a href="Impressum.php">Impressum</a></li>
            <li><a href="Datasecurity.php">Data Security</a></li>
        </ul>
        <p>&copy; 2026 Shinji2501. All rights reserved. Babylon 5 and all related indicia are trademarks of Warner Bros. Entertainment Inc.</p>
    </footer>
</body>
</html>
<?php
}
?>
