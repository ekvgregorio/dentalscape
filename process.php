<?php
session_start();
require_once 'conn.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Jenssegers\Agent\Agent;
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

function sendOtpEmail($email, $otp, $type = 'signup') {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        $mail->setFrom('dentalscape.iloilo.services@gmail.com', 'DentalScape Iloilo');
        $mail->addAddress($email);
        
        $mail->isHTML(true);
        // Unified email card style
        $template_start = "
        <div style='
          font-family: Arial, sans-serif;
          background-color: #f7f9fc;
          padding: 30px;
          border-radius: 10px;
          max-width: 600px;
          margin: auto;
          box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        '>
          <div style='background-color: #ffffff; border-radius: 8px; padding: 25px;'>
        ";

        $template_end = "
              <p style='color: #555; font-size: 14px; text-align: center;'>
                ⏳ This code will expire in <strong>10 minutes</strong>.
              </p>
              <hr style='border: none; border-top: 1px solid #ddd; margin: 25px 0;'>
              <p style='text-align: center; color: #333; margin-top: 30px; font-weight: bold;'>
                — The Support and Maintenance Team —
              </p>
            </div>
        </div>";

        switch ($type) {
            case 'signup':
                $mail->Subject = 'Welcome to DentalScape Iloilo - Email Verification';
                $mail->Body = $template_start . "
                    <h2 style='color: #4a90e2; text-align: center;'>Welcome to EWAR!</h2>
                    <p style='color: #333; font-size: 15px; text-align: center;'>
                      Thank you for signing up.<br>
                      Please use the following verification code to complete your registration:
                    </p>
                    <h1 style='color: #4a90e2; font-size: 36px; text-align: center; letter-spacing: 8px; margin: 20px 0;'>$otp</h1>
                    <p style='color: #555; font-size: 14px; text-align: center;'>
                      If you didn’t sign up, please ignore this email.
                    </p>
                " . $template_end;
                break;

            case 'login':
                $mail->Subject = 'Dentscape Iloilo - Login Verification Code';
                $mail->Body = $template_start . "
                    <h2 style='color: #4a90e2; text-align: center;'>Login Verification</h2>
                    <p style='color: #333; font-size: 15px; text-align: center;'>
                      We detected a login attempt to your account.<br>
                      Please use the following code to verify your identity:
                    </p>
                    <h1 style='color: #4a90e2; font-size: 36px; text-align: center; letter-spacing: 8px; margin: 20px 0;'>$otp</h1>
                    <p style='color: #555; font-size: 14px; text-align: center;'>
                      If you didn’t attempt to log in, please change your password immediately.
                    </p>
                " . $template_end;
                break;

            case 'password_reset':
                $mail->Subject = 'DentalScape - Password Reset Request';
                $mail->Body = $template_start . "
                    <h2 style='color: #4a90e2; text-align: center;'>Password Reset Request</h2>
                    <p style='color: #333; font-size: 15px; text-align: center;'>
                      We received a request to reset your password.<br>
                      Use the following verification code to proceed:
                    </p>
                    <h1 style='color: #4a90e2; font-size: 36px; text-align: center; letter-spacing: 8px; margin: 20px 0;'>$otp</h1>
                    <p style='color: #555; font-size: 14px; text-align: center;'>
                      If you didn’t request a password reset, please ignore this email<br>
                      or contact <a href='#' style='color: #4a90e2; text-decoration: none;'>EWAR Support</a>.
                    </p>
                " . $template_end;
                break;
        }
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $e->getMessage());
        return false;
    }
}


