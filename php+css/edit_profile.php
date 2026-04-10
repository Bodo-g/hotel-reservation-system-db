<?php
session_start();
require 'db.php';

if (!isset($_SESSION['cust_id'])) {
    header("Location: customer_login.php");
    exit;
}

$cust_id = $_SESSION['cust_id'];

$stmt = $pdo->prepare("SELECT Email FROM Customer WHERE Cust_ID = ?");
$stmt->execute([$cust_id]);
$customer = $stmt->fetch();
$current_email = $customer['Email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            text-align: center;
        }

        .form-box {
            background-color: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            margin-bottom: 25px;
            color: #2c3e50;
        }

        label {
            display: block;
            margin-top: 15px;
            text-align: left;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            margin-top: 25px;
            padding: 14px;
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

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #2980b9;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="form-container">
    

    <div class="form-box">
        <h2>Edit Profile</h2>
        <form method="post" action="edit_profile_process.php">
            <label>New Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($current_email) ?>" required>

            <label>New Password:</label>
            <input type="password" name="password" placeholder="Leave blank to keep current">
<a class="back-link" href="customer_dashboard.php">Back to Dashboard</a>
            <input type="submit" value="Update">
        </form>
    </div>
</div>
</body>
</html>
