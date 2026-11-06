<?php
/**
 * db_init.php
 * Emergency Database Purge & Admin Account Provisioning Core
 */

ini_set('display_errors', '1');
error_reporting(E_ALL);

header('Content-Type: text/plain; charset=UTF-8');

// Nutzt deine bestehende Verbindungsdatei, die bereits auf den Database-Ordner zeigt
if (!file_exists('Includes/db_connect.php')) {
    die("🚨 INITIALIZATION CRITICAL: 'Includes/db_connect.php' wurde nicht gefunden.");
}
require_once 'Includes/db_connect.php';

try {
    echo "=== BABCOM DATABASE PURGE SEQUENCE INITIALIZED ===\n\n";

    // Fremdschlüssel-Sicherheit temporär ausschalten
    $pdo->exec("PRAGMA foreign_keys = OFF;");

    echo "⏳ Lösche alte Spielstände und Benutzer...\n";
    
    // Die Kern-Tabellen deiner B5-Datenbank nacheinander leeren
    $tables = ['player_history', 'player_visited_locations', 'player_crew', 'player_slots', 'users'];
    foreach ($tables as $table) {
        try {
            $pdo->exec("DELETE FROM $table;");
            echo " -> Tabelle '$table' erfolgreich geleert.\n";
        } catch (Exception $e) {
            echo " -> Fehler bei Tabelle '$table': " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n⏳ Generiere verschlüsselten Zugangsschlüssel...\n";
    $plainPassword = "Commander@2026";
    $secureHash = password_hash($plainPassword, PASSWORD_DEFAULT);

    echo "⏳ Injiziere Hauptbenutzer 'Shinji2501' mit ID 1...\n";
    $insertUser = $pdo->prepare("INSERT INTO users (user_id, username, password_hash) VALUES (1, :username, :hash)");
    $insertUser->execute([
        ':username' => 'Shinji2501',
        ':hash'     => $secureHash
    ]);

    echo "⏳ Initialisiere 3 frische taktische Save-Slots (Start: Prolog Node 100)...\n";
    $insertSlots = $pdo->prepare("INSERT INTO player_slots (user_id, slot_number, current_node_id) VALUES (1, :slot, 100)");
    for ($slotNum = 1; $slotNum <= 3; $slotNum++) {
        $insertSlots->execute([':slot' => $slotNum]);
    }

    // Sicherheit wieder aktivieren
    $pdo->exec("PRAGMA foreign_keys = ON;");

    echo "\n🚀 UPLINK SUCCESSFUL: Die Datenbank im Database-Ordner wurde erfolgreich neu aufgesetzt!\n";
    echo "-> Benutzer: Shinji2501\n";
    echo "-> Passwort: " . $plainPassword . "\n";
    echo "-> zugewiesene ID: 1\n\n";
    echo "⚠️ WICHTIG: Entferne diese 'db_init.php'-Datei jetzt sofort von deinem Webserver!";

} catch (Exception $e) {
    echo "\n🚨 CRITICAL ENGINE RECOIL - Fehler beim Datenbank-Setup: " . $e->getMessage();
}
?>
