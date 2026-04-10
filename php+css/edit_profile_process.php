<?php
session_start();
require 'db.php';

if (!isset($_SESSION['cust_id'])) {
    header("Location: customer_login.php");
    exit;
}

$cust_id = $_SESSION['cust_id'];
$new_email = $_POST['email'];
$new_password = $_POST['password'];

try {
    $pdo->beginTransaction();

    // تحديث الإيميل
    $stmt1 = $pdo->prepare("UPDATE Customer SET Email = ? WHERE Cust_ID = ?");
    $stmt1->execute([$new_email, $cust_id]);

    // تحديث الباسورد لو مش فاضي
    if (!empty($new_password)) {
        $hash = hash('sha256', $new_password);
        $stmt2 = $pdo->prepare("UPDATE Customer_Login SET Password_Hash = ? WHERE Cust_ID = ?");
        $stmt2->execute([$hash, $cust_id]);
    }

    $pdo->commit();
    header("Location: customer_dashboard.php");

} catch (Exception $e) {
    $pdo->rollBack();
header("Location: customer_dashboard.php?updated=1");
}
?>
