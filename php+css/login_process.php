<?php
session_start();
require 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

if ($role == 'customer') {
    $stmt = $pdo->prepare("SELECT * FROM Customer_Login WHERE Username = ? AND Is_Active = 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && hash('sha256', $password) === $user['Password_Hash']) {
        $_SESSION['cust_id'] = $user['Cust_ID'];
        header('Location: customer_dashboard.php');
        exit;
    } else {
        echo "Invalid login for customer.";
    }

} elseif ($role == 'employee') {
    $stmt = $pdo->prepare("SELECT * FROM Employee_Login WHERE Username = ? AND Is_Active = 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['Password_Hash'])) {
        $_SESSION['emp_id'] = $user['Emp_ID'];
        $_SESSION['role'] = $user['Role'];

        // التوجيه حسب الوظيفة
        $redirect = match($user['Role']) {
            'Accountant' => 'accountant_dashboard.php',
            'Supervisor' => 'supervisor_dashboard.php',
            default => 'employee_dashboard.php',
        };

        header("Location: $redirect");
        exit;
    } else {
        echo "Invalid login for employee.";
    }
}
?>
