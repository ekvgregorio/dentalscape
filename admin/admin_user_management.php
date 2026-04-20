<?php 
require_once 'admin_process.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied. Admins only.'); window.location.href = 'admin_login.php';</script>";
    exit;
}

$total_users = getTotalUsers($conn);
$result = getUsers($conn);


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
  <link rel="shortcut icon" href="../assets/admin/images/logo1.png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
                    <h1 class="card-title">User Directory</h1>
                    <h5 class="text-primary mt-2">Total Registered Users: <?php echo $total_users; ?></h5>
                  </div>
                </div>                         
              <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table id="appointmentTable" class="display expandable-table" style="width:100%">
                        <thead>
                          <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Active</th>
                            <th>Verified</th>
                            <th>Status</th>
                            <th>Registration Date</th>
                            <th>Last Login</th>
                            <th style="width: 200px; min-width: 120px;">Action</th>

                          </tr>
                        </thead>
                        <tbody>
                          <?php while($user = $result->fetch_assoc()): 
                          $isActive = !is_null($user['last_login']) && 
                          (strtotime($user['last_login']) > strtotime('-24 hours'));
                          ?>
                          <tr style="cursor: pointer;" onmouseover="this.style.backgroundColor='#e6e6e6';" onmouseout="this.style.backgroundColor='';">


                          <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                              <?php if($isActive): ?>
                                    <span class="status-badge bg-success text-white">Active</span>
                                <?php else: ?>
                                    <span class="status-badge bg-secondary text-white">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($user['is_verified'] == 1): ?>
                                    <span class="status-badge bg-success text-white"> Verified
                                    </span>
                                <?php else: ?>
                                    <span class="status-badge bg-secondary text-white"> Unverified
                                    </span>
                                <?php endif; ?>
                            </td>
                              <td>
                                  <?php if ($user['status'] === 'blocked'): ?>
                                      <span class="status-badge bg-secondary text-white">Blocked</span>
                                  <?php elseif ($user['status'] === 'deleted'): ?>
                                      <span class="status-badge bg-danger text-white">Deleted</span>
                                  <?php elseif ($user['status'] === 'deactivated'): ?>
                                      <span class="status-badge bg-primary text-white" style="font-size:10px;">Deactivated</span>
                                  <?php else: ?>
                                      <span class="status-badge bg-success text-white">Active</span>
                                  <?php endif; ?>
                              </td>
                              <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                              <td>
                                  <?php 
                                  echo $user['last_login'] 
                                      ? date('m-d-y H:i', strtotime($user['last_login'])) 
                                      : 'Never logged in';
                                  ?>
                              </td>
<td>
    <!-- Delete Button -->
    <form action="/dentalscape/admin/admin_process.php" method="POST" style="display:inline;" 
          onsubmit="return confirmDelete('<?php echo $user['user_id']; ?>', '<?php echo htmlspecialchars($user['fullname']); ?>')">
        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
        <input type="hidden" name="delete_user" value="1">
        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
            <i class="fa-solid fa-trash" style="color: #ffffff;"></i>
        </button>
    </form>

    <!-- Block Button -->
    <form action="/dentalscape/admin/admin_process.php" method="POST" style="display:inline;" 
          onsubmit="return confirmBlock('<?php echo $user['user_id']; ?>', '<?php echo htmlspecialchars($user['fullname']); ?>')">
        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
        <input type="hidden" name="block_user" value="1">
        <button type="submit" class="btn btn-sm btn-warning" title="Block">
            <i class="fa-solid fa-ban" style="color: #ffffff;"></i>
        </button>
    </form>
    
    <!-- Unblock Button -->
<form action="/dentalscape/admin/admin_process.php" method="POST" style="display:inline;" 
      onsubmit="return confirmUnblock('<?php echo $user['user_id']; ?>', '<?php echo htmlspecialchars($user['fullname']); ?>')">
    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
    <input type="hidden" name="unblock_user" value="1">
    <button type="submit" class="btn btn-sm btn-success" title="Unblock">
        <i class="fa-solid fa-unlock" style="color: #ffffff;"></i>
    </button>
</form>

