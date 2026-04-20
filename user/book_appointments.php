<?php
session_start();
require_once '../conn.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}



$user_id = $_SESSION['user_id'];
$fullname = $_SESSION['fullname'] ?? 'Guest';
$events = [];


$fullname = $_SESSION['fullname'] ?? 'Guest';

$user_id = $_SESSION['user_id'];


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
  <link rel="stylesheet" type="../assets/use/rtext/css" href="js/select.dataTables.min.css">
  <link rel="stylesheet" href="../assets/user/css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="../assets/admin/css/style.css">
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
                  <h3 class="font-weight-bold">Hello, <?= htmlspecialchars($fullname, ENT_QUOTES, 'UTF-8'); ?>!</h3>
                  <h6 class="font-weight-normal mb-0">
                    <span class="text-primary"> Book your appointment by filling out the form below. We’ll confirm your schedule soon!</span>
                  </h6>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">Appointment Form</p>
                  <p class="instruction-text" style="font-size: 14px; color: #666; margin-bottom: 10px;">
                    Please note: You can only book an appointment up to 2 days in advance. Additionally, you may schedule a maximum of 3 appointments per week at any given time.
                  </p>
                  <hr>
                  <form action="/dentalscape/user/user_process.php" method="POST" id="appointmentForm">
                    <p class="card-description">Personal info</p>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Full Name</label>
                          <div class="col-sm-9">
                            <input type="text" name="full_name" class="form-control" placeholder="Full Name" required />
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Birthdate</label>
                          <div class="col-sm-9">
                            <input type="date" name="birthdate" class="form-control" placeholder="Enter birthdate..." required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Gender</label>
                          <div class="col-sm-9">
                            <select name="gender" class="form-control" required>
                              <option>Male</option>
                              <option>Female</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Email</label>
                          <div class="col-sm-9">
                            <input type="email" name="email" class="form-control" placeholder="Please provide a valid and active email address." required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Contact No.</label>
                          <div class="col-sm-9">
                            <input type="text" name="contact_number" class="form-control" placeholder="Contact Number" required />
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Address</label>
                          <div class="col-sm-9">
                            <input type="text" name="address" class="form-control" placeholder="Street, Barangay, District [if applicable], City/Municipality"required />
                          </div>
                        </div>
                      </div>

                    </div>
                    <hr>
                    <p class="card-description">Appointment Details</p>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Purpose of Appointment</label>
                            <div class="col-sm-9">
                                <select id="purpose_of_appointment" name="purpose_of_appointment" class="form-control" required>
                                    <option value="">Select Purpose</option>
                                    <option value="Consultation">Consultation</option>
                                    <option value="Follow-up">Follow-up Check-up</option>
                                    <option value="General Dentistry">General Dentistry</option>
                                    <option value="Dental Braces">Dental Braces</option>
                                    <option value="Partial/Full Dentures">Partial/Full Dentures</option>
                                    <option value="Veneers">Veneers</option>
                                    <option value="Crowns and Bridges">Crowns and Bridges</option>
                                    <option value="Tooth Extraction">Tooth Extraction</option>
                                    <option value="Restoration">Restoration (Fillings)</option>
                                    <option value="Oral Prophylaxis">Oral Prophylaxis (Cleaning)</option>
                                    <option value="Teeth Whitening">Teeth Whitening</option>
                                    <option value="Emergency">Emergency Dental Care</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Service Type</label>
                            <div class="col-sm-9">
                                <select id="service_type" name="service_type" class="form-control" required>
                                    <option value="">Select Service Type</option>
                                </select>
                            </div>
                        </div>
                    </div>
                  </div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Doctor</label>
            <div class="col-sm-9">
                <select id="doctor" name="doctor" class="form-control" required>
                    <option value="">Select Doctor</option>
                    <option value="Dr. Marañon">Dr. Marañon</option>
                    <option value="Dr. Pinuela">Dr. Pinuela</option>
                    <option value="Dr. Villaret">Dr. Villaret</option>
                    <option value="Dr. Savari">Dr. Savari</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Date</label>
            <div class="col-sm-9">
                <input type="date" id="appointment_date" name="appointment_date" class="form-control" required disabled>
                <small id="date-hint" style="color: red;">Select doctor first</small>
            </div>
        </div>
    </div>
</div>


                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Time</label>
                            <div class="col-sm-9">
                                <select id="appointment_time" name="appointment_time" class="form-control" required>
                                    <option value="">Select a date first</option>
                                </select>
                            </div>
                        </div>
                    </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Notes</label>
                          <div class="col-sm-9">
                            <textarea name="special_requests" class="form-control" rows="4" placeholder="Additional comments..."></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="d-flex justify-content-end">
                      <input type="submit" class="btn btn-primary me-2" name="book_appointment" value="Submit">
                    </div>
                  </form>
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
  <script src="../assets/js/toast.js"></script>
  <script src="../assets/js/security.js"></script>
  <script src="/dentalscape/user/booking.js"></script>

</body>
</html>
