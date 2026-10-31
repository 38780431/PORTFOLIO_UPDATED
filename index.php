<?php
require 'db.php';
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container <?php echo isset($_GET['action']) && $_GET['action'] === 'login' ? 'active' : ''; ?>">
        <div class="form-container sign-up">
            <form action="register.php" method="POST">
                <h1>Create Account</h1>
                <?php if (isset($_GET['error']) && $_GET['error'] === 'email_exists'): ?>
                    <div class="alert error">Email already exists!</div>
                <?php endif; ?>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="fname" placeholder="First Name" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="lname" placeholder="Last Name" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn">Sign Up</button>
            </form>
        </div>

        <div class="form-container sign-in">
            <form action="login.php" method="POST">
                <h1>Sign In</h1>
                <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_credentials'): ?>
                    <div class="alert error">Invalid email or password!</div>
                <?php elseif (isset($_GET['registered'])): ?>
                    <div class="alert success">Registration successful! Please login.</div>
                <?php endif; ?>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <a href="#" class="forgot-password">Forgot Your Password?</a>
                <button type="submit" class="btn">Sign In</button>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>