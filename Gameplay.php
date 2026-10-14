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
<h3>Early-Concept-Alpha / Operation: Core Matrix</h3>

<?php if ($isLoggedIn === 1): ?>

    <!-- Wir laden die CheerpJ-Laufzeitumgebung direkt via CDN -->
    <script src="https://leaningtech.com"></script>

    <!-- Der Terminal-Container im typischen Sci-Fi-Look -->
    <div style="border: 1px solid rgba(96, 172, 243, 0.3); border-radius: 4px; box-shadow: 0 0 20px rgba(0,0,0,0.7); overflow: hidden; background: #000; max-width: 850px; margin: 15px auto; padding: 15px;">
        <div id="cheerpj-terminal" style="width: 100%; height: 500px; color: #00ffcc; font-family: 'Courier New', monospace; background-color: #000;">
            [SYSTEM]: Synchronizing sub-space frequencies... Initializing Java 21 Core...
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', async () => {
            // 1. Initialisiert die Java-Engine im Browser
            await cheerpjInit();
            
            const container = document.getElementById('cheerpj-terminal');
            container.innerHTML = ""; // Löscht den Ladetext
            
            // 2. Erzeugt eine native Text-Konsole, die direkt auf Tastendrucke lauscht
            cheerpjCreateConsole(800, 500, container);
            
            // 3. KORREKTUR: CheerpJ benötigt zwingend das virtuelle /app/-Präfix vor der JAR,
            // damit die Runtime die Datei im lokalen Webverzeichnis findet!
            await cheerpjRunJar("/app/B5_Project.jar");
        });
    </script>

<?php
endif; // Schließt die Login-Prüfung

// 3. Holt den angesammelten HTML-Code aus dem Zwischenspeicher
$content = ob_get_clean();

// 4. Ruft die Funktion mit Titel und Inhalt passend zu deiner Layout.php auf
renderLayout("Tactical Simulation Deck", $content);
?>
