<?php
session_start();
ob_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DentalScape Iloilo</title>
    
    <link href="assets/img/logo1.png" rel="icon">
    <link href="assets/img/logo1.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.css" rel="stylesheet">
</head>
<style>
        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #d7dff6ff 0%, #e9eef6ff 100%);
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15), 0 0 100px rgba(25, 119, 204, 0.1);
            position: relative;
            overflow: hidden;
            width: 900px;
            max-width: 100%;
            min-height: 600px;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .sign-in-container {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .sign-up-container {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
        }

        .container.right-panel-active .sign-in-container {
            transform: translateX(100%);
        }

        .container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }

        @keyframes show {
            0%, 49.99% {
                opacity: 0;
                z-index: 1;
            }
            50%, 100% {
                opacity: 1;
                z-index: 5;
            }
        }

        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: 100;
        }

        .container.right-panel-active .overlay-container {
            transform: translateX(-100%);
        }

        .overlay {
            background: #4B79A1;
            background: linear-gradient(to right, #1977cc, rgb(10, 97, 173));
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 0 0;
            color: #FFFFFF;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        /* Decorative circles in overlay */
        .overlay::before {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            top: -50px;
            left: 20%;
            animation: float 15s infinite ease-in-out;
        }

        .overlay::after {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            bottom: -30px;
            right: 15%;
            animation: float 20s infinite ease-in-out reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(20px, -20px); }
        }

        .container.right-panel-active .overlay {
            transform: translateX(50%);
        }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transform: translateX(0);
            transition: transform 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: 1;
        }

        .overlay-left {
            transform: translateX(-20%);
        }

        .overlay-right {
            right: 0;
            transform: translateX(0);
        }

        .container.right-panel-active .overlay-left {
            transform: translateX(0);
        }

        .container.right-panel-active .overlay-right {
            transform: translateX(20%);
        }

        form {
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 50px;
            height: 100%;
            text-align: center;
        }

        h1 {
            font-weight: 700;
            margin: 0;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
        }

        .sign-in-container h3 {
            text-align: left !important;
            width: 100%;
            margin: 0;
            margin-bottom: 25px;
            color: #1977cc;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .logo-container {
            margin-bottom: 35px;
            animation: fadeInDown 0.8s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-container img {
            width: 400px;
            height: auto;
            max-width: 100%;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.08));
        }

        .input-group {
            position: relative;
            width: 100%;
            margin-bottom: 18px;
        }

        .input-group span {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #1977cc;
            transition: all 0.3s ease;
            z-index: 1;
        }

        .input-group input {
            width: 100%;
            padding: 14px 18px 14px 45px;
            border: 2px solid #e8e8f0;
            border-radius: 12px;
            font-size: 15px;
            background: #fafbff;
            transition: all 0.3s ease;
            font-family: 'Montserrat', sans-serif;
        }

        .input-group input:focus {
            outline: none;
            border-color: #1977cc;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(25, 119, 204, 0.15);
            transform: translateY(-1px);
        }

        .input-group input:focus + span {
            transform: translateY(-50%) scale(1.1);
            color: #1977cc;
        }

        .input-group input.error {
        border-color: #e74c3c; 
        box-shadow: 0 0 8px rgba(231, 76, 60, 0.2);
        }

        button {
            border-radius: 12px;
            border: none;
            background-color: #1977cc;
            color: #FFFFFF;
            font-size: 13px;
            font-weight: 600;
            padding: 14px 50px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.3s ease;
            margin-top: 25px;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(25, 119, 204, 0.3);
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(25, 119, 204, 0.4);
            background-color: #1565b8;
        }

        button:hover::before {
            left: 100%;
        }

        button:active {
            transform: translateY(0);
        }

        button.ghost {
            background-color: transparent;
            border: 2px solid #FFFFFF;
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
        }

        button.ghost:hover {
            background-color: #FFFFFF;
            color: #1977cc;
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
        }

        .form-options.single-option {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            width: 100%;
        }

        .forgot-password a {
            color: #4B79A1;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .forgot-password a:hover {
            color: #1977cc;
            text-decoration: underline;
        }

        .alert {
            padding: 16px 20px;
            margin-bottom: 20px;
            border: none;
            border-radius: 12px;
            width: 100%;
            font-size: 14px;
            font-weight: 500;
            animation: slideIn 0.4s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            color: #721c24;
            background: linear-gradient(135deg, #f8d7da 0%, #fce4e6 100%);
            border-left: 4px solid #dc3545;
        }

        .alert-success {
            color: #155724;
            background: linear-gradient(135deg, #d4edda 0%, #dff3e3 100%);
            border-left: 4px solid #28a745;
        }

        .password-strength {
            margin-top: 8px;
            font-size: 12px;
            text-align: left;
            width: 100%;
        }

        .strength-item {
            color: #dc3545;
            transition: color 0.3s ease;
            padding: 2px 0;
        }

        .strength-item.valid {
            color: #28a745;
        }

        .strength-item::before {
            content: '○';
            margin-right: 6px;
            transition: content 0.3s ease;
        }

        .strength-item.valid::before {
            content: '●';
        }

        #password-strength-meter {
            width: 100%;
            margin-top: 8px;
            height: 6px;
            margin-bottom: 8px;
            background: linear-gradient(90deg, #f0f0f5 0%, #e8e8f0 100%);
            border-radius: 10px;
            overflow: hidden;
        }

        #strength-bar {
            height: 100%;
            width: 0;
            border-radius: 10px;
            transition: width 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55), 
                        background-color 0.4s ease;
        }

        .message {
            padding: 14px 20px;
            border-radius: 12px;
            margin: 10px 0;
            width: 100%;
            font-size: 14px;
            text-align: center;
            font-weight: 500;
            animation: slideIn 0.4s ease-out;
        }

        .message.error {
            background: linear-gradient(135deg, #ffe6e6 0%, #ffebeb 100%);
            color: #dc3545;
        }

        .message.success {
            background: linear-gradient(135deg, #e6ffe6 0%, #f0fff0 100%);
            color: #28a745;
        }

        #password-error {
            color: red;
            display: none;
            text-align: left;
            font-size: 11px;
            margin-top: 5px;
            font-weight: 500;
        }

        /* Mobile-first responsive design */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .container {
                width: 100%;
                max-width: 400px;
                min-height: auto;
                border-radius: 20px;
                background-color: #fff;
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            }

            .form-container {
                position: static;
                width: 100% !important;
                height: auto;
                background-color: #fff;
                border-radius: 20px;
                margin-bottom: 15px;
            }

            .sign-in-container {
                display: block;
                position: static;
                transform: none !important;
            }

            .sign-up-container {
                display: none;
                position: static;
                opacity: 1;
                transform: none !important;
            }

            .container.right-panel-active .sign-in-container {
                display: none;
            }

            .container.right-panel-active .sign-up-container {
                display: block;
            }

            .overlay-container {
                position: static;
                width: 100%;
                height: auto;
                left: 0;
                transform: none !important;
                margin-top: 15px;
            }

            .overlay {
                position: static;
                width: 100%;
                height: auto;
                left: 0;
                transform: none !important;
                border-radius: 16px;
                padding: 35px 20px;
            }

            .overlay-panel {
                position: static;
                width: 100%;
                height: auto;
                padding: 0;
                transform: none !important;
            }

            .overlay-left {
                display: none;
            }

            .overlay-right {
                display: block;
            }

            .container.right-panel-active .overlay-left {
                display: block;
            }

            .container.right-panel-active .overlay-right {
                display: none;
            }

            form {
                padding: 35px 25px;
                height: auto;
            }

            .logo-container img {
                width: 250px;
                max-width: 100%;
            }

            .overlay-panel h1 {
                font-size: 1.5em;
                margin-bottom: 15px;
            }

            .overlay-panel p {
                font-size: 14px;
                margin-bottom: 20px;
                line-height: 1.6;
            }

            button {
                padding: 13px 40px;
                font-size: 12px;
            }

            .overlay::before,
            .overlay::after {
                display: none;
            }
        }

        /* Tablet responsive design */
        @media (min-width: 769px) and (max-width: 1024px) {
            .container {
                width: 90%;
                max-width: 800px;
            }

            form {
                padding: 0 35px;
            }

            .overlay-panel {
                padding: 0 30px;
            }

            .logo-container img {
                width: 300px;
            }
        }

        @media (min-width: 1200px) {
            .container {
                width: 900px;
            }
        }

        @media (max-height: 600px) and (orientation: landscape) {
            body {
                align-items: flex-start;
                padding-top: 20px;
            }

            .container {
                min-height: auto;
            }

            .logo-container {
                margin-bottom: 15px;
            }

            .logo-container img {
                width: 400px;
            }

            form {
                padding: 25px 30px;
            }
        }

        html {
            scroll-behavior: smooth;
        }

        *:focus-visible {
            outline: 2px solid #1977cc;
            outline-offset: 3px;
        }
    </style>
