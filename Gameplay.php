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
// ASYNCHRONER LIVE-KERNEL: Hält die Java-JAR dauerhaft im RAM geöffnet!
// =========================================================================
if ($isLoggedIn === 1 && isset($_GET['ajax_action'])) {
    $jarPath = __DIR__ . '/B5_Project.jar';

    // Simulation starten oder zurücksetzen
    if ($_GET['ajax_action'] === 'init') {
        // Schließe alte ungenutzte Ressourcen
        if (isset($_SESSION['java_process']) && is_resource($_SESSION['java_process'])) {
            @proc_close($_SESSION['java_process']);
        }
        unset($_SESSION['input_history']);

        $descriptorspec = [
            0 => ["pipe", "r"], // stdin
            1 => ["pipe", "w"], // stdout
            2 => ["pipe", "w"]  // stderr
        ];

        // Startet das Spiel ALS PERSISTENTEN LANGZEITPROZESS
        $_SESSION['java_process'] = proc_open("java -jar " . escapeshellarg($jarPath), $descriptorspec, $pipes);
        
        if (is_resource($_SESSION['java_process'])) {
            stream_set_blocking($pipes[1], 0); // Non-blocking, damit PHP nicht einfriert
            $_SESSION['java_pipes'] = $pipes;
            
            // Warte kurz auf das Hauptmenü und lies es aus
            usleep(250000);
            $output = stream_get_contents($pipes[1]);
            echo htmlspecialchars($output);
        } else {
            echo "[ERROR]: Local Java engine failure.";
        }
        exit;
    }

    // Wenn der Spieler eine Eingabe tätigt (AJAX Submit)
    if ($_GET['ajax_action'] === 'submit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $rawInput = isset($_POST['player_input']) ? $_POST['player_input'] : '';
        $pipes = $_SESSION['java_pipes'] ?? null;

        if ($pipes && is_resource($_SESSION['java_process'])) {
            // Wir senden die Eingabe DIREKT in den laufenden Prozess (Kein Neustart von vorne!)
            fwrite($pipes[0], $rawInput . "\n");
            fflush($pipes[0]);

            // Warte auf die Verarbeitung durch dein IntelliJ-Skript
            usleep(200000);
            $output = stream_get_contents($pipes[1]);
            
            // Falls das Spiel vorbei ist, Prozess sauber schließen
            $status = proc_get_status($_SESSION['java_process']);
            if (!$status['running']) {
                @proc_close($_SESSION['java_process']);
                unset($_SESSION['java_process']);
            }

            echo htmlspecialchars($output);
        } else {
            echo "[SYSTEM ERROR]: Tactical link lost. Please reset core matrix.";
        }
        exit;
    }
}

// Logik für den manuellen Reset-Button
if (isset($_GET['reset_game'])) {
    if (isset($_SESSION['java_process']) && is_resource($_SESSION['java_process'])) {
        @proc_close($_SESSION['java_process']);
    }
    unset($_SESSION['java_process']);
    unset($_SESSION['java_pipes']);
    header("Location: gameplay.php");
    exit;
}
?>

<h2>Tactical Simulation Deck</h2>
<h3>Early-Concept-Alpha / Operation: Persistent Paging Link</h3>

