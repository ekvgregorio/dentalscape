<?php 
  require '../conn.php';
  require_once 'admin_process.php';

  if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'secretary','doctor'])) {
    echo "<script>alert('Access denied.'); 
    window.location.href = '/dentalscape/index';</script>";
    exit;
  }
  

  $total_users = getTotalUsers($conn);
  $totalPendingAppointments = countTotalPendingAppointments($conn, 'Pending');
  $totalConfirmedAppointments = countTotalConfirmedAppointments($conn,'Confirmed');

  // Count upcoming confirmed appointments
function countUpcomingConfirmedAppointments($conn, $status = 'Confirmed') {
    $today = date('Y-m-d'); // current date

    $query = "SELECT COUNT(*) as total FROM appointments 
              WHERE status = ? AND appointment_date >= ?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $status, $today);
    $stmt->execute();
    $result = $stmt->get_result();

    $total = 0;
    if ($result && $row = $result->fetch_assoc()) {
        $total = $row['total'] ?? 0;
    }

    $stmt->close();
    return $total;
}

$totalUpcomingAppointments = countUpcomingConfirmedAppointments($conn);


  date_default_timezone_set('Asia/Manila'); // or your local timezone


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DentalScape Iloilo</title>
  <link href="../assets/admin/images/logo1.png" rel="icon">
  <link href="../assets/admin/images/logo1.png" rel="apple-touch-icon">
  <link rel="stylesheet" href="../assets/admin/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/admin/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/admin/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/admin/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="../assets/admin/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="../assets/admin/text/css" href="js/select.dataTables.min.css">
  <link rel="stylesheet" href="../assets/admin/css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="../assets/admin/css/vertical-layout-light/calendar.css">
  <link rel="stylesheet" href="../assets/admin/css/style.css">
  <link rel="shortcut icon" href="../assets/admin/images/favicon.png" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" >
  <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet">
</head>


<body>
  <div class="container-scroller">
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="/dentalscape/admin-dashboard/"><img src="../assets/admin/images/logo2.png" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="/dentalscape/admin-dashboard/"><img src="../assets/admin/images/logo5.png" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
            <div class="input-group">
              <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                <span class="input-group-text" id="search">
                  <i class="icon-search"></i>
                </span>
              </div>
              <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search">
            </div>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
                  <li class="nav-item">
            <a class="nav-link count-indicator" href="#" data-toggle="modal" data-target="#qrModal" title="Landing Page QR Code">
            <i class="fas fa-qrcode"></i>
            </a>
          </li>
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown">
              <i class="ti-power-off mx-0"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="ti-power-off text-primary"></i> Logout
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>

