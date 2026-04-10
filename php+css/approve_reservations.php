<?php
session_start();
require 'db.php';

if (!isset($_SESSION['emp_id']) || !in_array($_SESSION['role'], ['Receptionist', 'Accountant'])) {
    header("Location: employee_login.php");
    exit;
}

$error = '';
$success = '';

// الموافقة أو الرفض
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reservation_id = $_POST['reservation_id'];
    $room_no = $_POST['room_no'];

    $stmt = $pdo->prepare("SELECT Hotel_ID, Start_Date, End_Date FROM Reservation WHERE Reservation_ID = ?");
    $stmt->execute([$reservation_id]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        $error = "Reservation not found.";
    } else {
        $hotel_id = $reservation['Hotel_ID'];
        $start_date = $reservation['Start_Date'];
        $end_date = $reservation['End_Date'];

        $room_stmt = $pdo->prepare("SELECT Room_ID FROM Room WHERE Room_No = ? AND Hotel_ID = ?");
        $room_stmt->execute([$room_no, $hotel_id]);
        $room = $room_stmt->fetch(PDO::FETCH_ASSOC);

        if (!$room) {
            $error = "Room $room_no is not available for the selected dates.";
        } else {
            $room_id = $room['Room_ID'];

            $conflict_stmt = $pdo->prepare("
                SELECT 1 FROM Reservation_Details rd
                JOIN Reservation r ON r.Reservation_ID = rd.Reservation_ID
                WHERE rd.Room_ID = ? AND r.Status = 'Confirmed'
                AND (
                    (? BETWEEN r.Start_Date AND r.End_Date)
                    OR (? BETWEEN r.Start_Date AND r.End_Date)
                    OR (? <= r.Start_Date AND ? >= r.End_Date)
                )
            ");
            $conflict_stmt->execute([$room_id, $start_date, $end_date, $start_date, $end_date]);

            if ($conflict_stmt->fetch()) {
                $error = "Room $room_no is not available for the selected dates.";
            } else {
                $pdo->beginTransaction();

                // ✅ التحقق من عدم إدخال تكرار
                $check_stmt = $pdo->prepare("SELECT 1 FROM Reservation_Details WHERE Reservation_ID = ? AND Room_ID = ?");
                $check_stmt->execute([$reservation_id, $room_id]);

                if (!$check_stmt->fetch()) {
                    $pdo->prepare("INSERT INTO Reservation_Details (Reservation_ID, Room_ID) VALUES (?, ?)")
                         ->execute([$reservation_id, $room_id]);
                }

                $pdo->prepare("UPDATE Reservation SET Status = 'Confirmed' WHERE Reservation_ID = ?")
                     ->execute([$reservation_id]);

                $pdo->commit();
                $success = "Reservation approved successfully and room $room_no assigned.";
            }
        }
    }
}

// عرض الحجوزات المعلقة
$reservations = $pdo->query("SELECT * FROM Reservation WHERE Status = 'Pending'")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Approve Reservations</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f2f7fb;
            padding: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 14px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #0059b3;
            color: white;
        }
        .approve {
            background-color: #27ae60;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
        }
        .reject {
            background-color: #c0392b;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
        }
        .back {
            margin-bottom: 20px;
            display: inline-block;
            color: #0059b3;
            font-weight: bold;
            text-decoration: none;
        }
        .message {
            text-align: center;
            padding: 12px;
            margin-bottom: 15px;
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
    </style>
</head>
<body>

<h2>Pending Reservations</h2>

<div style="text-align:center; margin-bottom: 20px;">
    <a class="back" href="<?= $_SESSION['role'] === 'Receptionist' ? 'receptionist_dashboard.php' : 'accountant_dashboard.php' ?>">
        ← Back to Dashboard
    </a>
</div>

<?php if ($success): ?>
    <div class="message success"><?= $success ?></div>
<?php elseif ($error): ?>
    <div class="message error"><?= $error ?></div>
<?php endif; ?>

<table>
    <tr>
        <th>Reservation ID</th>
        <th>Hotel ID</th>
        <th>Customer ID</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Assign Room</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($reservations as $res): ?>
        <tr>
            <form method="post">
                <td><?= $res['Reservation_ID'] ?></td>
                <td><?= $res['Hotel_ID'] ?></td>
                <td><?= $res['Cust_ID'] ?></td>
                <td><?= $res['Start_Date'] ?></td>
                <td><?= $res['End_Date'] ?></td>
                <td>
                    <input type="number" name="room_no" required>
                    <input type="hidden" name="reservation_id" value="<?= $res['Reservation_ID'] ?>">
                </td>
                <td>
                    <button type="submit" class="approve">Approve</button>
                    <button type="submit" name="reject" class="reject">Reject</button>
                </td>
            </form>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
