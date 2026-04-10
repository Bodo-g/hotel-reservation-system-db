<?php
$host = 'localhost';
$db = 'hotel_booking_system';
$user = 'root';
$pass = 'boda'; // حسب ما قلت قبل كده
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>
