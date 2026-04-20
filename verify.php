<?php
session_start();


if (!isset($_SESSION['verify_email']) || !isset($_SESSION['verification_type'])) {
    header("Location: login.php");
    exit();
}

$verification_type = $_SESSION['verification_type'];

$verification_message = "";
switch($verification_type) {
    case 'signup':
        $verification_message = "account";
        break;
    case 'login':
        $verification_message = "login";
        break;
    case 'password_reset':
        $verification_message = "password reset";
        break;
    default:
        $verification_message = "account";
}

if ($verification_type === 'login' && !isset($_SESSION['login_verification'])) {
    header("Location: login");
    exit();
}

if ($verification_type === 'password_reset' && !isset($_SESSION['password_reset'])) {
    header("Location: forgot_password.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DentalScape Iloilo</title>
  <link href="assets/img/logo1.png" rel="icon">
  <link href="assets/img/logo1.png" rel="apple-touch-icon">

  <style>
           * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #f0f4ff 0%, #e8f0ff 100%);
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .email-icon {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
        }

        h1 {
            color: #1a1f36;
            font-size: 24px;
            margin-bottom: 12px;
        }

        p {
            color: #6b7280;
            margin-bottom: 30px;
            line-height: 1.5;
            font-size: 15px;
        }

        .otp-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            text-align: center;
            font-size: 20px;
            font-weight: 500;
            color: #1a1f36;
            transition: all 0.2s;
        }

        .otp-input:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        .verify-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            margin-bottom: 20px;
            transition: background-color 0.2s;
        }

        .verify-btn:hover {
            background: #1d4ed8;
        }

        .timer {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .resend {
            color: #6b7280;
            font-size: 14px;
        }

        .resend-btn {
            background: none;
            border: none;
            color: #2563eb;
            font-weight: 500;
            cursor: pointer;
            padding: 0;
            margin-left: 5px;
        }

        .resend-btn:disabled {
            color: #9ca3af;
            cursor: not-allowed;
        }

        .resend-btn.active {
            color: #2563eb;
            cursor: pointer;
        }

        .message {
            padding: 10px 15px;
            border-radius: 4px;
            margin: 10px 0;
            width: 100%;
            font-size: 14px;
            text-align: center;
        }

        .message.error {
            background-color: #ffe6e6;
            color: #dc3545;
            border: 1px solid #dc3545;
            font-size: 14px;
        }

        .message.success {
            background-color: #e6ffe6;
            color: #28a745;
            border: 1px solid #28a745;
        }

        /* Tablet */
        @media (max-width: 768px) {
            .container {
                padding: 35px 25px;
                max-width: 400px;
            }

            h1 {
                font-size: 22px;
            }
        }

        /* Mobile - Large */
        @media (max-width: 480px) {
            body {
                padding: 15px;
            }

            .container {
                padding: 30px 20px;
                border-radius: 10px;
            }

            h1 {
                font-size: 20px;
                margin-bottom: 10px;
            }

            p {
                font-size: 14px;
                margin-bottom: 25px;
            }

            .email-icon {
                width: 64px;
                height: 64px;
                margin-bottom: 16px;
            }

            .otp-group {
                gap: 8px;
                margin-bottom: 25px;
            }

            .otp-input {
                width: 44px;
                height: 44px;
                font-size: 18px;
                border-radius: 6px;
            }

            .verify-btn {
                padding: 11px 20px;
                font-size: 15px;
                border-radius: 8px;
            }

            .timer, .resend {
                font-size: 13px;
            }

            .message {
                font-size: 13px;
                padding: 9px 12px;
            }
        }

        /* Mobile - Small */
        @media (max-width: 380px) {
            body {
                padding: 12px;
            }

            .container {
                padding: 25px 16px;
            }

            h1 {
                font-size: 18px;
            }

            p {
                font-size: 13px;
            }

            .email-icon {
                width: 56px;
                height: 56px;
            }

            .otp-group {
                gap: 6px;
            }

            .otp-input {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .verify-btn {
                font-size: 14px;
                padding: 10px 18px;
            }

            .timer, .resend {
                font-size: 12px;
            }

            .message {
                font-size: 12px;
            }
        }

        /* Mobile - Extra Small */
        @media (max-width: 340px) {
            .container {
                padding: 20px 14px;
            }

            .otp-group {
                gap: 5px;
            }

            .otp-input {
                width: 36px;
                height: 36px;
                font-size: 15px;
            }
        }

        /* Landscape orientation adjustments */
        @media (max-height: 600px) and (orientation: landscape) {
            body {
                padding: 15px;
            }

            .container {
                padding: 20px;
            }

            .email-icon {
                width: 50px;
                height: 50px;
                margin-bottom: 12px;
            }

            h1 {
                font-size: 18px;
                margin-bottom: 8px;
            }

            p {
                margin-bottom: 15px;
            }

            .otp-group {
                margin-bottom: 15px;
            }

            .verify-btn {
                margin-bottom: 12px;
            }
        }
    </style>

</head>
<body>
    <div class="container">
        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%232563eb' width='80' height='80'%3E%3Cpath d='M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z'/%3E%3Cpath d='M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z'/%3E%3C/svg%3E" class="email-icon" alt="Email verification">
        
        <h1>Please Verify Account</h1>
        <p>Enter the six digit code we sent to your email address to verify your account</p>

        <form method="POST" action="process.php">
            <div class="otp-group">
                <input type="text" class="otp-input" maxlength="1" pattern="[A-Za-z0-9]" required>
                <input type="text" class="otp-input" maxlength="1" pattern="[A-Za-z0-9]" required>
                <input type="text" class="otp-input" maxlength="1" pattern="[A-Za-z0-9]" required>
                <input type="text" class="otp-input" maxlength="1" pattern="[A-Za-z0-9]" required>
                <input type="text" class="otp-input" maxlength="1" pattern="[A-za-z0-9]" required>
                <input type="text" class="otp-input" maxlength="1" pattern="[A-Za-z0-9]" required>
                <input type="hidden" name="otp" id="otpValue">
            </div>

            <button type="submit" name="verify_otp" class="verify-btn">Verify & Continue</button>
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

        <div class="timer" id="timer">Time remaining: 10:00</div>
        
        <div class="resend">
            Didn't receive the code?
            <form method="POST" action="process.php" style="display: inline;">
                <button type="submit" name="resend_otp" class="resend-btn" id="resendButton" disabled>
                    Resend Code
                </button>
            </form>
        </div>
    </div>
    <script>
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpValue = document.getElementById('otpValue');
        const resendButton = document.getElementById('resendButton');
        const timerDisplay = document.getElementById('timer');
        let timeLeft = 600; 
        let timerId = null;

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value !== '') {
                    input.value = e.target.value.slice(-1);
                    if (index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                }
                updateOtpValue();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        function updateOtpValue() {
            otpValue.value = Array.from(otpInputs).map(input => input.value).join('');
        }

        // Timer function
        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerDisplay.textContent = `Time remaining: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            
            if (timeLeft <= 0) {
                clearInterval(timerId);
                timerDisplay.textContent = 'Time expired';
                resendButton.disabled = false;
                resendButton.classList.add('active');
            } else {
                timeLeft--;
            }
        }

        // Start timer
        function startTimer() {
            timeLeft = 600; // Reset to 10 minutes
            resendButton.disabled = true;
            resendButton.classList.remove('active');
            
            if (timerId) {
                clearInterval(timerId);
            }
            
            updateTimer(); 
            timerId = setInterval(updateTimer, 1000);
        }

        // Handle resend button click
        resendButton.addEventListener('click', function() {
            startTimer(); 
        });

        window.onload = function() {
            startTimer();
        };
    </script>
    <script src="assets/js/security.js"></script>
</body>
</html>