<?php 
  require_once 'admin_process.php';

  if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'secretary', 'doctor'])) {
      echo "<script>alert('Access denied.'); window.location.href = 'admin_login.php';</script>";
      exit;
  }

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    // Delete related records first
    $conn->query("DELETE FROM patient_logs WHERE patient_id = $delete_id");
    $conn->query("DELETE FROM medical_history WHERE patient_id = $delete_id");
    $conn->query("DELETE FROM dental_history WHERE patient_id = $delete_id");

    // Then delete from parent table
    $conn->query("DELETE FROM patients WHERE patient_id = $delete_id");

    header("Location: admin_patient_list.php");
    exit;
}

$total_patients = 0;
try {
    $query = "SELECT COUNT(*) AS total FROM patients";
    $result = $conn->query($query);

    if ($result && $row = $result->fetch_assoc()) {
        $total_patients = $row['total'];
    }
} catch (Exception $e) {
    $total_patients = 0;
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
  <link rel="stylesheet" href="../assets/admin/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/admin/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="../assets/admin/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="../assets/admin/text/css" href="js/select.dataTables.min.css">
  <link rel="stylesheet" href="../assets/admin/css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="../assets/admin/css/style.css">
  <link rel="stylesheet" href="../assets/admin/css/style.css">
  <link rel="shortcut icon" href="../assets/admin/images/logo1.png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>


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
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                        <h1 class="card-title">Patient Management List</h1>
                        <h5 class="text-primary mt-2">
                          Total Patients: <?= htmlspecialchars($total_patients); ?></span>
                        </h5>

                    </div>
                  </div>                         
              <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table id="appointmentTable" class="display expandable-table" style="width:100%">
                        <thead>
                            <tr>
  <th>ID</th>
  <th>Full Name</th>
  <th>Age</th>
  <th>Gender</th>
    <th>Phone</th>
<th>Address</th>
  <th>Email</th>
    <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
<?php
$patients = getPatientList($conn);
$encryption_key = "a3f1d9e5c7b2a1f8d4e9b3c6a7f2d5c8e1b4f7d3c2e6a9b5f8d7c4e2a1b3c6";

function calculateAge($dob) {
    try {
        $dobDate = new DateTime($dob);
        $today = new DateTime();
        return $today->diff($dobDate)->y;
    } catch (Exception $e) {
        return 'N/A';
    }
}

if ($patients) {
    while ($record = $patients->fetch_assoc()):
        $patient_id = $record['patient_id'];
        $full_name = decryptData($record['full_name'], $encryption_key);
        $email = decryptData($record['email'], $encryption_key);
        $gender = decryptData($record['gender'], $encryption_key);
        $address = decryptData($record['address'], $encryption_key);
        $dob = decryptData($record['dob'], $encryption_key);
        $phone = decryptData($record['phone'], $encryption_key);

        // Check if the decrypted DOB is a valid date before calculating age
        $age = $dob && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob) ? calculateAge($dob) : 'N/A';
?>
<tr style="cursor: pointer;" onmouseover="this.style.backgroundColor='#e6e6e6';" onmouseout="this.style.backgroundColor='';">
  <td><?= htmlspecialchars($patient_id); ?></td>
  <td><?= htmlspecialchars($full_name); ?></td>
  <td><?= htmlspecialchars($age); ?></td>
  <td><?= htmlspecialchars($gender); ?></td>
  <td><?= htmlspecialchars($phone); ?></td>
  <td><?= htmlspecialchars($address); ?></td>
  <td><?= htmlspecialchars($email); ?></td>
  <td>
<a href="/dentalscape/patient-details/?id=<?= urlencode($patient_id); ?>" class="btn btn-sm btn-primary">
    <i class="fa-solid fa-eye"></i>
</a>
<form action="/dentalscape/admin/admin_process.php" method="POST" style="display:inline;">
    <input type="hidden" name="patient_id" value="<?= htmlspecialchars($patient_id); ?>">
    <button type="submit" name="delete_patient" class="btn btn-sm btn-danger" 
        onclick="return confirm('Are you sure you want to delete this patient?');">
        <i class="fa-solid fa-trash"></i>
    </button>
</form>



  </td>
</tr>
<?php
    endwhile;
} else {
    echo '<tr><td colspan="8">No patients found.</td></tr>';
}
?>




                          </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        
        <div class="toast-container">
                  <?php if(isset($_SESSION['success'])): ?>
                <div class="toast toast-success">
                  <div class="toast-content">
                      <i class="fas fa-check-circle toast-icon"></i>
                      <?php 
                      echo $_SESSION['success'];
                      unset($_SESSION['success']);
                      ?>
                  </div>
                  <button class="toast-close">
                      <i class="fas fa-times"></i>
                  </button>
              </div>
              <?php endif; ?>
              <?php if(isset($_SESSION['error'])): ?>
                <div class="toast toast-error">
                  <div class="toast-content">
                      <i class="fas fa-exclamation-circle toast-icon"></i>
                      <?php 
                      echo $_SESSION['error'];
                      unset($_SESSION['error']);
                      ?>
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
  <script src="../assets/admin/vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="../assets/admin/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <script src="../assets/admin/js/dataTables.select.min.js"></script>
  <script src="../assets/admin/js/off-canvas.js"></script>
  <script src="../assets/admin/js/hoverable-collapse.js"></script>
  <script src="../assets/admin/js/template.js"></script>
  <script src="../assets/admin/js/settings.js"></script>
  <script src="../assets/admin/js/todolist.js"></script>
  <script src="../assets/admin/js/dashboard.js"></script>
  <script src="../assets/admin/js/Chart.roundedBarCharts.js"></script>
  <script src="../assets/js/search-bar.js"></script>
  <script src="../assets/js/toast.js"></script>
  <script src="../assets/js/security.js"></script>


  <script>
    function confirmAction(event) {
        let button = event.submitter; // Get the button that triggered the form submission
        let action = button.value; // "confirm" or "cancel"
        
        if (action === "confirm") {
            return confirm("Are you sure you want to confirm this appointment?");
        } else if (action === "cancel") {
            return confirm("Are you sure you want to cancel this appointment?");
        }
        return true;
    }
  </script>




</body>

</html>

