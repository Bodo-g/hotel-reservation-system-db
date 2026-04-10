<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Customer Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Grand Palace Hotel</h1>
    <div class="login-box">
        <h2>Create Account</h2>
        <form method="post" action="register_process.php">
            <label>First Name:</label>
            <input type="text" name="fname" required>

            <label>Last Name:</label>
            <input type="text" name="lname" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Mobile Number:</label>
            <input type="text" name="mobile" required>

            <label>Date of Birth:</label>
            <input type="date" name="dob" required>

            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <input type="submit" value="Register">
        </form>
        <p>Already have an account? <a href="customer_login.php">Login here</a></p>
    </div>
</div>
</body>
</html>
