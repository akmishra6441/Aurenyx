<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Postify</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #eaf2f8; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .signup-container { background-color: #fff; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 400px; }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="email"], input[type="password"], input[type="number"] { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd; box-sizing: border-box; }
        input[type="file"] { width: 100%; }
        button { width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #0056b3; }
        .message { text-align: center; padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .error { background-color: #f8d7da; color: #721c24; }
        .success { background-color: #d4edda; color: #155724; }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Join Postify</h2>
        <div id="message-area" class="message" style="display: none;"></div>
        
        <form id="signup-form" action="core/handle_signup.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
             <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" required min="13">
            </div>
            <div class="form-group">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/png, image/jpeg, image/jpg">
            </div>
            <button type="submit">Sign Up</button>
        </form>
    </div>

</body>
</html>