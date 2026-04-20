<?php
session_start();
require '../conn.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ====== Ensure user is logged in ======
if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('Access denied. Please log in.');
            window.location.href = '../login.php';
          </script>";
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$user_id = $_SESSION['user_id'];

// ===== Fetch Full Name of User =====
$sql = "SELECT fullname FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($fullname);
$stmt->fetch();
$stmt->close();

$_SESSION['fullname'] = $fullname;

function isValidAppointmentDate($date) {
    $appointment_date = new DateTime($date);
    $today = new DateTime();
    $interval = $today->diff($appointment_date);
    return $interval->days >= 2;
}

// ===== Booking Appointment =====
if (isset($_POST['book_appointment'])) {
    $full_name = trim($_POST['full_name']);
    $gender = trim($_POST['gender']);
    $birthdate = trim($_POST['birthdate']);
    $contact_number = trim($_POST['contact_number']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $purpose_of_appointment = trim($_POST['purpose_of_appointment']);
    $doctor = trim($_POST['doctor']);
    $appointment_date = trim($_POST['appointment_date']);
    $appointment_time = trim($_POST['appointment_time']);
    $service_type = trim($_POST['service_type']);
    $special_requests = trim($_POST['special_requests']);
    $user_id = $_SESSION['user_id']; 

    if (!isValidAppointmentDate($appointment_date)) {
        $_SESSION['error'] = "Appointments must be scheduled at least 2 days in advance.";
        header("Location: book_appointments.php");
        exit();
    }

    $appointmentDateObj = new DateTime($appointment_date);
    $startOfWeek = clone $appointmentDateObj;
    $startOfWeek->modify('monday this week');
    $endOfWeek = clone $appointmentDateObj;
    $endOfWeek->modify('sunday this week');
    $start_date = $startOfWeek->format('Y-m-d');
    $end_date = $endOfWeek->format('Y-m-d');

    $check_sql = "SELECT COUNT(*) as total 
                  FROM appointments 
                  WHERE user_id = ? 
                  AND appointment_date BETWEEN ? AND ? 
                  AND status IN ('Pending', 'Approved')";

    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("iss", $user_id, $start_date, $end_date);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $check_row = $check_result->fetch_assoc();
    $check_stmt->close();

    if ($check_row['total'] >= 5) {
        $_SESSION['error'] = "You already have 5 appointments booked for that week.";
        header("Location: book_appointments.php");
        exit();
    }

    $slot_check_sql = "SELECT COUNT(*) FROM appointments 
                       WHERE appointment_date = ? AND appointment_time = ?";
    $slot_stmt = $conn->prepare($slot_check_sql);
    $slot_stmt->bind_param("ss", $appointment_date, $appointment_time);
    $slot_stmt->execute();
    $slot_stmt->bind_result($count);
    $slot_stmt->fetch();
    $slot_stmt->close();

    if ($count > 0) {
        $_SESSION['error'] = "This time slot is already booked. Please choose another.";
        header("Location: /dentalscape/booking/");
        exit();
    }

    $sql = "INSERT INTO appointments 
            (user_id, full_name, gender, birthdate, contact_number, email, address, 
            purpose_of_appointment, doctor, appointment_date, appointment_time, service_type, 
            special_requests, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("issssssssssss", 
            $user_id, $full_name, $gender, $birthdate, $contact_number, $email, 
            $address, $purpose_of_appointment,$doctor, $appointment_date, $appointment_time, 
            $service_type, $special_requests);

            // Appoinment Logs
            if ($stmt->execute()) {
            $appointment_id = $stmt->insert_id;

            $log_sql = "INSERT INTO appointment_logs 
                        (appointment_id, user_id, action, log_date) 
                        VALUES (?, ?, ?, NOW())";
            $log_stmt = $conn->prepare($log_sql);
            $action = "Booked Appointment";
            $log_stmt->bind_param("iis", $appointment_id, $user_id, $action);
            $log_stmt->execute();
            $log_stmt->close();

            $_SESSION['success'] = "Appointment booked successfully!";
            header("Location: /dentalscape/booking/");
            exit();
        } else {
            $_SESSION['error'] = "Error booking appointment: " . $stmt->error;
            header("Location: /dentalscape/booking/");
            exit();
        }

        if ($stmt->execute()) {
            $_SESSION['success'] = "Appointment booked successfully!";
            header("Location: /dentalscape/booking/");
            exit();
        } else {
            $_SESSION['error'] = "Error booking appointment: " . $stmt->error;
            header("Location: /dentalscape/booking/");
            exit();
        }

        $stmt->close();
    }
}

// ===== Appointment by Month Status ======
function getAppointmentsByStatus($conn, $user_id, $status, $month_range = null) {
    $month_ranges = [
        'jan-mar' => ['01-01', '03-31'],
        'mar-jun' => ['03-01', '06-30'],
        'jun-aug' => ['06-01', '08-31'],
        'aug-nov' => ['08-01', '11-30'],
        'default' => ['01-01', '12-31']
    ];

    $start_month = $month_ranges[$month_range][0] ?? '01-01';
    $end_month = $month_ranges[$month_range][1] ?? '12-31';

    $query = "SELECT id, purpose_of_appointment, appointment_date, appointment_time, 
              service_type, special_requests, status, created_at
              FROM appointments
              WHERE user_id = ? 
              AND status = ?
              AND appointment_date BETWEEN CONCAT(YEAR(CURDATE()), '-', ?) 
              AND CONCAT(YEAR(CURDATE()), '-', ?)
              ORDER BY appointment_date ASC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $user_id, $status, $start_month, $end_month);
    $stmt->execute();
    return $stmt->get_result();
}

