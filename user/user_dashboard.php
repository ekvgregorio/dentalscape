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

    

  .carousel-img {
  width: 75%;
  height: 170px;       /* Mobile default */
  object-fit: cover;
  }

  /* Tablet landscape and up */
  @media (min-width: 576px) {
    .carousel-img {
      height: 260px;
    }
  }

  /* Desktop */
  @media (min-width: 992px) {
    .carousel-img {
      height: 280px;
    }
  }

  .calendar-section {
    margin-top: 2rem;
}

.calendar-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    overflow: hidden;
    width: 100%;
}

.calendar-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.calendar-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
    margin: 0;
}

.calendar-nav {
    display: flex;
    gap: 0.5rem;
}

.nav-btn {
    background: none;
    border: none;
    padding: 0.5rem;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: background-color 0.2s;
    color: #6b7280;
}

.nav-btn:hover {
    background-color: #f3f4f6;
}

.calendar-body {
    padding: 1.5rem;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    margin-bottom: 1rem;
}

.day-header {
    padding: 0.75rem;
    text-align: center;
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
    background-color: #f9fafb;
}

.calendar-day {
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border-radius: 20px;
    transition: all 0.2s;
    position: relative;
    font-size: 0.875rem;
}

.calendar-day:hover {
    background-color: #f3f4f6;
}

.calendar-day.today {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
}

.calendar-day.selected {
    background-color: #dbeafe;
    color: #1d4ed8;
    font-weight: 500;
}

.calendar-day.has-event::after {
    content: '';
    position: absolute;
    bottom: 0.25rem;
    left: 50%;
    transform: translateX(-50%);
    width: 0.375rem;
    height: 0.375rem;
    background-color: #f59e0b;
    border-radius: 50%;
}

.calendar-day.today.has-event::after {
    background-color: #fbbf24;
}

.events-panel {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    padding: 1.5rem;
    margin-top: 1rem;
}

.events-header {
    font-size: 1.125rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 1rem;
}

.event-item {
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 0.75rem;
    transition: box-shadow 0.2s;
    background: #fff;
}

.event-item:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.event-title {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.75rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 1rem;
}

.event-type {
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    flex-shrink: 0;
}