<!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden; background: linear-gradient(135deg, #f6f8fb 0%, #ffffff 100%);">
            <div class="modal-body text-center p-5 position-relative">
                <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; opacity: 0.1;"></div>
                <div style="position: absolute; bottom: -30px; left: -30px; width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; opacity: 0.08;"></div>
                
                <div class="mb-4 position-relative">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle shadow-lg" style="width: 90px; height: 90px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); animation: pulse 2s infinite;">
                        <i class="fas fa-qrcode text-white" style="font-size: 40px;"></i>
                    </div>
                </div>
                
                <h4 class="font-weight-bold mb-2" style="color: #1a202c; font-size: 24px;">Quick Access</h4>
                <p class="mb-4" style="color: #718096; font-size: 14px; line-height: 1.6; font-weight: 400;">
                    Point your camera at the QR code below
                </p>
                
                <div class="mb-4 p-4 bg-white rounded-lg shadow-sm position-relative" style="display: inline-block; border-radius: 16px; border: 2px solid #f0f0f0;">
                    <div style="position: absolute; top: 8px; left: 8px; width: 20px; height: 20px; border-left: 3px solid #667eea; border-top: 3px solid #667eea; border-radius: 4px 0 0 0;"></div>
                    <div style="position: absolute; top: 8px; right: 8px; width: 20px; height: 20px; border-right: 3px solid #667eea; border-top: 3px solid #667eea; border-radius: 0 4px 0 0;"></div>
                    <div style="position: absolute; bottom: 8px; left: 8px; width: 20px; height: 20px; border-left: 3px solid #667eea; border-bottom: 3px solid #667eea; border-radius: 0 0 0 4px;"></div>
                    <div style="position: absolute; bottom: 8px; right: 8px; width: 20px; height: 20px; border-right: 3px solid #667eea; border-bottom: 3px solid #667eea; border-radius: 0 0 4px 0;"></div>
                    
                    <img src="../assets/img/dentalscapeqr.png" alt="QR Code" class="img-fluid" style="max-width: 220px; height: auto; display: block;" />
                </div>
                
                <div class="d-flex align-items-center justify-content-center mb-4" style="gap: 8px;">
                    <div style="width: 8px; height: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%;"></div>
                    <p class="mb-0" style="color: #a0aec0; font-size: 13px; font-weight: 500;">Scan with your mobile device</p>
                </div>
                
                <div>
                    <button type="button" class="btn shadow-sm" data-dismiss="modal" style="min-width: 140px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; font-weight: 600; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-check mr-2"></i>Got it
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="container-fluid page-body-wrapper">
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="/dentalscape/admin-dashboard/">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <i class="fas fa-users menu-icon"></i>
                <span class="menu-title"> Users Account <br> Management</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="/dentalscape/user-management/">User Account <br> Management</a></li>
                  <li class="nav-item"> <a class="nav-link" href="/dentalscape/employee-management/">Staff User<br> Management</a></li>
                </ul>
              </div>
            </li>
            <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
              <i class="fas fa-diagnoses menu-icon"></i>
              <span class="menu-title">Patient Records <br> Management</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="form-elements">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="/dentalscape/patient-list/">Patient List <br> Management</a></li>
                <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'secretary'])): ?>
                  <li class="nav-item"><a class="nav-link" href="/dentalscape/add-patient/">Add Patient</a></li>
                <?php endif; ?>
              </ul>
            </div>
          </li>
          <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'secretary'])): ?>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
              <i class="far fa-calendar-check menu-icon"></i>
              <span class="menu-title">Appoinment <br> Scheduling</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="charts">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="/dentalscape/pending-appointments/">Pending <br> Appointments</a></li>
                <li class="nav-item"> <a class="nav-link" href="/dentalscape/confirmed-appointments/">Confirmed <br>Appointments</a></li>
                <li class="nav-item"> <a class="nav-link" href="/dentalscape/history-appointments/">History <br>Appointments</a></li>      
              </ul>
            </div>
          </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
              <i class="fas fa-chart-area menu-icon"></i>
              <span class="menu-title">Report & <br> Analytics</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="tables">
              <ul class="nav flex-column sub-menu">
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                  <li class="nav-item"> <a class="nav-link" href="/dentalscape/users-report/">Users</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'secretary'])): ?>
                  <li class="nav-item"> <a class="nav-link" href="/dentalscape/appointments-report/">Appointments</a></li>
                <?php endif; ?>
                <li class="nav-item"> <a class="nav-link" href="/dentalscape/dental-report/">Dental Records</a></li>
              </ul>
            </div>
          </li>
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
              <i class="fas fa-user-shield menu-icon"></i>
              <span class="menu-title">Users Access & <br> Security Logs</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="icons">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="/dentalscape/user-access-logs/">Login Attemps</a>
                  <div class="collapse" id="icons">
                  </div>
                </li>
                <li class="nav-item"> <a class="nav-link" href="/dentalscape/user-lockout/">Account Lockout</a></li>
                  <div class="collapse" id="icons">
                  </div>
                <li class="nav-item"> <a class="nav-link" href="/dentalscape/user-otp/">OTP Usage</a></li>
                  <div class="collapse" id="icons">
                  </div>
                <li class="nav-item"> <a class="nav-link" href="/dentalscape/user-password/">Password Change <br>
                  / Resets</a></li>
                  <div class="collapse" id="icons">
                  </div>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
              <i class="fas fa-address-book menu-icon"></i>
              <span class="menu-title"> Patient Logs</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="/dentalscape/patient-logs/">Patient Records Logs</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#error" aria-expanded="false" aria-controls="error">
              <i class="fas fa-wrench menu-icon"></i>
              <span class="menu-title">Admin Panel <br> & Audit Logs</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="error">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="/dentalscape/admin-logs/">Login Attemps</a></li>
                <li class="nav-item"> <a class="nav-link" href="/dentalscape/admin-otp/"> OTP Usage </a></li>
              </ul>
            </div>
          </li>
        <?php endif; ?>
        </ul>
      </nav>

      <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                          <?php
                          if (isset($_SESSION['role'])) {
                              $role = ucfirst($_SESSION['role']); 
                              echo "<h3 class='font-weight-bold'>Welcome, $role!</h3>";
                          } else {
                              echo "<h3 class='font-weight-bold'>Welcome!</h3>";
                          }
                          ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="row">
                <div class="col-md-12 grid-margin transparent">
                    <div class="row">
                        <div class="col-md-4 mb-4 stretch-card transparent">
                            <a href="/dentalscape/user-management/" class="card card-tale text-decoration-none">
                                <div class="card-body">
                                    <p class="mb-4">Total Registered Accounts</p>
                                    <p class="fs-30 mb-2"><?php echo htmlentities($total_users, ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-4 stretch-card transparent">
                            <a href="/dentalscape/pending-appointments/" class="card card-dark-blue text-decoration-none">
                                <div class="card-body">
                                    <p class="mb-4">Total Unconfirmed Appointments</p>
                                    <p class="fs-30 mb-2"><?php echo htmlentities($totalPendingAppointments, ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-4 stretch-card transparent">
                            <a href="/dentalscape/confirmed-appointments/" class="card card-light-blue text-decoration-none">
                              <div class="card-body">
                                  <p class="mb-4">Total Scheduled Appointments</p>
                                  <p class="fs-30 mb-2"><?php echo htmlentities($totalUpcomingAppointments, ENT_QUOTES, 'UTF-8'); ?></p>
                              </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

                      

<div class="row">
  <div class="col-md-12 grid-margin">
    <div class="calendar-section">
      <!-- Put title + button in the same flex container -->
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h2 class="section-title mb-0">
          Appointment Calendar
        </h2>
        <button onclick="window.location.href='http://localhost/dentalscape/admin/calendar_feed.php'" 
                class="btn btn-primary ml-2">
          + 
        </button>
      </div>
      <div id='calendar'></div>
    </div>
  </div>
</div>




            <!-- Post Announcement Section -->
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Post an Announcement</h4>
                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger">
                                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                </div>
                            <?php endif; ?>

                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <div class="form-group">
                                    <label for="announcement_title">Title</label>
                                    <input type="text" class="form-control" id="announcement_title" name="title" required>
                                </div>
                                <div class="form-group">
                                    <label for="announcement_content">Content</label>
                                    <textarea class="form-control" id="announcement_content" name="content" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary" name="post_announcement">Post Announcement</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Toast Notifications -->
            <div class="toast-container">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="toast toast-success">
                        <div class="toast-content">
                            <i class="fas fa-check-circle toast-icon"></i>
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                        <button class="toast-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="toast toast-error">
                        <div class="toast-content">
                            <i class="fas fa-exclamation-circle toast-icon"></i>
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                        <button class="toast-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
  </div>

      <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                  <div class="modal-body text-center p-5">
                    <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; opacity: 0.1;"></div>
                    <div style="position: absolute; bottom: -30px; left: -30px; width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; opacity: 0.08;"></div>
                      <div class="mb-4">
                          <div class="d-inline-flex align-items-center justify-content-center rounded-circle shadow" style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                              <i class="fas fa-sign-out-alt text-white" style="font-size: 36px;"></i>
                          </div>
                      </div>
                      <h4 class="font-weight-bold mb-3" style="color: #2d3748;">Confirm Sign Out</h4>
                      <p class="mb-4" style="color: #718096; font-size: 15px; line-height: 1.6;">
                          You're about to end your current session.<br>
                          Are you sure you want to sign out?
                      </p>
                      <div>
                          <button type="button" class="btn btn-secondary" data-dismiss="modal" style="min-width: 100px; height: 42px;">Cancel</button>
                          <a href="/dentalscape/admin/admin_logout.php" class="btn btn-danger ml-2" style="min-width: 100px; height: 42px; display: inline-flex; align-items: center; justify-content: center;"><i class="fas fa-sign-out-alt mr-1"></i> Sign Out</a>
                      </div>
                  </div>
              </div>
          </div>
      </div>



  <script src="../assets/user/vendors/js/vendor.bundle.base.js"></script>
  <script src="../assets/user/vendors/chart.js/Chart.min.js"></script>
  <script src="../assets/user/vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="../assets/user/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <script src="../assets/user/js/dataTables.select.min.js"></script>
  <script src="../assets/user/js/off-canvas.js"></script>
  <script src="../assets/user/js/hoverable-collapse.js"></script>
  <script src="../assets/user/js/template.js"></script>
  <script src="../assets/user/js/settings.js"></script>
  <script src="../assets/user/js/todolist.js"></script>
  <script src="../assets/user/js/dashboard.js"></script>
  <script src="../assets/user/js/Chart.roundedBarCharts.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../assets/js/search-bar.js"></script>
  <script src="../assets/js/toast.js"></script>
  <script src="../assets/js/security.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>

<script>
$(document).ready(function() {
    $('#calendar').fullCalendar({
        themeSystem: 'bootstrap4',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listMonth'
        },
        weekNumbers: false,
        eventLimit: 3,
        contentHeight: 650,
        aspectRatio: 1.5,
        firstDay: 1,
        events: '/dentalscape/admin/admin_process.php?action=fetch_events',
        eventClick: function(event) {
            var alertText = 
                "Appointment ID: " + event.id + "\n" +
                "Name: " + event.full_name + "\n" +
                "Purpose: " + event.purpose + " [" + event.service_type + "]\n" +
                "Date: " + moment(event.start).format('MMMM Do YYYY') + "\n" +
                "Time: " + moment(event.start).format('h:mm a') + "\n" +
                "Status: " + event.status;

            alert(alertText);
        }
    });
});
</script>

  <script>
    const features = [
      { name: "Confirmed Appointments", url: "admin_confirmed_appointments.php", keywords: ["confirmed", "appointment", "schedule", "approved"] },
      { name: "View Patient Records", url: "admin_patient_records.php", keywords: ["patient", "records", "view", "history", "medical"] },
      { name: "User Account Management", url: "admin_user_management.php", keywords: ["user", "account", "manage", "delete", "edit"] },
      { name: "User Access Logs", url: "admin_user_access_logs.php", keywords: ["user", "logs", "history", "access", "activity"] },
      { name: "Employee Logs", url: "admin_employee_logs.php", keywords: ["employee", "logs", "attendance", "history", "work"] },
      { name: "Employee Account Management", url: "admin_employee_management.php", keywords: ["employee", "manage", "account", "profile"] },
      { name: "History Appointments", url: "admin_history_appointments.php", keywords: ["appointment", "records", "view", "history", "past"] },
      { name: "Pending Appointments", url: "admin_pending_appointments.php", keywords: ["pending", "appointment", "schedule", "waiting"] },
      { name: "User Report Analytics", url: "admin_user_report.php", keywords: ["user", "report", "analytics", "statistics", "data"] },
      { name: "Appointment Report Analytics", url: "admin_appointments_report.php", keywords: ["appointment", "report", "analytics", "statistics", "data"] }
    ];

    function createSuggestionsDropdown() {
      const searchInput = document.getElementById('navbar-search-input');
      const inputParent = searchInput.parentElement;
      
      const dropdown = document.createElement('div');
      dropdown.id = 'search-suggestions';
      dropdown.className = 'search-suggestions-dropdown';
      dropdown.style.display = 'none';
      dropdown.style.position = 'absolute';
      dropdown.style.top = '100%';
      dropdown.style.left = '0';
      dropdown.style.right = '0';
      dropdown.style.zIndex = '1000';
      dropdown.style.backgroundColor = '#fff';
      dropdown.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
      dropdown.style.borderRadius = '0 0 4px 4px';
      dropdown.style.maxHeight = '300px';
      dropdown.style.overflowY = 'auto';
      
      inputParent.style.position = 'relative';
      inputParent.appendChild(dropdown);
      
      return dropdown;
    }

    function initSearch() {
      const searchInput = document.getElementById('navbar-search-input');
      const dropdown = createSuggestionsDropdown();
      
      searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        
        if (query.length < 1) {
          dropdown.style.display = 'none';
          return;
        }
        
        const filteredFeatures = features.filter(feature => {
          if (feature.name.toLowerCase().startsWith(query)) return true;
          
          return feature.keywords.some(keyword => keyword.toLowerCase().startsWith(query));
        });

        if (filteredFeatures.length > 0) {
          dropdown.innerHTML = '';
          filteredFeatures.forEach(feature => {
            const item = document.createElement('div');
            item.className = 'suggestion-item';
            item.textContent = feature.name;
            item.style.padding = '10px 15px';
            item.style.cursor = 'pointer';
            item.style.borderBottom = '1px solid #eee';
            
            const matchText = feature.name;
            const matchIndex = matchText.toLowerCase().indexOf(query);
            if (matchIndex >= 0) {
              item.innerHTML = matchText.substring(0, matchIndex) + 
                              '<strong>' + matchText.substring(matchIndex, matchIndex + query.length) + '</strong>' + 
                              matchText.substring(matchIndex + query.length);
            }
            
            item.addEventListener('mouseover', function() {
              this.style.backgroundColor = '#f5f5f5';
            });
            
            item.addEventListener('mouseout', function() {
              this.style.backgroundColor = 'transparent';
            });
            
            item.addEventListener('click', function() {
              window.location.href = feature.url;
            });
            
            dropdown.appendChild(item);
          });
          
          dropdown.style.display = 'block';
        } else {
          dropdown.style.display = 'none';
        }
      });
      
      document.addEventListener('click', function(e) {
        if (e.target !== searchInput && !dropdown.contains(e.target)) {
          dropdown.style.display = 'none';
        }
      });
      
      searchInput.addEventListener('keydown', function(e) {
        if (dropdown.style.display === 'none') return;
        
        const items = dropdown.querySelectorAll('.suggestion-item');
        const activeItem = dropdown.querySelector('.suggestion-item.active');
        let activeIndex = -1;
        
        if (activeItem) {
          for (let i = 0; i < items.length; i++) {
            if (items[i] === activeItem) {
              activeIndex = i;
              break;
            }
          }
        }
        
        if (e.key === 'ArrowDown') {
          e.preventDefault();
          if (activeItem) activeItem.classList.remove('active');
          
          activeIndex = (activeIndex + 1) % items.length;
          items[activeIndex].classList.add('active');
          items[activeIndex].style.backgroundColor = '#e9ecef';
        }
        else if (e.key === 'ArrowUp') {
          e.preventDefault();
          if (activeItem) activeItem.classList.remove('active');
          
          activeIndex = (activeIndex - 1 + items.length) % items.length;
          items[activeIndex].classList.add('active');
          items[activeIndex].style.backgroundColor = '#e9ecef';
        }
        else if (e.key === 'Enter') {
          e.preventDefault();
          if (activeItem) {
            window.location.href = features[activeIndex].url;
          }
        }
      });
    }

    document.addEventListener('DOMContentLoaded', initSearch);
  </script>
  

</body>

</html>

