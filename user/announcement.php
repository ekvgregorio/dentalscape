<?php
  session_start();
  require "../conn.php";

  if (!isset($_SESSION['user_id'])) {
      header("Location: ../login.php");
      exit();
  }

  $user_id = $_SESSION['user_id'];
  $fullname = $_SESSION['fullname'] ?? 'Guest';
  $events = [];

  function getConfirmedAppointmentsCount($conn, $user_id) {
      $query = "SELECT COUNT(*) as total 
                FROM appointments 
                WHERE user_id = ? 
                  AND status = 'Confirmed'
                  AND appointment_date > CURDATE()"; 

      $stmt = $conn->prepare($query);
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();

      $row = $result->fetch_assoc();
      $stmt->close();

      return $row ? $row['total'] : 0;
  }

  function getTodaysAppointmentsCount($conn, $user_id) {
      $sql = "SELECT COUNT(*) AS total 
              FROM appointments 
              WHERE user_id = ? 
              AND DATE(appointment_date) = CURDATE()";

      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result()->fetch_assoc();

      return $result['total'] ?? 0;
  }

  $confirmed_count = getConfirmedAppointmentsCount($conn, $user_id);
  $todays_count = getTodaysAppointmentsCount($conn, $user_id); 


  $sql = "SELECT purpose_of_appointment, appointment_date, appointment_time, service_type 
          FROM appointments 
          WHERE appointment_date >= CURDATE() AND status = 'Confirmed' AND user_id = ?";
  $stmt = $conn->prepare($sql);
  if ($stmt) {
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();

      while ($row = $result->fetch_assoc()) {
          $date = $row['appointment_date'];
          $time = date("g:i A", strtotime($row['appointment_time']));
          $title = $row['purpose_of_appointment'] . ' (' . $row['service_type'] . ')';

          $events[$date][] = [
              "title" => $title,
              "time" => $time,
              "attendees" => 1,
              "location" => "Clinic",
              "type" => "appointment"
          ];
      }
      $stmt->close();
  }

  echo "<script>";
  echo "const events = " . json_encode($events, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ";";
  echo "</script>";

  $countMonthlyAnnouncementsQuery = "
    SELECT COUNT(*) AS total 
    FROM announcements 
    WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
      AND YEAR(created_at) = YEAR(CURRENT_DATE())
    ";
    $countResult = $conn->query($countMonthlyAnnouncementsQuery);
    $countRow = $countResult->fetch_assoc();
    $monthlyAnnouncements = $countRow['total'];

    $missedQuery = "
    SELECT COUNT(*) AS missed_count
    FROM appointments
    WHERE appointment_date < CURDATE()
      AND status NOT IN ('Done', 'Canceled', 'Pending')

    ";

    $missedResult = $conn->query($missedQuery);
    $missedRow = $missedResult->fetch_assoc();
    $missedAppointments = $missedRow['missed_count'];

    $currentMonth = date('m');
    $currentYear = date('Y');

    // Total Appointments (all statuses) This Month
    $stmt = $conn->prepare("
        SELECT COUNT(*) 
        FROM appointments 
        WHERE user_id = ? 
          AND MONTH(appointment_date) = ? 
          AND YEAR(appointment_date) = ?
    ");

    $stmt->bind_param("iii", $user_id, $currentMonth, $currentYear);
    $stmt->execute();
    $stmt->bind_result($totalAppointments);
    $stmt->fetch();
    $stmt->close();

    // Confirmed Appointments This Month
    $stmt = $conn->prepare("
        SELECT COUNT(*) 
        FROM appointments 
        WHERE user_id = ? 
          AND status = 'Confirmed' 
          AND MONTH(appointment_date) = ? 
          AND YEAR(appointment_date) = ?
    ");
    $stmt->bind_param("iii", $user_id, $currentMonth, $currentYear);
    $stmt->execute();
    $stmt->bind_result($confirmedAppointments);
    $stmt->fetch();
    $stmt->close();


    // Missed Appointments (only current month)
    $stmt = $conn->prepare("
        SELECT COUNT(*) 
        FROM appointments 
        WHERE user_id = ? 
          AND appointment_date < CURDATE() 
          AND status IN ('Pending','Confirmed')
          AND MONTH(appointment_date) = ? 
          AND YEAR(appointment_date) = ?
    ");
    $stmt->bind_param("iii", $user_id, $currentMonth, $currentYear);
    $stmt->execute();
    $stmt->bind_result($missedAppointments);
    $stmt->fetch();
    $stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DentalScape Iloilo </title>
  <link href="../assets/user/images/logo1.png" rel="icon">
  <link href="../assets/user/images/logo1.png" rel="apple-touch-icon">
  <link rel="stylesheet" href="../assets/user/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/user/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/user/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/user/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="../assets/user/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="../assets/user//css" href="js/select.dataTables.min.css">
  <link rel="stylesheet" href="../assets/user/css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<style>
    .announcements-section {
      background: white;
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
      max-width: 1200px;
      margin: 0 auto;
    }

    .section-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid #e8ecf1;
    }

    .section-header h2 {
      font-size: 1.5rem;
      font-weight: 600;
      color: #1a1d29;
    }

    .view-all {
      color: #6366f1;
      font-size: 0.875rem;
      font-weight: 500;
      text-decoration: none;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      transition: background 0.2s;
    }

    .view-all:hover {
      background: #f0f1ff;
    }

    .announcements-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .announcement-item {
      padding: 1.25rem;
      border: 1px solid #e8ecf1;
      border-radius: 12px;
      transition: all 0.2s;
      cursor: pointer;
    }

    .announcement-item:hover {
      border-color: #6366f1;
      box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
      transform: translateY(-2px);
    }

    .announcement-header {
      display: flex;
      align-items: start;
      gap: 0.75rem;
      margin-bottom: 0.75rem;
    }

    .announcement-icon {
      width: 36px;
      height: 36px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
      flex-shrink: 0;
    }

    .icon-info {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .icon-update {
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    }

    .icon-alert {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .icon-success {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .announcement-content {
      flex: 1;
    }

    .announcement-title {
      font-size: 1rem;
      font-weight: 600;
      color: #1a1d29;
      margin-bottom: 0.5rem;
    }

    .announcement-text {
      font-size: 0.875rem;
      color: #6b7280;
      line-height: 1.5;
      margin-bottom: 0.75rem;
    }

    .announcement-meta {
      display: flex;
      align-items: center;
      gap: 1rem;
      font-size: 0.8125rem;
      color: #9ca3af;
    }

    .meta-date::before {
      content: "📅";
      margin-right: 0.375rem;
    }

    .meta-badge {
      background: #f0f1ff;
      color: #6366f1;
      padding: 0.25rem 0.75rem;
      border-radius: 12px;
      font-weight: 500;
    }

    .empty-state {
      text-align: center;
      padding: 3rem 1rem;
      color: #9ca3af;
    }

    .empty-state-icon {
      font-size: 3rem;
      margin-bottom: 1rem;
      opacity: 0.5;
    }
</style>

<body>
  <div class="container-scroller">
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="/dentalscape/dashboard/"><img src="../assets/user/images/logo2.png" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="/dentalscape/dashboard/"><img src="../assets/user/images/logo5.png" alt="logo"/></a>
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
    <div class="container-fluid page-body-wrapper">
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="/dentalscape/dashboard/">
              <i class="fas fa-grip-horizontal menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="fas fa-calendar-check menu-icon"></i>
              <span class="menu-title">Appointments</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="/dentalscape/booking/">Book Appointments</a></li>
                <li class="nav-item"> <a class="nav-link" href="/dentalscape/pending/">List of Pending <br> Appointments</a></li>
                <li class="nav-item"> <a class="nav-link" href="/dentalscape/reschedule/">Reschedule & <br> Cancellations</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
              <i class="fas fa-medkit menu-icon"></i>
              <span class="menu-title">Health Records</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="form-elements">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="/dentalscape/health-records/">View Records</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
              <i class="fas fa-user menu-icon"></i>
              <span class="menu-title">Account</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="charts">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="/dentalscape/account_management/">Account <br> Management</a></li>
                <li class="nav-item"> <a class="nav-link" href="/dentalscape/help-support/">Help & Support</a></li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>

      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                    <div class="announcements-section">
                        <div class="section-header">
                        <h2>Announcements</h2>
                    
                        </div>

                        <div class="announcements-list">
                        <?php
                        $result = $conn->query("
                            SELECT title, content, created_at 
                            FROM announcements 
                            ORDER BY created_at DESC 
                        ");
                        
                        if ($result->num_rows > 0) {
                            $icons = ['🔔', '✨', '🎉', '🔒', '👋'];
                            $colors = ['icon-info', 'icon-update', 'icon-success', 'icon-alert', 'icon-info'];
                            $i = 0;
                            
                            while ($row = $result->fetch_assoc()) {
                            $icon = $icons[$i % count($icons)];
                            $color = $colors[$i % count($colors)];
                            
                            echo "<div class='announcement-item'>";
                            echo "<div class='announcement-header'>";
                            echo "<div class='announcement-icon $color'>$icon</div>";
                            echo "<div class='announcement-content'>";
                            echo "<h3 class='announcement-title'>" . htmlentities($row['title'], ENT_QUOTES, 'UTF-8') . "</h3>";
                            echo "<p class='announcement-text'>" . nl2br(htmlentities($row['content'], ENT_QUOTES, 'UTF-8')) . "</p>";
                            echo "<div class='announcement-meta'>";
                            echo "<span class='meta-date'>" . date("M j, Y", strtotime($row['created_at'])) . "</span>";
                            echo "<span class='meta-badge'>Update</span>";
                            echo "</div></div></div></div>";
                            
                            $i++;
                            }
                        } else {
                            echo "<div class='empty-state'>";
                            echo "<div class='empty-state-icon'>📭</div>";
                            echo "<p>No announcements yet.</p>";
                            echo "</div>";
                        }
                        ?>
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
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
                    <a href="../logout.php" class="btn btn-danger ml-2" style="min-width: 100px; height: 42px; display: inline-flex; align-items: center; justify-content: center;"><i class="fas fa-sign-out-alt mr-1"></i> Sign Out</a>
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
  <script src="../assets/js/search-bar.js"></script>
  <script src="../assets/js/toast.js"></script>
  <script src="../assets/js/search-features.js"></script>
  <script src="../assets/js/user-calendar.js"></script>
  
</body>
</html>

