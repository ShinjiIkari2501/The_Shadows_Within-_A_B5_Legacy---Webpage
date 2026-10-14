<?php
// 1. Die Layout-Zentrale laden (Startet auch die Session)
require_once 'Includes/layout.php';

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start();

// LOGIN-PRÜFUNG: Korrekt deklariert
$isLoggedIn = 0;
if (isset($_SESSION['eingeloggt']) && $_SESSION['eingeloggt'] === true) {
    $isLoggedIn = 1;
}
?>

<h2>Tactical Simulation Deck</h2>
<h3>Early-Concept-Alpha / Operation: Test</h3>

<?php if ($isLoggedIn === 1): ?>

    <!-- Wir laden den offiziellen CheerpJ Java-Web-Emulator direkt via CDN -->
    <script src="https://leaningtech.com"></script>

    <!-- Das native Konsolen-Fenster verschmilzt perfekt mit deinem Sci-Fi-Design -->
    <div style="border: 1px solid rgba(96, 172, 243, 0.3); border-radius: 4px; box-shadow: 0 0 20px rgba(0,0,0,0.7); overflow: hidden; background: #000; max-width: 850px; margin: 15px auto; padding: 15px;">
        <div id="cheerpj-container" style="width: 100%; height: 500px; color: #00ffcc; font-family: 'Courier New', monospace;">
            [SYSTEM]: Calibrating localized hyperdrive matrix... Loading tactical modules...
        </div>
    </div>

    <!-- Das Steuerungs-Skript zündet deine JAR direkt im Browser-Speicher -->
    <script>
        window.addEventListener('DOMContentLoaded', async () => {
            // Initialisiert die Java-Laufzeitumgebung im Browser
            await cheerpjInit();
            
            // Verbindet die Java-Standardkonsole mit unserem HTML-Container
            const container = document.getElementById('cheerpj-container');
            container.innerHTML = ""; // Löscht den Ladetext
            
            // Startet deine originale JAR-Datei direkt lokal auf der Webseite!
            // KORREKTUR: Relativer Pfad ohne Slash, damit der Server die Datei direkt findet.
            cheerpjCreateDisplay(800, 500, container);
            await cheerpjRunJar("B5_Project.jar");
        });
    </script>

<?php
endif; // Schließt die Login-Prüfung

// 3. Holt den angesammelten HTML-Code aus dem Zwischenspeicher
$content = ob_get_clean();

// 4. Ruft die Funktion mit Titel und Inhalt passend zu deiner Layout.php auf
renderLayout("Tactical Simulation Deck", $content);
?>
