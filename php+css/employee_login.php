<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // جلب بيانات الموظف بناءً على اسم المستخدم
    $stmt = $pdo->prepare("SELECT el.*, e.Job_Description FROM Employee_Login el
                           JOIN Employee e ON el.Emp_ID = e.Emp_ID
                           WHERE el.Username = ? AND el.Is_Active = 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // التحقق من كلمة السر المشفرة باستخدام SHA-256
    if ($user && hash('sha256', $password) === $user['Password_Hash']) {
        $job = $user['Job_Description'];

        // الوظائف المسموح لها بالدخول
        $allowed_roles = ['Accountant', 'Receptionist', 'Supervisor'];

        if (in_array($job, $allowed_roles)) {
            $_SESSION['emp_id'] = $user['Emp_ID'];
            $_SESSION['role'] = $job;

            switch ($job) {
                case 'Accountant':
                    header('Location: accountant_dashboard.php');
                    break;
                case 'Receptionist':
                    header('Location: receptionist_dashboard.php');
                    break;
                case 'Supervisor':
                    header('Location: supervisor_dashboard.php');
                    break;
            }
            exit;
        } else {
            $error = "You are not authorized to access the system.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Employee Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f2f7fb;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }

        .login-box {
            background-color: white;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        label {
            display: block;
            margin-top: 15px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 5px;
        }

        input[type="submit"] {
            width: 100%;
            margin-top: 25px;
            padding: 12px;
            background-color: #0059b3;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #003d80;
        }

        .error {
            margin-top: 15px;
            color: red;
            text-align: center;
            background: #ffe6e6;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ffcccc;
        }
    </style>
</head>
<body>
    <form class="login-box" method="POST">
        <h2>Employee Login</h2>

        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Login">

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </form>
</body>
</html>
