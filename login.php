<?php require_once 'core/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Postify</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #eaf2f8; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-container { background-color: #fff; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 400px; }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; }
        input[type="email"], input[type="password"] { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #0056b3; }
        .message { text-align: center; padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .error { background-color: #f8d7da; color: #721c24; }
        .success { background-color: #d4edda; color: #155724; }
        .extra-links { text-align: center; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Postify Login</h2>

        <?php
            if (isset($_GET['status']) && $_GET['status'] == 'success') {
                echo '<div class="message success">Registration successful! Please log in.</div>';
            }
            if (isset($_GET['error']) && $_GET['error'] == 'invalidcredentials') {
                echo '<div class="message error">Invalid email or password.</div>';
            }
        ?>

        <form action="core/handle_login.php" method="post">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="extra-links">
            <p>Don't have an account? <a href="signup.php">Create Account</a></p>
        </div>
    </div>
</body>
</html>