<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DentalScape Iloilo </title>
    <link href="assets/img/logo1.png" rel="icon">
    <link href="assets/img/logo1.png" rel="apple-touch-icon">
    <link rel="stylesheet" href="assets/css/forgot.css">
</head>
<body>
    <div class="container">
        <svg class="lock-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
        </svg>

        <h1>Forgot Password</h1>
        <p>Enter your email address and we'll send you a link to reset your password.</p>

        <form method="POST" action="process.php">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>

            <button type="submit" name="forgot_password" class="submit-btn">Send Reset Link</button>
        </form>

        <?php if (isset($_SESSION['error'])): ?>
                    <div class="message error">
                        <?= htmlspecialchars($_SESSION['error']); ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="message success">
                        <?= htmlspecialchars($_SESSION['success']); ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

        <a href="login" class="back-link">← Back to Login</a>
    </div>
</body>
</html>