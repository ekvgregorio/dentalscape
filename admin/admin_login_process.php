<?php
session_start();
require '../conn.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
use Jenssegers\Agent\Agent;

function sendOtpEmail($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        // SMTP setup
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dentalscape.iloilo.services@gmail.com';
        $mail->Password = 'etvk bish lrxg fpcz'; // Use App Password or secure storage
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender info
        $mail->setFrom('dentalscape.iloilo.services@gmail.com', 'DentalScape Iloilo');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'DentalScape Iloilo - Admin Login OTP';

        // --- Unified email design ---
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

        // --- Email body ---
        $mail->Body = $template_start . "
            <h2 style='color: #4a90e2; text-align: center;'>Admin Login Verification</h2>
            <p style='color: #333; font-size: 15px; text-align: center;'>
              A login attempt was detected on your administrator account.<br>
              Please use the following OTP code to verify your access:
            </p>
            <h1 style='
                color: #4a90e2;
                font-size: 36px;
                text-align: center;
                letter-spacing: 8px;
                margin: 20px 0;
            '>$otp</h1>
            <p style='color: #555; font-size: 14px; text-align: center;'>
              If you didn’t attempt to log in, please contact <a href='#' style='color: #4a90e2; text-decoration: none;'>EWAR Support</a> immediately.
            </p>
        " . $template_end;

        // Send mail
        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Mail Error: " . $e->getMessage());
        return false;
    }
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

// Admin Login
if (isset($_POST['admin_login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $ip_address = getClientIP();
    $device_info = getDeviceInfo();
    $admin_id = null;

    $latitude = !empty($_POST['latitude']) ? $_POST['latitude'] : null;
    $longitude = !empty($_POST['longitude']) ? $_POST['longitude'] : null;
    $neighbourhood = !empty($_POST['neighbourhood']) ? $_POST['neighbourhood'] : null;
    $municipality = !empty($_POST['municipality']) ? $_POST['municipality'] : null;

    $stmt = $conn->prepare("SELECT id, password, failed_attempts, lockout_until, role FROM admin WHERE email = ?");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        $_SESSION['error'] = "System error occurred. Please try again later.";
        header("Location: /dentalscape/admin/");
        exit();
    }

    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        $_SESSION['error'] = "System error occurred. Please try again later.";
        header("Location: /dentalscape/admin/");
        exit();
    }

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        $admin_id = $admin['id'];
        $failed_attempts = $admin['failed_attempts'];
        $lockout_until = $admin['lockout_until'];
        $role = $admin['role'];  

        // Check if account is locked
        if ($lockout_until !== null && strtotime($lockout_until) > time()) {
            $_SESSION['error'] = "Too many failed attempts. Try again later.";
            header("Location: /dentalscape/admin/");
            exit();
        }

        if (password_verify($password, $admin['password'])) {
            // Reset failed attempts on successful login
            $reset_stmt = $conn->prepare("UPDATE admin SET failed_attempts = 0, lockout_until = NULL WHERE id = ?");
            if ($reset_stmt) {
                $reset_stmt->bind_param("i", $admin_id);
                $reset_stmt->execute();
            }

            $_SESSION['role'] = $role;

            // Generate alphanumeric OTP
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $otp = '';
            for ($i = 0; $i < 6; $i++) {
                $otp .= $characters[random_int(0, strlen($characters) - 1)];
            }

            $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));


            // Store OTP
            $otp_stmt = $conn->prepare("INSERT INTO admin_otp_history (admin_id, otp, otp_expiry, is_used, created_at) VALUES (?, ?, ?, 0, NOW())");
            if ($otp_stmt) {
                $otp_stmt->bind_param("iss", $admin_id, $otp, $expiry);
                $otp_stmt->execute();
            }

            // Send OTP
            if (sendOtpEmail($email, $otp)) {
                $_SESSION['admin_verify_email'] = $email;
                $_SESSION['admin_id'] = $admin_id;
                $_SESSION['admin_verification_type'] = 'login';
                $_SESSION['admin_login_verification'] = true;
                session_regenerate_id(true);

                // Log success
                $log_stmt = $conn->prepare("INSERT INTO admin_login_history 
                    (admin_id, ip_address, device_info, latitude, longitude, neighbourhood, municipality, status, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'success', NOW())");

                if ($log_stmt) {
                    $log_stmt->bind_param("issddss", $admin_id, $ip_address, $device_info, $latitude, $longitude, $neighbourhood, $municipality);
                    $log_stmt->execute();
                }

                header("Location: /dentalscape/verify-admin-otp/");
                exit();
            } else {
                $_SESSION['error'] = "Failed to send OTP.";
            }
        } else {
            // Wrong password
            $failed_attempts++;
            $lockout_time = ($failed_attempts >= 5) ? date('Y-m-d H:i:s', strtotime('+5 minutes')) : NULL;

            $update_stmt = $conn->prepare("UPDATE admin SET failed_attempts = ?, lockout_until = ? WHERE id = ?");
            if ($update_stmt) {
                $update_stmt->bind_param("isi", $failed_attempts, $lockout_time, $admin_id);
                $update_stmt->execute();
            }

            $_SESSION['error'] = "Invalid credentials.";
        }
    } else {
        $_SESSION['error'] = "Invalid credentials.";
    }

    // Log failed login
    $log_stmt = $conn->prepare("INSERT INTO admin_login_history 
        (admin_id, ip_address, device_info, latitude, longitude, neighbourhood, municipality, status, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'failed', NOW())");

    if ($log_stmt) {
        $log_stmt->bind_param("issddss", $admin_id, $ip_address, $device_info, $latitude, $longitude, $neighbourhood, $municipality);
        $log_stmt->execute();
    }

    header("Location: /dentalscape/admin/");
    exit();
}

