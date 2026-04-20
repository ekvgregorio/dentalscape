<?php 
require_once 'report_process.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied. Admins only.'); 
    window.location.href = 'admin_login.php';</script>";
    exit;
}

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
  <link rel="stylesheet" href="../assets/admin//vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/admin/css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="../assets/admin/images/favicon.png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<style>

canvas {
    max-width: 100% !important;
    height: 270px !important;
  }

  #pieChart, #doughnutChart, #lineChart{
      max-width: 350px;
      height: 350px;
      margin: auto; 
  }

  @media print {
    body::before {
          content: "Confidential - Administrator's Copy"; 
          position: fixed;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%) rotate(-45deg); 
          font-size: 70px; 
          font-weight: bold;
          color: rgba(0, 0, 0, 0.1); 
          z-index: 9999;
          pointer-events: none;
          white-space: nowrap;
          text-transform: uppercase;
      }

      body {
        font-family: Timew New Roman;
        font-size: 10px;
      }


          #printHeader {
              display: block !important;
              font-family: Times New Roman;
          }

          .navbar-brand-wrapper, 
          .navbar-menu-wrapper, 
          .theme-setting-wrapper, 
          .sidebar,
          .card-header-body,
          .btn,
          .footer {
              display: none !important;
          }
        }
</style>

<body>
  <div class="container-scroller">
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="/dentalscape/admin-dashboard/"><img src="../assets/admin/images/logo3.png" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="/dentalscape/admin-dashboard/"><img src="../assets/admin/images/logo1.png" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>

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
>

      <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Daily User Engagement</h4>
                <canvas id="doughnutChart"></canvas>
                <?php
                  $active = $active_data['active'];
                  $inactive = $active_data['inactive'];
                  $total = $active + $inactive;

                  $active_percent = $total > 0 ? round(($active / $total) * 100, 2) : 0;
                  $inactive_percent = $total > 0 ? round(($inactive / $total) * 100, 2) : 0;

                  $summary = "A total of $total users have been recorded. Among them, $active users are currently active, representing $active_percent% of the user base, while $inactive users are inactive ($inactive_percent%). ";

                  if ($active_percent >= 75) {
                      $summary .= "This indicates a strong level of user engagement.";
                  } elseif ($inactive_percent >= 50) {
                      $summary .= "This suggests a need for re-engagement strategies.";
                  } else {
                      $summary .= "User activity is balanced, but there is room for improvement.";
                  }
                ?>
                 <p class="mt-3 text-muted">
                  <strong>Summary:</strong> <?= $summary ?>
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Users Verified/Unverified</h4>
                <canvas id="pieChart"></canvas>
                <?php
                  $verified = $verified_data['verified'];
                  $unverified = $verified_data['unverified'];
                  $total = $verified + $unverified;

                  $verified_percent = $total > 0 ? round(($verified / $total) * 100, 2) : 0;
                  $unverified_percent = $total > 0 ? round(($unverified / $total) * 100, 2) : 0;

                  $summary = "From a total of $total users, $verified have completed verification, comprising $verified_percent% of the user base. Meanwhile, $unverified users remain unverified ($unverified_percent%). ";

                  if ($verified_percent >= 80) {
                    $summary .= "The verification process is proceeding successfully.";
                  } elseif ($unverified_percent >= 50) {
                    $summary .= "A large portion of users have yet to complete verification and may need assistance or follow-up.";
                  } else {
                      $summary .= "Verification progress is steady but could be improved.";
                  }
                ?>
                <p class="mt-3 text-muted">
                  <strong>Summary:</strong> <?= $summary ?>
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">User Activity Over Time</h4>
                <canvas id="lineChart"></canvas>
                <?php
                  $labels = $login_dates; 
                  $success = $success_counts;
                  $failed = $failed_counts;

                  if (!empty($success) && !empty($failed)) {
                      $total_success = array_sum($success);
                      $total_failed = array_sum($failed);

                      $max_success = max($success);
                      $max_failed = max($failed);

                      $peak_success_date = $labels[array_search($max_success, $success)];
                      $peak_failed_date = $labels[array_search($max_failed, $failed)];

                      $summary = "Between " . $labels[0] . " and " . end($labels) . ", there were a total of $total_success successful logins and $total_failed failed logins. ";
                      $summary .= "The highest number of successful logins was $max_success on $peak_success_date. ";
                      $summary .= "The most failed logins occurred on $peak_failed_date with $max_failed attempts. ";

                      if ($total_success > $total_failed) {
                          $summary .= "Overall, more logins were successful than failed.";
                      } else if ($total_success < $total_failed) {
                          $summary .= "Interestingly, there were more failed login attempts than successful ones.";
                      } else {
                          $summary .= "The number of successful and failed login attempts was equal.";
                      }
                  } else {
                      $summary = "No login activity data available for the selected period.";
                  }
                ?>
                <p class="mt-3 text-muted">
                  <strong>Summary:</strong> <?= $summary ?>
                </p>
              </div>
            </div>
          </div>
                  <div class="col-md-6 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">User Growth Over Time</h4>
      <canvas id="userGrowthChart"></canvas>
      <?php
