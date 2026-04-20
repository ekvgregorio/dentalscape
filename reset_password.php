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

.lock-icon {
    width: 80px;
    height: 80px;
    margin-bottom: 20px;
    color: #2563eb;
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
}

.form-group {
    margin-bottom: 20px;
    text-align: left;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #374151;
    font-size: 14px;
    font-weight: 500;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.2s;
}

.form-group input:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
}

.submit-btn {
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

.submit-btn:hover {
    background: #1d4ed8;
}

.submit-btn:active {
    transform: scale(0.98);
}

.back-link {
    color: #6b7280;
    text-decoration: none;
    font-size: 14px;
    display: inline-block;
}

.back-link:hover {
    color: #2563eb;
}

.message {
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
    font-size: 14px;
}

.error {
    background-color: #fee2e2;
    color: #dc2626;
}

.success {
    background-color: #dcfce7;
    color: #16a34a;
}

.password-requirements {
    background: #f9fafb;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: left;
}

.password-requirements h3 {
    color: #374151;
    font-size: 14px;
    margin-bottom: 8px;
}

.password-requirements ul {
    list-style: none;
    font-size: 13px;
    color: #6b7280;
}

.password-requirements li {
    margin-bottom: 4px;
    display: flex;
    align-items: center;
}

.password-requirements li::before {
    content: "•";
    color: #9ca3af;
    margin-right: 8px;
}

.password-requirements li.valid::before {
    content: "✓";
    color: #16a34a;
}

/* Responsive Media Queries */
@media (max-width: 768px) {
    .container {
        padding: 30px 24px;
        border-radius: 10px;
    }

    h1 {
        font-size: 22px;
    }

    .lock-icon {
        width: 70px;
        height: 70px;
    }
}

@media (max-width: 480px) {
    body {
        padding: 15px;
    }

    .container {
        padding: 24px 20px;
        border-radius: 8px;
    }

    h1 {
        font-size: 20px;
        margin-bottom: 10px;
    }

    p {
        font-size: 14px;
        margin-bottom: 24px;
    }

    .lock-icon {
        width: 60px;
        height: 60px;
        margin-bottom: 16px;
    }

    .form-group input {
        padding: 10px 12px;
        font-size: 16px; /* Keep 16px to prevent zoom on iOS */
    }

    .submit-btn {
        padding: 11px 20px;
        font-size: 15px;
    }

    .password-requirements {
        padding: 12px;
    }

    .password-requirements h3 {
        font-size: 13px;
    }

    .password-requirements ul {
        font-size: 12px;
    }

    .message {
        padding: 10px;
        font-size: 13px;
    }
}

@media (max-width: 360px) {
    .container {
        padding: 20px 16px;
    }

    h1 {
        font-size: 18px;
    }

    .lock-icon {
        width: 50px;
        height: 50px;
    }
}


        #password-strength-meter {
            width: 100%;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            margin-top: 8px;
            overflow: hidden;
        }

        #strength-bar {
            height: 100%;
            width: 0%;
            border-radius: 4px;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        #strength-text {
            font-size: 13px;
            margin-top: 6px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .strength-weak {
            background-color: #dc2626;
        }

        .strength-fair {
            background-color: #f59e0b;
        }

        .strength-good {
            background-color: #10b981;
        }

        .strength-strong {
            background-color: #059669;
        }

        .text-weak { color: #dc2626; }
        .text-fair { color: #f59e0b; }
        .text-good { color: #10b981; }
        .text-strong { color: #059669; }
    </style>
</head>
<body>
    <div class="container">
        <svg class="lock-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
        </svg>

        <h1>Reset Password</h1>
        <p>Please enter your new password below.</p>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="message error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST" action="process.php" id="resetForm">
            <input type="hidden" name="token" value="<?php echo $_GET['token'] ?? ''; ?>">
            
          <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="new_password" required>
                <div id="password-strength-meter">
                    <div id="strength-bar"></div>
                </div>
                <div id="strength-text"></div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>


            <button type="submit" name="reset_password" class="submit-btn">Reset Password</button>
        </form>
    </div>

  <script>
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');

        function calculatePasswordStrength(password) {
            let strength = 0;
            
            if (password.length === 0) return 0;
            
            // Length check
            if (password.length >= 8) strength += 25;
            if (password.length >= 12) strength += 15;
            
            // Contains lowercase
            if (/[a-z]/.test(password)) strength += 25;
            
            // Contains uppercase
            if (/[A-Z]/.test(password)) strength += 15;
            
            // Contains numbers
            if (/\d/.test(password)) strength += 15;
            
            // Contains special characters
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 25;
            
            return Math.min(strength, 100);
        }

        function getStrengthLabel(strength) {
            if (strength === 0) return '';
            if (strength < 40) return 'Weak';
            if (strength < 60) return 'Fair';
            if (strength < 80) return 'Good';
            return 'Strong';
        }

        function getStrengthClass(strength) {
            if (strength === 0) return '';
            if (strength < 40) return 'weak';
            if (strength < 60) return 'fair';
            if (strength < 80) return 'good';
            return 'strong';
        }

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            const label = getStrengthLabel(strength);
            const strengthClass = getStrengthClass(strength);
            
            // Update bar width
            strengthBar.style.width = strength + '%';
            
            // Remove all strength classes
            strengthBar.className = '';
            strengthText.className = '';
            
            // Add appropriate class
            if (strengthClass) {
                strengthBar.classList.add('strength-' + strengthClass);
                strengthText.classList.add('text-' + strengthClass);
                strengthText.textContent = label;
            } else {
                strengthText.textContent = '';
            }
        });

        document.getElementById('password-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const password = passwordInput.value;
            const strength = calculatePasswordStrength(password);
            
            if (strength < 40) {
                alert('Please choose a stronger password');
                return;
            }
            
            alert('Password updated successfully!');
            // Here you would normally send the password to your server
        });
    </script>
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