// ====== Rechedule and Cancel Appointment ======
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = intval($_POST['id']);
    $user_id = $_SESSION['user_id'];

    $verify_sql = "SELECT appointment_date, status FROM appointments WHERE id = ? AND user_id = ?";
    $verify_stmt = $conn->prepare($verify_sql);
    $verify_stmt->bind_param("ii", $id, $user_id);
    $verify_stmt->execute();
    $result = $verify_stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Invalid appointment";
        exit();
    }

    $appointment = $result->fetch_assoc();

    if (!isValidAppointmentDate($appointment['appointment_date'])) {
        echo "Cannot modify appointments within 2 days of scheduled date";
        exit();
    }

    if ($action == "reschedule" && isset($_POST['new_date'], $_POST['new_time'])) {
        $new_date = trim($_POST['new_date']);
        $new_time = trim($_POST['new_time']);

        if (!isValidAppointmentDate($new_date)) {
            echo "New appointment date must be at least 2 days in advance";
            exit();
        }

        $new_date_obj = new DateTime($new_date);
        $start_of_week = clone $new_date_obj;
        $start_of_week->modify('monday this week');
        $start_date = $start_of_week->format('Y-m-d');

        $end_of_week = clone $new_date_obj;
        $end_of_week->modify('sunday this week');
        $end_date = $end_of_week->format('Y-m-d');

        $limit_check_sql = "SELECT COUNT(*) as total 
                            FROM appointments 
                            WHERE user_id = ? 
                            AND id != ? 
                            AND appointment_date BETWEEN ? AND ? 
                            AND status IN ('Pending', 'Approved')";
        $limit_stmt = $conn->prepare($limit_check_sql);
        $limit_stmt->bind_param("iiss", $user_id, $id, $start_date, $end_date);
        $limit_stmt->execute();
        $limit_result = $limit_stmt->get_result();
        $limit_row = $limit_result->fetch_assoc();
        $limit_stmt->close();

        if ($limit_row['total'] >= 5) {
            echo "You already have 5 appointments booked for that week.";
            exit();
        }

        $check_sql = "SELECT COUNT(*) FROM appointments WHERE appointment_date = ? AND appointment_time = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $new_date, $new_time);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            echo "This time slot is already booked. Please choose another.";
            exit();
        }
        
        $update_sql = "UPDATE appointments 
                       SET appointment_date = ?, 
                           appointment_time = ?, 
                           status = 'Pending',
                           last_modified = NOW() 
                       WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssii", $new_date, $new_time, $id, $user_id);

        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "Error rescheduling appointment";
        }
        $stmt->close();

    } elseif ($action == "cancel") {
        $cancel_sql = "UPDATE appointments 
                       SET status = 'Canceled',
                           last_modified = NOW() 
                       WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($cancel_sql);
        $stmt->bind_param("ii", $id, $user_id);

        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "Error cancelling appointment";
        }
        $stmt->close();
    }
}
  


