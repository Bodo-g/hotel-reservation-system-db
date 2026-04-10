<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to Grand Palace Hotel</h1>

        <div class="login-box">
            <h2>Customer Login</h2>
            <form method="post" action="login_process_customer.php">
                <label>Username:</label>
                <input type="text" name="username" required>

                <label>Password:</label>
                <input type="password" name="password" required>

                <input type="submit" value="Login">
            </form>

            <p>Don't have an account? <a href="register.php">Create one here</a></p>
        </div>
    </div>
</body>
</html>
