<?php
session_start();
require 'db.php';

if (!isset($_SESSION['cust_id'])) {
    header("Location: customer_login.php");
    exit;
}

$showMessage = isset($_GET['updated']) ? true : false;

$stmt = $pdo->prepare("SELECT F_Name, L_Name FROM Customer WHERE Cust_ID = ?");
$stmt->execute([$_SESSION['cust_id']]);
$customer = $stmt->fetch();

$full_name = $customer ? $customer['F_Name'] . ' ' . $customer['L_Name'] : 'Customer';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f2f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            text-align: center;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 25px;
            color: #2c3e50;
        }

        .dashboard-box {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: auto;
        }

        h2 {
            font-size: 20px;
            margin-bottom: 25px;
            color: #34495e;
        }

        .dashboard-button {
            width: 100%;
            padding: 14px;
            margin: 12px 0;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }

        .dashboard-button.green {
            background-color: #0059b3;
            color: white;
        }
        
        .dashboard-button.green:hover {
            background-color: #003d80;
        }

        .dashboard-button.logout {
            background-color: #e74c3c;
            color: white;
        }

        .dashboard-button.logout:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?= htmlspecialchars($full_name) ?></h1>

        <div class="dashboard-box">
            <h2>What would you like to do?</h2>

            <a href="book_room.php"><button class="dashboard-button green">Book a Room</button></a>
            <a href="my_reservations.php"><button class="dashboard-button green">View My Reservations</button></a>
            <a href="edit_profile.php"><button class="dashboard-button green">Edit Profile</button></a>
            <a href="logout_customer.php"><button class="dashboard-button logout">Logout</button></a>
        </div>
    </div>
</body>
</html>
