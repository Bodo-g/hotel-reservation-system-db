<?php
session_start();
require 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM Customer_Login WHERE Username = ? AND Is_Active = 1");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && hash('sha256', $password) === $user['Password_Hash']) {
    $_SESSION['cust_id'] = $user['Cust_ID'];
    header('Location: customer_dashboard.php');
    exit;
} else {
    echo "Invalid customer login.";
}
?>
