<?php
session_start();
require '../conn.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../vendor/autoload.php';

function sendAppointmentEmail($to, $fullName, $status, $appointmentDetails) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD']; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('dentalscape.iloilo.services@gmail.com', 'DentalScape Iloilo');
        $mail->addAddress($to, $fullName);

        // Email subject
        $mail->isHTML(true);
        $mail->Subject = "Appointment Status Update";

        // Sanitize inputs
        $fullName = htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8');
        $status   = htmlspecialchars($status, ENT_QUOTES, 'UTF-8');

        // HTML Email Template
        $htmlBody = "
        <html>
        <body style='font-family: Arial, sans-serif; background-color: #f5f6fa; margin: 0; padding: 30px;'>
            <div style='max-width: 600px; margin: auto; background: #ffffff; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);'>
                
                <!-- Header -->
                <div style='text-align: center; border-bottom: 2px solid #0096c7; padding-bottom: 10px; margin-bottom: 20px;'>
                    <img src='../assets/user/img/login.png' alt='DentalScape Logo' width='80' style='margin-bottom: 10px;' />
                    <h2 style='color: #0096c7; margin: 0;'>DentalScape Iloilo</h2>
                    <p style='color: #555;'>Your Smile, Our Masterpiece</p>
                </div>

                <!-- Main Content -->
                <h3 style='color: #023e8a;'>Appointment Status Update</h3>
                <p>Dear <strong>{$fullName}</strong>,</p>
                <p>Your appointment has been <strong style='color: " . ($status === 'Confirmed' ? '#38b000' : ($status === 'Canceled' ? '#d00000' : '#f48c06')) . "; text-transform: capitalize;'>{$status}</strong>.</p>

                <div style='background: #f1faff; padding: 15px; border-left: 4px solid #0096c7; border-radius: 6px; margin: 15px 0;'>
                    <h4 style='margin-top: 0;'>Appointment Details</h4>
                    <ul style='list-style-type: none; padding-left: 0;'>
                        <li><strong>Date:</strong> " . date('F d, Y', strtotime($appointmentDetails['appointment_date'])) . "</li>
                        <li><strong>Time:</strong> " . date('h:i A', strtotime($appointmentDetails['appointment_time'])) . "</li>";

        if (!empty($appointmentDetails['service_type'])) {
            $htmlBody .= "<li><strong>Service Type:</strong> {$appointmentDetails['service_type']}</li>";
        }
        if (!empty($appointmentDetails['purpose_of_appointment'])) {
            $htmlBody .= "<li><strong>Purpose:</strong> {$appointmentDetails['purpose_of_appointment']}</li>";
        }

        $htmlBody .= "</ul>
                </div>";

        // Notes based on status
        if ($status === 'Confirmed') {
            $htmlBody .= "
                <div style='background: #e9f5e9; padding: 12px 15px; border-radius: 8px;'>
                    <p><strong>Important Notes:</strong></p>
                    <ul>
                        <li>Please arrive 10 minutes before your scheduled appointment time.</li>
                        <li>Bring your valid ID and any previous dental records if applicable.</li>
                        <li>If you need to reschedule or cancel, please contact us as soon as possible.</li>
                    </ul>
                </div>";
        } elseif ($status === 'Canceled') {
            $htmlBody .= "<p style='color: #d00000;'>We’re sorry to hear that your appointment was canceled. You may reschedule anytime through our website or clinic.</p>";
        }

        // Footer
        $htmlBody .= "
                <p style='margin-top: 25px;'>Thank you for choosing <strong>DentalScape Iloilo</strong>. We look forward to serving you!</p>
                <hr style='border: none; border-top: 1px solid #ddd;'>
                <p style='font-size: 12px; color: #777; text-align: center;'>
                    This is an automated email. Please do not reply directly.<br>
                    &copy; " . date('Y') . " DentalScape Iloilo. All rights reserved.
                </p>
            </div>
        </body>
        </html>";

        // Assign to PHPMailer
        $mail->Body    = $htmlBody;
        $mail->AltBody = strip_tags(str_replace(['<br>', '</p>'], ["\n", "\n\n"], $htmlBody));

        // Send email
        if ($mail->send()) {
            return ['success' => true, 'message' => 'Appointment email sent successfully.'];
        } else {
            return ['success' => false, 'message' => 'Email sending failed.'];
        }

    } catch (Exception $e) {
        error_log("Email sending failed ({$to}): " . $mail->ErrorInfo);
        return ['success' => false, 'message' => $mail->ErrorInfo];
    }
}


function sendEmailNotification($toEmail, $subject, $message) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username = 'dentalscape.iloilo.services@gmail.com';
        $mail->Password = 'etvk bish lrxg fpcz';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('dentalscape.iloilo.services@gmail.com', 'DentalScape Iloilo');
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email Error: " . $mail->ErrorInfo);
        return false;
    }
}

//liliwaton
function getAllUserEmails($conn) {
    $query = "SELECT email, fullname FROM users WHERE email IS NOT NULL";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->get_result();
}

