<?php
  require_once '../admin/admin_process.php';
  // Redirect if not logged in
  if (!isset($_SESSION['user_id'])) {
      header("Location: login.php");
      exit();
  }

  $user_id = $_SESSION['user_id'];
  $fullname = $_SESSION['fullname'] ?? 'Guest';
  $user_profile = [];
  $events = [];

  // Fetch patient record
  $stmt = $conn->prepare("SELECT patient_id FROM patients WHERE user_id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $res = $stmt->get_result();
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
  <style>
    @media print {
      body::before {
            content: "Unofficial Copy"; 
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
        <a class="navbar-brand brand-logo mr-5" href="/dentalscape/dashboard/"><img src="../assets/admin/images/logo2.png" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="/dentalscape/dashboard/"><img src="../assets/admin/images/logo5.png" alt="logo"/></a>
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
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="card-header-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap"> 
                      <div class="d-flex ml-auto">
                        <button type="button" class="btn btn-info btn-icon-text mr-2" onclick="window.print()">
                          Print <i class="ti-printer btn-icon-append"></i>                                                                              
                        </button>
                      </div>   
                    </div>  
                      <form action="admin_process.php" method="POST" id="patientForm" enctype="multipart/form-data">
                        <input type="hidden" name="patient_id" value="<?= $patient_id ?>">
                          <div class="card">
                            <div class="card-body">
                              <?php if ($res->num_rows === 0): ?>
                                  <div class="alert alert-danger text-center col-md-6" role="alert">
                                      No patient record found. Please make sure your details are complete.
                                  </div>
                              <?php else: ?>
                                  <?php
                                      $patient_id = $res->fetch_assoc()['patient_id'];
                                      $dental         = fetchDentalHistory($conn, $patient_id, $encryption_key);
                                      $patient        = fetchPatientDetails($conn, $patient_id, $encryption_key);
                                      $history        = fetchMedicalHistory($conn, $patient_id, $encryption_key);
                                      $dentalRecords  = fetchDentalRecords($conn, $patient_id, $encryption_key);
                                  ?>
                                  <h4 class="card-title">Personal Details</h4>
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label for="user_id">User ID</label>
                                        <input type="text" class="form-control" id="user_id" name="user_id" value="<?= htmlspecialchars($patient['user_id']) ?>" disabled>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label for="patient_id">Patient ID</label>
                                        <input type="text" class="form-control" id="patient_id" name="patient_id" value="<?= htmlspecialchars($patient['patient_id']) ?>" readonly>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label for="full_name">Full Name</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($patient['full_name']) ?>" disabled>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label for="nickname">Nickname</label>
                                        <input type="text" class="form-control" id="nickname" name="nickname" value="<?= htmlspecialchars($patient['nickname']) ?>" disabled>
                                      </div>
                                    </div>                                 
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label for="dob">Date of Birth</label>
                                        <input type="date" class="form-control" id="dob" name="dob" value="<?= htmlspecialchars($patient['dob']) ?>" disabled>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select class="form-control" id="gender" name="gender" disabled>
                                          <option <?= $patient['gender'] == '' ? 'selected' : '' ?>>Select Gender</option>
                                          <option <?= $patient['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                          <option <?= $patient['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                                          <option <?= $patient['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label for="civil_status">Civil Status</label>
                                        <select class="form-control" id="civil_status" name="civil_status" disabled>
                                          <option <?= $patient['civil_status'] == '' ? 'selected' : '' ?>>Select Status</option>
                                          <option <?= $patient['civil_status'] == 'Single' ? 'selected' : '' ?>>Single</option>
                                          <option <?= $patient['civil_status'] == 'Married' ? 'selected' : '' ?>>Married</option>
                                          <option <?= $patient['civil_status'] == 'Widowed' ? 'selected' : '' ?>>Widowed</option>
                                          <option <?= $patient['civil_status'] == 'Divorced' ? 'selected' : '' ?>>Divorced</option>
                                          <option <?= $patient['civil_status'] == 'Separated' ? 'selected' : '' ?>>Separated</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($patient['phone']) ?>" disabled>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($patient['email']) ?>" disabled>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label for="occupation">Occupation</label>
                                        <input type="text" class="form-control" id="occupation" name="occupation" value="<?= htmlspecialchars($patient['occupation']) ?>" disabled>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label for="nationality">Nationality</label>
                                        <input type="text" class="form-control" id="nationality" name="nationality" value="<?= htmlspecialchars($patient['nationality']) ?>" disabled>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label for="religion">Religion</label>
                                        <input type="text" class="form-control" id="religion" name="religion" value="<?= htmlspecialchars($patient['religion']) ?>" disabled>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label for="address">Home Address</label>
                                        <input type="address" class="form-control" id="address" name="address" value="<?= htmlspecialchars($patient['address']) ?>" disabled>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label for="dental_insurance">Dental Insurance</label>
                                        <input type="dental_insurance" class="form-control" id="dental_insurance" name="dental_insurance" value="<?= htmlspecialchars($patient['dental_insurance']) ?>" disabled>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label for="effective_date">Effective Date</label>
                                        <input type="effective_date" class="form-control" id="effective_date" name="effective_date" value="<?= htmlspecialchars($patient['effective_date']) ?>" disabled>
                                      </div>
                                    </div>                                                                    
                                  </div>
                                  <hr>

                                <h4 class="card-title">Dental History</h4>
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="previous_dentist">Previous Dentist</label>
                                      <input type="text" class="form-control" id="previous_dentist" name="previous_dentist" value="<?= htmlspecialchars($dental['previous_dentist'] ?? '') ?>" disabled>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="last_dental_visit">Last Dental Visit</label>
                                      <input type="text" class="form-control" id="last_dental_visit" name="last_dental_visit" value="<?= htmlspecialchars($dental['last_dental_visit']) ?>" disabled>
                                    </div>
                                  </div>
                                </div>
                                            
                              <hr>
                              <h4 class="card-title">Medical History</h4>
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="physician">Name of Physician </label>
                                      <input type="text" class="form-control" id="physician" name="physician" value="<?= htmlspecialchars($history['physician_name']) ?>" disabled>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="specialty">Specialty, if applicable</label>
                                      <input type="text" class="form-control" id="specialty" name="specialty" value="<?= htmlspecialchars($history['specialty']) ?>" disabled>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="office_address">Office Address</label>
                                      <input type="text" class="form-control" id="office_address" name="office_address" value="<?= htmlspecialchars($history['office_address']) ?>" disabled>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="office_number">Office Number</label>
                                      <input type="text" class="form-control" id="office_number" name="office_number" value="<?= htmlspecialchars($history['office_number']) ?>" disabled>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="form-group">
                                      <div class="table-responsive">
                                        <table class="table table-borderless">
                                          <thead>
                                            <tr>
                                              <th>Question</th>
                                              <th>Answer</th>
                                              <th>Details</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr>
                                              <td><strong>1. Are you in good health?</strong></td>
                                              <td><?= ($history['good_health'] === 'yes') ? 'Yes' : 'No' ?></td>
                                              <td>-</td>
                                            </tr>

                                            <tr>
                                              <td><strong>2. Are you under medical treatment now?</strong></td>
                                              <td><?= ($history['medical_treatment'] === 'yes') ? 'Yes' : 'No' ?></td>
                                              <td><?= ($history['medical_treatment'] === 'yes' && !empty($history['medical_condition'])) 
                                                  ? htmlspecialchars(($history['medical_condition'])) 
                                                  : '-' ?>
                                              </td>
                                            </tr>
                                            <tr>
                                                <td><strong>3. Have you ever had serious illness or surgical operation?</strong></td>
                                                <td><?= ($history['serious_illness'] === 'yes') ? 'Yes' : 'No' ?></td>
                                                <td> <?= ($history['serious_illness'] === 'yes') 
                                                  ? htmlspecialchars(($history['serious_illness_details'])) 
                                                  : '-' ?>
                                                </td>
                                            </tr>
                                            <tr>
                                              <td><strong>4. Have you ever been hospitalized?</strong></td>
                                              <td><?= ($history['hospitalized'] === 'yes') ? 'Yes' : 'No' ?></td>
                                              <td>
                                                <?= ($history['hospitalized'] === 'yes' && !empty($history['hospitalization_details'])) 
                                                ? htmlspecialchars(($history['hospitalization_details'])) 
                                                : '-' ?>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td><strong>5. Are you taking any prescription/non-prescription medication?</strong></td>
                                              <td><?= ($history['prescription'] === 'yes') ? 'Yes' : 'No' ?></td>
                                              <td>
                                                <?= ($history['prescription'] === 'yes' && !empty($history['prescription_details'])) 
                                                ? htmlspecialchars(($history['prescription_details'] )) 
                                                : '-' ?>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td><strong>6. Do you use tobacco products?</strong></td>
                                              <td><?= ($history['tobacco'] === 'yes') ? 'Yes' : 'No' ?></td>
                                              <td>-</td>
                                            </tr>
                                            <tr>
                                              <td><strong>7. Do you use alcohol, cocaine, or other dangerous drugs?</strong></td>
                                              <td><?= ($history['alcohol_drugs'] === 'yes') ? 'Yes' : 'No' ?></td>
                                              <td>-</td>
                                            </tr>
                                            <tr>
                                              <td><strong>8. Allergies</strong></td>
                                              <td colspan="2">
                                                <?= !empty($history['allergies']) ? htmlspecialchars($history['allergies']) : 'None specified' ?>
                                                <?php if (!empty($history['other_allergy'])): ?>
                                                  <br><em>Other: <?= htmlspecialchars($history['other_allergy']) ?></em>
                                                <?php endif; ?>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td><strong>9. Bleeding Time</strong></td>
                                              <td colspan="2"><?= !empty($history['bleeding_time']) ? htmlspecialchars($history['bleeding_time']) : 'Not recorded' ?></td>
                                            </tr>            
                                            <tr>
                                              <td colspan="3"><strong>10. For Women Only</strong></td>
                                            </tr>
                                            <tr>
                                              <td style="padding-left: 50px;">Are you pregnant?</td>
                                              <td><?= ($history['pregnant'] === 'yes') ? 'Yes' : 'No' ?></td>
                                              <td>-</td>
                                            </tr>
                                            <tr>
                                              <td style="padding-left: 50px;">Are you nursing?</td>
                                              <td><?= ($history['nursing'] === 'yes') ? 'Yes' : 'No' ?></td>
                                              <td>-</td>
                                            </tr>
                                            <tr>
                                              <td style="padding-left: 50px;">Are you taking birth control pills?</td>
                                              <td><?= ($history['birth_control'] === 'yes') ? 'Yes' : 'No' ?></td>
                                              <td>-</td>
                                            </tr>
                                            
                                            <tr>
                                              <td><strong>11. Blood Type</strong></td>
                                              <td colspan="2"><?= !empty($history['blood_type']) ? htmlspecialchars($history['blood_type']) : 'Not specified' ?></td>
                                            </tr>
                                            
                                            <tr>
                                              <td><strong>12. Blood Pressure</strong></td>
                                              <td colspan="2"><?= !empty($history['blood_pressure']) ? htmlspecialchars($history['blood_pressure']) : 'Not recorded' ?></td>
                                            </tr>
                                            <tr>
                                              <td><strong>13. Medical Conditions</strong></td>
                                              <td colspan="2"><?= !empty($history['medical_conditions']) ? htmlspecialchars($history['medical_conditions']) : 'None reported' ?></td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                    <hr>
                                    <h4 class="card-title">Dental Records</h4>
                                      <div class="col-md-12">
                                        <div class="form-group" id="dental_records">
                                          <div class="table-responsive" style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6;">
                                            <table class="table table-hover table-bordered">
                                              <thead style="background-color: #1a1a76ff; color: white;">
                                                <tr>
                                                  <th>Visit Date</th>
                                                  <th>Tooth Number</th>
                                                  <th>Diagnosis</th>
                                                  <th>Treatment</th>
                                                  <th>Notes</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <?php 
                                                $dentalRecords = fetchDentalRecords($conn, $patient_id, $encryption_key); 

                                                if (!empty($dentalRecords)) {
                                                    foreach ($dentalRecords as $record): ?>
                                                        <tr>
                                                            <td><?php echo date("M d, Y", strtotime($record['visit_date'])); ?></td>
                                                            <td><?php echo htmlspecialchars($record['tooth_number']); ?></td>
                                                            <td><?php echo htmlspecialchars($record['diagnosis']); ?></td>
                                                            <td><?php echo htmlspecialchars($record['treatment']); ?></td>
                                                            <td><?php echo htmlspecialchars($record['notes']); ?></td>
                                                        </tr>
                                                    <?php endforeach;
                                                } else {
                                                    echo '<tr><td colspan="6" class="text-center">No dental records found.</td></tr>';
                                                }
                                                ?>
                                              </tbody>
                                            </table>
                                          </div>
                                        </div>
                            
                                      </div>
                                      <hr>
                                      <div class="card">
                                        <div class="card-body">
                                          <h4 class="card-title">Emergency Contacts</h4>

                                          <form method="POST" action="admin_process.php">
                                            <input type="hidden" name="patient_id" value="<?= $patient_id ?>">
                                            
                                            <div id="existingContacts">
                                              <?php
                                              $emergency_contacts = fetchEmergencyContacts($conn, $patient_id);
                                              if (!empty($emergency_contacts)) :
                                                foreach ($emergency_contacts as $contact) : ?>
                                                  <div class="contact-group border p-3 mb-3 bg-light">
                                                    <div class="row">
                                                      <div class="col-md-6">
                                                        <div class="form-group">
                                                          <label>Contact Name</label>
                                                          <input type="text" class="form-control" value="<?= htmlspecialchars($contact['contact_name']) ?>" readonly>
                                                        </div>
                                                      </div>
                                                      <div class="col-md-6">
                                                        <div class="form-group">
                                                          <label>Relationship</label>
                                                          <input type="text" class="form-control" value="<?= htmlspecialchars($contact['relationship']) ?>" readonly>
                                                        </div>
                                                      </div>
                                                      <div class="col-md-6">
                                                        <div class="form-group">
                                                          <label>Phone Number</label>
                                                          <input type="text" class="form-control" value="<?= htmlspecialchars($contact['emergency_phone']) ?>" readonly>
                                                        </div>
                                                      </div>
                                                      <div class="col-md-6">
                                                        <div class="form-group">
                                                          <label>Alternate Phone Number</label>
                                                          <input type="text" class="form-control" value="<?= htmlspecialchars($contact['alt_phone']) ?>" readonly>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                              <?php endforeach;
                                              else :
                                                echo "<p>No emergency contacts found.</p>";
                                              endif;
                                              ?>
                                            </div>

                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <hr>
                                <div class="card">
                                  <div class="card-body">
                                    <div class="col-md-12 mt-2">
                                      <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mt-2 mb-3">Recent Uploads Section</h5>
                                      </div>
                                    </div>
                                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6;">
                                      <table class="table table-hover table-bordered">
                                        <thead style="background-color: #1a1a76ff; color: white;">
                                          <tr>
                                            <th>File Name</th>
                                            <th>Type</th>
                                            <th>Report Title</th>
                                            <th>Report Date</th>
                                            <th>Issued By </th>
                                            <th>Notes</th>
                                            <th>Visibility</th>
                                            <th>Actions</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <?php
                                            $reports = fetchDentalReports($conn, $patient_id, $encryption_key);

                                            $stmt = $conn->prepare("SELECT full_name FROM patients WHERE patient_id = ?");
                                            $stmt->bind_param("i", $patient_id);
                                            $stmt->execute();
                                            $stmt->bind_result($encrypted_name);
                                            $stmt->fetch();
                                            $stmt->close();

                                            $patient_name = decryptData($encrypted_name, $encryption_key);

                                            $stmt = $conn->prepare("SELECT report_id, report_type, report_title, report_date, issued_by, notes, file, visibility 
                                                                    FROM dental_reports WHERE patient_id = ?");
                                            $stmt->bind_param("i", $patient_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {

                                                    $folder = "uploads/" . $patient_id . " - " . $patient_name;
                                                    $file_path = $folder . "/" . $row['file'];
                                            ?>
                                            <tr>
                                              <td>
                                                <a href="<?= htmlspecialchars($file_path) ?>" target="_blank">
                                                <?= htmlspecialchars($row['file']) ?></a>
                                              </td>
                                              <td><?= htmlspecialchars($row['report_type']) ?></td>
                                              <td><?= htmlspecialchars($row['report_title']) ?></td>
                                              <td><?= htmlspecialchars($row['report_date']) ?></td>
                                              <td><?= htmlspecialchars($row['issued_by']) ?></td>
                                              <td><?= nl2br(htmlspecialchars($row['notes'])) ?></td>
                                              <td><?= htmlspecialchars($row['visibility']) ?></td>
                                              <td>
                                                <a href="<?= htmlspecialchars($file_path) ?>" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                                                <a href="<?= htmlspecialchars($file_path) ?>" download class="btn btn-sm btn-success"><i class="fas fa-download"></i></a>
                                                <form action="" method="POST" style="display:inline-block;">
                                                  <input type="hidden" name="report_id" value="<?= $row['report_id'] ?>">
                                                  <input type="hidden" name="patient_id" value="<?= $patient_id ?>">
                                                </form>
                                              </td>
                                            </tr>
                                          <?php
                                              }
                                            } else {
                                              echo '<tr><td colspan="8" class="text-center">No records found.</td></tr>';
                                            }
                                          ?>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                  <hr>
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
      </form>
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../assets/js/search-bar.js"></script>
  <script src="../assets/js/toast.js"></script>
  <script src="../assets/js/security.js"></script>

</body>
</html>