</td>

                          </tr>
                          <?php endwhile; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>   
              </div>
            </div>
          </div>

          <!-- User Profile Modal -->
          <div class="modal fade" id="userProfileModal" tabindex="-1" role="dialog" aria-labelledby="userProfileModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title">User Profile Validation</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <form>
                              <p class="card-description mb-3">Personal Info</p>
                              <div class="row">
                                  <div class="col-md-4 mb-3">
                                      <label>First Name</label>
                                      <input type="text" id="first_name" class="form-control" disabled>
                                  </div>
                                  <div class="col-md-4 mb-3">
                                      <label>Middle Name</label>
                                      <input type="text" id="middle_name" class="form-control" disabled>
                                  </div>
                                  <div class="col-md-4 mb-3">
                                      <label>Last Name</label>
                                      <input type="text" id="last_name" class="form-control" disabled>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-4 mb-3">
                                      <label>Gender</label>
                                      <input type="text" id="gender" class="form-control" disabled>
                                  </div>
                                  <div class="col-md-4 mb-3">
                                      <label>Birthdate</label>
                                      <input type="text" id="birthdate" class="form-control" disabled>
                                  </div>
                                  <div class="col-md-4 mb-3">
                                      <label>Civil Status</label>
                                      <input type="text" id="civil_status" class="form-control" disabled>
                                  </div>
                              </div>

                              <p class="card-description mb-3 mt-3">Contact Details</p>
                              <div class="row">
                                  <div class="col-md-4 mb-3">
                                      <label>Barangay</label>
                                      <input type="text" id="barangay" class="form-control" disabled>
                                  </div>
                                  <div class="col-md-4 mb-3">
                                      <label>Municipality</label>
                                      <input type="text" id="municipality" class="form-control" disabled>
                                  </div>
                                  <div class="col-md-4 mb-3">
                                      <label>Province</label>
                                      <input type="text" id="province" class="form-control" disabled>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-4 mb-3">
                                      <label>Zip Code</label>
                                      <input type="text" id="zip_code" class="form-control" disabled>
                                  </div>
                                  <div class="col-md-4 mb-3">
                                      <label>Contact Number</label>
                                      <input type="text" id="contact_number" class="form-control" disabled>
                                  </div>
                                  <div class="col-md-4 mb-3">
                                      <label>Email</label>
                                      <input type="email" id="email" class="form-control" disabled>
                                  </div>
                              </div>

                              <p class="card-description mb-3 mt-3">Identification</p>
                              <div class="row">
                                  <div class="col-md-4 mb-3">
                                      <label>ID Type</label>
                                      <input type="text" id="id_type" class="form-control" disabled>
                                  </div>
                                  <div class="col-md-4 mb-3">
                                      <label>ID Number</label>
                                      <input type="text" id="id_number" class="form-control" disabled>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-4 mb-3 text-center">
                                      <label>ID Image</label>
                                      <img id="id_image" class="img-fluid border rounded" style="max-height: 150px; width: auto;" alt="ID Image">
                                  </div>
                                  <div class="col-md-4 mb-3 text-center">
                                      <label>Selfie with ID</label>
                                      <img id="selfie_with_id" class="img-fluid border rounded" style="max-height: 150px; width: auto;" alt="Selfie with ID">
                                  </div>
                              </div>

                              <p class="card-description mb-3 mt-3">Health & Emergency Details</p>
                              <div class="row">
                                  <div class="col-md-4 mb-3">
                                      <label>Blood Type</label>
                                      <input type="text" id="blood_type" class="form-control" disabled>
                                  </div>
                                  <div class="col-md-4 mb-3">
                                      <label>Emergency Contact Name</label>
                                      <input type="text" id="emergency_contact_name" class="form-control" disabled>
                                  </div>
                                  <div class="col-md-4 mb-3">
                                      <label>Emergency Contact No.</label>
                                      <input type="text" id="emergency_contact_number" class="form-control" disabled>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-4 mb-3">
                                      <label>Relationship to Emergency Contact</label>
                                      <input type="text" id="relationship_to_emergency_contact" class="form-control" disabled>
                                  </div>
                                  <div class="col-md-4 mb-3">
                                      <label>Profile Created At</label>
                                      <input type="text" id="created_at" class="form-control" disabled>
                                  </div>
                              </div>
                          </form>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
  function confirmDelete(userId, fullname) {
      return confirm("Are you sure you want to delete " + fullname + "?");
  }

  function confirmBlock(userId, fullname) {
    return confirm("Are you sure you want to block " + fullname + " (ID: " + userId + ")?");
}

function confirmUnblock(userId, fullname) {
    return confirm("Are you sure you want to unblock " + fullname + " (ID: " + userId + ")?");
}


  $(document).ready(function() {
    $('.view-user-profile').click(function() {
        let userId = $(this).data('user-id');
        $('#userProfileModal').modal('show'); 
        $.ajax({
            url: 'admin_process.php',
            type: 'POST',
            data: { action: 'getUserProfile', user_id: userId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Populate the modal fields
                    $('#first_name').val(response.data.first_name);
                    $('#middle_name').val(response.data.middle_name);
                    $('#last_name').val(response.data.last_name);
                    $('#gender').val(response.data.gender);
                    $('#birthdate').val(response.data.birthdate);
                    $('#civil_status').val(response.data.civil_status);
                    $('#barangay').val(response.data.barangay);
                    $('#municipality').val(response.data.municipality);
                    $('#province').val(response.data.province);
                    $('#zip_code').val(response.data.zip_code);
                    $('#contact_number').val(response.data.contact_number);
                    $('#email').val(response.data.email);
                    $('#id_type').val(response.data.id_type);
                    $('#id_number').val(response.data.id_number);
                    $('#id_image').attr('src', response.data.id_image); 
                    $('#selfie_with_id').attr('src', response.data.selfie_with_id); 
                    $('#blood_type').val(response.data.blood_type);
                    $('#emergency_contact_name').val(response.data.emergency_contact_name);
                    $('#emergency_contact_number').val(response.data.emergency_contact_number);
                    $('#relationship_to_emergency_contact').val(response.data.relationship_to_emergency_contact);
                    $('#created_at').val(response.data.created_at);
                } else {
                    alert('Error fetching user profile: ' + response.message);
                    window.location.href = "admin_user_management.php"; 
                }
            },
            error: function(xhr, status, error) {
                alert('AJAX Error: ' + error);
            }
        });
    });
});
</script>

</body>

</html>