function sendAnnouncementEmail($to, $fullName, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('dentalscape.iloilo.services@gmail.com', 'DentalScape Iloilo');
        $mail->addAddress($to, $fullName);
        $mail->addReplyTo('dentalscape.iloilo.services@gmail.com', 'DentalScape Iloilo');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody = "
            <html>
            <body style='font-family: Arial, sans-serif; background-color: #f5f6fa; margin: 0; padding: 30px;'>
                <div style='max-width: 600px; margin: auto; background: #ffffff; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);'>

                    <!-- Header -->
                    <div style='text-align: center; border-bottom: 2px solid #0096c7; padding-bottom: 10px; margin-bottom: 20px;'>
                        <img src='https://i.imgur.com/ScZ1VtZ.png' alt='DentalScape Logo' width='80' style='margin-bottom: 10px;' />
                        <h2 style='color: #0096c7; margin: 0;'>DentalScape Iloilo</h2>
                        <p style='color: #555; font-size: 14px;'>Your Smile, Our Masterpiece</p>
                    </div>

                    <!-- Main Content -->
                    <h3 style='color: #023e8a; margin-top: 0;'>Clinic Announcement</h3>
                    <p>Dear <strong>{$fullName}</strong>,</p>
                    <p style='font-size: 15px; color: #333; line-height: 1.6;'>
                        " . nl2br(htmlspecialchars($message)) . "
                    </p>

                    <div style='background: #f1faff; padding: 15px; border-left: 4px solid #0096c7; border-radius: 6px; margin: 20px 0;'>
                        <p style='margin: 0; color: #333; font-size: 14px;'>
                            Please stay tuned for further updates or contact us if you have any questions regarding this announcement.
                        </p>
                    </div>

                    <!-- Footer -->
                    <hr style='border: none; border-top: 1px solid #ddd; margin: 20px 0;'>
                    <p style='font-size: 12px; color: #777; text-align: center;'>
                        This is an automated message from <strong>DentalScape Iloilo</strong>.<br>
                        Please do not reply directly to this email.<br>
                        &copy; " . date('Y') . " DentalScape Iloilo. All rights reserved.
                    </p>
                </div>
            </body>
            </html>";
            ;

        return $mail->send();
    } catch (Exception $e) {
        error_log("Email sending failed: {$mail->ErrorInfo}");
        return false;
    }
}

// Handle announcement post
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_announcement'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $created_at = date("Y-m-d H:i:s");

    $sql = "INSERT INTO announcements (title, content, created_at) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $title, $content, $created_at);

        if ($stmt->execute()) {
            // Send emails to all users
            $result = getAllUserEmails($conn);
            while ($row = $result->fetch_assoc()) {
                sendAnnouncementEmail($row['email'], $row['fullname'], $title, $content);
            }

            $_SESSION['success'] = "Announcement posted and emailed successfully!";
            header("Location: /dentalscape/admin-dashboard/");
        } else {
            $_SESSION['error'] = "Error inserting data: " . $stmt->error;
            header("Location: admin_process.php");
        }

        $stmt->close();
        exit();
    } else {
        $_SESSION['error'] = "Prepare statement failed: " . $conn->error;
        header("Location: /dentalscape/admin-dashboard/");
        exit();
    }
}


//User Management
function getUsers($conn) {
    $query = "SELECT u.*, 
              (SELECT login_datetime 
               FROM login_history 
               WHERE user_id = u.user_id 
               AND status = 'success' 
               ORDER BY login_datetime DESC 
               LIMIT 1) as last_login
              FROM users u";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->get_result();
}

function getTotalUsers($conn) {
    $query = "SELECT COUNT(*) as total FROM users";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'];
}

function recordLoginAttempt($conn, $user_id, $status, $ip_address, $device_info) {
    $query = "INSERT INTO login_history (user_id, status, ip_address, device_info) 
              VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $user_id, $status, $ip_address, $device_info);
    return $stmt->execute();
}

function deleteUser($conn, $user_id) {
    $conn->begin_transaction();
    
    try {
        $query1 = "DELETE FROM otp_history WHERE user_id = ?";
        $stmt1 = $conn->prepare($query1);
        $stmt1->bind_param("i", $user_id);
        $stmt1->execute();
        
        $query2 = "DELETE FROM login_history WHERE user_id = ?";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();

        $query4 = "DELETE FROM lockout_history WHERE user_id = ?";
        $stmt4 = $conn->prepare($query4);
        $stmt4->bind_param("i", $user_id);
        $stmt4->execute();

        $query3 = "DELETE FROM users WHERE user_id = ?";
        $stmt3 = $conn->prepare($query3);
        $stmt3->bind_param("i", $user_id);
        $stmt3->execute();
        
        $conn->commit();
        return true;
        
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

//Delete User
if(isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    
    if(deleteUser($conn, $user_id)) {
        $_SESSION['success'] = "User deleted successfully";
    } else {
        $_SESSION['error'] = "Failed to delete user";
    }
    
    header("Location: /dentalscape/user-management/");
    exit();
}

function blockUser($conn, $user_id) {
    $stmt = $conn->prepare("UPDATE users SET status = 'blocked' WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    return $stmt->execute();
}


if (isset($_POST['block_user'])) {
    $user_id = $_POST['user_id'];

    if (blockUser($conn, $user_id)) {
        $_SESSION['success'] = "User blocked successfully";
    } else {
        $_SESSION['error'] = "Failed to block user";
    }

    header("Location: /dentalscape/user-management/");
    exit();
}

function unblockUser($conn, $user_id) {
    $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    return $stmt->execute();
}
// ✅ Unblock User
if (isset($_POST['unblock_user'])) {
    $user_id = $_POST['user_id'];

    if (unblockUser($conn, $user_id)) {
        $_SESSION['success'] = "User unblocked successfully";
    } else {
        $_SESSION['error'] = "Failed to unblock user";
    }

    header("Location: /dentalscape/user-management/");
    exit();
}

//Login History
function getLoginHistory($conn, $status = null) {
    $query = "SELECT h.*, u.fullname 
              FROM login_history h 
              LEFT JOIN users u ON h.user_id = u.user_id";

    if ($status) {
        $query .= " WHERE h.status = ?";
    }

    $query .= " ORDER BY h.login_datetime DESC";

    $stmt = $conn->prepare($query);

    if ($status) {
        $stmt->bind_param("s", $status);
    }

    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        return false;
    }

    return $stmt->get_result();
}

function getUserLockedOut($conn) {
    $query = "
        SELECT lh.*, u.email, u.fullname
        FROM lockout_history lh
        JOIN users u ON lh.user_id = u.user_id
        ORDER BY lh.lockout_time DESC
    ";

    $result = $conn->query($query);

    $lockouts = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $lockouts[] = $row;
        }
    }

    return $lockouts;
}


