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

    <!-- Das iFrame bettet dein echtes Python-Textadventure nahtlos in deine Seite ein -->
    <div style="border: 1px solid rgba(96, 172, 243, 0.3); border-radius: 4px; box-shadow: 0 0 20px rgba(0,0,0,0.7); overflow: hidden; background: #050505; max-width: 850px; margin: 15px auto;">

        <!-- Ersetze die URL mit der echten Web-URL deines Python-Dienstes auf Render -->
        <iframe src="https://the-shadows-within-a-b5-legacy-3s55.onrender.com"
                style="width: 100%; height: 500px; border: none; background: #010203;"
                scrolling="yes"
                loading="lazy"
                title="Sublink Core Terminal">
        </iframe>

    </div>

<?php
endif; // Schließt die Login-Prüfung aus Zeile 16

// 3. Holt den angesammelten HTML-Code aus dem Zwischenspeicher
$content = ob_get_clean();

// 4. Ruft die Funktion mit Titel und Inhalt passend zu deiner Layout.php auf
renderLayout("Tactical Simulation Deck", $content);
?>
