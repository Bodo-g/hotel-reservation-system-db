<?php
session_start();
require 'db.php';

if (!isset($_SESSION['cust_id'])) {
    header("Location: customer_login.php");
    exit;
}

$cust_id = $_SESSION['cust_id'];

$hotel_id = $_POST['hotel_id'];
$room_type = $_POST['room_type'];
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];
$total_price = $_POST['total_price'];

// تحقق من صحة التواريخ
if (strtotime($checkin) >= strtotime($checkout)) {
    header("Location: book_room.php?error=date");
    exit;
}

if (strtotime($checkin) < strtotime(date('Y-m-d'))) {
    header("Location: book_room.php?error=past");
    exit;
}

// ضم الوقت إلى التاريخ
$checkin_datetime = $checkin . " 00:00:00";
$checkout_datetime = $checkout . " 00:00:00";

try {
    $stmt = $pdo->prepare("INSERT INTO Reservation 
    (Cust_ID, Hotel_ID, Room_Type, Start_Date, End_Date, Status, Total_Price) 
    VALUES (?, ?, ?, ?, ?, 'Pending', ?)");
    $stmt->execute([$cust_id, $hotel_id, $room_type, $checkin, $checkout, $total_price]);

    header("Location: my_reservations.php?success=1");
    exit;
} catch (Exception $e) {
    header("Location: book_room.php?error=server");
    exit;
}
?>
