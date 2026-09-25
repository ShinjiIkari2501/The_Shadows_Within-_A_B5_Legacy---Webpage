<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="description" content="The Shadows within: A B5 Legacy">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Daniel Baedorf aka Shinji2501">
    
    <title>B5 Legacy - Main Terminal</title>
    
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
                height: 100vh !important;      
                max-height: 100vh !important;  
                overflow: hidden !important; /* Blockiert das Scrollen des Gesamtframerate-Fensters */
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
