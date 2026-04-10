<?php
session_start();
require 'db.php';

if (!isset($_SESSION['emp_id']) || $_SESSION['role'] !== 'Receptionist') {
    header("Location: employee_login.php");
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    if (empty($fname) || empty($lname) || empty($email) || empty($phone) || empty($password)) {
        $error = "❌ Please fill in all fields.";
    } else {
        // تحقق من عدم وجود اسم المستخدم مسبقاً (البريد الإلكتروني يُستخدم كـ Username)
        $stmt = $pdo->prepare("SELECT * FROM Customer_Login WHERE Username = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = "❌ This email is already registered.";
        } else {
            try {
                $pdo->beginTransaction();

                // إضافة بيانات العميل
                $stmt = $pdo->prepare("INSERT INTO Customer (F_Name, L_Name, Phone) VALUES (?, ?, ?)");
                $stmt->execute([$fname, $lname, $phone]);
                $cust_id = $pdo->lastInsertId();

                // إنشاء حساب تسجيل الدخول
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO Customer_Login (Cust_ID, Username, Password) VALUES (?, ?, ?)");
                $stmt->execute([$cust_id, $email, $hashed]);

                $pdo->commit();
                $success = "✅ Customer registered successfully.";
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "❌ Something went wrong. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Customer</title>
    <style>
        body {
            background-color: #f0f4f8;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 50px;
            height: 100vh;
            position: relative;
        }

        .form-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            max-width: 450px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #0059b3;
            color: white;
            font-weight: bold;
            margin-top: 20px;
            cursor: pointer;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .back-link {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
        }

        .back-link a {
            text-decoration: none;
            color: #0059b3;
            font-weight: bold;
            font-size: 15px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <form class="form-box" method="post">
        <h2>Register New Customer</h2>

        <?php if ($success): ?>
            <div class="message success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>

        <label>First Name:</label>
        <input type="text" name="fname" required>

        <label>Last Name:</label>
        <input type="text" name="lname" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Phone:</label>
        <input type="text" name="phone" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Register Customer">
    </form>

    <div class="back-link">
        <a href="receptionist_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
