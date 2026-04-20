<?php 
  require_once 'admin_process.php';
  
  if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'secretary','doctor'])) {
    echo "<script>alert('Access denied.'); window.location.href = 'admin_login.php';</script>";
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

<style>


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
                  <div class="card-header-body">
                      <form action="/dentalscape/admin/admin_process.php" method="POST" id="patientForm" enctype="multipart/form-data">
                        <div id="editable-sections">
                          <div class="card">
                            <div class="card-body">
                              <h4 class="card-title">Personal Details</h4>
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="user_id">User ID</label>
                                      <input type="text" class="form-control" id="user_id" name="user_id" placeholder="Enter User ID">
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="patient_id">Patient ID</label>
                                      <input type="text" class="form-control" id="patient_id" name="patient_id" placeholder="Patient ID" readonly>
                                    </div>
                                  </div>
                                  <div class="col-md-8">
                                    <div class="form-group">
                                      <label for="full_name">Full Name</label>
                                      <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Enter full name..">
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="nickname">Nickname</label>
                                      <input type="text" class="form-control" id="nick_name" name="nickname" placeholder="Enter nickname...">
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="dob">Date of Birth</label>
                                      <input type="date" class="form-control" id="dob" name="dob" placeholder="Enter date of birth...">
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="gender">Gender</label>
                                      <select class="form-control" id="gender" name="gender">
                                        <option>Select Gender</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="civil_status">Civil Status</label>
                                      <select class="form-control" id="civil_status" name="civil_status">
                                        <option>Select Status</option>
                                        <option>Single</option>
                                        <option >Married</option>
                                        <option >Widowed</option>
                                        <option >Divorced</option>
                                        <option >Separated</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="phone">Phone</label>
                                      <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter phone number...">
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="email">Email</label>
                                      <input type="email" class="form-control" id="email" name="email" placeholder="Enter email adress...">
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="occupation">Occupation</label>
                                      <input type="text" class="form-control" id="occupation" name="occupation" placeholder="Enter occupation...">
                                    </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="nationality">Nationality</label>
                                      <input type="text" class="form-control" id="nationality" name="nationality" placeholder="Enter nationality...">
                                    </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="religion">Religion</label>
                                      <input type="text" class="form-control" id="religion" name="religion" placeholder="Enter religion...">
                                    </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="address">Home Address</label>
                                      <input type="address" class="form-control" id="address" name="address" placeholder="Enter address...">
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="dental_insurance">Dental Insurance</label>
                                      <input type="text" class="form-control" id="dental_insurance" name="dental_insurance" placeholder="Enter dental insurance...">
                                    </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="effective_date">Effective Date</label>
                                      <input type="date" class="form-control" id="aeffective_date" name="effective_date" placeholder="Enter effective_date...">
                                    </div>
                                  </div>
                                </div>
                                <hr>

                                <h4 class="card-title">Dental History</h4>
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="previous_dentist">Previous Dentist</label>
                                      <input type="text" class="form-control" id="height" name="previous_dentist" placeholder="Enter previous dentist...">
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="last_dental_visit">Last Dental Visit</label>
                                      <input type="date" class="form-control" id="last_dental_visit" name="last_dental_visit" placeholder="Enter previous dentist...">
                                    </div>
                                  </div>
                                </div>
                                            
<h4 class="card-title">Medical History</h4>
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label for="physician_name">Name of Physician </label>
      <input type="text" class="form-control" id="physician_name" name="physician_name" placeholder="Enter physician name...">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="specialty">Specialty, if applicable</label>
      <input type="text" class="form-control" id="specialty" name="specialty" placeholder="Enter dentist specialty...">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="office_address">Office Address</label>
      <input type="text" class="form-control" id="office_address" name="office_address" placeholder="Enter office address...">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="office_number">Office Number</label>
      <input type="text" class="form-control" id="office_number" name="office_number" placeholder="Enter office number...">
    </div>
  </div>
  
  <div class="col-md-12">
    <div class="form-group">
      <div class="table-responsive">
        <table class="table table-borderless">
          <thead>
            <tr>
              <th>Question</th>
              <th class="text-center">Yes</th>
              <th class="text-center">No</th>
              <th>Details (if Yes)</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>1. Are you in good health?</strong></td>
              <td class="text-center">
                <input type="radio" name="good_health" id="good_health_yes" value="yes">
              </td>
              <td class="text-center">
                <input type="radio" name="good_health" id="good_health_no" value="no">
              </td>
              <td></td>
            </tr>

            <tr>
              <td><strong>2. Are you under medical treatment now?</strong></td>
              <td class="text-center">
                <input type="radio" name="medical_treatment" id="medical_treatment_yes" value="yes" onchange="toggleCondition('medical_condition_field', true)">
              </td>
              <td class="text-center">
                <input type="radio" name="medical_treatment" id="medical_treatment_no" value="no" onchange="toggleCondition('medical_condition_field', false)">
              </td>
              <td>
                <input type="text" class="form-control" id="medical_condition_field" name="medical_condition" placeholder="What condition?" style="display:none;">
              </td>
            </tr>

            <tr>
              <td><strong>3. Have you ever had serious illness or surgical operation?</strong></td>
              <td class="text-center">
                <input type="radio" name="serious_illness" id="serious_illness_yes" value="yes" onchange="toggleCondition('serious_illness_field', true)">
              </td>
              <td class="text-center">
                <input type="radio" name="serious_illness" id="serious_illness_no" value="no" onchange="toggleCondition('serious_illness_field', false)">
              </td>
              <td>
                <input type="text" class="form-control" id="serious_illness_field" name="serious_illness_details" placeholder="Serious illnesses or surgical operations:" style="display:none;">
              </td>
            </tr>

            <tr>
              <td><strong>4. Have you ever been hospitalized?</strong></td>
              <td class="text-center">
                <input type="radio" name="hospitalized" id="hospitalized_yes" value="yes" onchange="toggleCondition('hospitalized_field', true)">
              </td>
              <td class="text-center">
                <input type="radio" name="hospitalized" id="hospitalized_no" value="no" onchange="toggleCondition('hospitalized_field', false)">
              </td>
              <td>
                <input type="text" class="form-control" id="hospitalized_field" name="hospitalization_details" placeholder="If yes, please specify the reason and dates" style="display:none;">
              </td>
            </tr>

            <tr>
              <td><strong>5. Are you taking any prescription/non-prescription medication?</strong></td>
              <td class="text-center">
                <input type="radio" name="prescription" id="prescription_yes" value="yes" onchange="toggleCondition('prescription_field', true)">
              </td>
              <td class="text-center">
                <input type="radio" name="prescription" id="prescription_no" value="no" onchange="toggleCondition('prescription_field', false)">
              </td>
              <td>
                <input type="text" class="form-control" id="prescription_field" name="prescription_details" placeholder="Current medications (prescription and non-prescription):" style="display:none;">
              </td>
            </tr>

            <tr>
              <td><strong>6. Do you use tobacco products?</strong></td>
              <td class="text-center">
                <input type="radio" name="tobacco" id="tobacco_yes" value="yes">
              </td>
              <td class="text-center">
                <input type="radio" name="tobacco" id="tobacco_no" value="no">
              </td>
              <td></td>
            </tr>

            <tr>
              <td><strong>7. Do you use alcohol, cocaine, or other dangerous drugs?</strong></td>
              <td class="text-center">
                <input type="radio" name="alcohol_drugs" id="alcohol_drugs_yes" value="yes">
              </td>
              <td class="text-center">
                <input type="radio" name="alcohol_drugs" id="alcohol_drugs_no" value="no">
              </td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="form-group">
  <!-- 8. Allergies -->
  <label><strong>8. Are you allergic to any of the following:</strong></label>
  <div class="d-flex flex-wrap align-items-center mb-3" style="gap: 1rem;">

    <div class="form-check">
      <input class="form-check-input ml-2" type="checkbox" id="allergy_lidocaine" name="allergies[]" value="local anesthetic">
      <label class="form-check-label" for="allergy_lidocaine">Local Anesthetic (ex. Lidocaine)</label>
    </div>

    <div class="form-check">
      <input class="form-check-input ml-2" type="checkbox" id="allergy_penicillin" name="allergies[]" value="penicillin antibiotics">
      <label class="form-check-label" for="allergy_penicillin">Penicillin, Antibiotics</label>
    </div>

    <div class="form-check">
      <input class="form-check-input ml-2" type="checkbox" id="allergy_sulfa" name="allergies[]" value="sulfa drugs">
      <label class="form-check-label" for="allergy_sulfa">Sulfa drugs</label>
    </div>

    <div class="form-check">
      <input class="form-check-input ml-2" type="checkbox" id="allergy_aspirin" name="allergies[]" value="aspirin">
      <label class="form-check-label" for="allergy_aspirin">Aspirin</label>
    </div>

    <div class="form-check">
      <input class="form-check-input ml-2" type="checkbox" id="allergy_latex" name="allergies[]" value="latex">
      <label class="form-check-label" for="allergy_latex">Latex</label>
    </div>

    <div class="form-check d-flex align-items-center">
      <input class="form-check-input ml-2 mb-3" type="checkbox" id="allergy_other" name="allergies[]" value="other" onchange="toggleOtherAllergy(this)">
      <label class="form-check-label" for="allergy_other" style="cursor:pointer;">Others:</label>
      <input type="text" class="form-control ml-2" id="other_allergy" name="other_allergy" placeholder="Please specify" style="display:none; max-width: 200px;">
    </div>
  </div>
  
  <!-- 9. Bleeding Time -->
<!-- 9. Bleeding Time -->
<div class="mb-3">
  <label for="bleeding_time"><strong>9. Bleeding Time:</strong></label>
  <input type="text" 
         class="form-control" 
         id="bleeding_time" 
         name="bleeding_time" 
         placeholder="Enter bleeding time (e.g., 1-3 minutes)">
</div>
  
  <div class="form-group">
    <div class="table-responsive">
      <table class="table table-borderless">
        <label><strong>10. For women only:</strong></label>
        <tbody>
          <tr>
            <td>Are you pregnant?</td>
            <td class="text-center">
              <input type="radio" name="pregnant" id="pregnant_yes" value="yes">
              <label for="pregnant_yes" class="ml-1">Yes</label>
            </td>
            <td class="text-center">
              <input type="radio" name="pregnant" id="pregnant_no" value="no">
              <label for="pregnant_no" class="ml-1">No</label>
            </td>
          </tr>
          <tr>
            <td>Are you nursing?</td>
            <td class="text-center">
              <input type="radio" name="nursing" id="nursing_yes" value="yes">
              <label for="nursing_yes" class="ml-1">Yes</label>
            </td>
            <td class="text-center">
              <input type="radio" name="nursing" id="nursing_no" value="no">
              <label for="nursing_no" class="ml-1">No</label>
            </td>
          </tr>
          <tr>
            <td>Are you taking birth control pills?</td>
            <td class="text-center">
              <input type="radio" name="birth_control" id="birth_control_yes" value="yes">
              <label for="birth_control_yes" class="ml-1">Yes</label>
            </td>
            <td class="text-center">
              <input type="radio" name="birth_control" id="birth_control_no" value="no">
              <label for="birth_control_no" class="ml-1">No</label>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  
  <div class="form-row">
    <div class="col-md-6 mb-3">
      <label for="blood_type"><strong>11. Blood Type:</strong></label>
      <select class="form-control" id="blood_type" name="blood_type">
        <option value="" selected disabled>Select blood type</option>
        <option value="A+">A+</option>
        <option value="A-">A-</option>
        <option value="B+">B+</option>
        <option value="B-">B-</option>
        <option value="AB+">AB+</option>
        <option value="AB-">AB-</option>
        <option value="O+">O+</option>
        <option value="O-">O-</option>
      </select>
    </div>

    <div class="col-md-6 mb-3">
      <label for="blood_pressure"><strong>12. Blood Pressure:</strong></label>
      <input type="text" class="form-control" id="blood_pressure" name="blood_pressure" placeholder="Enter blood pressure">
    </div>
  </div>
  
<!-- 13. Medical History --> 
<label><strong>13. Do you have or have you had any of the following? Check which apply:</strong></label>
<div class="row">

  <div class="col-md-3 ml-4">
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_high_bp" name="medical_conditions[]" value="High Blood Pressure"><label class="form-check-label" for="condition_high_bp">High Blood Pressure</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_low_bp" name="medical_conditions[]" value="Low Blood Pressure"><label class="form-check-label" for="condition_low_bp">Low Blood Pressure</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_epilepsy" name="medical_conditions[]" value="Epilepsy / Convulsions"><label class="form-check-label" for="condition_epilepsy">Epilepsy / Convulsions</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_aids" name="medical_conditions[]" value="AIDS or HIV Infection"><label class="form-check-label" for="condition_aids">AIDS or HIV Infection</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_sti" name="medical_conditions[]" value="Sexually Transmitted Disease"><label class="form-check-label" for="condition_sti">Sexually Transmitted Disease</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_stomach" name="medical_conditions[]" value="Stomach Troubles / Ulcers"><label class="form-check-label" for="condition_stomach">Stomach Troubles / Ulcers</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_fainting" name="medical_conditions[]" value="Fainting Seizure"><label class="form-check-label" for="condition_fainting">Fainting Seizure</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_weight_loss" name="medical_conditions[]" value="Rapid Weight Loss"><label class="form-check-label" for="condition_weight_loss">Rapid Weight Loss</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_radiation" name="medical_conditions[]" value="Radiation Therapy"><label class="form-check-label" for="condition_radiation">Radiation Therapy</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_joint_replacement" name="medical_conditions[]" value="Joint Replacement / Implant"><label class="form-check-label" for="condition_joint_replacement">Joint Replacement / Implant</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_heart_surgery" name="medical_conditions[]" value="Heart Surgery"><label class="form-check-label" for="condition_heart_surgery">Heart Surgery</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_heart_attack" name="medical_conditions[]" value="Heart Attack"><label class="form-check-label" for="condition_heart_attack">Heart Attack</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_thyroid" name="medical_conditions[]" value="Thyroid Problem"><label class="form-check-label" for="condition_thyroid">Thyroid Problem</label></div>
  </div>

  <div class="col-md-4 ml-4">
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_heart_disease" name="medical_conditions[]" value="Heart Disease"><label class="form-check-label" for="condition_heart_disease">Heart Disease</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_heart_murmur" name="medical_conditions[]" value="Heart Murmur"><label class="form-check-label" for="condition_heart_murmur">Heart Murmur</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_hepatitis_liver" name="medical_conditions[]" value="Hepatitis / Liver Disease"><label class="form-check-label" for="condition_hepatitis_liver">Hepatitis / Liver Disease</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_rheumatic_fever" name="medical_conditions[]" value="Rheumatic Fever"><label class="form-check-label" for="condition_rheumatic_fever">Rheumatic Fever</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_hay_fever" name="medical_conditions[]" value="Hay Fever / Allergies"><label class="form-check-label" for="condition_hay_fever">Hay Fever / Allergies</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_respiratory" name="medical_conditions[]" value="Respiratory Problems"><label class="form-check-label" for="condition_respiratory">Respiratory Problems</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_hepatitis_jaundice" name="medical_conditions[]" value="Hepatitis / Jaundice"><label class="form-check-label" for="condition_hepatitis_jaundice">Hepatitis / Jaundice</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_tuberculosis" name="medical_conditions[]" value="Tuberculosis"><label class="form-check-label" for="condition_tuberculosis">Tuberculosis</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_swollen_ankles" name="medical_conditions[]" value="Swollen ankles"><label class="form-check-label" for="condition_swollen_ankles">Swollen ankles</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_kidney_disease" name="medical_conditions[]" value="Kidney disease"><label class="form-check-label" for="condition_kidney_disease">Kidney disease</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_diabetes" name="medical_conditions[]" value="Diabetes"><label class="form-check-label" for="condition_diabetes">Diabetes</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_chest_pain" name="medical_conditions[]" value="Chest pain"><label class="form-check-label" for="condition_chest_pain">Chest pain</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_stroke" name="medical_conditions[]" value="Stroke"><label class="form-check-label" for="condition_stroke">Stroke</label></div>
  </div>

  <div class="col-md-4 ml-4">
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_cancer" name="medical_conditions[]" value="Cancer / Tumors"><label class="form-check-label" for="condition_cancer">Cancer / Tumors</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_anemia" name="medical_conditions[]" value="Anemia"><label class="form-check-label" for="condition_anemia">Anemia</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_angina" name="medical_conditions[]" value="Angina"><label class="form-check-label" for="condition_angina">Angina</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_asthma" name="medical_conditions[]" value="Asthma"><label class="form-check-label" for="condition_asthma">Asthma</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_emphysema" name="medical_conditions[]" value="Emphysema"><label class="form-check-label" for="condition_emphysema">Emphysema</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_bleeding_problems" name="medical_conditions[]" value="Bleeding Problems"><label class="form-check-label" for="condition_bleeding_problems">Bleeding Problems</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_blood_diseases" name="medical_conditions[]" value="Blood Diseases"><label class="form-check-label" for="condition_blood_diseases">Blood Diseases</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_head_injuries" name="medical_conditions[]" value="Head Injuries"><label class="form-check-label" for="condition_head_injuries">Head Injuries</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_arthritis" name="medical_conditions[]" value="Arthritis / Rheumatism"><label class="form-check-label" for="condition_arthritis">Arthritis / Rheumatism</label></div>
    <div class="form-check"><input class="form-check-input" type="checkbox" id="condition_other" name="medical_conditions[]" value="Other"><label class="form-check-label" for="condition_other">Other</label></div>
  </div>

</div>

                                    </div>
                                  </div>
                                </div>

                                <script>
function toggleOtherAllergy(checkbox) {
  const otherInput = document.getElementById('other_allergy'); // Changed from 'allergy_other_text'
  if (checkbox.checked) {
    otherInput.style.display = 'inline-block';
    otherInput.focus();
  } else {
    otherInput.style.display = 'none';
    otherInput.value = '';
  }
}
                                </script>

            
                              <hr>

                              <div class="card">
                                <div class="card-body">
                                  <h4 class="card-title">Emergency Contacts</h4>
                                    <div class="row">
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="primary_contact_name">Contact Name</label>
                                          <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Enter contact name">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="primary_relationship">Relationship</label>
                                          <input type="text" class="form-control" id="relationship" name="relationship" placeholder="Enter relationship">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="primary_phone">Phone Number</label>
                                          <input type="text" class="form-control" id="emergency_phone" name="emergency_phone" placeholder="Enter phone number">
                                        </div>
                                      </div>
                                      <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="primary_alt_phone">Alternate Phone Number</label>
                                          <input type="text" class="form-control" id="alt_phone" name="alt_phone" placeholder="Enter alternate phone number">
                                        </div>
                                      </div>
                      
                                    </div>
                                </div>
                              </div>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap"> 
  <div class="d-flex ml-auto">
  <button type="submit" name="add_patient" class="btn btn-info btn-icon-text mr-2">
    Save <i class="ti-save btn-icon-append"></i>
  </button>
    <button type="reset" class="btn btn-secondary btn-icon-text">
      Clear <i class="ti-cut btn-icon-append"></i>
    </button>
  </div>
</div>

   </form>
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
  </div>        


      </div>
    </form>
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../assets/js/search-bar.js"></script>
  <script src="../assets/js/toast.js"></script>
  <script src="../assets/js/security.js"></script>



<script>
  function toggleCondition(inputId, show) {
    const input = document.getElementById(inputId);
    if (input) {
      if(show) {
        input.style.display = 'block';
        input.focus();
      } else {
        input.style.display = 'none';
        input.value = '';
      }
    }
  }

  // Optional: Initialize visibility on page load for all inputs
  window.onload = function() {
    // For medical treatment
    toggleCondition('medical_condition', document.getElementById('medical_treatment_yes').checked);
    // For allergies
    toggleCondition('serious_illness', document.getElementById('serious_illness').checked);
  }
</script>

</body>

</html>