if (!empty($growth_labels) && !empty($growth_counts)) {
    $total_users = array_sum($growth_counts);
    $first_period = $growth_labels[0];
    $last_period = end($growth_labels);

    $max_users = max($growth_counts);
    $peak_period = $growth_labels[array_search($max_users, $growth_counts)];

    $average_users = round($total_users / count($growth_counts), 2);

    $summary_growth = "Between $first_period and $last_period, a total of $total_users users registered. ";
    $summary_growth .= "The highest number of new users was $max_users during $peak_period. ";
    $summary_growth .= "On average, there were about $average_users new users per period. ";

    if ($max_users > $average_users * 1.5) {
        $summary_growth .= "This indicates a strong peak in user growth during $peak_period.";
    } else {
        $summary_growth .= "User registrations were relatively consistent over time.";
    }
} else {
    $summary_growth = "No user registration data available for the selected period.";
}
?>

<p class="mt-3 text-muted">
  <strong>Summary:</strong> <?= $summary_growth ?>
</p>

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
  <script src="../assets/admin/vendors/js/vendor.bundle.base.js"></script>
  <script src="../assets/admin/vendors/chart.js/Chart.min.js"></script>
  <script src="../assets/admin/js/off-canvas.js"></script>
  <script src="../assets/admin/js/hoverable-collapse.js"></script>
  <script src="../assets/admin/js/template.js"></script>
  <script src="../assets/admin/js/settings.js"></script>
  <script src="../assets/admin/js/todolist.js"></script>
  <script src="../assets/js/security.js"></script>

  <script>
      // Doughnut Chart - Users Active/Inactive
      const activeUsersData = {
          labels: ['Active', 'Inactive'],
          datasets: [{
              data: [<?php echo $active_data['active']; ?>, <?php echo $active_data['inactive']; ?>],
              backgroundColor: ['#f779eb', '#e03fd0']
          }]
      };
      new Chart(document.getElementById('doughnutChart'), { type: 'doughnut', data: activeUsersData, options: { responsive: true } });


      const verifiedUsersData = {
          labels: ['Verified', 'Unverified'],
          datasets: [{
              data: [<?php echo $verified_data['verified']; ?>, <?php echo $verified_data['unverified']; ?>],
              backgroundColor: ['#6A0DAD', '#A0C4FF']
          }]
      };
      new Chart(document.getElementById('pieChart'), { type: 'pie', data: verifiedUsersData, options: { responsive: true } });


      // Line Chart - User Activity Over Time
      const loginData = {
          labels: <?php echo json_encode($login_dates); ?>,
          datasets: [
              {
                  label: 'Successful Logins',
                  data: <?php echo json_encode($success_counts); ?>,
                  borderColor: '#36A2EB',
                  backgroundColor: 'rgba(54, 162, 235, 0.2)',
                  fill: true
              },
              {
                  label: 'Failed Logins',
                  data: <?php echo json_encode($failed_counts); ?>,
                  borderColor: '#6A0DAD',
                  backgroundColor: 'rgba(249, 76, 243, 0.83)',
                  fill: true
              }
          ]
      };
      new Chart(document.getElementById('lineChart'), { type: 'line', data: loginData, options: { responsive: true } });
      const growthLabels = <?= json_encode($growth_labels) ?>;
      const growthCounts = <?= json_encode($growth_counts) ?>;

      new Chart(document.getElementById('userGrowthChart'), {
        type: 'line',
        data: {
          labels: growthLabels,
          datasets: [{
            label: 'New Users',
            data: growthCounts,
            borderWidth: 2,
            fill: false,
            tension: 0.3,
            borderColor: 'rgba(54, 162, 235, 1)',      
            backgroundColor: 'rgba(54, 162, 235, 0.2)', 
            pointBackgroundColor: 'rgba(54, 162, 235, 1)', 
            pointBorderColor: 'rgba(255, 255, 255, 1)',    
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              ticks: { stepSize: 1 }
            }
          }
        }
      });



    </script>

<script>
  function printReport() {
    let navbar = document.querySelector(".navbar-brand-wrapper");
    let menuWrapper = document.querySelector(".navbar-menu-wrapper");
    let settings = document.querySelector(".theme-setting-wrapper");
    let sidebar = document.querySelector(".sidebar");
    let printHeader = document.getElementById("printHeader");

    if (printHeader) printHeader.style.display = "block";

    if (navbar) navbar.style.display = "none";
    if (menuWrapper) menuWrapper.style.display = "none";
    if (settings) settings.style.display = "none";
    if (sidebar) sidebar.style.display = "none";

    window.print();

    setTimeout(() => {
        if (printHeader) printHeader.style.display = "none";
        if (navbar) navbar.style.display = "flex";
        if (menuWrapper) menuWrapper.style.display = "flex";
        if (settings) settings.style.display = "block";
        if (sidebar) sidebar.style.display = "block";
    }, 1000);
}
</script>

</body>

</html>
