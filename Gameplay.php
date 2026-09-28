<?php 
// 1. Die Layout-Zentrale laden (Startet auch die Session)
require_once 'Includes/layout.php'; 

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start(); 

// LOGIN-PRÜFUNG: Sicher deklariert
$isLoggedIn = 0;
if (isset($_SESSION['eingeloggt']) && $_SESSION['eingeloggt'] === true) {
    $isLoggedIn = 1;
}

// ==========================================================================
// UNZERSTÖRBARE PHP STORY ENGINE (Deine originalen Kapitel 1:1 übersetzt)
// ==========================================================================
if ($isLoggedIn === 1) {
    // Initialisiert den Spiel-Status (Akt) bei Neustart oder Erstaufruf
    if (!isset($_SESSION['b5_akt']) || (isset($_POST['game_input']) && trim(strtolower($_POST['game_input'])) === 'restart')) {
        $_SESSION['b5_akt'] = "charakter_erstellung";
        $_SESSION['b5_origin'] = "";
        $_SESSION['b5_log'] = "=== SIMULATION BOOT SEQUENCE COMPLETE ===\nUplink aktiv. Willkommen im System, Commander.\n\n[CHARAKTER-AUSWAHL: DIE RECHENSCHAFT DER VERGANGENHEIT]\nBevor du in die Schächte eintauchst, wähle deine Herkunft:\n1 = GEHEIMDIENST-VETERAN (Hoher Analyse-Fokus, kennt militärische Protokolle)\n2 = UNTERWELT-SCHMUGGLER (Kennt illegale Schleusen und unregistrierte Routen)";
    }

    // Wenn der Spieler etwas eingibt und Enter drückt
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['game_input'])) {
        $befehl = trim(strtolower($_POST['game_input']));
        
        if ($befehl !== "" && $befehl !== "restart") {
            // Fügt den eingegebenen Befehl zum sichtbaren Terminal-Verlauf hinzu
            $_SESSION['b5_log'] .= "\n\ncmd_vector> " . $_POST['game_input'];

            // --- WEICHE 1: CHARAKTER-ERSTELLUNG & PROLOG ---
            if ($_SESSION['b5_akt'] === "charakter_erstellung") {
                if ($befehl === "1" || $befehl === "2") {
                    if ($befehl === "1") {
                        $_SESSION['b5_origin'] = "Geheimdienst";
                        $_SESSION['b5_log'] .= "\n\n-> Du bist ein Phantom des ehemaligen Earthforce-Geheimdienstes.";
                    } else {
                        $_SESSION['b5_origin'] = "Unterwelt";
                        $_SESSION['b5_log'] .= "\n\n-> Du bist ein Geist des Braunen Sektors, ein Meister unregistrierter Fracht.";
                    }
                    
                    // AKT I Text aus deinem originalen Skript anhängen
                    $_SESSION['b5_log'] .= "\n\n======================================================\n=== AKT I: DER FUNKE IM DRECK ========================\n======================================================\nDie Luft im Braunen Sektor von Babylon 5 schmeckt nach recyceltem Sauerstoff,\nbilligem synthetischem Kaffee und dem Dunst unzähliger Frachterkühler.\nHier, in den USA, bewegst du dich im Graubereich.\nJede schattige Ecke hast du in ein logisches Raster eingeordnet.\nEs ist die einzige Art, wie du nach dem tragischen Verlust deines Partners Marcus Cole überleben konntest.\n\nPlötzlich stolpert eine Gestalt aus einer Wartungsschleuse.\nEin Mann in der zerfetzten Kluft der Rangers bricht direkt vor dir zusammen.\nHinter ihm, am Ende des Tunnels, scannen Psi-Corps-Agenten die Gasse mit Bioscannern.\n\nOhne ein Geräusch zu machen, aktivierst du dein illegales Chamäleon-Netz.\nDas holografische Feld summt minimal auf. Als das Licht der Agenten über dich gleitet,\nsehen sie nur eine leere Wand und gehen irritiert weiter.\n\nDer Ranger keucht, Blut tritt auf seine Lippen. Er blickt dir direkt in die Augen.\nIn seinen sterbenden Augen liegt stummes Erkennen. Er presst dir einen Kristall in die Hand.\n\nDer sterbende Ranger flüstert mit rauer, abgehackter Stimme:\n 'Nimm ihn... Bring ihn... persönlich zum Kommandostab... Vertrau niemandem...'\n 'Die Schläfer erwachen... Wir sterben... für den Einen...'\n\nEin letzten Rasseln, dann erschlafft sein Körper. Seine Finger lösen sich.\nDas Anla'shok-Medaillon gleitet in deine Faust. Du stehst allein im Korridor.\nDu begreifst stumm und schmerzhaft, was für ein unerbittliches Leben Marcus damals gewählt hatte.\n\nTippe 'weiter' um die Stationsleitung aufzusuchen...";
                    $_SESSION['b5_akt'] = "akt_1_gelesen";
                } else {
                    $_SESSION['b5_log'] .= "\n⚠ ERROR: Ungültige Herkunft. Wähle 1 oder 2.";
                }
            }
            
            // --- WEICHE 2: ZACK ALLANS BÜRO ---
            elseif ($_SESSION['b5_akt'] === "akt_1_gelesen") {
                if ($befehl === "weiter") {
                    $_SESSION['b5_log'] .= "\n\n======================================================\n=== AKT II: DIE ÜBERGABE IN DER SICHERHEITSZENTRALE ==\n======================================================\nDu nutzt unregistrierte Schmuggelwege und Servicekorridore des Braunen Sektors.\nErst direkt vor der Luftschleuse trittst du mit eisiger Dringlichkeit hervor.\nDie Officers lassen dich irritiert in das private Büro von Zack Allan.\n\nOhne ein Wort der Erklärung legst du den Kristall und das Medaillon auf die Konsole.\nDu: 'Mr. Allan. Ein Ranger ist gerade im Braunen Sektor diesseits gestorben. Das Corps jagt\n     diese Daten. Es war absolut lebenswichtig für ihn, dass dieser Kristall nur\n     in die Hände der Stationsleitung gelangt. Sorgen Sie persönlich dafür.'\n\nZack Allan blickt auf das Abzeichen, nickt grimmig und greift nach den Gegenständen.\nEr packt den Kristall in seine Manteltasche und greift nach seinem Datenpad.\nZack Allan dreht sich um: 'Verdammt... Wo liegt die Leiche? Sagen Sie mir, wo er--'\n\nDoch er spricht gegen die nackte Wand. In den zwei Sekunden seiner Ablenkung hast du\nden perfekten Moment abgepasst und bist lautlos im unruhigen Strom untergetaucht.\n\nTippe 'weiter' um G'Kars verschlüsselte Botschaft abzurufen...";
                    $_SESSION['b5_akt'] = "sicherheitszentrale_gelesen";
                }
            }
            
            // --- WEICHE 3: G'KARS BOTSCHAFT & DER PUTSCH ---
            elseif ($_SESSION['b5_akt'] === "sicherheitszentrale_gelesen") {
                if ($befehl === "weiter") {
                    $_SESSION['b5_log'] .= "\n\nWenig später schließt Zack Allan die schwere Panzertür des Ratsbüros.\nCaptain Elizabeth Lochley schiebt den Kristall in das gesicherte Allianz-Terminal.\nDie Konsole summt auf. Ein lebensgroßes Holo-Bild flackert im Raum auf: G’KAR.\nSeine aufgezeichnete, weise hallende Stimme erfüllt ehrfürchtig den Raum:\n\nG’Kar (Holo): 'Liebe Freunde, wenn euch diese Nachricht erreicht, ist es hoffentlich\n              noch nicht zu spät. In diesem Datenkristall findet ihr verschlüsselte\n              Koordinaten. Dort findet ihr die Welt einer Zivilisation, die einst genau\n              dasselbe finstere Schicksal erlitt wie euer Volk. Aber seid gewarnt...'\n\nDas Bild erlischt. Die Nachricht vom stummen Putsch hat die Station wie eine Schockwelle getroffen.\nAlfred Bester verkündet live aus Genf inmitten der Black Omega Garde triumphierend,\ndass die Erde ab dem heutigen Tage unter der unumkehrbaren Verwaltung des Corps steht.\n\nPlötzlich blockieren mehrere Sicherheitswachen den Korridor. Zack Allan tritt hervor.\nZack Allan: 'Keine Bewegung. Ich verhafte dich nicht. Aber die Hölle ist opengebrochen.'\nEr schiebt dich direkt hoch ins Ratsbüro der Kommandozentrale.\n\nTippe 'weiter' um dich dem Verhör im Ratsbüro zu stellen...";
                    $_SESSION['b5_akt'] = "ratsbuero_bereit";
                }
            }

            // --- WEICHE 4: RATSBÜRO-VERHÖR ---
            elseif ($_SESSION['b5_akt'] === "ratsbuero_bereit") {
                if ($befehl === "weiter") {
                    $_SESSION['b5_log'] .= "\n\n[BABYLON 5 - RATSBÜRO DER KOMMANDOZENTRALE]\nCaptain Lochley fixiert die blinkenden Fehlerprotokolle der toten Relais.\nZack Allan schiebt dich in den Raum und schließt die schwere Panzertür.\n\nLochley blickt dich ernst an: 'Hat der Ranger im Sterben gar nichts gesagt? Kein einziges Wort?'\nDu: 'Er keuchte nur: \"Nimm ihn... Bring ihn persönlich zum Kommandostab...\"\n     Und seine letzten Worte waren: \"Die Schläfer erwachen... Wir sterben... für den Einen...\"'\n\nLochley: 'Wenn Sie Marcus Coles Partner beim Geheimdienst waren, verstehen Sie die Dunkelheit.'\nZack Allan atmet tief durch: 'Marcus... Valen sei Dank. Er wusste, wem er vertraut.'\n\n=== ENDE DES AKTUELLEN BETA-FEEDS ===\nDie Triebwerke deines Skripts laufen absolut stabil!";
                    $_SESSION['b5_akt'] = "spiel_ende";
                }
            }
            
            // Fallback für sonstige Befehle
            else {
                if ($befehl === "look" || $befehl === "scan") {
                    $_SESSION['b5_log'] .= "\nSensoren scannen die Umgebung. Keine neuen Signaturen.";
                } else {
                    $_SESSION['b5_log'] .= "\n⚠ Unbekannter Befehlsvektor.";
                }
            }
        }
    }
}
?>