//Total login history count
function getLoginHistoryCount($conn, $status = null) {
    $countQuery = "SELECT COUNT(*) as total FROM login_history";
    if ($status) {
        $countQuery .= " WHERE status = ?";
    }

    $stmt = $conn->prepare($countQuery);
    if ($status) {
        $stmt->bind_param("s", $status); 
    }

    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        return false;
    }

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'];
}

//weekly successful login count
function getWeeklyLoginCount($conn) {
    $query = "SELECT COUNT(*) as weekly_count FROM login_history 
              WHERE login_datetime >= DATE_SUB(NOW(), INTERVAL 1 WEEK)
              AND status = 'success'";

    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['weekly_count'];
    }
    
    return 0;
}

// Usage
$status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

$totalRecords = getLoginHistoryCount($conn, $status);
$totalPages = ceil($totalRecords / $perPage);
$weeklyCount = getWeeklyLoginCount($conn);



// ===== Get Pending Appointments ======
function getPendingAppointments($conn, $month_range = null, $status = 'Pending', $offset = 0, $perPage = 10) {
    $query = "SELECT * FROM appointments WHERE status = ?";

    if ($month_range) {
        switch ($month_range) {
            case 'jan-mar':
                $query .= " AND appointment_date BETWEEN '2025-01-01' AND '2025-03-31'";
                break;
            case 'mar-jun':
                $query .= " AND appointment_date BETWEEN '2025-03-01' AND '2025-06-30'";
                break;
            case 'jun-aug':
                $query .= " AND appointment_date BETWEEN '2025-06-01' AND '2025-08-31'";
                break;
            case 'aug-nov':
                $query .= " AND appointment_date BETWEEN '2025-08-01' AND '2025-11-30'";
                break;
        }
    }

    $query .= " ORDER BY appointment_date DESC, appointment_time DESC LIMIT ?, ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $status, $offset, $perPage);
    $stmt->execute();
    return $stmt->get_result();
}