function getTotalAppointmentsByStatus($conn, $user_id, $status, $month_range = null) {
    $month_ranges = [
        'jan-mar' => ['01-01', '03-31'],
        'mar-jun' => ['03-01', '06-30'],
        'jun-aug' => ['06-01', '08-31'],
        'aug-nov' => ['08-01', '11-30'],
        'default' => ['01-01', '12-31']
    ];

    $start_month = $month_ranges[$month_range][0] ?? '01-01';
    $end_month = $month_ranges[$month_range][1] ?? '12-31';

    $query = "SELECT COUNT(*) as total 
              FROM appointments 
              WHERE user_id = ? 
              AND status = ?
              AND appointment_date BETWEEN CONCAT(YEAR(CURDATE()), '-', ?) 
              AND CONCAT(YEAR(CURDATE()), '-', ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $user_id, $status, $start_month, $end_month);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $row = $result->fetch_assoc();
    return $row ? $row['total'] : 0;
}

function getMonthRangeText($month_range) {
    $ranges = [
        'jan-mar' => 'January - March',
        'mar-jun' => 'March - June',
        'jun-aug' => 'June - August',
        'aug-nov' => 'August - November'
    ];
    return $ranges[$month_range] ?? 'January to December';
}

$month_range = $_GET['month_range'] ?? null;
$current_month_range_text = getMonthRangeText($month_range);

function getPendingAppointments($conn, $user_id, $month_range = null) {
    $month_ranges = [
        'jan-mar' => ['01-01', '03-31'],
        'mar-jun' => ['03-01', '06-30'],
        'jun-aug' => ['06-01', '08-31'],
        'aug-nov' => ['08-01', '11-30'],
        'default' => ['01-01', '12-31']
    ];

    $start_month = $month_ranges[$month_range][0] ?? '01-01';
    $end_month = $month_ranges[$month_range][1] ?? '12-31';

    $query = "SELECT id, purpose_of_appointment, doctor, appointment_date, appointment_time, 
              service_type, special_requests, status, created_at
              FROM appointments
              WHERE user_id = ? 
              AND status = 'Pending'
              AND appointment_date BETWEEN CONCAT(YEAR(CURDATE()), '-', ?) 
              AND CONCAT(YEAR(CURDATE()), '-', ?)
              ORDER BY appointment_date ASC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $start_month, $end_month);
    $stmt->execute();
    return $stmt->get_result();
}

// ===== Get Confirmed Appointments 
function getConfirmedAppointments($conn, $user_id, $month_range = null) {
    $month_ranges = [
        'jan-mar' => ['01-01', '03-31'],
        'mar-jun' => ['03-01', '06-30'],
        'jun-aug' => ['06-01', '08-31'],
        'aug-nov' => ['08-01', '11-30'],
        'default' => ['01-01', '12-31']
    ];

    $start_month = $month_ranges[$month_range][0] ?? '01-01';
    $end_month = $month_ranges[$month_range][1] ?? '12-31';

    $query = "SELECT id, purpose_of_appointment, doctor, appointment_date, appointment_time, 
              service_type, special_requests, status, created_at
              FROM appointments
              WHERE user_id = ? 
              AND status = 'Confirmed'
              AND appointment_date BETWEEN CONCAT(YEAR(CURDATE()), '-', ?) 
              AND CONCAT(YEAR(CURDATE()), '-', ?)
              ORDER BY appointment_date ASC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $start_month, $end_month);
    $stmt->execute();
    return $stmt->get_result();
}