<h2>Tactical Simulation Deck</h2>
<h3>Early-Concept-Alpha / Operation: Test</h3>

<?php if ($isLoggedIn === 1): ?>

    <p style="color: #00c850; text-shadow: 0 0 4px rgba(0, 200, 80, 0.4); margin-bottom: 20px;">
        ✔ Credentials verified. Access granted to Sub-Space Alpha Core.
    </p>

    <div style="display: flex !important; flex-direction: row !important; flex-wrap: wrap !important; gap: 20px !important; max-width: 950px !important; margin: 0 auto !important; justify-content: center !important; box-sizing: border-box !important;">
        
        <!-- 1. DAS TERMINAL-GEHÄUSE -->
        <div style="flex: 2 !important; min-width: 450px !important; background-color: #050714 !important; border: 2px solid #ff9900 !important; border-radius: 6px !important; box-shadow: 0 0 15px rgba(255, 153, 0, 0.3) !important; padding: 15px !important; font-family: monospace !important; box-sizing: border-box !important;">

            <!-- 2. DIE TAKTISCHE BEFEHLS-LEGENDE -->
        <div style="flex: 1 !important; min-width: 240px !important; max-width: 300px !important; background-color: rgba(13, 20, 59, 0.5) !important; border: 1px solid rgba(96, 172, 243, 0.3) !important; backdrop-filter: blur(5px) !important; -webkit-backdrop-filter: blur(5px) !important; border-radius: 6px !important; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5) !important; padding: 15px !important; font-family: Arial, sans-serif !important; box-sizing: border-box !important; text-align: left !important; height: fit-content !important; align-self: flex-start !important;">
            <h4 style="color: #ff9900 !important; text-shadow: 0 0 5px rgba(255, 153, 0, 0.5) !important; margin-top: 0 !important; margin-bottom: 12px !important; font-family: 'B5Station', Arial, sans-serif !important; letter-spacing: 0.5px !important; border-bottom: 1px solid rgba(96, 172, 243, 0.2) !important; padding-bottom: 5px !important;">
                🛰️ COMMAND MATRIX
            </h4>
            <p style="font-size: 0.85em !important; color: hsl(0, 9%, 85%) !important; margin-bottom: 15px !important; line-height: 1.4 !important;">
                Nutze die Eingaben deines Adventure-Vektors, um die Story-Simulation zu steuern:
            </p>
            <ul style="list-style-type: none !important; padding: 0 !important; margin: 0 !important; font-size: 0.9em !important; line-height: 1.7 !important;">
                <li style="margin-bottom: 8px !important;"><strong style="color: #60acf3 !important; font-family: monospace !important;">1 / 2</strong><br><span style="color: #aaa !important; font-size: 0.85em !important;">➔ Pfade & Herkunft wählen</span></li>
                <li style="margin-bottom: 8px !important;"><strong style="color: #60acf3 !important; font-family: monospace !important;">weiter</strong><br><span style="color: #aaa !important; font-size: 0.85em !important;">➔ Zum nächsten Handlungsakt vorrücken</span></li>
                <li style="margin-bottom: 8px !important;"><strong style="color: #60acf3 !important; font-family: monospace !important;">restart</strong><br><span style="color: #aaa !important; font-size: 0.85em !important;">➔ Setzt die Simulation komplett zurück</span></li>
            </ul>
        </div>

    </div>

    <!-- AUTO-SCROLL-RELAIS: Drückt das Textfenster nach dem Laden sofort nach unten -->
    <script type="text/javascript">
        window.onload = function() {
            const outputDiv = document.getElementById("terminal-output");
            const inputField = document.getElementById("terminal-input");
            
            if (outputDiv) {
                outputDiv.scrollTop = outputDiv.scrollHeight;
            }
            if (inputField) {
                inputField.focus();
            }
        };
    </script>

<?php else: ?>
    <div style="background-color: rgba(255, 51, 51, 0.1) !important; border: 1px solid #ff3333 !important; padding: 20px !important; border-radius: 6px !important; text-align: center !important; margin: 30px auto !important; max-width: 600px !important;">
        <h4 style="color: #ff3333 !important; text-shadow: 0 0 5px #ff3333 !important; margin-bottom: 10px !important; font-family: 'B5Station', Arial, sans-serif !important;">
            🔒 RESTRICTED SECTOR: EARLY-CONCEPT-ALPHA-TEST CLOSED
        </h4>
        <p style="font-size: 0.95em !important;">The test simulation deck is currently running under a private alpha phalanx. Please log in.</p>
    </div>
<?php endif; ?>

<?php 
$seitenInhalt = ob_get_clean(); 
renderLayout("B5 Legacy - Simulation Deck", $seitenInhalt); 
?>
