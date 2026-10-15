<?php
// Startet die Session-Zentrale
session_start();

// 1. Alle spielspezifischen Variablen restlos vernichten
unset($_SESSION['input_history']);
unset($_SESSION['active_save_slot']);
unset($_SESSION['fallback_slots']);
unset($_SESSION['b5_current_scene']);
unset($_SESSION['b5_player_origin']);

// 2. Alle Session-Variablen komplett leeren
$_SESSION = array();

// 3. Die Session auf dem Server komplett zerstören
session_destroy();

// 4. Den User zurück zur Startseite schicken (Groß-/Kleinschreibung angeglichen)
header("Location: Index.php");
exit();
?>
