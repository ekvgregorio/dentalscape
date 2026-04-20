<?php
  session_start();
  require_once '../conn.php';
  

  if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
  }

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>DentalScape Iloilo</title>
  <link href="../assets/user/images/logo1.png" rel="icon">
  <link href="../assets/user/images/logo1.png" rel="apple-touch-icon">
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
</head>

<style>
    #appointmentTable tbody tr:hover {
    background-color: #c9c9c9;
    }
    
    #appointmentTable thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    }   

       .help-category {
      padding: 20px;
      border-left: 4px solid #007bff;
      margin-bottom: 15px;
      background: #f8f9fa;
      border-radius: 4px;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .help-category:hover {
      background: #e9ecef;
      border-left-color: #0056b3;
    }
    .help-category i {
      font-size: 24px;
      color: #007bff;
      margin-right: 15px;
    }
    .faq-item {
      border-bottom: 1px solid #e9ecef;
      padding: 15px 0;
    }
    .faq-item:last-child {
      border-bottom: none;
    }
    .faq-question {
      cursor: pointer;
      font-weight: 500;
      color: #333;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .faq-question:hover {
      color: #007bff;
    }
    .faq-answer {
      display: none;
      margin-top: 10px;
      color: #666;
      padding-left: 10px;
    }
    .faq-answer.show {
      display: block;
    }
    .contact-option {
      text-align: center;
      padding: 30px 20px;
      border-radius: 8px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      height: 100%;
    }
    .contact-option i {
      font-size: 48px;
      margin-bottom: 15px;
    }
    .contact-option h5 {
      margin-bottom: 10px;
      color: white;
    }
    .contact-option p {
      color: rgba(255,255,255,0.9);
      margin-bottom: 15px;
    }
    .btn-light-custom {
      background: rgba(255,255,255,0.2);
      border: 1px solid rgba(255,255,255,0.3);
      color: white;
      padding: 8px 24px;
      border-radius: 4px;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    .btn-light-custom:hover {
      background: rgba(255,255,255,0.3);
      color: white;
    }
    .search-box {
      position: relative;
      margin-bottom: 30px;
    }
    .search-box input {
      padding-left: 45px;
      height: 50px;
      border-radius: 8px;
      border: 1px solid #ddd;
    }
    .search-box i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #999;
    }
    .tutorial-card {
      border-left: 4px solid #28a745;
      padding: 15px;
      background: #f8f9fa;
      margin-bottom: 10px;
      border-radius: 4px;
    }
    .tutorial-card:hover {
      background: #e9ecef;
    }
    .badge-category {
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 500;
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
      
      <!-- Page Header -->
      <div class="row mb-4">
        <div class="col-12">
          <h3 class="mb-2">Help & Support Center</h3>
          <p class="text-muted">Find answers to your questions and get assistance with Health & Appointment Records</p>
        </div>
      </div>


      <!-- Help Categories -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h5 class="mb-4">Browse by Category</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="help-category" onclick="scrollToSection('appointments')">
                    <i class="fas fa-calendar-check"></i>
                    <div style="display: inline-block; vertical-align: top;">
                      <h6 class="mb-1">Appointments</h6>
                      <small class="text-muted">Booking, rescheduling, and managing appointments</small>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="help-category" onclick="scrollToSection('records')">
                    <i class="fas fa-file-medical"></i>
                    <div style="display: inline-block; vertical-align: top;">
                      <h6 class="mb-1">Health Records</h6>
                      <small class="text-muted">Accessing and understanding your health records</small>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="help-category" onclick="scrollToSection('account')">
                    <i class="fas fa-user-cog"></i>
                    <div style="display: inline-block; vertical-align: top;">
                      <h6 class="mb-1">Account Settings</h6>
                      <small class="text-muted">Password, profile, and security settings</small>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="help-category" onclick="scrollToSection('technical')">
                    <i class="fas fa-tools"></i>
                    <div style="display: inline-block; vertical-align: top;">
                      <h6 class="mb-1">Technical Support</h6>
                      <small class="text-muted">Login issues, errors, and troubleshooting</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- FAQs Section -->
      <div class="row mb-4">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <h5 class="mb-4" id="appointments">
                <i class="fas fa-calendar-check text-primary me-2"></i>
                Appointment Management
              </h5>
              
              <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                  <span>How do I book a new appointment?</span>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  Navigate to the "Appointments" section from the main menu, click "Book New Appointment," select your preferred date, time, and type of service, then click "Submit." You'll receive a confirmation email once your appointment is scheduled.
                </div>
              </div>

              <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                  <span>Can I reschedule or cancel an appointment?</span>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  Yes! Go to "My Appointments," find the appointment you want to modify, and click the "Reschedule" or "Cancel" button. Please note that cancellations made less than 24 hours before the appointment may be subject to a fee.
                </div>
              </div>

              <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                  <span>How will I receive appointment reminders?</span>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  Appointment reminders are sent via email and SMS (if enabled) 24 hours before your scheduled appointment. You can customize your notification preferences in Account Settings.
                </div>
              </div>

              <hr class="my-4">

              <h5 class="mb-4" id="records">
                <i class="fas fa-file-medical text-success me-2"></i>
                Health Records
              </h5>

              <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                  <span>How do I access my health records?</span>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  Click on "Health Records" in the main menu to view your complete medical history, including visit summaries, test results, prescriptions, and treatment plans. You can also download records as PDF files.
                </div>
              </div>

              <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                  <span>Are my health records secure and private?</span>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  Absolutely. We use industry-standard encryption and comply with HIPAA regulations to protect your health information. Your records are only accessible to you and authorized healthcare providers.
                </div>
              </div>

              <hr class="my-4">

              <h5 class="mb-4" id="account">
                <i class="fas fa-user-cog text-warning me-2"></i>
                Account Settings
              </h5>

              <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                  <span>How do I change my password?</span>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  Go to Account Settings > Security > Change Password. Enter your current password, then your new password twice. Make sure your password is at least 8 characters and includes uppercase, lowercase, numbers, and symbols.
                </div>
              </div>

              <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                  <span>I forgot my password. What should I do?</span>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  Click "Forgot Password" on the login page. Enter your email address, and we'll send you instructions to reset your password. If you don't receive the email within 10 minutes, check your spam folder.
                </div>
              </div>

              <hr class="my-4">

              <h5 class="mb-4" id="technical">
                <i class="fas fa-tools text-danger me-2"></i>
                Technical Support
              </h5>

              <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                  <span>I'm having trouble logging in. What should I do?</span>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  First, verify your email and password are correct. Clear your browser cache and cookies, then try again. If the issue persists, use the "Forgot Password" feature or contact technical support.
                </div>
              </div>

              <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                  <span>Which browsers are supported?</span>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                  Our system works best with the latest versions of Chrome, Firefox, Safari, and Edge. For optimal performance, ensure your browser is up to date.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Submit Ticket -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h5 class="mb-3">Can't Find What You're Looking For?</h5>
              <p class="text-muted mb-4">Submit a support ticket and our team will get back to you within 24 hours.</p>
<form id="supportTicketForm" method="POST" action="/dentalscape/user/user_process.php">
  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">Your Name</label>
      <input type="text" class="form-control" name="name" placeholder="Enter your name" required>
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">Email Address</label>
      <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
    </div>
    <div class="col-md-12 mb-3">
      <label class="form-label">Subject</label>
      <input type="text" class="form-control" name="subject" placeholder="Brief description of your issue" required>
    </div>
    <div class="col-md-12 mb-3">
      <label class="form-label">Category</label>
      <select class="form-control" name="category" required>
        <option value="">Select a category</option>
        <option value="appointments">Appointments</option>
        <option value="records">Health Records</option>
        <option value="account">Account Settings</option>
        <option value="technical">Technical Issue</option>
        <option value="other">Other</option>
      </select>
    </div>
    <div class="col-md-12 mb-3">
      <label class="form-label">Message</label>
      <textarea class="form-control" name="message" rows="5" placeholder="Describe your issue in detail..." required></textarea>
    </div>
    <div class="col-md-12">
      <button type="submit" name="send_help" class="btn btn-primary">
        <i class="fas fa-paper-plane me-2"></i>Submit Ticket
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script>
    function toggleFaq(element) {
      const answer = element.nextElementSibling;
      const icon = element.querySelector('i');
      
      answer.classList.toggle('show');
      
      if (answer.classList.contains('show')) {
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
      } else {
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
      }
    }

    function scrollToSection(sectionId) {
      const element = document.getElementById(sectionId);
      if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }

    function openLiveChat() {
      alert('Live chat feature would open here. This would typically integrate with a chat service like Intercom, Zendesk, or custom chat solution.');
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const faqItems = document.querySelectorAll('.faq-item');
      
      faqItems.forEach(item => {
        const question = item.querySelector('.faq-question span').textContent.toLowerCase();
        const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
        
        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
          item.style.display = 'block';
        } else {
          item.style.display = searchTerm ? 'none' : 'block';
        }
      });
    });

    // Form submission
document.getElementById('supportTicketForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);

    fetch('/dentalscape/user/user_process.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.status === 'success') this.reset();
    })
    .catch(err => console.error(err));
});
  </script>


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
  <script src="../assets/js/search-bar.js"></script>
  <script src="../assets/js/toast.js"></script>
  <script src="../assets/admin/js/Chart.roundedBarCharts.js"></script>

</body>

</html>

