<?php
// Ganz oben die Session starten, damit das Login-System weiß, wer eingeloggt ist
session_start();

// Diese Funktion wird von den einzelnen Seiten aufgerufen, um das Layout zu rendern
function renderLayout($seitenTitel, $inhaltHtml) {
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

        <!-- 🛰️ DYNAMISCHE SCHRIFTEN-INJEKTION (Direkt im Head verankert!) -->
        <!-- 🛰️ SYSTEM-WIDE FONTS & LOGO-SYMBOL INJECTION -->
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

        /* NAVIGATION: Schaltet das linke Menü in JEDEM Zustand auf die breite B5Station um */
        mainNav ul li a, mainNav a, .main-nav a, mainNav ul li a span {
            font-family: 'B5Station', Arial, sans-serif !important;
        }

        /* SYMBOL-BRÜCKE: Zwingt deine Logo-Klassen auf die B5Station, 
           damit sich das '©' und '%' in die echten Sci-Fi-Symbole verwandeln! */
        .Logo5, .LogoB, h1 .Logo5, h1 .LogoB, h2 span.Logo5, h2 span.LogoB {
            font-family: 'B5Station', Arial, sans-serif !important;
            font-weight: bold !important;
            display: inline-block !important;
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

            footer {
                grid-column: span 2 !important;      
            }
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
        <!-- ==========================================================================
             DYNAMISCHES TERMINAL-FLASH-MESSAGE-SYSTEM (INTEGRIERT)
             ========================================================================== -->
        
        <!-- 1. PRÜFUNG AUF ROTEN ALARM (Fehlerfall via isset) -->
        <?php if (isset($_SESSION['flash_message_error'])): ?>
            <div style="background-color: rgba(255, 51, 51, 0.2) !important; border: 1px solid #ff3333 !important; color: #ff3333 !important; padding: 12px !important; margin-bottom: 20px !important; border-radius: 6px !important; text-align: center !important; font-family: 'B5Station', Arial, sans-serif !important; font-weight: bold !important; text-shadow: 0 0 5px #ff3333 !important; letter-spacing: 0.5px !important;">
                <?php echo $_SESSION['flash_message_error']; ?>
            </div>
            <?php 
            // Automatische Vernichtung: Setzt die Variable für die Zukunft auf null
            unset($_SESSION['flash_message_error']); 
            ?>
        <?php endif; ?>

        <!-- 2. BESTÄTIGUNG: Login war erfolgreich (KORREKTUR: Jetzt in lesbarem B5-Gold!) -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div style="background-color: rgba(0, 200, 80, 0.15) !important; border: 1px solid #00c850 !important; color: #ff9900 !important; padding: 12px !important; margin-bottom: 20px !important; border-radius: 6px !important; text-align: center !important; font-family: 'B5Station', Arial, sans-serif !important; font-weight: bold !important; text-shadow: 0 0 6px rgba(255, 153, 0, 0.6) !important; letter-spacing: 0.5px !important;">
                <?php echo $_SESSION['flash_message']; ?>
            </div>
            <?php 
            // Einmal zeigen, danach sofort aus dem Speicher löschen
            unset($_SESSION['flash_message']); 
            ?>
        <?php endif; ?>


        <!-- Hier injiziert PHP den exklusiven Inhalt der jeweiligen Seite hinein -->
        <?php echo $inhaltHtml; ?>
    </main>

    <!-- Der bündige Footer in deiner Includes/layout.php -->
    <footer>
        <bottomNav>
            <ul>
                <!-- KORREKTUR: Zeigt jetzt exakt auf deine neue Impressum.php -->
                <li><a href="Impressum.php">Impressum</a></li>
                <li><a href="Contact.php">Contact</a></li>
                <li><a href="Datasecurity.php">Datasecurity</a></li>
            </ul>
        </bottomNav>
        <p>&copy; 2026 ShinjIkari2501. All rights reserved. Babylon 5 and all related indicia are trademarks of Warner Bros. Entertainment Inc.</p>
    </footer>

</body>
</html>
<?php
} // <-- Diese wichtige Klammer beendet die PHP-Funktion sauber!
?>
