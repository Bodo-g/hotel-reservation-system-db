<?php
require 'db.php';

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$mobile = $_POST['mobile'];
$dob = $_POST['dob'];
$username = $_POST['username'];
$password = $_POST['password'];
$password_hash = hash('sha256', $password);

// حفظ بيانات العميل
try {
    $pdo->beginTransaction();

    $stmt1 = $pdo->prepare("INSERT INTO Customer (F_Name, L_Name, Email, Mobile_no, DOB) VALUES (?, ?, ?, ?, ?)");
    $stmt1->execute([$fname, $lname, $email, $mobile, $dob]);

    $cust_id = $pdo->lastInsertId();

    $stmt2 = $pdo->prepare("INSERT INTO Customer_Login (Cust_ID, Username, Password_Hash) VALUES (?, ?, ?)");
    $stmt2->execute([$cust_id, $username, $password_hash]);

    $pdo->commit();

    header("Location: customer_login.php?success=1");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Registration failed: " . $e->getMessage();
}
?>
