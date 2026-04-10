<?php
session_start();
$errorMessage = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'date':
            $errorMessage = "❌ Check-out date must be after check-in date.";
            break;
        case 'past':
            $errorMessage = "❌ You cannot book for a past date.";
            break;
        case 'server':
            $errorMessage = "❌ Something went wrong. Please try again later.";
            break;
    }
}

require 'db.php';

if (!isset($_SESSION['cust_id'])) {
    header("Location: customer_login.php");
    exit;
}

// جلب مواقع الفنادق من قاعدة البيانات
$hotels = $pdo->query("SELECT Hotel_ID, Location FROM Hotel")->fetchAll(PDO::FETCH_ASSOC);

// أنواع الغرف الثابتة حسب أسعار الملف
$room_types = ['Single', 'Double', 'Suite', 'Deluxe'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book a Room</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        select, input[type="date"], input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
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
            display: block;
            margin-bottom: 20px;
            text-align: center;
            color: #2980b9;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>

    <script>
        let roomPrices = {
            'Single': 500,
            'Double': 800,
            'Suite': 1300,
            'Deluxe': 2000
        };

        function updatePrice() {
            const roomType = document.getElementById("room_type").value;
            const checkin = new Date(document.getElementById("checkin").value);
            const checkout = new Date(document.getElementById("checkout").value);

            if (roomType && checkout > checkin) {
                const nights = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
                const price = roomPrices[roomType];
                document.getElementById("nights").value = nights;
                document.getElementById("total_price").value = nights * price;
            } else {
                document.getElementById("nights").value = '';
                document.getElementById("total_price").value = '';
            }
        }
    </script>
</head>
<body>
    <form class="form-container" method="post" action="book_room_process.php" oninput="updatePrice()">
        <h2>Book a Room</h2>

        <label>Hotel Location:</label>
        <select name="hotel_id" required>
            <?php foreach ($hotels as $hotel): ?>
                <option value="<?= $hotel['Hotel_ID'] ?>"><?= htmlspecialchars($hotel['Location']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Room Type:</label>
        <select name="room_type" id="room_type" required onchange="updatePrice()">
            <?php foreach ($room_types as $type): ?>
                <option value="<?= $type ?>"><?= $type ?></option>
            <?php endforeach; ?>
        </select>

        <label>Check-in Date:</label>
        <input type="date" name="checkin" id="checkin" required onchange="updatePrice()" min="<?= date('Y-m-d') ?>">

        <label>Check-out Date:</label>
        <input type="date" name="checkout" id="checkout" required onchange="updatePrice()" min="<?= date('Y-m-d', strtotime('+1 day')) ?>">

        <label>Number of Nights:</label>
        <input type="text" id="nights" name="nights" readonly>

        <label>Total Price (EGP):</label>
        <input type="text" id="total_price" name="total_price" readonly>
        <a class="back-link" href="customer_dashboard.php"> Back to Dashboard</a>

        <input type="submit" value="Submit Reservation">
    </form>
</body>
</html>
