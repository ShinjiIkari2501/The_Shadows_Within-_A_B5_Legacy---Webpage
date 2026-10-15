<?php 
// 1. Die Layout-Zentrale laden (Sie startet auch automatisch die Session)
require_once 'Includes/layout.php'; 

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start(); 

// LOGIN-PRÜFUNG UND VARIABLEN-SETUP
$isLoggedIn = 0;
$userId = 0;
$currentSceneInfo = "[Kein aktiver Spielstand]";

if (isset($_SESSION['eingeloggt']) && $_SESSION['eingeloggt'] === true) {
    $isLoggedIn = 1;
    // Fallback auf Standard-ID 1, falls dein Login-System noch keine IDs verteilt
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1; 

    // =========================================================================
    // AUTOMATISCHER SPEICHERSTANDS-DETEKTOR FÜR DEN INDEX
    // =========================================================================
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=deine_datenbank;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Holt den neuesten Speicherstand des angemeldeten Benutzers aus der SQL-Datenbank
        $stmt = $pdo->prepare("SELECT slot_number, updated_at, save_data FROM game_saves WHERE user_id = ? ORDER BY updated_at DESC LIMIT 1");
        $stmt->execute([$userId]);
        $latestSave = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($latestSave) {
            $saveData = json_decode($latestSave['save_data'], true);
            // Zählt die Einträge im Array, um zu wissen, bei welchem Schritt der Spieler ist
            $steps = is_array($saveData) ? count($saveData) : 0;
            $currentSceneInfo = "Slot 0" . $latestSave['slot_number'] . " (Schritt: " . $steps . " | Letztes Update: " . date('d.m.Y H:i', strtotime($latestSave['updated_at'])) . ")";
        }
    } catch (PDOException $e) {
        // FALLBACK: Falls wir auf Render.com ohne SQL sind, scannen wir den Session-Zwischenspeicher aus
        if (isset($_SESSION['fallback_slots'][$userId])) {
            $slots = $_SESSION['fallback_slots'][$userId];
            // Sortiert nach dem neuesten Eintrag im Session-Array
            krsort($slots);
            foreach ($slots as $slotNum => $slotContent) {
                $steps = is_array($slotContent['data']) ? count($slotContent['data']) : 0;
                $currentSceneInfo = "Slot 0" . $slotNum . " (Schritt: " . $steps . " | Stand: " . $slotContent['date'] . " - Session-Mode)";
                break; 
            }
        }
    }
}
?>