function getTotalPendingAppointments($conn, $user_id, $month_range = null) {
    $month_ranges = [
        'jan-mar' => ['01-01', '03-31'],
        'mar-jun' => ['03-01', '06-30'],
        'jun-aug' => ['06-01', '08-31'],
        'aug-nov' => ['08-01', '11-30'],
        'default' => ['01-01', '12-31']
    ];

    $start_month = $month_ranges[$month_range][0] ?? '01-01';
    $end_month = $month_ranges[$month_range][1] ?? '12-31';

    $query = "SELECT COUNT(*) as total 
              FROM appointments 
              WHERE user_id = ? 
              AND status = 'Pending'
              AND appointment_date BETWEEN CONCAT(YEAR(CURDATE()), '-', ?) 
              AND CONCAT(YEAR(CURDATE()), '-', ?) ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $start_month, $end_month);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $row = $result->fetch_assoc();
    return $row ? $row['total'] : 0;
}

function getTotalConfirmedAppointments($conn, $user_id, $month_range = null) {
    $month_ranges = [
        'jan-mar' => ['01-01', '03-31'],
        'mar-jun' => ['03-01', '06-30'],
        'jun-aug' => ['06-01', '08-31'],
        'aug-nov' => ['08-01', '11-30'],
        'default' => ['01-01', '12-31']
    ];

    $start_month = $month_ranges[$month_range][0] ?? '01-01';
    $end_month = $month_ranges[$month_range][1] ?? '12-31';

    $query = "SELECT COUNT(*) as total 
              FROM appointments 
              WHERE user_id = ? 
              AND status = 'Confirmed'
              AND appointment_date BETWEEN CONCAT(YEAR(CURDATE()), '-', ?) 
              AND CONCAT(YEAR(CURDATE()), '-', ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $start_month, $end_month);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $row = $result->fetch_assoc();
    return $row ? $row['total'] : 0;
}

$checkQuery = "SELECT COUNT(*) FROM appointments WHERE appointment_date = ? AND appointment_time = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("ss", $appointment_date, $appointment_time);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count > 0) {
    $_SESSION['error'] = "Selected time slot is already booked. Please choose another.";
    header("Location: book_appointments.php");
    exit();
}


// Handle form submission - only process if POST request with form data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['birthdate'])) {
    $birthdate = $_POST['birthdate'] ?? null;
    $gender = $_POST['gender'] ?? '';
    $civil_status = $_POST['civil_status'] ?? '';
    $blood_type = $_POST['blood_type'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';
    $email = $_POST['email'] ?? '';
    $emergency_contact_name = $_POST['emergency_contact_name'] ?? '';
    $emergency_contact_number = $_POST['emergency_contact_number'] ?? '';
    $address = $_POST['address'] ?? '';

    // Parse address
    $barangay = $municipality = $province = $zip_code = '';
    if ($address) {
        $parts = array_map('trim', explode(',', $address));
        $barangay = $parts[0] ?? '';
        $municipality = $parts[1] ?? '';
        $province = $parts[2] ?? '';
        $zip_code = $parts[3] ?? '';
    }

    // Check if profile already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM profile WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($exists);
        $stmt->fetch();
        $stmt->close();

        // Perform UPDATE or INSERT
        if ($exists) {
            $stmt = $conn->prepare("UPDATE profile SET birthdate=?, gender=?, civil_status=?, blood_type=?, contact_number=?, email=?, emergency_contact_name=?, emergency_contact_number=?, barangay=?, municipality=?, province=?, zip_code=? WHERE user_id=?");
            if ($stmt) {
                $stmt->bind_param("ssssssssssssi", $birthdate, $gender, $civil_status, $blood_type, $contact_number, $email, $emergency_contact_name, $emergency_contact_number, $barangay, $municipality, $province, $zip_code, $user_id);
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO profile (user_id, birthdate, gender, civil_status, blood_type, contact_number, email, emergency_contact_name, emergency_contact_number, barangay, municipality, province, zip_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("issssssssssss", $user_id, $birthdate, $gender, $civil_status, $blood_type, $contact_number, $email, $emergency_contact_name, $emergency_contact_number, $barangay, $municipality, $province, $zip_code);
            }
        }

        if ($stmt && $stmt->execute()) {
            echo "success";
        } else {
            echo "Error: " . ($stmt ? $stmt->error : "Failed to prepare statement");
        }

        if ($stmt) {
            $stmt->close();
        }
    } else {
        echo "Error: Failed to prepare check statement";
    }
    
    $conn->close();
    exit(); 
}