if (isset($_POST['verify_admin_otp'])) {
    $otp = $_POST['otp'];
    $admin_id = $_SESSION['admin_id'];

    $stmt = $conn->prepare("SELECT otp_history_id FROM admin_otp_history WHERE admin_id = ? AND otp = ? AND is_used = 0 AND otp_expiry > NOW()");
    if ($stmt === false) {
        error_log("Prepare failed: " . $conn->error);
        $_SESSION['error'] = "System error occurred. Please try again later.";
        header("Location: /dentalscape/verify-admin-otp/");
        exit();
    }

    $stmt->bind_param("is", $admin_id, $otp);
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        $_SESSION['error'] = "System error occurred. Please try again later.";
        header("Location: /dentalscape/verify-admin-otp/");
        exit();
    }

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        $update_stmt = $conn->prepare("UPDATE admin_otp_history SET is_used = 1 WHERE otp_history_id = ?");
        if ($update_stmt === false) {
            error_log("Prepare failed: " . $conn->error);
            $_SESSION['error'] = "System error occurred. Please try again later.";
            header("Location: /dentalscape/verify-admin-otp/");
            exit();
        }

        $update_stmt->bind_param("i", $row['otp_history_id']);
        $update_stmt->execute();

        $_SESSION['admin_logged_in'] = true;
        session_regenerate_id(true);
        header("Location: /dentalscape/admin-dashboard/");
        exit();
    } else {
        $_SESSION['error'] = "Invalid OTP.";
        header("Location: /dentalscape/verify-admin-otp/");
        exit();
    }
}

function getAllAdmins($conn) {
    $query = "SELECT id, email, role, created_at FROM admin ORDER BY created_at DESC";
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

$limit = 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); 
$offset = ($page - 1) * $limit;

$status = isset($_GET['status']) ? $_GET['status'] : ''; 

// Fetch total records (with optional status filter)
$total_records_query = "SELECT COUNT(*) as total FROM admin_login_history";
if (!empty($status)) {
    $total_records_query .= " WHERE status = ?";
}
$total_stmt = $conn->prepare($total_records_query);

if (!empty($status)) {
    $total_stmt->bind_param("s", $status);
}
$total_stmt->execute();
$total_result = $total_stmt->get_result()->fetch_assoc();
$total_records = $total_result['total'];
$totalPages = ceil($total_records / $limit);

// Fetch login history
$query = "SELECT admin_login_history.*, admin.email FROM admin_login_history 
          JOIN admin ON admin_login_history.admin_id = admin.id";
if (!empty($status)) {
    $query .= " WHERE admin_login_history.status = ?";
}
$query .= " ORDER BY admin_login_history.created_at DESC LIMIT ? OFFSET ?";

$stmt = $conn->prepare($query);

if (!empty($status)) {
    $stmt->bind_param("sii", $status, $limit, $offset);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
$login_history = $result->fetch_all(MYSQLI_ASSOC);


// Get total records for pagination
function getTotalLoginHistoryCount($conn) {
    $query = "SELECT COUNT(*) as total FROM admin_login_history";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['total'];
}

// ===============================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_admin'])){
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    if (!in_array($role, ['admin', 'doctor', 'secretary'])) {
        $_SESSION['error'] = "Invalid role selected.";
        header("Location: admin_employee_management.php");
        exit();
    }

    $check_stmt = $conn->prepare("SELECT id FROM admin WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $_SESSION['error'] = "Email already exists.";
        header("Location: admin_employee_management.php");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO admin (email, password, role) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sss", $email, $password, $role);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Admin added successfully!";
        } else {
            $_SESSION['error'] = "Error adding admin.";
        }
    } else {
        $_SESSION['error'] = "Database error: " . $conn->error;
    }

    header("Location: admin_employee_management.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_admin'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM admin WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Admin deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting admin.";
    }

    header("Location: /dentalscape/employee-management/");
    exit();
}

?>

