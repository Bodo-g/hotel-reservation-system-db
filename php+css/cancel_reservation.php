<?php
session_start();
require 'db.php';

if (!isset($_SESSION['cust_id'])) {
    header("Location: customer_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
    $reservation_id = $_POST['reservation_id'];
    $cust_id = $_SESSION['cust_id'];

    // تأكد أن الحجز يعود للعميل الحالي
    $stmt = $pdo->prepare("SELECT * FROM Reservation WHERE Reservation_ID = ? AND Cust_ID = ?");
    $stmt->execute([$reservation_id, $cust_id]);
    $reservation = $stmt->fetch();

    if ($reservation) {
        // تحديث الحالة إلى Canceled
        $cancel = $pdo->prepare("UPDATE Reservation SET Status = 'Canceled' WHERE Reservation_ID = ?");
        $cancel->execute([$reservation_id]);
    }
}

header("Location: my_reservations.php");
exit;
?>