<?php if ($isLoggedIn === 1): ?>

    <!-- Das reinrassige Retro-Text-Terminal -->
    <div style="border: 1px solid rgba(96, 172, 243, 0.3); border-radius: 4px; box-shadow: 0 0 20px rgba(0,0,0,0.7); background: #010203; max-width: 850px; margin: 15px auto; padding: 20px;">
        
        <!-- Steuerungs-Hinweise für das Blättern -->
        <div style="color: #60acf3; font-family: 'Courier New', monospace; font-size: 12px; margin-bottom: 10px; text-align: space-between; display: flex; justify-content: space-between;">
            <span>[MODE: PERSISTENT CORE]</span>
            <span>Navigieren: [Bild-Ab / ➔] Nächste Seite | [Bild-Auf / ➔] Vorherige Seite</span>
        </div>

        <!-- Der Terminal-Bildschirm -->
        <pre id="terminal-screen" style="width: 100%; height: 450px; overflow-y: hidden; color: #00ffcc; font-family: 'Courier New', monospace; font-size: 16px; white-space: pre-wrap; margin: 0 0 20px 0; background: #000; padding: 15px; border-radius: 4px; border: 1px solid #111;">[SYSTEM]: Synchronizing sub-space frequencies...</pre>

        <!-- Die interaktive Input-Leiste -->
        <form id="terminal-form" style="display: flex; gap: 10px;">
            <span style="color: #00ffcc; font-family: 'Courier New', monospace; font-size: 18px; line-height: 40px;">&gt;</span>
            <input type="text" 
                   name="player_input" 
                   style="flex-grow: 1; background: #000; border: 1px solid #00ffcc; color: #00ffcc; padding: 10px; font-family: 'Courier New', monospace; font-size: 16px; border-radius: 4px;" 
                   placeholder="Zahl eingeben oder Enter drücken..." 
                   autocomplete="off"
                   id="terminal-input">
            <button type="submit" style="background: transparent; border: 1px solid #00ffcc; color: #00ffcc; padding: 0 20px; font-family: 'Courier New', monospace; font-size: 14px; cursor: pointer; border-radius: 4px;">Senden</button>
        </form>

        <div style="text-align: right; margin-top: 15px;">
            <a href="?reset_game=1" style="color: #ff3366; font-family: 'Courier New', monospace; font-size: 12px; text-decoration: none; border: 1px solid #ff3366; padding: 5px 10px; border-radius: 4px;">⚡ Reset Core Matrix</a>
        </div>
    </div>

    <!-- JAVASCRIPT: Das Herzstück der seitenweisen Text-Paging-Steuerung -->
    <script>
        const form = document.getElementById('terminal-form');
        const input = document.getElementById('terminal-input');
        const screen = document.getElementById('terminal-screen');
        
        let allPages = [];      // Speichert die aufgeteilten Textseiten
        let currentPageIndex = 0; // Aktuelle sichtbare Seite
        let typeInterval = null;

        // Teilt den eintreffenden Java-Text präzise in Seiten auf, die auf das Display passen
        function paginateText(text) {
            const lines = text.split('\n');
            const linesPerPage = 18; // Maximale Zeilenanzahl passend zur Displayhöhe (450px)
            const pages = [];
            
            for (let i = 0; i < lines.length; i += linesPerPage) {
                pages.push(lines.slice(i, i + linesPerPage).join('\n'));
            }
            return pages;
        }

        // Zeichnet die gewählte Seite mit flüssigem Schreibmaschinen-Effekt
        function renderPage(pageIndex) {
            if (pageIndex < 0 || pageIndex >= allPages.length) return;
            currentPageIndex = pageIndex;
            
            if (typeInterval) clearInterval(typeInterval);
            screen.innerHTML = '';
            
            let index = 0;
            const text = allPages[pageIndex];
            const speed = 12; // Angenehmes Lesetempo

            typeInterval = setInterval(() => {
                if (index < text.length) {
                    screen.innerHTML += text.charAt(index);
                    index++;
                } else {
                    clearInterval(typeInterval);
                    // Zeigt am Seitenende an, ob man weiterblättern kann
                    if (currentPageIndex < allPages.length - 1) {
                        screen.innerHTML += "\n\n\n[--- DRÜCKE BILD-AB ODER PFEIL-RECHTS FÜR DIE NÄCHSTE SEITE ---]";
                    }
                }
            }, speed);
        }

        // Holt Daten asynchron via AJAX
        function handleAction(actionUrl, inputValue) {
            const formData = new FormData();
            formData.append('player_input', inputValue);

            fetch(actionUrl, { method: 'POST', body: formData })
            .then(response => response.text())
            .then(data => {
                if (data.trim() !== "") {
                    // KORREKTUR: Wir patchen den Text in Seiten auf!
                    allPages = paginateText(data);
                    renderPage(0); // Starte immer auf Seite 1 des neuen Abschnitts
                }
                input.value = '';
                input.focus();
            });
        }

        // Erstmaliges Zünden beim Laden der Seite
        window.addEventListener('DOMContentLoaded', () => {
            handleAction('?ajax_action=init', '');
        });

        // Formular-Eingabe absenden (Zahlen oder leere Enters für Javas nextLine)
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            handleAction('?ajax_action=submit', input.value);
        });

        // TASTEN-STEUERUNG FÜR DAS PAGING (Bild-auf/Bild-ab & Pfeiltasten)
        window.addEventListener('keydown', (e) => {
            // Nächste Seite: Bild-Runter (PageDown) oder Pfeil-Rechts
            if (e.key === "PageDown" || e.key === "ArrowRight") {
                if (currentPageIndex < allPages.length - 1) {
                    e.preventDefault();
                    renderPage(currentPageIndex + 1);
                }
            }
            // Vorherige Seite: Bild-Hoch (PageUp) oder Pfeil-Links
            if (e.key === "PageUp" || e.key === "ArrowLeft") {
                if (currentPageIndex > 0) {
                    e.preventDefault();
                    renderPage(currentPageIndex - 1);
                }
            }
        });
        
        input.focus();
    </script>

<?php
endif;

// 3. Holt den HTML-Code aus dem Puffer
$content = ob_get_clean();

// 4. Ruft das Layout auf
renderLayout("Tactical Simulation Deck", $content);
?>
