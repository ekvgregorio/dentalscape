<?php
require_once 'user_process.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$user_id = $_SESSION['user_id'];
$fullname = $_SESSION['fullname'] ?? 'Guest';
$events = [];

$month_range = $_GET['month_range'] ?? null;

$result = getConfirmedAppointments($conn, $user_id, $month_range);
$total_appointments = getTotalConfirmedAppointments($conn, $user_id, $month_range);

$success_message = $_SESSION['success'] ?? null;
$error_message = $_SESSION['error'] ?? null;

unset($_SESSION['success']);
unset($_SESSION['error']);

?>

<!DOCTYPE html>
<html lang="en">


<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DentalScape Iloilo</title>
  <link href="../assets/user/images/logo1.png" rel="icon">
  <link href="../assets/user/images/logo1.png" rel="apple-touch-icon">
  <link rel="stylesheet" href="../assets/user/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/user/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/user/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/user/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="../assets/user/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="../assets/user/text/css" href="js/select.dataTables.min.css">
  <link rel="stylesheet" href="../assets/user/css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

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

    <!-- partial -->
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
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 text-center text-md-left">
  <!-- Left side -->
  <div class="mb-2 mb-md-0">
    <h1 class="card-title mb-0">Scheduled Consultations</h1>
    <h5 class="text-primary mt-2">
      Total Approved Appointments: <?php echo $total_appointments; ?>
    </h5>
  </div>

  <!-- Right side (dropdown) -->
  <div>
    <div class="dropdown">
      <button
        class="btn btn-sm btn-light bg-white dropdown-toggle"
        type="button"
        id="dropdownMenuDate2"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
      >
        <i class="mdi mdi-calendar"></i>
        <?php echo $current_month_range_text; ?>
      </button>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
        <a class="dropdown-item" href="?month_range=jan-mar">January - March</a>
        <a class="dropdown-item" href="?month_range=mar-jun">March - June</a>
        <a class="dropdown-item" href="?month_range=jun-aug">June - August</a>
        <a class="dropdown-item" href="?month_range=aug-nov">August - November</a>
      </div>
    </div>
  </div>
