<?php
// Session starten, um Zugriff darauf zu haben
session_start();

// Alle Session-Variablen löschen
$_SESSION = array();

// Die Session auf dem Server komplett zerstören
session_destroy();

// Den User zurück zur Startseite schicken
header("Location: Index.php");
exit();
?>