//====== Account Management - Change Pasword =======
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "New passwords do not match.";
        header("Location: /dentalscape/account_management/");
        exit;
    }

    if (strlen($new_password) < 8 ||
        !preg_match('/[A-Z]/', $new_password) ||
        !preg_match('/[a-z]/', $new_password) ||
        !preg_match('/[0-9]/', $new_password) ||
        !preg_match('/[\W]/', $new_password)) {
        $_SESSION['error'] = "Password must be at least 8 characters and include uppercase, lowercase, number, and symbol.";
        header("Location: /dentalscape/account_management/");
        exit;
    }

    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current_password, $hashed_password)) {
        $_SESSION['error'] = "Current password is incorrect.";
        header("Location: /dentalscape/account_management/");
        exit;
    }

    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $stmt->bind_param("si", $new_hashed_password, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Password updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update password. Try again.";
    }

    $stmt->close();
    $conn->close();

    header("Location: /dentalscape/login"); 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_email'])) {
    $current_password = trim($_POST['current_password']);
    $new_email = trim($_POST['new_email']);
    $confirm_email = trim($_POST['confirm_email']);

    if ($new_email !== $confirm_email) {
        $_SESSION['error'] = "Email addresses do not match.";
        header("Location: /dentalscape/account_management/");
        exit();
    }

    // Verify password and get current email
    $stmt = $conn->prepare("SELECT password, email FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($db_password, $current_email);
    $stmt->fetch();
    $stmt->close();

    if (!$db_password || !password_verify($current_password, $db_password)) {
        $_SESSION['error'] = "Incorrect password.";
        header("Location: /dentalscape/account_management/");
        exit();
    }

    // Generate OTP
    $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    // Insert OTP into otp_history
    $stmt = $conn->prepare("
        INSERT INTO otp_history (user_id, otp, otp_type, otp_expiry)
        VALUES (?, ?, 'email_change', ?)
    ");
    $stmt->bind_param("iss", $user_id, $otp, $expiry);
    $stmt->execute();
    $stmt->close();

    // Store pending email & session info
    $_SESSION['pending_email'] = $new_email;
    $_SESSION['verify_email'] = $current_email; // OTP goes to current email
    $_SESSION['verification_type'] = 'email_change';

    // Send OTP via PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('dentalscape.iloilo.services@gmail.com', 'DentalScape');
        $mail->addAddress($current_email);
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email Change Request';
        $mail->Body = "
            <p>Dear user,</p>
            <p>Your OTP to confirm changing your email to <strong>{$new_email}</strong> is:</p>
            <h2 style='color:#1a1a76;'>{$otp}</h2>
            <p>This OTP will expire in 10 minutes.</p>
            <br><small>If you didn’t request this, please ignore this email.</small>
        ";
        $mail->send();

        $_SESSION['success'] = "An OTP has been sent to your current email for verification.";
        header("Location: account-verify.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Failed to send OTP: {$mail->ErrorInfo}";
        header("Location: /dentalscape/account_management/");
        exit();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {
    $entered_otp = trim($_POST['otp']);
    $email = $_SESSION['verify_email'] ?? '';

    if (!$email) {
        $_SESSION['error'] = "Session expired. Please try again.";
        header("Location: account_management.php");
        exit();
    }

    // Get user_id by current email
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        $_SESSION['error'] = "User not found.";
        header("Location: account_management.php");
        exit();
    }

    $user_id = $user['user_id'];

    // Fetch latest OTP reliably
    $stmt = $conn->prepare("
        SELECT otp, otp_expiry, is_used 
        FROM otp_history 
        WHERE user_id = ? AND otp_type = 'email_change'
        ORDER BY created_at DESC
        LIMIT 1
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $otp_row = $result->fetch_assoc();
    $stmt->close();

    if (!$otp_row) {
        $_SESSION['error'] = "No OTP found. Please request a new one.";
        header("Location: account_management.php");
        exit();
    }

    $db_otp = $otp_row['otp'];
    $otp_expiry = $otp_row['otp_expiry'];
    $is_used = $otp_row['is_used'];

    if ($is_used) {
        $_SESSION['error'] = "OTP already used.";
        header("Location: account_management.php");
        exit();
    }

    if (new DateTime() > new DateTime($otp_expiry)) {
        $_SESSION['error'] = "OTP expired. Please request a new one.";
        header("Location: account_management.php");
        exit();
    }

    if ($entered_otp !== $db_otp) {
        $_SESSION['error'] = "Invalid OTP entered.";
        header("Location: account-verify.php");
        exit();
    }

    // Mark OTP as used
    $stmt = $conn->prepare("UPDATE otp_history SET is_used = 1 WHERE user_id = ? AND otp = ?");
    $stmt->bind_param("is", $user_id, $db_otp);
    $stmt->execute();
    $stmt->close();

    // Update email
    $new_email = $_SESSION['pending_email'] ?? '';
    if (!$new_email) {
        $_SESSION['error'] = "Session expired. Please restart the email change process.";
        header("Location: account_management.php");
        exit();
    }

    $stmt = $conn->prepare("UPDATE users SET email = ? WHERE user_id = ?");
    $stmt->bind_param("si", $new_email, $user_id);
    $stmt->execute();
    $stmt->close();

    unset($_SESSION['pending_email'], $_SESSION['verify_email'], $_SESSION['verification_type']);
    $_SESSION['success'] = "Your email has been updated successfully!";
    header("Location: account_management.php");
    exit();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acc_action'])) {
    $user_id = $_SESSION['user_id']; // make sure user_id is stored in session
    $action = $_POST['acc_action'];

    if ($action === 'deactivate_acc') {
        $stmt = $conn->prepare("UPDATE users SET status = 'deactivated' WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            session_destroy();
            header("Location: /dentalscape/index?msg=" . urlencode("Account deactivated. You can reactivate by logging in."));
            exit;
        } else {
            $error = "Failed to deactivate account.";
        }
        $stmt->close();

    } elseif ($action === 'delete_acc') {
        $stmt = $conn->prepare("UPDATE users SET status = 'deleted' WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            session_destroy();
            header("Location: /dentalscape/index?msg=" . urlencode("Account marked as deleted. Waiting for admin confirmation."));
            exit;
        } else {
            $error = "Failed to mark account as deleted.";
        }
        $stmt->close();

    } else {
        $error = "Invalid action.";
    }

    if (isset($error)) {
        echo "<div class='alert alert-danger'>$error</div>";
    }
}




if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_help'])) {


    // Fetch email from database
    $stmt = $conn->prepare("SELECT email FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();

    if (!$email) {
        $_SESSION['error'] = "Unable to get your email. Please try again.";
        header("Location: /dentalscape/help-support/");
        exit();
    }

    // Form data
    $subject = htmlspecialchars($_POST['subject']);
    $category = htmlspecialchars($_POST['category']);
    $message = htmlspecialchars($_POST['message']);

    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Email headers
        $mail->setFrom($_ENV['MAIL_USERNAME'], 'DenstalScape Support'); 
        $mail->addReplyTo($email, $fullname); 
        $mail->addAddress('dentalscape.iloilo.services@gmail.com', 'DenstalScape Support');

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "DenstalScape Support $subject ($category)";
        $mail->Body = "
            <h3>New Support Ticket</h3>
            <p><strong>Name:</strong> {$fullname}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Category:</strong> {$category}</p>
            <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
        ";

        $mail->send();

        $_SESSION['success'] = "Support ticket submitted successfully.";
        header("Location: /dentalscape/help-support/");
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        header("Location: /dentalscape/help-support/");
        exit();
    }
}

?>