</head>

<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="process.php" method="POST" autocomplete="off">
                <h1 style="color: #1977cc; margin-bottom: 20px;">Create Account</h1>
                
                <div class="input-group">
                    <span><i class="fas fa-user"></i></span>
                    <input type="text" name="fullname" placeholder="Full Name" required autocomplete="off">
                </div>
                <div class="input-group">
                    <span><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <span><i class="fas fa-lock" style="margin-bottom: 45px;"></i></span>
                    <input type="password" name="password" id="password" placeholder="Password" required autocomplete="off">
                    <div id="password-strength-meter">
                        <div id="strength-bar"></div>
                    </div>
                    <small id="password-hint" style="display: block; color: #777; font-size: 10px; margin-top: 4px;">
                        Password should contain at least 8 characters, including uppercase and lowercase letters, numbers, and special characters.
                    </small>
                </div>
                <div class="input-group">
                    <span><i class="fas fa-lock"></i></span>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required autocomplete="off">
                </div>
                <button type="submit" name="signup">Sign Up</button>
            </form>
        </div>

        <div class="form-container sign-in-container">
            <form action="process.php" method="POST" autocomplete="off">
                <div class="logo-container">
                    <img src="assets/img/login.png" alt="logo">
                </div>
                <h3 class="left-align">Sign in here!</h3>

                <div class="input-group">
                    <span><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" placeholder="Email" required autocomplete="off">
                </div>
                <div class="input-group">
                    <span><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" placeholder="Password" required autocomplete="off">
                </div>
                <input type="hidden" id="neighbourhood" name="neighbourhood">
                <input type="hidden" id="municipality" name="municipality">
                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="longitude" name="longitude">
                
                <div class="form-options single-option">
                    <div class="forgot-password">
                        <a href="forgot-password">Forgot your password?</a>
                    </div>
                </div>
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

                <button type="submit" name="login">Sign In</button>
                                            
            </form>

        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello!</h1>
                    <p>Enter your personal details and start your journey with us</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