//Count Total Pending Appointments
function countTotalPendingAppointments($conn, $month_range = null, $status = 'Pending') {
    $query = "SELECT COUNT(*) as total FROM appointments WHERE status = ?";

    if ($month_range) {
        switch ($month_range) {
            case 'jan-mar':
                $query .= " AND appointment_date BETWEEN '2025-01-01' AND '2025-03-31'";
                break;
            case 'mar-jun':
                $query .= " AND appointment_date BETWEEN '2025-03-01' AND '2025-06-30'";
                break;
            case 'jun-aug':
                $query .= " AND appointment_date BETWEEN '2025-06-01' AND '2025-08-31'";
                break;
            case 'aug-nov':
                $query .= " AND appointment_date BETWEEN '2025-08-01' AND '2025-11-30'";
                break;
        }
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['total'];
}

//Pending Appointment Filters
function buildWhereClause($filters) {
    $whereClauses = [];

    if (isset($filters['status'])) {
        if ($filters['status'] == 'active') {
            $whereClauses[] = "status = 'active'";
        } elseif ($filters['status'] == 'inactive') {
            $whereClauses[] = "status = 'inactive'";
        }
    }

    if (isset($filters['verified'])) {
        $whereClauses[] = "verified = " . (int)$filters['verified'];
    }

    if (!empty($whereClauses)) {
        return " WHERE " . implode(' AND ', $whereClauses);
    }

    return '';
}

// ===== Confirm or Cancel Appointment =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['appointment_id'])) {
    $appointment_id = isset($_POST['appointment_id']) ? intval($_POST['appointment_id']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    if ($appointment_id <= 0) {
        $_SESSION['error'] = "Invalid appointment ID";
        header("Location: /dentalscape/admin-dashboard/");
        exit();
    }
    
    $new_status = '';
    switch($action) {
        case 'confirm':
            $new_status = 'Confirmed';
            break;
        case 'cancel':
            $new_status = 'Canceled';
            break;
        case 'done':
            $new_status = 'Done';
            break;
        default:
            $_SESSION['error'] = "Invalid action";
            header("Location: /dentalscape/admin-dashboard/");
            exit();
    }
    
    $query = "UPDATE appointments SET status = ?, last_modified = NOW() WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $new_status, $appointment_id);
    
    if ($stmt->execute()) {
        // Get appointment details for email notification
        $notify_query = "SELECT email, full_name, appointment_date, appointment_time, 
                               service_type, purpose_of_appointment 
                        FROM appointments WHERE id = ?";
        $notify_stmt = $conn->prepare($notify_query);
        $notify_stmt->bind_param('i', $appointment_id);
        $notify_stmt->execute();
        $result = $notify_stmt->get_result();
        
        if ($appointment = $result->fetch_assoc()) {
            // Only send email for Confirmed and Canceled
            if ($new_status === 'Confirmed' || $new_status === 'Canceled') {
                if (sendAppointmentEmail(
                    $appointment['email'],
                    $appointment['full_name'],
                    $new_status,
                    $appointment
                )) {
                    $_SESSION['success'] = "Appointment successfully " . strtolower($new_status) . " and email sent";
                } else {
                    $_SESSION['success'] = "Appointment successfully " . strtolower($new_status) . " but email failed to send";
                }
            } else {
                $_SESSION['success'] = "Appointment marked as " . strtolower($new_status) . " successfully";
            }
        }

    } else {
        $_SESSION['error'] = "Failed to update appointment status";
    }
    
    if ($action === 'done') {
        header("Location: /dentalscape/confirmed-appointments/"); 
    } else {
        header("Location: /dentalscape/pending-appointments/"); 
    }
    exit();
}

//Get Confirmed Appointments
function getConfirmedAppointments($conn, $month_range = null, $status = 'Confirmed', $offset = 0, $perPage = 10) {
    $query = "SELECT * FROM appointments WHERE status = ?";

    if ($month_range) {
        switch ($month_range) {
            case 'jan-mar':
                $query .= " AND appointment_date BETWEEN '2025-01-01' AND '2025-03-31'";
                break;
            case 'mar-jun':
                $query .= " AND appointment_date BETWEEN '2025-03-01' AND '2025-06-30'";
                break;
            case 'jun-aug':
                $query .= " AND appointment_date BETWEEN '2025-06-01' AND '2025-08-31'";
                break;
            case 'aug-nov':
                $query .= " AND appointment_date BETWEEN '2025-08-01' AND '2025-11-30'";
                break;
        }
    }

    $query .= " ORDER BY appointment_date DESC, appointment_time DESC LIMIT ?, ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $status, $offset, $perPage);
    $stmt->execute();
    return $stmt->get_result();
}

//Count Total Confirmed Appointments
function countTotalConfirmedAppointments($conn, $yearMonth, $status = 'Confirmed') {
    $today = date('Y-m-d');

    // Get the first and last day of the specified month
    $start = $yearMonth . '-01';
    $end = date('Y-m-t', strtotime($start)); // last day of month

    // If the whole month has passed, return 0
    if ($today > $end) {
        return 0;
    }

    // Only count appointments from today forward (if today is within the month)
    $start = max($start, $today);

    $query = "SELECT COUNT(*) as total FROM appointments 
              WHERE status = ? AND appointment_date BETWEEN ? AND ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $status, $start, $end);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['total'];
}



function historyAppointments($conn, $month_range = null, $status = ['Canceled', 'Done'], $offset = 0, $perPage = 10) {
    $statusPlaceholder = implode(',', array_fill(0, count($status), '?'));
    $query = "SELECT * FROM appointments WHERE status IN ($statusPlaceholder)";

    if ($month_range) {
        switch ($month_range) {
            case 'jan-mar':
                $query .= " AND appointment_date BETWEEN '2025-01-01' AND '2025-03-31'";
                break;
            case 'mar-jun':
                $query .= " AND appointment_date BETWEEN '2025-03-01' AND '2025-06-30'";
                break;
            case 'jun-aug':
                $query .= " AND appointment_date BETWEEN '2025-06-01' AND '2025-08-31'";
                break;
            case 'aug-nov':
                $query .= " AND appointment_date BETWEEN '2025-08-01' AND '2025-11-30'";
                break;
        }
    }

    $query .= " ORDER BY appointment_date DESC, appointment_time DESC LIMIT ?, ?";

    $stmt = $conn->prepare($query);
    $params = array_merge($status, [$offset, $perPage]);
    
    $types = str_repeat('s', count($status)) . "ii"; 
    $stmt->bind_param($types, ...$params);
    
    $stmt->execute();
    return $stmt->get_result();
}


//Count Total Cancel/Done Appointments
function countHistoryAppointments($conn, $month_range = null, $status = ['Canceled', 'Done']) {
    $query = "SELECT COUNT(*) as total FROM appointments WHERE status = ?";

    if ($month_range) {
        switch ($month_range) {
            case 'jan-mar':
                $query .= " AND appointment_date BETWEEN '2025-01-01' AND '2025-03-31'";
                break;
            case 'mar-jun':
                $query .= " AND appointment_date BETWEEN '2025-03-01' AND '2025-06-30'";
                break;
            case 'jun-aug':
                $query .= " AND appointment_date BETWEEN '2025-06-01' AND '2025-08-31'";
                break;
            case 'aug-nov':
                $query .= " AND appointment_date BETWEEN '2025-08-01' AND '2025-11-30'";
                break;
        }
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['total'];
}


function getUserInformation($conn, $user_id) {
    $query = "SELECT * FROM user_profiles WHERE user_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc(); 
}


//=========================================== Patient Details =================================================================

// ========== Encrypt Data ============
function encryptData($data, $key) {
    $cipher = "AES-256-CBC"; 
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher)); 
    $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
    return base64_encode($iv . $encrypted); 
}

