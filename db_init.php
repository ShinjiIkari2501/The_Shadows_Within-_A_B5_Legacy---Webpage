<?php
/**
 * db_init.php
 * Emergency Database Purge & Admin Account Provisioning Core
 */

// Fehlerberichterstattung aktivieren, um eventuelle Probleme sofort zu sehen
ini_set('display_errors', '1');
error_reporting(E_ALL);

header('Content-Type: text/plain; charset=UTF-8');

// Verbindung zur bestehenden SQLite-Datenbank laden
if (!file_exists('Includes/db_connect.php')) {
    die("🚨 INITIALIZATION CRITICAL: 'Includes/db_connect.php' wurde nicht gefunden.");
}
require_once 'Includes/db_connect.php';

try {
    echo "=== BABCOM DATABASE PURGE SEQUENCE INITIALIZED ===\n\n";

    // Fremdschlüssel-Prüfung temporär ausschalten, um Tabellen gefahrlos leeren zu können
    $pdo->exec("PRAGMA foreign_keys = OFF;");

    // Alle Benutzerdaten und Spielstände löschen
    echo "⏳ Lösche alte Tabelleninhalte...\n";
    $pdo->exec("DELETE FROM player_history;");
    $pdo->exec("DELETE FROM player_visited_locations;");
    $pdo->exec("DELETE FROM player_crew;");
    $pdo->exec("DELETE FROM player_slots;");
    $pdo->exec("DELETE FROM users;");
    
    // SQLite Auto-Increment Zähler zurücksetzen
    $pdo->exec("DELETE FROM sqlite_sequence WHERE name='users';");
    $pdo->exec("DELETE FROM sqlite_sequence WHERE name='player_slots';");
    echo "✅ Alle alten Benutzer und Spielstände erfolgreich entfernt.\n\n";

    // Sicheren, PHP-kompatiblen Bcrypt-Hash für dein neues Passwort generieren
    $plainPassword = "Commander@2026";
    $secureHash = password_hash($plainPassword, PASSWORD_DEFAULT);

    echo "⏳ Erstelle Hauptbenutzer 'Shinji2501' (ID: 1)...\n";
    // User explizit mit der ID 1 anlegen
    $insertUser = $pdo->prepare("INSERT INTO users (user_id, username, password_hash) VALUES (1, :username, :hash)");
    $insertUser->execute([
        ':username' => 'Shinji2501',
        ':hash'     => $secureHash
    ]);

    echo "⏳ Initialisiere 3 frische taktische Save-Slots für den Commander...\n";
    // Genau 3 Slots erzeugen, die am Prolog (Node 100) starten
    $insertSlots = $pdo->prepare("INSERT INTO player_slots (user_id, slot_number, current_node_id) VALUES (1, :slot, 100)");
    for ($slotNum = 1; $slotNum <= 3; $slotNum++) {
        $insertSlots->execute([':slot' => $slotNum]);
    }

    // Fremdschlüssel-Sicherheit wieder einschalten
    $pdo->exec("PRAGMA foreign_keys = ON;");

    echo "\n🚀 UPLINK SUCCESSFUL: Die Datenbank ist sauber zurückgesetzt!\n";
    echo "-> Benutzer: Shinji2501\n";
    echo "-> Passwort: " . $plainPassword . "\n";
    echo "-> User-ID im System: 1\n\n";
    echo "⚠️ WICHTIG: Lösche diese 'db_init.php'-Datei jetzt sofort aus Sicherheitsgründen!";

} catch (Exception $e) {
    echo "🚨 ENGINE RECOIL - Datenbankfehler: " . $e->getMessage();
}
?>