<script>
        // Panel switching functionality with mobile support
        function isMobile() {
            return window.innerWidth <= 768;
        }

        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            const signUpButton = document.getElementById('signUp');
            const signInButton = document.getElementById('signIn');
            const container = document.getElementById('container');

            console.log('SignUp Button:', signUpButton);
            console.log('SignIn Button:', signInButton);
            console.log('Container:', container);

            if (signUpButton) {
                signUpButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Sign Up clicked');
                    if (container) {
                        container.classList.add("right-panel-active");
                        
                        // Smooth scroll to top on mobile
                        if (isMobile()) {
                            setTimeout(function() {
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                            }, 100);
                        }
                    }
                });
            }

            if (signInButton) {
                signInButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Sign In clicked');
                    if (container) {
                        container.classList.remove("right-panel-active");
                        
                        // Smooth scroll to top on mobile
                        if (isMobile()) {
                            setTimeout(function() {
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                            }, 100);
                        }
                    }
                });
            }
        });

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                // Optional: Reset to sign-in panel on resize to desktop
                if (!isMobile() && container && container.classList.contains('right-panel-active')) {
                    // Uncomment if you want to reset on desktop resize
                    // container.classList.remove("right-panel-active");
                }
            }, 250);
        });

        // Password strength meter - only for sign-up form
        const signUpForm = document.querySelector('.sign-up-container form');
        const password = signUpForm ? signUpForm.querySelector('#password') : null;
        const strengthBar = document.getElementById('strength-bar');

        if (password && strengthBar) {
            password.addEventListener('input', function() {
                const value = password.value;
                let strength = 0;

                if (value.length >= 8) strength += 20;
                if (/[A-Z]/.test(value)) strength += 20;
                if (/[a-z]/.test(value)) strength += 20;
                if (/[0-9]/.test(value)) strength += 20;
                if (/[^A-Za-z0-9]/.test(value)) strength += 20;

                strengthBar.style.width = strength + '%';

                // Remove existing strength classes
                strengthBar.classList.remove('medium', 'strong');

                if (strength < 40) {
                    strengthBar.style.backgroundColor = '#dc3545';
                } else if (strength < 80) {
                    strengthBar.style.backgroundColor = '#ffc107';
                    strengthBar.classList.add('medium');
                } else {
                    strengthBar.style.backgroundColor = '#28a745';
                    strengthBar.classList.add('strong');
                }
            });
        }

        // Password confirmation validation - only for sign-up form
        const confirmPassword = signUpForm ? signUpForm.querySelector('#confirm_password') : null;
        const passwordError = document.getElementById('password-error');

        function checkPasswordMatch() {
            if (password && confirmPassword && passwordError) {
                const passwordValue = password.value;
                const confirmValue = confirmPassword.value;
                
                if (confirmValue !== '' && passwordValue !== confirmValue) {
                    passwordError.style.display = "block";
                    passwordError.textContent = "Passwords do not match!";
                    confirmPassword.style.borderColor = "#dc3545";
                    confirmPassword.style.borderWidth = "2px";
                } else if (confirmValue !== '' && passwordValue === confirmValue) {
                    passwordError.style.display = "none";
                    confirmPassword.style.borderColor = "#28a745";
                    confirmPassword.style.borderWidth = "2px";
                } else {
                    passwordError.style.display = "none";
                    confirmPassword.style.borderColor = "#e8e8f0";
                    confirmPassword.style.borderWidth = "2px";
                }
            }
        }

        if (confirmPassword) {
            confirmPassword.addEventListener('input', checkPasswordMatch);
            confirmPassword.addEventListener('blur', checkPasswordMatch);
        }

        if (password) {
            password.addEventListener('input', checkPasswordMatch);
        }

        // Add fade-in animation on page load
        window.addEventListener('load', () => {
            if (container) {
                container.style.opacity = '0';
                container.style.transform = 'translateY(20px)';
                container.style.transition = 'none';
                
                setTimeout(() => {
                    container.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                    container.style.opacity = '1';
                    container.style.transform = 'translateY(0)';
                }, 100);
            }
        });

        // Prevent form submission if passwords don't match
        if (signUpForm) {
            signUpForm.addEventListener('submit', (e) => {
                if (password && confirmPassword) {
                    if (password.value !== confirmPassword.value) {
                        e.preventDefault();
                        if (passwordError) {
                            passwordError.style.display = "block";
                            passwordError.textContent = "Please make sure passwords match before submitting";
                        }
                        confirmPassword.focus();
                        
                        // Scroll to the error on mobile
                        if (isMobile()) {
                            confirmPassword.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        return false;
                    }
                }
            });
        }

        // Auto-hide success/error messages after 5 seconds
        const messages = document.querySelectorAll('.message');
        if (messages.length > 0) {
            messages.forEach(message => {
                setTimeout(() => {
                    message.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    message.style.opacity = '0';
                    message.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        message.remove();
                    }, 500);
                }, 5000);
            });
        }

    </script>  