function createOtp($conn, $user_id, $email, $type) {
    $digits = '0123456789';
    $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters = $digits . $letters;
    $otp_length = 6;

    $otp = '';
    $otp .= $digits[random_int(0, strlen($digits) - 1)];
    $otp .= $letters[random_int(0, strlen($letters) - 1)];

    for ($i = 2; $i < $otp_length; $i++) {
        $otp .= $characters[random_int(0, strlen($characters) - 1)];
    }

    $otp = str_shuffle($otp);

    $otp_expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));
    $conn->begin_transaction();

    try {
        $invalidate_stmt = $conn->prepare("UPDATE otp_history 
            SET is_used = 1 
            WHERE user_id = ? AND otp_type = ? AND is_used = 0");
        $invalidate_stmt->bind_param("is", $user_id, $type);
        $invalidate_stmt->execute();

        $stmt = $conn->prepare("INSERT INTO otp_history 
            (user_id, otp, otp_type, otp_expiry, created_at) 
            VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("isss", $user_id, $otp, $type, $otp_expiry);

        if ($stmt->execute() && sendOtpEmail($email, $otp, $type)) {
            $conn->commit();
            return true;
        }

        $conn->rollback();
        return false;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("OTP creation failed: " . $e->getMessage());
        return false;
    }
}



function verifyOtp($conn, $email, $otp, $type) {
    error_log("Verifying OTP - Email: $email, Type: $type");
    
    $stmt = $conn->prepare("
        SELECT 
            u.user_id, 
            u.fullname, 
            o.otp_history_id, 
            o.otp_expiry,
            o.is_used,
            TIMESTAMPDIFF(MINUTE, NOW(), o.otp_expiry) as minutes_remaining
        FROM users u 
        JOIN otp_history o ON u.user_id = o.user_id 
        WHERE u.email = ? 
        AND o.otp = ? 
        AND o.otp_type = ? 
        AND o.is_used = 0 
        AND o.otp_expiry > NOW()
        ORDER BY o.created_at DESC 
        LIMIT 1
    ");
    
    $stmt->bind_param("sss", $email, $otp, $type);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {

        $check_stmt = $conn->prepare("
            SELECT 
                o.otp,
                o.otp_type,
                o.is_used,
                o.otp_expiry,
                TIMESTAMPDIFF(MINUTE, NOW(), o.otp_expiry) as minutes_remaining
            FROM users u 
            JOIN otp_history o ON u.user_id = o.user_id 
            WHERE u.email = ? 
            ORDER BY o.created_at DESC 
            LIMIT 1
        ");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($row = $check_result->fetch_assoc()) {
            error_log("OTP verification failed. Details: " . print_r($row, true));
        }
    }
    
    return $result;
}

if (isset($_POST['forgot_password'])) {
    $email = trim($_POST['email']);
    
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND is_verified = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['verification_type'] = 'password_reset'; 
        if (createOtp($conn, $user['user_id'], $email, 'password_reset')) {
            $_SESSION['verify_email'] = $email;
            $_SESSION['password_reset'] = true;
            header("Location: verify-otp");
            exit();
        }
    }
    
    $_SESSION['error'] = "The email you entered is not registered. Please try again.";
    header("Location: forgot-password");
    exit();
}

function getClientIP() {
    $ip = 'UNKNOWN';
    $ipKeys = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ];

    foreach ($ipKeys as $key) {
        if (!empty($_SERVER[$key])) {
            $ipList = explode(',', $_SERVER[$key]);
            foreach ($ipList as $ipEntry) {
                $ipEntry = trim($ipEntry);
                if (filter_var($ipEntry, FILTER_VALIDATE_IP)) {
                    $ip = $ipEntry;
                    break 2;
                }
            }
        }
    }

    return ($ip === '::1') ? '127.0.0.1' : $ip;
}


function getDeviceInfo() {
    $agent = new Agent();

    $device = $agent->device(); // e.g., iPhone
    $platform = $agent->platform(); // e.g., Windows
    $platformVersion = $agent->version($platform);
    $browser = $agent->browser(); // e.g., Chrome
    $browserVersion = $agent->version($browser);

    return "$device | $platform $platformVersion | $browser $browserVersion";
}


