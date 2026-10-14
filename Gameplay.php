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

// =========================================================================
// PROXY TUNNEL SETUP: Spiegelt den Java-Server direkt auf deine Domain!
// =========================================================================
if ($isLoggedIn === 1 && isset($_GET['proxy_stream'])) {
    $targetUrl = "https://onrender.com";
    // Holt die Terminal-Daten im Hintergrund vom Java-Server ab
    $context = stream_context_create(['http' => ['header' => "User-Agent: PHP-Proxy\r\n"]]);
    echo file_get_contents($targetUrl, false, $context);
    exit; // Beendet das Skript, um nur den reinen Terminal-Stream auszugeben
}
// =========================================================================
?>

<h2>Tactical Simulation Deck</h2>
<h3>Early-Concept-Alpha / Operation: Test</h3>

<?php if ($isLoggedIn === 1): ?>

    <!-- Das iFrame lädt das Terminal jetzt direkt von deiner EIGENEN Domain! -->
    <!-- Dadurch fällt die Cross-Origin-Sperre des Browsers sofort in sich zusammen -->
    <div style="border: 1px solid rgba(96, 172, 243, 0.3); border-radius: 4px; box-shadow: 0 0 20px rgba(0,0,0,0.7); overflow: hidden; background: #050505; max-width: 850px; margin: 15px auto;">

        <!-- WICHTIG: Die src zeigt auf deine eigene Datei mit dem Proxy-Parameter! -->
        <iframe id="gameTerminal" 
                src="?proxy_stream=1"
                style="width: 100%; height: 500px; border: none; background: #010203;"
                scrolling="yes"
                loading="lazy"
                allow="fullscreen"
                title="Sublink Core Terminal">
        </iframe>

    </div>

    <!-- JAVASCRIPT: Wirft deinen Cursor bei jedem Klick direkt in das iFrame-Terminal -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const iframe = document.getElementById('gameTerminal');
            
            iframe.addEventListener('load', () => {
                setTimeout(() => {
                    iframe.contentWindow.focus();
                }, 200);
            });
            
            window.addEventListener('click', () => {
                iframe.contentWindow.focus();
            });
        });
    </script>

<?php
endif; // Schließt die Login-Prüfung

// 3. Holt den angesammelten HTML-Code aus dem Zwischenspeicher
$content = ob_get_clean();

// 4. Ruft die Funktion mit Titel und Inhalt passend zu deiner Layout.php auf
renderLayout("Tactical Simulation Deck", $content);
?>