<script>
function getLocationAndSendToPython() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            console.log("Latitude:", lat, "Longitude:", lon);

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lon;

            fetch('http://localhost:5002/get_location', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ latitude: lat, longitude: lon })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Location response:", data);

                if (data.error) {
                    console.log("Location fetch failed:", data.error);
                    return;
                }

                document.getElementById('neighbourhood').value = data.neighbourhood || '';
                document.getElementById('municipality').value = data.municipality || '';
            })
            .catch(err => {
                console.error("Fetch error:", err);
            });

        }, function (error) {
            console.warn("Geolocation error:", error.message);
        });
    } else {
        console.log("Your browser does not support geolocation.");
    }
}

document.addEventListener('DOMContentLoaded', getLocationAndSendToPython);
</script>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const password = document.getElementById('password');
    const confirm = document.getElementById('confirm_password');
    const errorMsg = document.getElementById('password-error');

    function checkPasswordMatch() {
        if (confirm.value === "") {
            confirm.classList.remove('error');
            errorMsg.style.display = 'none';
            return;
        }

        if (password.value !== confirm.value) {
            confirm.classList.add('error');
            errorMsg.style.display = 'block';
        } else {
            confirm.classList.remove('error');
            errorMsg.style.display = 'none';
        }
    }

    password.addEventListener('input', checkPasswordMatch);
    confirm.addEventListener('input', checkPasswordMatch);
});
</script>

<script src="assets/js/security.js"></script>

</body>
</html>
<?php
function obfuscate_html($buffer) {
    $encoded = base64_encode($buffer); 
    return "<script>document.write(atob('$encoded'));</script>"; 
}

$output = ob_get_clean(); 
echo obfuscate_html($output); 
?>