if (isset($_POST['reset_password'])) {
    if (!isset($_SESSION['verify_email']) || !isset($_SESSION['password_reset'])) {
        header("Location: forgot-password");
        exit();
    }

    $email = $_SESSION['verify_email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password format
    $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    if (!preg_match($password_pattern, $new_password)) {
        $_SESSION['error'] = "Password must contain at least 8 characters, one uppercase, one lowercase, one number, and one special character.";
        header("Location: reset_password.php");
        exit();
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: reset_password.php");
        exit();
    }

    $conn->begin_transaction();
    try {
        // Hash and update password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $update_stmt->bind_param("ss", $hashed_password, $email);

        if (!$update_stmt->execute()) {
            throw new Exception("Failed to update password.");
        }

        // Get user_id
        $user_stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $user_stmt->bind_param("s", $email);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();
        $user = $user_result->fetch_assoc();
        $user_id = $user['user_id'];

        // Mark OTP as used
        $clear_otp_stmt = $conn->prepare("
            UPDATE otp_history oh
            JOIN users u ON oh.user_id = u.user_id
            SET oh.is_used = 1
            WHERE u.email = ? AND oh.otp_type = 'password_reset'
        ");
        $clear_otp_stmt->bind_param("s", $email);
        $clear_otp_stmt->execute();

        // Log password reset event
        $ip_address = getClientIP();
        $device_info = getDeviceInfo();
        $log_stmt = $conn->prepare("
            INSERT INTO user_password_history (user_id, change_type, ip_address, device_info)
            VALUES (?, 'reset', ?, ?)
        ");
        $log_stmt->bind_param("iss", $user_id, $ip_address, $device_info);
        $log_stmt->execute();

        // Commit transaction
        $conn->commit();

        // Clear session
        unset($_SESSION['verify_email']);
        unset($_SESSION['password_reset']);
        unset($_SESSION['verification_type']);

        $_SESSION['success'] = "Password reset successfully. Please login with your new password.";
        header("Location: login");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Password reset failed: " . $e->getMessage());
        $_SESSION['error'] = "Failed to reset password. Please try again.";
        header("Location: reset_password.php");
        exit();
    }
}

if (isset($_POST['verify_otp'])) {
    $email = $_SESSION['verify_email'];
    $otp = $_POST['otp'];
    
    $type = $_SESSION['verification_type'] ?? 'signup';
    
    error_log("Verification attempt - Type: $type, Email: $email");
    
    $stmt = $conn->prepare("
        SELECT u.user_id, u.fullname, o.otp_history_id, o.otp_expiry
        FROM users u 
        JOIN otp_history o ON u.user_id = o.user_id 
        WHERE u.email = ? 
        AND o.otp = ? 
        AND o.otp_type = ? 
        AND o.is_used = 0 
        AND o.otp_expiry > NOW()
        ORDER BY o.created_at DESC 
        LIMIT 1
    ");
    
    $stmt->bind_param("sss", $email, $otp, $type);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        $conn->begin_transaction();
        
        try {
            $update_stmt = $conn->prepare("UPDATE otp_history SET is_used = 1 WHERE otp_history_id = ?");
            $update_stmt->bind_param("i", $user['otp_history_id']);
            $update_stmt->execute();
            
            switch($type) {
                case 'login':
                    // Removed duplicate login history insertion here
                    
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['fullname'] = $user['fullname'];
                    
                    unset($_SESSION['login_verification']);
                    unset($_SESSION['verify_email']);
                    unset($_SESSION['verification_type']);
                    
                    $conn->commit();
                    header("Location: /dentalscape/dashboard/");
                    break;
                    
                case 'signup':
                    $update_stmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE user_id = ?");
                    $update_stmt->bind_param("i", $user['user_id']);
                    $update_stmt->execute();
                    
                    unset($_SESSION['verify_email']);
                    unset($_SESSION['verification_type']);
                    
                    $conn->commit();
                    $_SESSION['success'] = "Account verified successfully. Please login.";
                    header("Location: login");
                    break;
                    
                case 'password_reset':
                    $conn->commit();
                    header("Location: reset_password.php");
                    break;
            }
            exit();
            
        } catch (Exception $e) {
            $conn->rollback();
            error_log("Verification failed: " . $e->getMessage());
            $_SESSION['error'] = "Verification failed. Please try again.";
            header("Location: verify-otp");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid or expired OTP. Please check the code or request a new one.";
        header("Location: verify-otp");
        exit();
    }
}


// Handle Signup
if (isset($_POST['signup'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    if (!preg_match($password_pattern, $password)) {
        $_SESSION['error'] = "Password must contain at least 8 characters, one uppercase, one lowercase, one number and one special character";
        header("Location: login");
        exit();
    }
    
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match";
        header("Location: login");
        exit();
    }
    
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['error'] = "Email already exists";
        header("Location: login");
        exit();
    }
    
    $conn->begin_transaction();
    
    try {

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, is_verified) VALUES (?, ?, ?, 0)");
        $stmt->bind_param("sss", $fullname, $email, $hashed_password);
        
        if ($stmt->execute()) {
            $user_id = $conn->insert_id;
            
            $_SESSION['verify_email'] = $email;
            $_SESSION['verification_type'] = 'signup';
            $_SESSION['pending_verification'] = true;
            
            if (createOtp($conn, $user_id, $email, 'signup')) {
                $conn->commit();
                $_SESSION['success'] = "Account created successfully. Please verify your email.";
                header("Location: verify-otp");
                exit();
            } else {
                throw new Exception("Failed to create OTP");
            }
        } else {
            throw new Exception("Failed to insert user");
        }
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Signup failed: " . $e->getMessage());
        $_SESSION['error'] = "Registration failed. Please try again.";
        header("Location: login");
        exit();
    }
}

//Login attempts
function checkLoginAttempts($conn, $user_id) {
    $stmt = $conn->prepare("
        SELECT COUNT(*) as attempt_count 
        FROM login_history 
        WHERE status = 'failed' 
        AND user_id = ? 
        AND login_datetime > DATE_SUB(NOW(), INTERVAL 1 MINUTE)
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['attempt_count'];
}


function recordLockout($conn, $user_id, $ip_address, $device_info, $latitude, $longitude, $neighbourhood, $municipality, $lockout_type = '1min') {
    // Prevent duplicate lockout entries within 1 minute
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS recent 
        FROM lockout_history 
        WHERE user_id = ? 
        AND lockout_time > DATE_SUB(NOW(), INTERVAL 1 MINUTE)
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['recent'] == 0) {
        $stmt = $conn->prepare("
            INSERT INTO lockout_history 
            (user_id, ip_address, device_info, latitude, longitude, neighbourhood, municipality, lockout_type) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("isssssss", $user_id, $ip_address, $device_info, $latitude, $longitude, $neighbourhood, $municipality, $lockout_type);
        $stmt->execute();
    }
}



function isUserLockedOut($conn, $user_id) {
    $stmt = $conn->prepare("
        SELECT lockout_time 
        FROM lockout_history 
        WHERE user_id = ? 
        ORDER BY lockout_time DESC 
        LIMIT 1
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $lockout_time = strtotime($row['lockout_time']);
        if (time() - $lockout_time < 3600) { // 1 hour lock
            return true;
        }
    }
    return false;
}

function countRecentLockouts($conn, $user_id) {
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total 
        FROM lockout_history 
        WHERE user_id = ? 
        AND lockout_time > DATE_SUB(NOW(), INTERVAL 10 MINUTE)
        AND lockout_type = '1min'
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'];
}


if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $device_info = getDeviceInfo();
    $ip_address = getClientIP();

    // Get location data
    $latitude = $_POST['latitude'] ?? '';
    $longitude = $_POST['longitude'] ?? '';
    $neighbourhood = $_POST['neighbourhood'] ?? '';
    $municipality = $_POST['municipality'] ?? '';

    // Block login if location is missing
    if (empty($latitude) || empty($longitude) || !is_numeric($latitude) || !is_numeric($longitude)) {
        $_SESSION['error'] = "You must enable location services and allow location access to login.";
        header("Location: login");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // 🚫 Check if user is blocked
        if (isset($user['status']) && $user['status'] === 'blocked') {
            $_SESSION['error'] = "Your account has been blocked. Please contact the administrator.";
            header("Location: login");
            exit();
        }

        // 🚫 Check if user is marked as deleted
        if (isset($user['status']) && $user['status'] === 'deleted') {
            $_SESSION['error'] = "Your account has been deleted. Please contact the administrator for assistance.";
            header("Location: login");
            exit();
        }

        // Check for active 1-hour lockout
        if (isUserLockedOut($conn, $user['user_id'])) {
            $stmt = $conn->prepare("
                SELECT COUNT(*) AS recent 
                FROM lockout_history 
                WHERE user_id = ? AND lockout_type = '1hr'
                AND lockout_time > DATE_SUB(NOW(), INTERVAL 1 MINUTE)
            ");
            $stmt->bind_param("i", $user['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['recent'] == 0) {
                recordLockout($conn, $user['user_id'], $ip_address, $device_info, $latitude, $longitude, $neighbourhood, $municipality, '1hr');
            }

            $_SESSION['error'] = "Your account is locked for 1 hour due to too many failed attempts.";
            header("Location: login");
            exit();
        }

        // Check 1-minute threshold
        $attempts = checkLoginAttempts($conn, $user['user_id']);
        if ($attempts >= 5) {
            $recentLockouts = countRecentLockouts($conn, $user['user_id']);
            $lockoutType = ($recentLockouts >= 3) ? '1hr' : '1min';

            recordLockout($conn, $user['user_id'], $ip_address, $device_info, $latitude, $longitude, $neighbourhood, $municipality, $lockoutType);

            $_SESSION['error'] = ($lockoutType === '1hr')
                ? "Your account is locked for 1 hour due to repeated failed attempts."
                : "Account is temporarily locked. Please try again after 1 minute.";
            header("Location: login");
            exit();
        }

        if (password_verify($password, $user['password'])) {
            if ($user['is_verified'] == 0) {
                $_SESSION['verification_type'] = 'signup';
                if (createOtp($conn, $user['user_id'], $email, 'signup')) {
                    $_SESSION['verify_email'] = $email;
                    $_SESSION['error'] = "Your account is not verified. Please verify your email to continue.";
                    header("Location: verify-otp");
                    exit();
                }
                $_SESSION['error'] = "Failed to send verification email. Please try again.";
                header("Location: login");
                exit();
            }

            // ✅ Successful login log
            $stmt = $conn->prepare("
                INSERT INTO login_history 
                (user_id, ip_address, device_info, status, login_datetime, latitude, longitude, neighbourhood, municipality)
                VALUES (?, ?, ?, 'success', NOW(), ?, ?, ?, ?)
            ");
            $stmt->bind_param("issssss", $user['user_id'], $ip_address, $device_info, $latitude, $longitude, $neighbourhood, $municipality);
            $stmt->execute();

            // OTP Verification
            if (createOtp($conn, $user['user_id'], $email, 'login')) {
                $_SESSION['verify_email'] = $email;
                $_SESSION['verification_type'] = 'login';
                $_SESSION['login_verification'] = true;
                header("Location: verify-otp");
                exit();
            } else {
                $_SESSION['error'] = "Failed to send verification code. Please try again.";
                header("Location: login");
                exit();
            }
        } else {
            // ❌ Failed login log
            $stmt = $conn->prepare("
                INSERT INTO login_history 
                (user_id, ip_address, device_info, status, login_datetime, latitude, longitude, neighbourhood, municipality)
                VALUES (?, ?, ?, 'failed', NOW(), ?, ?, ?, ?)
            ");
            $stmt->bind_param("issssss", $user['user_id'], $ip_address, $device_info, $latitude, $longitude, $neighbourhood, $municipality);
            $stmt->execute();

            $_SESSION['error'] = "Invalid email or password.";
            header("Location: login");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: login");
        exit();
    }
}

// Handle Resend OTP
if (isset($_POST['resend_otp'])) {
    $email = $_SESSION['verify_email'];
    
    $type = isset($_SESSION['verification_type']) ? $_SESSION['verification_type'] : 'signup';
    
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (createOtp($conn, $user['user_id'], $email, $type)) {
            $_SESSION['success'] = "New OTP sent successfully. Please check your email.";
        } else {
            $_SESSION['error'] = "Failed to send new OTP. Please try again.";
        }
    } else {
        $_SESSION['error'] = "Email not found";
    }
    
    header("Location: verify-otp");
    exit();
}
?>