</div>


                    <?php if ($success_message): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error_message): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>
    
                  <div class="row">
                    <div class="col-12">
                      <?php if ($total_appointments == 0): ?>
                        <div class="alert alert-info text-center" role="alert">
                          No available appointments for the month of <?php echo $current_month_range_text; ?>
                        </div>
                        <?php else: ?>
                          <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table id="appointmentTable" class="display expandable-table" style="width:100%">
                              <thead>
                                <tr>
                                  <th>Appointment ID</th>
                                  <th>Purpose of Appointment</th>
                                  <th>Service Type</th>
                                  <th> Doctor </th>
                                  <th>Appointment Date</th>
                                  <th>Appointment Time</th>
                                  <th>Special Requests/Notes</th>
                                  <th>Status</th>
                                  <th>Time Created</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                  <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['purpose_of_appointment']); ?></td>
                                    <td><?php echo htmlspecialchars($row['service_type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['doctor']); ?></td>
                                    <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['appointment_time']); ?></td>
                                    <td><?php echo htmlspecialchars($row['special_requests']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                    <td>
                                      <div class="d-flex">
                                        <button
                                          style="margin-right: 8px;"
                                          class="btn-custom btn-reschedule btn btn-sm btn-warning"
                                          data-id="<?php echo $row['id']; ?>"
                                          data-date="<?php echo $row['appointment_date']; ?>"
                                          data-time="<?php echo $row['appointment_time']; ?>"
                                          data-service="<?php echo htmlspecialchars($row['service_type']); ?>">
                                          <i class="fa-solid fa-pen-alt"></i>
                                        </button>


                                          <button class="btn-custom btn-cancel btn btn-sm btn-danger" 
                                              data-id="<?php echo $row['id']; ?>">
                                              <i class="fa-solid fa-ban"></i> 
                                          </button>
                                      </div>
                                  </td>
                                  </tr>
                                <?php endwhile; ?>
                              </tbody>
                            </table>
                          </div>
                          <?php endif; ?>
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
    </div>
  </div>

<!-- Reschedule Modal -->
  <div class="modal fade" id="rescheduleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="rescheduleForm">
          <div class="modal-header">
            <h5 class="modal-title">Reschedule Appointment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span>&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="reschedule_id" name="id" />
            <input type="hidden" name="action" value="reschedule" />
            <div class="form-group">
              <label for="new_date">New Date</label>
              <input type="date" id="new_date" name="new_date" class="form-control" required />
            </div>
            <div class="form-group">
              <label for="new_time">New Time</label>
              <select id="new_time" name="new_time" class="form-control" required>
                <option value="">Select a date first</option>
              </select>
            </div>
            <input type="hidden" id="service_type" name="service_type" />
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          </div>
        </form>
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
  <script src="assets/js/security.js"></script>

  <script>
document.addEventListener("DOMContentLoaded", function () {
  const rescheduleModal = $("#rescheduleModal");
  const rescheduleForm = document.getElementById("rescheduleForm");
  const newDateInput = document.getElementById("new_date");
  const newTimeSelect = document.getElementById("new_time");
  const rescheduleIdInput = document.getElementById("reschedule_id");
  const serviceTypeInput = document.getElementById("service_type");

  // Open modal function
  window.openRescheduleModal = function (appointmentId, currentDate, currentTime, serviceType) {
    rescheduleIdInput.value = appointmentId;
    newDateInput.value = currentDate;
    serviceTypeInput.value = serviceType;

    fetchAvailableTimes(currentDate, serviceType, appointmentId);
    rescheduleModal.modal("show");
  };

  // Fetch available times
  function fetchAvailableTimes(date, service, appointmentId) {
    newTimeSelect.innerHTML = '<option value="">Loading...</option>';
    fetch(`/dentalscape/user/fetch_available_times.php?date=${date}&service_type=${encodeURIComponent(service)}&appointment_id=${appointmentId}`)
      .then(res => res.json())
      .then(times => {
        newTimeSelect.innerHTML = "";
        if (times.length) {
          times.forEach(time => {
            const option = document.createElement("option");
            option.value = time;
            option.textContent = time;
            newTimeSelect.appendChild(option);
          });
        } else {
          newTimeSelect.innerHTML = '<option value="">No available slots</option>';
        }
      })
      .catch(() => {
        newTimeSelect.innerHTML = '<option value="">Error loading slots</option>';
      });
  }

  // Update available times if date changes
  newDateInput.addEventListener("change", () => {
    const date = newDateInput.value;
    const service = serviceTypeInput.value;
    const appointmentId = rescheduleIdInput.value;
    if (date && service) {
      fetchAvailableTimes(date, service, appointmentId);
    } else {
      newTimeSelect.innerHTML = '<option value="">Select a date and service first</option>';
    }
  });

  // Attach click handlers to reschedule buttons
  document.querySelectorAll(".btn-reschedule").forEach(btn => {
    btn.addEventListener("click", function () {
      const appointmentId = this.dataset.id;
      const appointmentDate = this.dataset.date;
      const appointmentTime = this.dataset.time;
      const serviceType = this.dataset.service || "General Check-up";
      openRescheduleModal(appointmentId, appointmentDate, appointmentTime, serviceType);
    });
  });

  // Submit reschedule form with AJAX
  rescheduleForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(rescheduleForm);

    fetch("/dentalscape/user/user_process.php", {
      method: "POST",
      body: formData,
      credentials: "same-origin"
    })
      .then(res => res.text())
      .then(text => {
        if (text.trim() === "Success") {
          alert("Appointment rescheduled successfully.");
          rescheduleModal.modal("hide");
          location.reload();
        } else {
          alert(text);
        }
      })
      .catch(() => alert("Error rescheduling appointment."));
  });

  // --- NEW: Cancel button handler ---
  document.querySelectorAll(".btn-cancel").forEach(button => {
    button.addEventListener("click", function () {
      const appointmentId = this.dataset.id;

      if (!appointmentId) {
        alert("Invalid appointment ID.");
        return;
      }

      if (!confirm("Are you sure you want to cancel this appointment?")) {
        return;
      }

      const formData = new FormData();
      formData.append("action", "cancel");
      formData.append("id", appointmentId);

      fetch("/dentalscape/user/user_process.php", {  // Use your backend PHP file here
        method: "POST",
        body: formData,
        credentials: "same-origin"
      })
        .then(res => res.text())
        .then(response => {
          if (response.trim() === "Success") {
            alert("Appointment cancelled successfully.");
            location.reload();  // reload to update the appointment list
          } else {
            alert(response);
          }
        })
        .catch(() => {
          alert("Error cancelling appointment.");
        });
    });
  });

});

</script>
</body>
</html>