// ========== Decrypt Data ============
function decryptData($encryptedData, $key) {
    $cipher = "AES-256-CBC";
    $ivLength = openssl_cipher_iv_length($cipher);
    $data = base64_decode($encryptedData);
    if (!$data) return false;

    $iv = substr($data, 0, $ivLength);
    $encryptedText = substr($data, $ivLength);

    if (strlen($iv) !== $ivLength) {
        return false;
    }

    return openssl_decrypt($encryptedText, $cipher, $key, 0, $iv);
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$encryption_key = $_ENV['ENCRYPTION_KEY'];

// ========== View Patient Info ============
if (isset($_GET['id'])) {
    $patient_id = $_GET['id'];
    $dental = fetchDentalHistory($conn, $patient_id, $encryption_key);
    $patient = fetchPatientDetails($conn, $patient_id, $encryption_key);
    $history = fetchMedicalHistory($conn, $patient_id, $encryption_key);
    $dentalRecords = fetchDentalRecords($conn, $patient_id, $encryption_key); 

    if (!$patient) {
        echo "Patient not found.";
        exit;
    }
}

// ========== Fetch Patient List ============
function getPatientList($conn) {
    $sql = "SELECT * FROM patients";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->get_result();
}

// ========== Fecth Patient Info ============
function fetchPatientDetails($conn, $patient_id, $encryption_key) {
    $stmt = $conn->prepare("SELECT * FROM patients WHERE patient_id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();

    if ($patient) {
        foreach (['full_name', 'nickname', 'email', 'gender', 'address', 'dob', 'phone', 'civil_status', 'occupation', 'nationality', 'religion', 'dental_insurance', 'effective_date'] as $field) {
            $patient[$field] = decryptData($patient[$field], $encryption_key);
        }
    }

    return $patient;
}

// ========== Fecth Dental History ============
function fetchDentalHistory($conn, $patient_id, $encryption_key) {
    $stmt = $conn->prepare("SELECT * FROM dental_history WHERE patient_id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $dental = $result->fetch_assoc();

    if ($dental) {
        foreach (['previous_dentist', 'last_dental_visit'] as $field) {
            if (!empty($dental[$field])) {
                $dental[$field] = decryptData($dental[$field], $encryption_key);
            }
        }
    }

    return $dental;
}

// ========== Fetch Medical History ============
function fetchMedicalHistory($conn, $patient_id, $encryption_key) {
    $stmt = $conn->prepare("SELECT * FROM medical_history WHERE patient_id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $history = $result->fetch_assoc();
    $stmt->close();

    if ($history) {
        $encrypted_fields = [
            'physician_name', 'specialty', 'office_address', 'office_number',
            'medical_condition', 'serious_illness_details', 'hospitalization_details',
            'prescription_details', 'allergies', 'other_allergy', 'bleeding_time',
            'blood_type', 'blood_pressure', 'medical_conditions'
        ];

        foreach ($encrypted_fields as $field) {
            if (!empty($history[$field])) {
                $decrypted = decryptData($history[$field], $encryption_key);
                $history[$field] = ($decrypted !== false) ? $decrypted : '';
            } else {
                $history[$field] = '';
            }
        }

        $enum_fields = [
            'good_health', 'medical_treatment', 'serious_illness', 'hospitalized',
            'prescription', 'tobacco', 'alcohol_drugs', 'pregnant', 'nursing', 'birth_control'
        ];

        foreach ($enum_fields as $field) {
            if (!isset($history[$field]) || $history[$field] === null) {
                $history[$field] = 'no'; 
            }
        }
    }

    return $history;
}

// ========== Fetch Dental Records ============
function fetchDentalRecords($conn, $patient_id, $encryption_key) {

    $stmt = $conn->prepare("SELECT * FROM dental_records WHERE patient_id = ? ORDER BY visit_date DESC");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $records = [];
    while ($row = $result->fetch_assoc()) {
        // Decrypt sensitive fields before returning
        $row['diagnosis'] = decryptData($row['diagnosis'], $encryption_key);
        $row['treatment'] = decryptData($row['treatment'], $encryption_key);
        $row['notes']     = decryptData($row['notes'], $encryption_key);

        $records[] = $row;
    }

    $stmt->close();
    return $records;
}

//=====Fetch Emergency Contacts =======
function fetchEmergencyContacts($conn, $patient_id) {
    $stmt = $conn->prepare("SELECT * FROM emergency_contacts WHERE patient_id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $patient_id);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    $emergency_contacts = [];

    while ($row = $result->fetch_assoc()) {
        $emergency_contacts[] = $row;
    }

    return $emergency_contacts;
}

// === Function to Fetch Dental Reports ===
function fetchDentalReports($conn, $patient_id, $encryption_key) {
    $stmt = $conn->prepare("SELECT * FROM dental_reports WHERE patient_id = ? ORDER BY report_date DESC");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $reports = [];
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }

    return $reports;
}

// ====== Get user_id ======
$user_id = $_POST['user_id'] ?? null;

// ========== Create Patient ============
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_patient'])) {

    // Encrypt helper
    function safeEncrypt($value, $key) {
        if (is_array($value)) {
            $value = implode(', ', $value);
        }
        return encryptData($value ?? '', $key);
    }

    function yn($field) {
        return (isset($_POST[$field]) && $_POST[$field] === 'yes') ? 'yes' : 'no';
    }

    // ====== Encrypt patient personal info ======
    $patient_fields = [
        'full_name', 'nickname', 'email', 'gender', 'address', 'dob',
        'phone', 'civil_status', 'occupation', 'nationality', 'religion',
        'dental_insurance', 'effective_date'
    ];
    foreach ($patient_fields as $field) {
        $$field = safeEncrypt($_POST[$field] ?? '', $encryption_key);
    }

    // ====== Dental history ======
    $previous_dentist   = safeEncrypt($_POST['previous_dentist'] ?? '', $encryption_key);
    $last_dental_visit  = safeEncrypt($_POST['last_dental_visit'] ?? '', $encryption_key);

    // ====== Medical history ======
    $physician_name  = safeEncrypt($_POST['physician_name'] ?? '', $encryption_key);
    $specialty = safeEncrypt($_POST['specialty'] ?? '', $encryption_key);
    $office_address  = safeEncrypt($_POST['office_address'] ?? '', $encryption_key);
    $office_number = safeEncrypt($_POST['office_number'] ?? '', $encryption_key);

    $good_health        = yn('good_health');
    $medical_treatment  = yn('medical_treatment');
    $medical_condition  = safeEncrypt($_POST['medical_condition'] ?? '', $encryption_key);
    $serious_illness    = yn('serious_illness');
    $serious_illness_details = safeEncrypt($_POST['serious_illness_details'] ?? '', $encryption_key);
    $hospitalized       = yn('hospitalized');
    $hospitalization_details = safeEncrypt($_POST['hospitalization_details'] ?? '', $encryption_key);
    $prescription       = yn('prescription');
    $prescription_details = safeEncrypt($_POST['prescription_details'] ?? '', $encryption_key);
    $tobacco            = yn('tobacco');
    $alcohol_drugs      = yn('alcohol_drugs');

    // ✅ Fixed allergies handling
    $allergies_list = $_POST['allergies'] ?? [];
    $allergies_list = array_filter($allergies_list, fn($a) => $a !== 'other');
    $allergies      = safeEncrypt($allergies_list, $encryption_key);

    $other_allergy  = safeEncrypt($_POST['other_allergy'] ?? '', $encryption_key);
    $bleeding_time  = safeEncrypt($_POST['bleeding_time'] ?? '', $encryption_key);
    $pregnant       = yn('pregnant');
    $nursing        = yn('nursing');
    $birth_control  = yn('birth_control');
    $blood_type     = safeEncrypt($_POST['blood_type'] ?? '', $encryption_key);
    $blood_pressure = safeEncrypt($_POST['blood_pressure'] ?? '', $encryption_key);
    
    // ✅ Fixed medical conditions handling
    $medical_conditions_list = $_POST['medical_conditions'] ?? [];
    $medical_conditions_list = array_filter($medical_conditions_list, fn($m) => $m !== 'other');
    $medical_conditions = safeEncrypt($medical_conditions_list, $encryption_key);

    // ====== Emergency contact ======
    $contact_name    = $_POST['contact_name'];
    $relationship    = $_POST['relationship'] ;
    $emergency_phone = $_POST['emergency_phone'] ;
    $alt_phone       = $_POST['alt_phone'] ;

    // ====== Insert into patients table ======
    $stmt = $conn->prepare("INSERT INTO patients 
        (user_id, full_name, nickname, email, gender, address, dob, phone, civil_status, occupation, nationality, religion, dental_insurance, effective_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "isssssssssssss", 
        $user_id, $full_name, $nickname, $email, $gender, $address, $dob, 
        $phone, $civil_status, $occupation, $nationality, $religion,
        $dental_insurance, $effective_date
    );
    $stmt->execute();
    $patient_id = $stmt->insert_id;
    $stmt->close();

    // ====== Insert into dental_history ======
    $stmt = $conn->prepare("INSERT INTO dental_history 
        (patient_id, previous_dentist, last_dental_visit) 
        VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $patient_id, $previous_dentist, $last_dental_visit);
    $stmt->execute();
    $stmt->close();

    // ====== Insert into medical_history ======
    $stmt = $conn->prepare("INSERT INTO medical_history 
        (patient_id, physician_name, specialty, office_address, office_number, good_health, medical_treatment, medical_condition, serious_illness, serious_illness_details,
         hospitalized, hospitalization_details, prescription, prescription_details, tobacco, alcohol_drugs,
         allergies, other_allergy, bleeding_time, pregnant, nursing, birth_control, blood_type, blood_pressure, medical_conditions) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "issssssssssssssssssssssss",
        $patient_id, $physician_name, $specialty, $office_address, $office_number, $good_health, $medical_treatment, $medical_condition, $serious_illness, $serious_illness_details,
        $hospitalized, $hospitalization_details, $prescription, $prescription_details, $tobacco, $alcohol_drugs,
        $allergies, $other_allergy, $bleeding_time, $pregnant, $nursing, $birth_control, $blood_type, $blood_pressure, $medical_conditions
    );
    $stmt->execute();
    $stmt->close();

    // ====== Insert into emergency_contacts ======
    $stmt = $conn->prepare("INSERT INTO emergency_contacts 
        (patient_id, contact_name, relationship, emergency_phone, alt_phone) 
        VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $patient_id, $contact_name, $relationship, $emergency_phone, $alt_phone);
    $stmt->execute();
    $stmt->close();

    // ====== Insert into patient_logs ======
    if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
        $added_by = $_SESSION['admin_id'];
        $role     = $_SESSION['role'];

        $stmt = $conn->prepare("INSERT INTO patient_logs (patient_id, added_by, role, action) VALUES (?, ?, ?, 'Add Patient')");
        $stmt->bind_param("iis", $patient_id, $added_by, $role);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['success'] = "Patient created successfully.";
    header("Location: admin_patient_details.php?id=" . urlencode($patient_id));
    exit();
}

// ===== Adding Dental Record =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['visit_date']) && isset($_POST['add_dental_record'])) {
    $patient_id = $_POST['patient_id'];
    if (empty($patient_id)) die("Patient ID is missing.");

    // Encrypt sensitive fields
    $visit_date   = $_POST['visit_date']; // Date usually doesn't need encryption
    $tooth_number = $_POST['tooth_number']; // Optional encryption if you want privacy
    $diagnosis    = encryptData($_POST['diagnosis'], $encryption_key);
    $treatment    = encryptData($_POST['treatment'], $encryption_key);
    $notes        = encryptData($_POST['notes'], $encryption_key);

    // Insert into dental_records
    $stmt = $conn->prepare("INSERT INTO dental_records (patient_id, visit_date, tooth_number, diagnosis, treatment, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $patient_id, $visit_date, $tooth_number, $diagnosis, $treatment, $notes);
    $stmt->execute();

    // Log the action
    if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
        $added_by = $_SESSION['admin_id'];
        $role = $_SESSION['role'];

        $stmtLog = $conn->prepare("INSERT INTO patient_logs (patient_id, added_by, role, action) VALUES (?, ?, ?, 'Add Dental Record')");
        $stmtLog->bind_param("iis", $patient_id, $added_by, $role);
        $stmtLog->execute();
        $stmtLog->close();
    }

    $_SESSION['success'] = "Dental record added successfully";
    header("Location: admin_patient_details.php?id=" . urlencode($patient_id));
    exit();
}

// ===== Add more Emergency Contacts =====
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['emergency'])) {
    $patient_id = $_POST['patient_id'];

    $contact_names = $_POST['contact_name'] ?? [];
    $relationships = $_POST['relationship'] ?? [];
    $phones = $_POST['emergency_phone'] ?? [];
    $alt_phones = $_POST['alt_phone'] ?? [];

    for ($i = 0; $i < count($contact_names); $i++) {
        $name = trim($contact_names[$i]);
        $relationship = trim($relationships[$i]);
        $phone = trim($phones[$i]);
        $alt_phone = isset($alt_phones[$i]) ? trim($alt_phones[$i]) : '';

        if (!empty($name) && !empty($relationship) && !empty($phone)) {
            $stmt = $conn->prepare("INSERT INTO emergency_contacts (patient_id, contact_name, relationship, emergency_phone, alt_phone) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $patient_id, $name, $relationship, $phone, $alt_phone);
            
            if ($stmt->execute()) {
                // Successfully inserted
            } else {
                // Handle error if needed
                error_log("Failed to insert emergency contact: " . $stmt->error);
            }
            $stmt->close();

            if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
            $added_by = $_SESSION['admin_id'];
            $role = $_SESSION['role'];

            $stmt = $conn->prepare("INSERT INTO patient_logs (patient_id, added_by, role, action) VALUES (?, ?, ?, 'Add New Contact')");
            $stmt->bind_param("iis", $patient_id, $added_by, $role);
            $stmt->execute();
            $stmt->close();
            }
        }
    }

    $_SESSION['success'] = "Emergency contact added successfully";
    header("Location: admin_patient_details.php?id=" . urlencode($patient_id));
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reports'])) {
    try {
        $patient_id = $_POST['patient_id'];

        // Fetch encrypted full_name from patients table
        $stmt = $conn->prepare("SELECT full_name FROM patients WHERE patient_id = ?");
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $stmt->bind_result($encrypted_name);
        $stmt->fetch();
        $stmt->close();

        if (!$encrypted_name) {
            throw new Exception("Patient not found.");
        }

        // ✅ Decrypt only for folder naming
        $patient_name = decryptData($encrypted_name, $encryption_key);
        
        // ✅ ADDED: Sanitize patient name to match fetch logic
        $safe_patient_name = preg_replace('/[^a-zA-Z0-9\s_-]/', '', $patient_name);

        // ✅ No encryption on fields now
        $report_type  = $_POST['report_type'];
        $report_title = $_POST['report_title'];
        $report_date  = $_POST['report_date'];
        $issued_by    = $_POST['issued_by'];
        $notes        = $_POST['notes'];
        $visibility   = $_POST['visibility'];

        // ✅ Create folder for this patient's files if not exists
        // CHANGED: Use sanitized name
        $folder_name = "uploads/" . $patient_id . " - " . $safe_patient_name;
        if (!file_exists($folder_name)) {
            mkdir($folder_name, 0777, true);
        }

        // ✅ File Handling
        if (!isset($_FILES['report_file']) || $_FILES['report_file']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("File upload error.");
        }

        $tmp_name = $_FILES['report_file']['tmp_name'];
        $original_filename = basename($_FILES['report_file']['name']);
        $new_file_name = uniqid("report_") . "_" . $original_filename;

        if (!move_uploaded_file($tmp_name, $folder_name . "/" . $new_file_name)) {
            throw new Exception("Failed to save file.");
        }

        // ✅ Insert report (NO patient_name here)
        $stmt = $conn->prepare("
            INSERT INTO dental_reports 
            (patient_id, report_type, report_title, report_date, issued_by, notes, file, visibility)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "isssssss",
            $patient_id,
            $report_type,
            $report_title,
            $report_date,
            $issued_by,
            $notes,
            $new_file_name,
            $visibility
        );

        $stmt->execute();
        $stmt->close();

        $_SESSION['success'] = "Medical report uploaded successfully.";
        header("Location: admin_patient_details.php?id=" . urlencode($patient_id));
        exit();

    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}


// ================= DELETE REPORT =================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_report'])) {

    $report_id = intval($_POST['report_id']);
    $patient_id = intval($_POST['patient_id']);

    // 1. Get encrypted patient name + file name
    $stmt = $conn->prepare("
        SELECT dr.file, p.full_name
        FROM dental_reports dr
        JOIN patients p ON dr.patient_id = p.patient_id
        WHERE dr.report_id = ?
    ");
    $stmt->bind_param("i", $report_id);
    $stmt->execute();
    $stmt->bind_result($fileName, $encrypted_name);
    $stmt->fetch();
    $stmt->close();

    if (!$fileName) {
        $_SESSION['error'] = "File not found.";
        header("Location: admin_patient_details.php?id={$patient_id}");
        exit();
    }

    // 2. Decrypt patient name for folder path
    $patient_name = decryptData($encrypted_name, $encryption_key);

    // 3. Build full file path
    $folder_name = "uploads/" . $patient_id . " - " . $patient_name;
    $file_path = $folder_name . "/" . $fileName;

    // 4. Delete file from folder
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    // 5. Remove DB entry
    $stmt = $conn->prepare("DELETE FROM dental_reports WHERE report_id = ?");
    $stmt->bind_param("i", $report_id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success'] = "Report deleted successfully.";
    header("Location: admin_patient_details.php?id={$patient_id}");
    exit();
}

// ====== Delete Dental Records =====
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_dentalRecords'])) {
    try {
        $record_id  = intval($_POST['record_id']);
        $patient_id = intval($_POST['patient_id']);

        if ($record_id <= 0) {
            throw new Exception("Invalid record ID.");
        }

        // Prepare delete query
        $stmt = $conn->prepare("DELETE FROM dental_records WHERE record_id = ?");
        $stmt->bind_param("i", $record_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['success'] = "Dental record deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete the record or record not found.";
        }

        $stmt->close();

        // Redirect back to patient details page
        header("Location: /dentalscape/patient-details/?id=" . urlencode($patient_id));
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = "Error deleting dental record: " . $e->getMessage();
        header("Location: /dentalscape/patient-details/?id=" . urlencode($_POST['patient_id']));
        exit();
    }
}

// ====== Delete Patient =====
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_patient'])) {
    try {
        $patient_id = intval($_POST['patient_id']);

        if ($patient_id <= 0) {
            throw new Exception("Invalid patient ID.");
        }

        // Fetch encrypted name
        $check = $conn->prepare("SELECT full_name FROM patients WHERE patient_id = ?");
        $check->bind_param("i", $patient_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Patient not found.");
        }

        $row = $result->fetch_assoc();
        $encrypted_name = $row['full_name'];

        $patient_name = decryptData($encrypted_name, $encryption_key, $cipher);

        // Begin transaction
        $conn->begin_transaction();

        $conn->query("DELETE FROM patient_logs WHERE patient_id = $patient_id");
        $conn->query("DELETE FROM dental_history WHERE patient_id = $patient_id");
        $conn->query("DELETE FROM medical_history WHERE patient_id = $patient_id");
        $conn->query("DELETE FROM dental_records WHERE patient_id = $patient_id");

        // Finally delete the patient record
        $stmt = $conn->prepare("DELETE FROM patients WHERE patient_id = ?");
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();

        $conn->commit();

        $_SESSION['success'] = "Patient '" . htmlspecialchars($patient_name) . "' and related records deleted successfully.";
        header("Location: /dentalscape/patient-list/");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Error deleting patient: " . $e->getMessage();
        header("Location: /dentalscape/patient-list/");
        exit;
    }
}

// ===== Fetch Patient Logs =====
function fetchPatientLogs($conn, $encryption_key) {
    $patient_log = [];

    $stmt = $conn->prepare("
        SELECT pl.*, p.full_name 
        FROM patient_logs pl
        INNER JOIN patients p ON pl.patient_id = p.patient_id
    ");
    
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $row['full_name'] = decryptData($row['full_name'], $encryption_key);
            $patient_log[] = $row;
        }

        $stmt->close();
    }

    return $patient_log;
}


// ========================== Calendar ======================================
if (isset($_GET['action']) && $_GET['action'] === 'fetch_events') {
    $sql = "SELECT id, full_name, appointment_date, appointment_time, service_type, purpose_of_appointment, status 
            FROM appointments 
            WHERE status IN ('Confirmed', 'Done', 'Pending', 'Canceled')";
    
    $result = $conn->query($sql);
    $events = [];
    
    while ($row = $result->fetch_assoc()) {
        $events[] = [
            'id' => $row['id'],
            'title' => $row['full_name'] . (!empty($row['service_type']) ? "\n" . $row['service_type'] : ""),
            'start' => $row['appointment_date'] . "T" . $row['appointment_time'],
            'className' => strtolower($row['status']),
            // Added missing properties for eventClick
            'full_name' => $row['full_name'],
            'purpose' => $row['purpose_of_appointment'],
            'service_type' => $row['service_type'],
            'status' => $row['status']
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($events);
    exit;
}
?>






