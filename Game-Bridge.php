<?php
header('Content-Type: application/json');

$befehl = isset($_POST['befehl']) ? $_POST['befehl'] : "";
$zustand = isset($_POST['zustand']) ? $_POST['zustand'] : "";

$safe_befehl = escapeshellarg($befehl);
$safe_zustand = escapeshellarg($zustand);

// Führt die Datei auf Render aus. Das "2>&1" am Ende zwingt Linux, uns Fehlermeldungen zu senden!
$kommando = "python3 'The Shadows Within - A B5 Legacy.py' $safe_befehl $safe_zustand 2>&1";
$output = shell_exec($kommando);

// PRÜFUNG: Wenn die Ausgabe KEIN gültiges JSON ist (weil Python abstürzt),
// fangen wir die Fehlermeldung ab und zeigen sie im Browser an!
if ($output && strpos($output, '{') === false) {
    echo json_encode([
        "text" => "<span style='color: #ff5555; font-weight: bold;'>[🚨 CRITICAL LINUX PYTHON ERROR]:</span>\n" . htmlspecialchars($output, ENT_QUOTES, 'UTF-8'),
        "zustand" => $zustand
    ]);
    exit;
}

if ($output) {
    echo $output;
} else {
    echo json_encode([
        "text" => "[Systemfehler]: Keine Antwort vom Reaktor-Kern auf Render.com.",
        "zustand" => $zustand
    ]);
}
exit;
