<?php
// Includes/db.php
$host = 'localhost';
$db   = 'b5_station';
$user = 'root'; // Standard bei XAMPP
$pass = '';     // Standard bei XAMPP leer
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("🔒 SECURITY PHALANX ERROR: Database uplink failed.");
}
?>
