<?php
/**
 * make_admin.php - Einmaliges Freischaltskript
 */
ini_set('display_errors', '1');
error_reporting(E_ALL);
header('Content-Type: text/plain; charset=UTF-8');

if (!file_exists('Includes/db_connect.php')) {
    die("🚨 Fehler: 'Includes/db_connect.php' nicht gefunden.");
}
require_once 'Includes/db_connect.php';

try {
    // Setzt das is_admin-Flag für deinen Account auf 1
    $stmt = $pdo->prepare("UPDATE users SET is_admin = 1 WHERE username = 'Shinji2501'");
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "🚀 UPLINK SUCCESSFUL: 'Shinji2501' besitzt jetzt administrative Rechte!\n";
        echo "Logge dich jetzt einmal aus und wieder ein, damit die Rechte aktiv werden.";
    } else {
        echo "ℹ️ Hinweis: Der Benutzer 'Shinji2501' wurde in der Datenbank nicht gefunden oder war bereits Admin.";
    }
} catch (Exception $e) {
    echo "🚨 Datenbankfehler: " . $e->getMessage();
}
?>
