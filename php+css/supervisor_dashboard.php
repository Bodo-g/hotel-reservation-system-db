<?php
session_start();
if (!isset($_SESSION['emp_id']) || $_SESSION['role'] !== 'Supervisor') {
    header("Location: employee_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Supervisor Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f2f7fb;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .dashboard {
            background-color: white;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .button {
            display: block;
            width: 100%;
            margin: 12px 0;
            padding: 14px;
            background-color: #0059b3;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
        }

        .button:hover {
            background-color: #003d80;
        }

        .danger {
            background-color: #d35400;
        }

        .danger:hover {
            background-color: #a84300;
        }

        .logout {
            background-color: #c0392b;
        }

        .logout:hover {
            background-color: #a8322a;
        }
    </style>
</head>
<body>

<div class="dashboard">
    <h2>Welcome Supervisor</h2>

    <a href="manage_employees.php" class="button">Manage Employees</a>
    <a href="add_employee.php" class="button">Add New Employee</a>
    <a href="remove_employee.php" class="button danger">Remove Employee</a>
    <a href="logout_employee.php" class="button logout">Logout</a>
</div>

</body>
</html>