.event-type.meeting { background-color: #3b82f6; }
.event-type.presentation { background-color: #8b5cf6; }
.event-type.review { background-color: #10b981; }

.event-details {
    font-size: 0.875rem;
    color: #6b7280;
    line-height: 1.6;
}

.event-detail {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.event-detail:last-child {
    margin-bottom: 0;
}

.event-icon {
    width: 16px;
    height: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.no-events {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}

.stats-card {
    background: linear-gradient(135deg, #8e9fed 0%, #6c77ec 100%);
    color: white;
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.stats-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.stats-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
}

.stats-label {
    color: rgba(255, 255, 255, 0.8);
}

.stats-value {
    font-weight: 600;
}

/* Tablet styles (768px - 1024px) */
@media (max-width: 1024px) {
    .calendar-section {
        margin-top: 1.5rem;
    }
    
    .calendar-card,
    .events-panel,
    .stats-card {
        border-radius: 16px;
    }
    
    .calendar-title {
        font-size: 1.125rem;
    }
    
    .stats-title,
    .events-header {
        font-size: 1rem;
    }
}

/* Mobile styles (max-width: 768px) */
@media (max-width: 768px) {
    .calendar-section {
        margin-top: 1rem;
    }
    
    .calendar-card,
    .events-panel,
    .stats-card {
        border-radius: 12px;
    }
    
    .calendar-header {
        padding: 1rem;
    }
    
    .calendar-title {
        font-size: 1rem;
    }
    
    .calendar-body,
    .events-panel,
    .stats-card {
        padding: 1rem;
    }
    
    .calendar-grid {
        font-size: 0.75rem;
        gap: 2px;
    }
    
    .day-header {
        padding: 0.5rem 0.25rem;
        font-size: 0.75rem;
    }
    
    .calendar-day {
        height: 2.5rem;
        font-size: 0.75rem;
        border-radius: 8px;
    }
    
    .calendar-day.has-event::after {
        width: 0.25rem;
        height: 0.25rem;
        bottom: 0.2rem;
    }
    
    .event-item {
        padding: 0.875rem;
        border-radius: 0.5rem;
    }
    
    .event-title {
        font-size: 0.9375rem;
        margin-bottom: 0.5rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .event-details {
        font-size: 0.8125rem;
    }
    
    .event-detail {
        gap: 0.375rem;
    }
    
    .stats-title,
    .events-header {
        font-size: 0.9375rem;
        margin-bottom: 0.75rem;
    }
    
    .stats-item {
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    .no-events {
        padding: 1.5rem;
        font-size: 0.875rem;
    }
}

/* Small mobile styles (max-width: 480px) */
@media (max-width: 480px) {
    .calendar-header {
        padding: 0.875rem;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .calendar-title {
        font-size: 0.9375rem;
        width: 100%;
        text-align: center;
    }
    
    .calendar-nav {
        width: 100%;
        justify-content: center;
    }
    
    .nav-btn {
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
    }
    
    .calendar-body,
    .events-panel,
    .stats-card {
        padding: 0.875rem;
    }
    
    .day-header {
        padding: 0.375rem 0.125rem;
        font-size: 0.6875rem;
    }
    
    .calendar-day {
        height: 2.25rem;
        font-size: 0.6875rem;
    }
    
    .event-item {
        padding: 0.75rem;
    }
    
    .event-title {
        font-size: 0.875rem;
    }
    
    .event-details {
        font-size: 0.75rem;
    }
    
    .event-icon {
        width: 14px;
        height: 14px;
    }
}

/* Extra small devices (max-width: 360px) */
@media (max-width: 360px) {
    .calendar-card,
    .events-panel,
    .stats-card {
        border-radius: 10px;
    }
    
    .calendar-day {
        height: 2rem;
        border-radius: 6px;
    }
    
    .day-header {
        font-size: 0.625rem;
    }
    
    .calendar-day.has-event::after {
        width: 0.2rem;
        height: 0.2rem;
    }
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


      <!-- Main -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">Welcome, <?= htmlspecialchars($fullname, ENT_QUOTES, 'UTF-8'); ?> !</h3>
                  <h6 class="font-weight-normal mb-0">
                  Here is the most <span class="text-primary">recent announcement</span> regarding important changes, updates, or upcoming events.
                  Please take a moment to read through the details and stay informed.
                  </h6>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card"> 
              <div class="card tale-bg">
                <div class="card-people mt-auto">
                  <div id="galleryCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
                    <ol class="carousel-indicators">
                      <li data-target="#galleryCarousel" data-slide-to="0" class="active"></li>
                      <li data-target="#galleryCarousel" data-slide-to="1"></li>
                      <li data-target="#galleryCarousel" data-slide-to="2"></li>
                      <li data-target="#galleryCarousel" data-slide-to="3"></li>
                      <li data-target="#galleryCarousel" data-slide-to="4"></li>
                      <li data-target="#galleryCarousel" data-slide-to="5"></li>
                      <li data-target="#galleryCarousel" data-slide-to="6"></li>
                    </ol>
                    <div class="carousel-inner">
                      <div class="carousel-item active">
                        <img class="d-block w-100 carousel-img" src="../assets/user/images/1.png" alt="First slide">
                      </div>
                      <div class="carousel-item">
                        <img class="d-block w-100 carousel-img" src="../assets/user/images/2.png" alt="Second slide">
                      </div>
                      <div class="carousel-item">
                        <img class="d-block w-100 carousel-img" src="../assets/user/images/3.png" alt="Third slide">
                      </div>
                      <div class="carousel-item">
                        <img class="d-block w-100 carousel-img" src="../assets/user/images/4.png" alt="Fourth slide">
                      </div>
                      <div class="carousel-item">
                        <img class="d-block w-100 carousel-img" src="../assets/user/images/5.png" alt="Fifth slide">
                      </div>
                      <div class="carousel-item">
                        <img class="d-block w-100 carousel-img" src="../assets/user/images/6.png" alt="Sixth slide">
                      </div>
                      <div class="carousel-item">
                        <img class="d-block w-100 carousel-img" src="../assets/user/images/7.png" alt="Seventh slide">
                      </div>
                    </div>
                    <a class="carousel-control-prev" href="#galleryCarousel" role="button" data-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#galleryCarousel" role="button" data-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="sr-only">Next</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin transparent">
              <div class="row">
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-tale">
                    <div class="card-body">
                        <p class="mb-4">Upcoming Appointments</p>
                        <p class="fs-30 mb-2"><?php echo $confirmed_count; ?></p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-4 stretch-card transparent">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <p class="mb-4">Monthly Announcements</p>
                      <p class="fs-30 mb-2"><?php echo $monthlyAnnouncements; ?></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                  <div class="card card-light-blue">
                    <div class="card-body">
                      <p class="mb-4">Today's Appointment</p>
                      <p class="fs-30 mb-2"><?php echo $todays_count; ?></p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 stretch-card transparent">
                  <div class="card card-light-danger">
                    <div class="card-body">
                      <p class="mb-4">Missed Appointments</p>
                      <p class="fs-30 mb-2"><?php echo $missedAppointments; ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row calendar-section">
            <div class="col-lg-4 col-md-12 grid-margin">
              <div class="stats-card">
                <div class="stats-title">Monthly Overview</div>
                  <div class="stats-item">
                    <span class="stats-label">Total Appointments</span>
                    <span class="stats-value"><?php echo $totalAppointments; ?></span>
                  </div>

                  <div class="stats-item">
                    <span class="stats-label">Confirmed Appointments</span>
                    <span class="stats-value"><?php echo $confirmedAppointments; ?></span>
                  </div>

                  <div class="stats-item">
                    <span class="stats-label">Missed Appointments</span>
                    <span class="stats-value"><?php echo $missedAppointments; ?></span>
                  </div>
                </div>
                <div class="events-panel">
                  <div class="events-header" id="events-date">Events for Today</div>
                    <div id="events-list">   
                  </div>
                </div>
              </div>
              <div class="col-lg-8 col-md-12 grid-margin stretch-card">
                <div class="calendar-card">
                  <div class="calendar-header">
                    <h3 class="calendar-title" id="calendar-title">May 2025</h3>
                    <div class="calendar-nav">
                      <button class="nav-btn" onclick="previousMonth()">‹</button>
                      <button class="nav-btn" onclick="nextMonth()">›</button>
                      </div>
                    </div>
                    <div class="calendar-body">
                      <div class="calendar-grid" id="calendar-grid">
                        <div class="day-header">Sun</div>
                        <div class="day-header">Mon</div>
                        <div class="day-header">Tue</div>
                        <div class="day-header">Wed</div>
                        <div class="day-header">Thu</div>
                        <div class="day-header">Fri</div>
                        <div class="day-header">Sat</div>
                      </div>
                    </div>
                  </div>
               </div>
               </div>
              <div class="row">
                <div class="col-md-12 grid-margin">
              <div class="announcements-section">
                <div class="section-header">
                  <h2>Recent Announcements</h2>
                  <a href="/dentalscape/announcements/" class="view-all">View All →</a>
                </div>

                <div class="announcements-list">
                  <?php
                  $result = $conn->query("
                    SELECT title, content, created_at 
                    FROM announcements 
                    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
                    ORDER BY created_at DESC 
                    LIMIT 5
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
                      
                      <!-- Title -->
                      <h4 class="font-weight-bold mb-3" style="color: #2d3748;">Confirm Sign Out</h4>
                      
                      <!-- Description -->
                      <p class="mb-4" style="color: #718096; font-size: 15px; line-height: 1.6;">
                          You're about to end your current session.<br>
                          Are you sure you want to sign out?
                      </p>
                      
                      <!-- Buttons with equal width and height -->
                      <div>
                          <button type="button" class="btn btn-secondary" data-dismiss="modal" style="min-width: 100px; height: 42px;">Cancel</button>
                          <a href="../logout.php" class="btn btn-danger ml-2" style="min-width: 100px; height: 42px; display: inline-flex; align-items: center; justify-content: center;"><i class="fas fa-sign-out-alt mr-1"></i> Sign Out</a>
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
  <script src="../assets/js/search-bar.js"></script>
  <script src="../assets/js/toast.js"></script>
  <script src="../assets/js/security.js"></script>
  <script src="../assets/js/search-features.js"></script>
  <script src="../assets/js/user-calendar.js"></script>
  
</body>
</html>

