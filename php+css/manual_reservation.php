<?php
session_start();
require 'db.php';

if (!isset($_SESSION['emp_id']) || $_SESSION['role'] !== 'Receptionist') {
    header("Location: employee_login.php");
    exit;
}

$hotels = $pdo->query("SELECT Hotel_ID, Location FROM Hotel")->fetchAll(PDO::FETCH_ASSOC);
$room_types = ['Single', 'Double', 'Suite', 'Deluxe'];

$room_prices = [
    'Single' => 500,
    'Double' => 800,
    'Suite' => 1300,
    'Deluxe' => 2000
];

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cust_id = $_POST['cust_id'];
    $hotel_id = $_POST['hotel_id'];
    $room_type = $_POST['room_type'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $total_price = $_POST['total_price'];

    if (strtotime($checkin) >= strtotime($checkout)) {
        $error = "❌ Check-out date must be after check-in date.";
    } elseif (strtotime($checkin) < strtotime(date('Y-m-d'))) {
        $error = "❌ Cannot book for a past date.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO Reservation (Cust_ID, Hotel_ID, Room_Type, Start_Date, End_Date, Status, Total_Price) VALUES (?, ?, ?, ?, ?, 'Pending', ?)");
            $stmt->execute([$cust_id, $hotel_id, $room_type, $checkin, $checkout, $total_price]);
            $success = "✅ Reservation submitted successfully.";
        } catch (Exception $e) {
            $error = "❌ Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manual Reservation</title>
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

        input, select {
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
    <script>
        const prices = {
            'Single': 500,
            'Double': 800,
            'Suite': 1300,
            'Deluxe': 2000
        };

        function updatePrice() {
            const type = document.getElementById('room_type').value;
            const checkin = new Date(document.getElementById('checkin').value);
            const checkout = new Date(document.getElementById('checkout').value);

            if (checkout > checkin && prices[type]) {
                const nights = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
                document.getElementById('total_price').value = nights * prices[type];
            } else {
                document.getElementById('total_price').value = '';
            }
        }
    </script>
</head>
<body>
    <form class="form-box" method="post" oninput="updatePrice()">
        <h2>Manual Reservation</h2>

        <?php if ($success): ?>
            <div class="message success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>

        <label>Customer ID:</label>
        <input type="number" name="cust_id" required>

        <label>Hotel Location:</label>
        <select name="hotel_id" required>
            <?php foreach ($hotels as $hotel): ?>
                <option value="<?= $hotel['Hotel_ID'] ?>"><?= htmlspecialchars($hotel['Location']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Room Type:</label>
        <select name="room_type" id="room_type" required>
            <?php foreach ($room_types as $type): ?>
                <option value="<?= $type ?>"><?= $type ?></option>
            <?php endforeach; ?>
        </select>

        <label>Check-in Date:</label>
        <input type="date" name="checkin" id="checkin" required min="<?= date('Y-m-d') ?>">

        <label>Check-out Date:</label>
        <input type="date" name="checkout" id="checkout" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">

        <label>Total Price (EGP):</label>
        <input type="text" id="total_price" name="total_price" readonly>

        <input type="submit" value="Submit Reservation">
    </form>

    <div class="back-link">
        <a href="receptionist_dashboard.php"> Back to Dashboard</a>
    </div>
</body>
</html>
