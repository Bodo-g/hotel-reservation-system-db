<?php
session_start();
require 'db.php';

if (!isset($_SESSION['cust_id'])) {
    header("Location: customer_login.php");
    exit;
}

$cust_id = $_SESSION['cust_id'];

$stmt = $pdo->prepare("SELECT Hotel_ID, Room_Type, Start_Date, End_Date, Status, Total_Price 
                       FROM Reservation 
                       WHERE Cust_ID = ? 
                       ORDER BY Reservation_ID DESC");
$stmt->execute([$cust_id]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Reservations</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eef4fa;
            text-align: center;
            padding: 40px;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 30px;
        }

        table {
            margin: auto;
            width: 90%;
            max-width: 1000px;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 14px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #0b6fb2;
            color: white;
        }

        td {
            font-size: 15px;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .back-link {
            display: block;
            margin-top: 30px;
            color: #2980b9;
            font-weight: bold;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>My Reservations</h2>

    <?php if (count($reservations) === 0): ?>
        <p>No reservations found.</p>
    <?php else: ?>
        <table>
            <tr>
                    <th>Hotel ID</th>
                    <th>Room Type</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Status</th>
                    <th>Total Price</th>
                </tr>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?= $reservation['Hotel_ID'] ?></td>
                        <td><?= htmlspecialchars($reservation['Room_Type']) ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($reservation['Start_Date'])) ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($reservation['End_Date'])) ?></td>
                        <td class="status-pending"><?= $reservation['Status'] ?></td>
                        <td><?= number_format($reservation['Total_Price'], 2) ?> EGP</td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

    <a class="back-link" href="customer_dashboard.php">Back to Dashboard</a>
</body>
</html>
