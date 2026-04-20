<?php 
require_once 'report_process.php';

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'secretary'])) {
    echo "<script>alert('Access denied.'); window.location.href = 'login.php';</script>";
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
  <link href="../assets/admin/images/logo.1png" rel="apple-touch-icon">
  <link rel="stylesheet" href="../assets/admin/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/admin/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/admin//vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/admin/css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="../assets/admin/images/favicon.png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.5.5/css/simple-line-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<style>
  canvas {
    max-width: 100% !important;
    height: 300px !important;
  }

  #pieChart, #doughnutChart, #barChartMonthly, #barChartTimeSlot, #barChartServiceType, #lineChart{
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
        <a class="navbar-brand brand-logo mr-5" href="/dentalscape/admin-dashboard/"><img src="../assets/admin/images/logo2.png" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="/dentalscape/admin-dashboard/"><img src="../assets/admin/images/logo5.png" alt="logo"/></a>
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

      <div class="main-panel">      
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Gender-Based Distribution</h4>
                  <canvas id="doughnutChart"></canvas>
                  <?php
                    $gender_query = "SELECT gender, COUNT(*) as count FROM appointments GROUP BY gender";
                    $gender_result = $conn->query($gender_query);

                    $gender_labels = [];
                    $gender_data = [];

                    while ($row = $gender_result->fetch_assoc()) {
                        $gender_labels[] = $row['gender'];
                        $gender_data[] = (int)$row['count'];
                    }

                    // Calculate summary
                    $male = 0;
                    $female = 0;
                    $total_gender = array_sum($gender_data);

                    foreach ($gender_labels as $index => $gender) {
                        if ($gender === 'Male') {
                            $male = $gender_data[$index];
                        } elseif ($gender === 'Female') {
                            $female = $gender_data[$index];
                        }
                    }

                    $male_percent = $total_gender > 0 ? round(($male / $total_gender) * 100, 2) : 0;
                    $female_percent = $total_gender > 0 ? round(($female / $total_gender) * 100, 2) : 0;

                    $gender_summary = "Out of $total_gender total appointments, $male were made by male patients ($male_percent%), and $female were made by female patients ($female_percent%). ";

                    if ($male_percent > $female_percent + 20) {
                        $gender_summary .= "This shows a strong preference or need among male patients.";
                    } elseif ($female_percent > $male_percent + 20) {
                        $gender_summary .= "Female patients are significantly more active in appointments.";
                    } else {
                        $gender_summary .= "The gender distribution is relatively balanced.";
                    }
                  ?>
                  <p class="mt-3 text-muted">
                    <strong>Summary:</strong> <?= $gender_summary ?>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Monthly Appointment Status</h4>
                    <canvas id="statusLineChart"></canvas>
                    <?php
                      $summaryLines = [];
                      $overall = ['Pending' => 0, 'Confirmed' => 0, 'Canceled' => 0, 'Done' => 0];
                      $visibleCount = 1;
                      $currentIndex = 0;

                      foreach ($month_names as $mNum => $mName) {
                        $monthlyCounts = [];
                        $monthlyTotal = 0;

                      foreach ($statuses as $status) {
                        $count = $datasetMap[$status][$mNum];
                        $monthlyCounts[$status] = $count;
                        $overall[$status] += $count;
                        $monthlyTotal += $count;
                      }

                      if ($monthlyTotal > 0) {
                        $statusDetails = [];
                        $topStatus = '';
                        $topCount = 0;

                        foreach ($monthlyCounts as $status => $count) {
                          if ($count > 0) {
                            $percent = round(($count / $monthlyTotal) * 100);
                            $statusDetails[] = "$count were $status ($percent%)";
                            if ($count > $topCount) {
                              $topCount = $count;
                              $topStatus = $status;
                            }
                          }
                        }
                        
                        $lineText = "In $mName, there were $monthlyTotal appointments. ";
                        $lineText .= implode(', ', $statusDetails) . ". Most appointments were <strong>$topStatus</strong>.";

                        $summaryLines[] = [
                          'text' => $lineText,
                          'visible' => $currentIndex < $visibleCount
                        ];
                        $currentIndex++;
                      }
                      }
                      $overallTotal = array_sum($overall);
                      $overallParts = [];
                      foreach ($overall as $status => $count) {
                        $percent = $overallTotal > 0 ? round(($count / $overallTotal) * 100) : 0;
                        $overallParts[] = "$count were $status ($percent%)";
                      }
                      $overallText = "<strong>Overall:</strong> There were $overallTotal appointments in total. " . implode(', ', $overallParts) . ".";

                      $summaryLines[] = [
                        'text' => $overallText,
                        'visible' => false
                      ];
                    ?>
                    <div class="mt-4">
                      <ul class="text-muted" id="monthlySummary" style="list-style-type: disc; padding-left: 20px;">
                        <?php foreach ($summaryLines as $index => $line): ?>
                          <li 
                          <?= !$line['visible'] ? 'class="extra-summary" style="display:none;"' : '' ?>
                          <?= $index === $visibleCount - 1 ? 'id="moreToggleLine"' : '' ?>>
                          <?= $line['text'] ?>
                          <?php if ($index === $visibleCount - 1): ?>
                            <span id="inlineToggleBtn" style="cursor:pointer; color:#007bff;" onclick="toggleSummary()"> [more]</span>
                            <?php endif; ?>
                          </li>
                              <?php endforeach; ?>
                              <li class="extra-summary" style="display:none; list-style-type:none;">
                                  <span id="lessToggleBtn" style="cursor:pointer; color:#007bff;" onclick="toggleSummary()">[less]</span>
                              </li>
                          </ul>
                      </div>
                    </div>
                </div>
            </div>

          </div>
          <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                 <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Appointments Trend (Daily, Weekly, Monthly)</h4>
                    <select id="chartFilter" 
                      class="form-select form-select-sm rounded-pill text-muted fw-light bg-light text-center" 
                      style="width: auto; min-width: 100px;">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                  </div>
                  <canvas id="trendChart"></canvas>
                </div>
              </div>
            </div>
              <div class="col-lg-6 grid-margin stretch-card">
                  <div class="card">
                      <div class="card-body">
                          <h4 class="card-title"> Age-Based Appointment Summary </h4>
                          <canvas id="barChartAgeService"></canvas>
                      </div>
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="col-lg-6 grid-margin stretch-card">
                  <div class="card">
                      <div class="card-body">
                          <h4 class="card-title">Appointments by Time Slot</h4>
                          <canvas id="barChartTimeSlot"></canvas>
                      </div>
                  </div>
              </div>
              <div class="col-lg-6 grid-margin stretch-card">
                  <div class="card">
                      <div class="card-body">
                          <h4 class="card-title">Monthly Appointment Summary</h4>
                          <canvas id="barChartMonthly"></canvas>
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    // Gender-Based Distribution
    const genderData = {
        labels: <?php echo json_encode($gender_labels); ?>,
        datasets: [{
            data: <?php echo json_encode($gender_data); ?>,
            backgroundColor: ['#36A2EB', '#e03fd0']
        }]
    };
    new Chart(document.getElementById('doughnutChart'), { type: 'doughnut', data: genderData, options: { responsive: true } });

    //Monthly Appointment Status
    const statusLineData = {
        labels: <?= json_encode($months) ?>,
        datasets: [
            {
                label: 'Pending',
                borderColor: '#9641e0',
                backgroundColor: '#be94e3',
                fill: true,
                tension: 0.4,
                data: <?= json_encode(array_values($datasetMap['Pending'])) ?>
            },
            {
                label: 'Confirmed',
                borderColor: '#e03fd0',
                backgroundColor: '#e394db',
                fill: true,
                tension: 0.4,
                data: <?= json_encode(array_values($datasetMap['Confirmed'])) ?>
            },
            {
                label: 'Canceled',
                borderColor: '#5965b5',
                backgroundColor: '#959ede',
                fill: true,
                tension: 0.4,
                data: <?= json_encode(array_values($datasetMap['Canceled'])) ?>
            },
            {
                label: 'Done',
                borderColor: '#007bff',
                backgroundColor: '#007bff33',
                fill: true,
                tension: 0.4,
                data: <?= json_encode(array_values($datasetMap['Done'])) ?>
            }
        ]
    };

    new Chart(document.getElementById('statusLineChart'), {
        type: 'line',
        data: statusLineData,
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false
                },
                legend: {
                    position: 'top'
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Appointments Count'
                    }
                }
            }
        }
    });

    //Appointments Trend (Daily, Weekly, Monthly)
    const chartData = {
        daily: {
            labels: <?php echo json_encode($daily_dates); ?>,
            data: <?php echo json_encode($daily_counts); ?>
        },
        weekly: {
            labels: <?php echo json_encode($weekly_labels); ?>,
            data: <?php echo json_encode($weekly_counts); ?>
        },
        monthly: {
            labels: <?php echo json_encode($monthly_labels); ?>,
            data: <?php echo json_encode($monthly_counts); ?>
        }
    };

    const ctx = document.getElementById('trendChart').getContext('2d');
    let trendChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.daily.labels,
            datasets: [{
                label: 'Appointments',
                data: chartData.daily.data,
                backgroundColor: '#9641e0'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    document.getElementById('chartFilter').addEventListener('change', function () {
        const selected = this.value;
        trendChart.data.labels = chartData[selected].labels;
        trendChart.data.datasets[0].data = chartData[selected].data;
        trendChart.update();
    });

    //Appointment Distribution by Service Type
    const ageServiceData = {
        labels: <?php echo json_encode($age_labels); ?>,
        datasets: <?php echo json_encode($datasets); ?>
    };

    new Chart(document.getElementById('barChartAgeService'), {
        type: 'bar',
        data: ageServiceData,
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Patient Age'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Number of Appointments'
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    title: {
                        display: true,
                        text: 'Service Types'
                    }
                },
                tooltip: {
                    callbacks: {
                        title: function(tooltipItems) {
                            return 'Age: ' + tooltipItems[0].label.replace('Age ', '');
                        },
                        label: function(context) {
                            return context.dataset.label + ': ' + context.formattedValue + ' appointments';
                        }
                    }
                }
            }
        }
    });

    //Appointments by Time Slot
    const timeSlotData = {
        labels: <?php echo json_encode($time_labels); ?>,
        datasets: [{
            label: 'Appointments',
            data: <?php echo json_encode($time_data); ?>,
            backgroundColor: '#7a63ff'
        }]
    };
    new Chart(document.getElementById('barChartTimeSlot'), { type: 'bar', data: timeSlotData, options: { responsive: true } });

    // Monthly Appointment Summary
    const monthlyAppointmentsData = {
        labels: <?php echo json_encode($monthly_labels); ?>,
        datasets: [{
            label: 'Appointments',
            data: <?php echo json_encode($monthly_data); ?>,
            backgroundColor: '#A0C4FF'
        }]
    };
    new Chart(document.getElementById('barChartMonthly'), { type: 'bar', data: monthlyAppointmentsData, options: { responsive: true } });
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

  <script>
    function toggleSummary() {
        const extras = document.querySelectorAll('.extra-summary');
        const moreToggleBtn = document.getElementById('inlineToggleBtn');
        const lessToggleBtn = document.getElementById('lessToggleBtn');
        const isHidden = extras[0].style.display === 'none';

        extras.forEach(el => el.style.display = isHidden ? 'list-item' : 'none');
        moreToggleBtn.style.display = isHidden ? 'none' : 'inline';
        lessToggleBtn.style.display = isHidden ? 'inline' : 'none';
    }
  </script>

</body>
</html>
