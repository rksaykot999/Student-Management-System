<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Teacher Registration - Academic Management System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f0f4f8;
    }

    .dropdown-menu {
      display: none;
    }

    .dropdown-menu.show {
      display: block;
    }

    .mobile-menu-container {
      transition: all 0.3s ease-in-out;
      transform: translateY(-100%);
      opacity: 0;
      pointer-events: none;
    }

    .mobile-menu-container.show {
      transform: translateY(0);
      opacity: 1;
      pointer-events: auto;
    }

    :root {
      --primary: #4361ee;
      --secondary: #3a0ca3;
      --accent: #4cc9f0;
      --light: #f8f9fa;
      --dark: #212529;
      --success: #4bb543;
      --danger: #dc3545;
      --warning: #ffc107;
    }

    * {
      font-family: 'Poppins', sans-serif;
    }

    main {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      padding: 20px 0;
    }

    .registration-container {
      max-width: 1000px;
      margin: 0 auto;
    }

    .registration-card {
      border-radius: 20px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      border: none;
    }

    .card-header {
      background: linear-gradient(to right, var(--primary), var(--secondary));
      color: white;
      padding: 25px 30px;
      border-bottom: none;
    }

    .card-body {
      padding: 30px;
      background-color: white;
    }

    .form-label {
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 8px;
    }

    .form-control,
    .form-select {
      border-radius: 10px;
      padding: 12px 15px;
      border: 1.5px solid #e1e5ee;
      transition: all 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    .required::after {
      content: " *";
      color: var(--danger);
    }

    .btn-primary {
      background: linear-gradient(to right, var(--primary), var(--secondary));
      border: none;
      border-radius: 10px;
      padding: 12px 30px;
      font-weight: 600;
      transition: all 0.3s;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
    }

    .btn-outline-primary {
      border-radius: 10px;
      padding: 12px 30px;
      font-weight: 600;
    }

    .preview-container {
      border: 2px dashed #dee2e6;
      border-radius: 10px;
      padding: 15px;
      text-align: center;
      transition: all 0.3s;
      background-color: #f8f9fa;
      cursor: pointer;
    }

    .preview-container:hover {
      border-color: var(--primary);
    }

    .preview-img {
      max-width: 150px;
      max-height: 150px;
      object-fit: cover;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .upload-icon {
      font-size: 40px;
      color: var(--primary);
      margin-bottom: 10px;
    }

    .step-indicator {
      display: flex;
      justify-content: space-between;
      margin-bottom: 30px;
      position: relative;
    }

    .step-indicator::before {
      content: '';
      position: absolute;
      top: 15px;
      left: 0;
      right: 0;
      height: 2px;
      background-color: #e1e5ee;
      z-index: 1;
    }

    .step {
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;
      z-index: 2;
    }

    .step-circle {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      background-color: #e1e5ee;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #6c757d;
      font-weight: 600;
      margin-bottom: 5px;
    }

    .step.active .step-circle {
      background-color: var(--primary);
      color: white;
    }

    .step-label {
      font-size: 14px;
      color: #6c757d;
    }

    .step.active .step-label {
      color: var(--primary);
      font-weight: 600;
    }

    .form-section {
      display: none;
    }

    .form-section.active {
      display: block;
      animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .nav-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
    }

    .alert {
      border-radius: 10px;
      border: none;
      padding: 15px 20px;
    }

    .alert-danger {
      background-color: rgba(220, 53, 69, 0.1);
      color: var(--danger);
    }

    .alert-success {
      background-color: rgba(75, 181, 67, 0.1);
      color: var(--success);
    }

    .password-strength {
      height: 5px;
      border-radius: 5px;
      margin-top: 5px;
      transition: all 0.3s;
    }

    .strength-weak {
      width: 25%;
      background-color: var(--danger);
    }

    .strength-fair {
      width: 50%;
      background-color: var(--warning);
    }

    .strength-good {
      width: 75%;
      background-color: #ffa500;
    }

    .strength-strong {
      width: 100%;
      background-color: var(--success);
    }

    .login-link {
      text-align: center;
      margin-top: 20px;
      color: #6c757d;
    }

    .login-link a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    .department-suggestions {
      position: absolute;
      background: white;
      border: 1px solid #e1e5ee;
      border-radius: 5px;
      max-height: 150px;
      overflow-y: auto;
      width: 100%;
      z-index: 1000;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      display: none;
    }

    .department-suggestion {
      padding: 8px 12px;
      cursor: pointer;
    }

    .department-suggestion:hover {
      background-color: #f8f9fa;
    }

    .department-container {
      position: relative;
    }

    .qualification-select {
      height: 120px;
    }

    @media (max-width: 768px) {
      .step-label {
        font-size: 12px;
      }

      .card-body {
        padding: 20px;
      }
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="sticky top-0 z-50 bg-white shadow-lg py-[10px] px-4 md:px-8 flex justify-between items-center">

    <!-- Logo -->
    <a href="index.php" class="flex items-center space-x-2 cursor-pointer">
      <img src="Images/Logo.png" alt="BPI Logo" class="h-12 w-12">
    </a>

    <!-- Desktop Menu -->
    <div class="hidden md:flex items-center space-x-6">
      <a href="index.php" class="text-gray-600 font-bold hover:text-blue-600 transition">Home</a>
      <a href="about.php" class="text-gray-600 font-bold hover:text-blue-600 transition">About</a>
      <a href="academic.php" class="text-gray-600 font-bold hover:text-blue-600 transition">Academic</a>
      <a href="department.php" class="text-gray-600 font-bold hover:text-blue-600 transition">Departments</a>
      <a href="teachers.php" class="text-gray-600 font-bold hover:text-blue-600 transition">Teachers</a>
      <a href="students.php" class="text-gray-600 font-bold hover:text-blue-600 transition">Students</a>
      <a href="notice.php" class="text-gray-600 font-bold hover:text-blue-600 transition">Notice</a>
    </div>

    <!-- Right Section -->
    <div class="flex items-center space-x-4 relative">
      <!-- Desktop Login -->
      <button id="logoButton"
        class="hidden md:flex items-center space-x-2 text-gray-600 hover:text-blue-600 focus:outline-none">
        <span class="font-semibold">Login</span>
        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white">
          <i class="fas fa-user"></i>
        </div>
      </button>

      <!-- Mobile Menu Button -->
      <button id="menuButton" class="md:hidden text-gray-600 hover:text-blue-600 focus:outline-none">
        <i class="fas fa-bars text-2xl"></i>
      </button>

      <!-- Dropdown Menu -->
      <div id="loginDropdown"
        class="dropdown-menu absolute top-full right-0 mt-3 w-48 bg-white rounded-lg shadow-xl py-2 z-50">
        <a href="student-login.php"
          class="block px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 text-center">Student Login</a>
        <a href="teacher-login.php"
          class="block px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 text-center">Teacher Login</a>
        <a href="admin_login.php"
          class="block px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 text-center">Admin Login</a>
      </div>
    </div>
  </nav>

  <!-- Mobile Menu -->
  <div id="mobileMenu" class="mobile-menu-container fixed top-16 left-0 w-full bg-white shadow-lg p-6 z-40 md:hidden">
    <a href="index.php" class="block py-2 text-gray-600 hover:text-blue-600">Home</a>
    <a href="academic.php" class="block py-2 text-gray-600 font-semibold">Academics</a>
    <a href="department.php" class="block py-2 text-gray-600 hover:text-blue-600">Departments</a>
    <a href="notice.php" class="block py-2 text-gray-600 hover:text-blue-600">Notice</a>
    <a href="teachers.php" class="block py-2 text-gray-600 hover:text-blue-600">Teachers</a>
    <a href="students.php" class="block py-2 text-gray-600 hover:text-blue-600">Students</a>
    <a href="about.php" class="block py-2 text-gray-600 hover:text-blue-600">About</a>

    <!-- Mobile Login Button -->
    <button id="mobileLoginButton" class="w-full mt-4 bg-blue-600 text-white py-2 rounded-md">Login</button>
  </div>
  <main>
    <div class="container registration-container">
      <div class="card registration-card">
        <div class="card-header text-center">
          <h2 class="mb-1"><i class="fas fa-user-graduate me-2"></i>Teacher Registration</h2>
          <p class="mb-0 opacity-75">Academic Management System - New Teacher Registration</p>
        </div>

        <div class="card-body">
          <!-- Step Indicator -->
          <div class="step-indicator">
            <div class="step active" data-step="1">
              <div class="step-circle">1</div>
              <div class="step-label">Personal Information</div>
            </div>
            <div class="step" data-step="2">
              <div class="step-circle">2</div>
              <div class="step-label">Professional Details</div>
            </div>
            <div class="step" data-step="3">
              <div class="step-circle">3</div>
              <div class="step-label">Review & Submit</div>
            </div>
          </div>

          <!-- Error/Success Messages -->
          <div id="messageContainer">
            <!-- Messages will be dynamically inserted here -->
          </div>

          <form method="post" enctype="multipart/form-data" id="registrationForm">
            <!-- Step 1: Personal Information -->
            <div class="form-section active" id="step1">
              <h4 class="mb-4 text-primary"><i class="fas fa-user me-2"></i>Personal Information</h4>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label required">Full Name</label>
                  <input name="name" class="form-control" id="name" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label required">Email Address</label>
                  <input name="email" type="email" class="form-control" id="email" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label required">Password</label>
                  <div class="input-group">
                    <input name="password" type="password" class="form-control" id="password" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>
                  <div class="password-strength" id="passwordStrength"></div>
                  <small class="text-muted">Password strength: <span id="passwordText">Not set</span></small>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Phone Number</label>
                  <input name="phone" class="form-control" id="phone">
                </div>
              </div>

              <div class="nav-buttons">
                <div></div> <!-- Empty div for spacing -->
                <button type="button" class="btn btn-primary next-step" data-next="2">Next <i class="fas fa-arrow-right ms-2"></i></button>
              </div>
            </div>

            <!-- Step 2: Professional Information -->
            <div class="form-section" id="step2">
              <h4 class="mb-4 text-primary"><i class="fas fa-briefcase me-2"></i>Professional Details</h4>

              <div class="row g-3">
                <div class="col-md-6 department-container">
                  <label class="form-label">Department</label>
                  <input name="department" class="form-control" id="department" list="departmentOptions">
                  <datalist id="departmentOptions">
                    <option value="Computer Science">
                    <option value="Mathematics">
                    <option value="Physics">
                    <option value="Chemistry">
                    <option value="Biology">
                    <option value="English">
                    <option value="History">
                    <option value="Economics">
                  </datalist>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Shift</label>
                  <select name="shift" class="form-select" id="shift">
                    <option value="">Select Shift</option>
                    <option value="Morning">Morning</option>
                    <option value="Day">Day</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Qualification</label>
                  <select name="qualification" class="form-select qualification-select" id="qualification" multiple>
                    <option value="PhD">PhD</option>
                    <option value="Masters">Masters</option>
                    <option value="Bachelor's">Bachelor's</option>
                    <option value="Diploma">Diploma</option>
                    <option value="Certification">Certification</option>
                  </select>
                  <small class="text-muted">Hold Ctrl/Cmd to select multiple qualifications</small>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Designation</label>
                  <select name="designation" class="form-select" id="designation">
                    <option value="">Select Designation</option>
                    <option value="Professor">Professor</option>
                    <option value="Associate Professor">Associate Professor</option>
                    <option value="Assistant Professor">Assistant Professor</option>
                    <option value="Lecturer">Lecturer</option>
                    <option value="Instructor">Instructor</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label">Profile Photo (jpg, png, gif) - max 2MB</label>
                  <div class="preview-container">
                    <input type="file" name="image" accept="image/*" class="d-none" id="tchImage">
                    <div id="uploadArea" class="cursor-pointer">
                      <i class="fas fa-cloud-upload-alt upload-icon"></i>
                      <p class="mb-2">Click to upload photo</p>
                      <small class="text-muted">Max size: 2MB</small>
                    </div>
                    <img id="tchPreview" class="preview-img mt-3 d-none">
                  </div>
                </div>
              </div>

              <div class="nav-buttons">
                <button type="button" class="btn btn-outline-primary prev-step" data-prev="1"><i class="fas fa-arrow-left me-2"></i>Previous</button>
                <button type="button" class="btn btn-primary next-step" data-next="3">Next <i class="fas fa-arrow-right ms-2"></i></button>
              </div>
            </div>

            <!-- Step 3: Review and Submit -->
            <div class="form-section" id="step3">
              <h4 class="mb-4 text-primary"><i class="fas fa-check-circle me-2"></i>Review Your Information</h4>

              <div class="card mb-4">
                <div class="card-body">
                  <h5 class="card-title">Personal Information</h5>
                  <div class="row">
                    <div class="col-md-6">
                      <p><strong>Name:</strong> <span id="reviewName"></span></p>
                      <p><strong>Email:</strong> <span id="reviewEmail"></span></p>
                    </div>
                    <div class="col-md-6">
                      <p><strong>Phone:</strong> <span id="reviewPhone"></span></p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card mb-4">
                <div class="card-body">
                  <h5 class="card-title">Professional Details</h5>
                  <div class="row">
                    <div class="col-md-6">
                      <p><strong>Department:</strong> <span id="reviewDept"></span></p>
                      <p><strong>Shift:</strong> <span id="reviewShift"></span></p>
                    </div>
                    <div class="col-md-6">
                      <p><strong>Qualification:</strong> <span id="reviewQual"></span></p>
                      <p><strong>Designation:</strong> <span id="reviewDesig"></span></p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card mb-4">
                <div class="card-body">
                  <h5 class="card-title">Profile Photo</h5>
                  <div id="reviewPhoto" class="text-center">
                    <p class="text-muted">No photo selected</p>
                  </div>
                </div>
              </div>

              <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                <label class="form-check-label" for="agreeTerms">
                  I confirm that all the information provided above is correct and I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms of Service</a>
                </label>
              </div>

              <div class="nav-buttons">
                <button type="button" class="btn btn-outline-primary prev-step" data-prev="2"><i class="fas fa-arrow-left me-2"></i>Previous</button>
                <button type="submit" class="btn btn-success" id="submitBtn"><i class="fas fa-paper-plane me-2"></i>Submit Registration</button>
              </div>
            </div>
          </form>

          <div class="login-link">
            Already have an account? <a href="teacher-login.php">Login here</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Terms of Service Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="termsModalLabel">Terms of Service</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>By registering as a teacher in our Academic Management System, you agree to the following terms:</p>
            <ul>
              <li>All information provided must be accurate and up-to-date</li>
              <li>Your registration will be reviewed by administrators before approval</li>
              <li>You agree to follow the institution's code of conduct</li>
              <li>Your account may be suspended for violations of system policies</li>
              <li>You are responsible for maintaining the confidentiality of your account</li>
            </ul>
            <p>For more details, please refer to our complete terms and conditions document.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <?php include_once '../includes/footer.php'; ?>
  <!-- End Footer -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const loginButton = document.getElementById('logoButton');
    const loginDropdown = document.getElementById('loginDropdown');
    const mobileMenuButton = document.getElementById('menuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileLoginButton = document.getElementById('mobileLoginButton');

    // Desktop login dropdown
    if (loginButton) {
      loginButton.addEventListener('click', (e) => {
        loginDropdown.classList.toggle('show');
        e.stopPropagation();
      });
    }

    // Hide dropdown when clicking outside
    window.addEventListener('click', (e) => {
      if (!loginDropdown.contains(e.target) && !loginButton.contains(e.target)) {
        loginDropdown.classList.remove('show');
      }
    });

    // Toggle mobile menu
    mobileMenuButton.addEventListener('click', () => {
      mobileMenu.classList.toggle('show');
    });

    // Mobile login dropdown toggle
    mobileLoginButton.addEventListener('click', (e) => {
      e.preventDefault();
      loginDropdown.classList.toggle('show');
    });

    // Hide mobile menu on link click
    mobileMenu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        mobileMenu.classList.remove('show');
      });
    });
    document.addEventListener('DOMContentLoaded', function() {
      // Step navigation
      const steps = document.querySelectorAll('.step');
      const formSections = document.querySelectorAll('.form-section');
      const nextButtons = document.querySelectorAll('.next-step');
      const prevButtons = document.querySelectorAll('.prev-step');

      nextButtons.forEach(button => {
        button.addEventListener('click', function() {
          const currentStep = document.querySelector('.step.active').getAttribute('data-step');
          const nextStep = this.getAttribute('data-next');

          // Validate current step before proceeding
          if (validateStep(currentStep)) {
            // Update step indicator
            steps.forEach(step => {
              if (step.getAttribute('data-step') === nextStep) {
                step.classList.add('active');
              } else {
                step.classList.remove('active');
              }
            });

            // Update form sections
            formSections.forEach(section => {
              if (section.id === 'step' + nextStep) {
                section.classList.add('active');
              } else {
                section.classList.remove('active');
              }
            });

            // Update review section with current values
            if (nextStep === '3') {
              updateReviewSection();
            }
          }
        });
      });

      prevButtons.forEach(button => {
        button.addEventListener('click', function() {
          const prevStep = this.getAttribute('data-prev');

          // Update step indicator
          steps.forEach(step => {
            if (step.getAttribute('data-step') === prevStep) {
              step.classList.add('active');
            } else {
              step.classList.remove('active');
            }
          });

          // Update form sections
          formSections.forEach(section => {
            if (section.id === 'step' + prevStep) {
              section.classList.add('active');
            } else {
              section.classList.remove('active');
            }
          });
        });
      });

      // Image upload and preview
      const uploadArea = document.getElementById('uploadArea');
      const imageInput = document.getElementById('tchImage');
      const previewImg = document.getElementById('tchPreview');

      uploadArea.addEventListener('click', function() {
        imageInput.click();
      });

      imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewImg.classList.remove('d-none');
            uploadArea.querySelector('p').textContent = 'Click to change photo';
          };
          reader.readAsDataURL(file);
        }
      });

      // Password strength indicator
      const passwordInput = document.getElementById('password');
      const strengthBar = document.getElementById('passwordStrength');
      const strengthText = document.getElementById('passwordText');
      const togglePassword = document.getElementById('togglePassword');

      passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;

        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        if (password.match(/\d/)) strength++;
        if (password.match(/[^a-zA-Z\d]/)) strength++;

        // Reset classes
        strengthBar.className = 'password-strength';

        if (password.length === 0) {
          strengthText.textContent = 'Not set';
        } else if (strength <= 1) {
          strengthBar.classList.add('strength-weak');
          strengthText.textContent = 'Weak';
        } else if (strength === 2) {
          strengthBar.classList.add('strength-fair');
          strengthText.textContent = 'Fair';
        } else if (strength === 3) {
          strengthBar.classList.add('strength-good');
          strengthText.textContent = 'Good';
        } else {
          strengthBar.classList.add('strength-strong');
          strengthText.textContent = 'Strong';
        }
      });

      // Toggle password visibility
      togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
      });

      // Update review section with current form values
      function updateReviewSection() {
        document.getElementById('reviewName').textContent = document.getElementById('name').value;
        document.getElementById('reviewEmail').textContent = document.getElementById('email').value;
        document.getElementById('reviewPhone').textContent = document.getElementById('phone').value || 'Not provided';
        document.getElementById('reviewDept').textContent = document.getElementById('department').value || 'Not provided';
        document.getElementById('reviewShift').textContent = document.getElementById('shift').value || 'Not provided';

        // Handle multiple qualifications
        const qualificationSelect = document.getElementById('qualification');
        const selectedQualifications = Array.from(qualificationSelect.selectedOptions).map(option => option.value);
        document.getElementById('reviewQual').textContent = selectedQualifications.length > 0 ? selectedQualifications.join(', ') : 'Not provided';

        document.getElementById('reviewDesig').textContent = document.getElementById('designation').value || 'Not provided';

        // Handle photo preview in review section
        const reviewPhoto = document.getElementById('reviewPhoto');
        if (previewImg.src) {
          reviewPhoto.innerHTML = `<img src="${previewImg.src}" class="preview-img">`;
        } else {
          reviewPhoto.innerHTML = '<p class="text-muted">No photo selected</p>';
        }
      }

      // Validate current step before proceeding
      function validateStep(step) {
        let isValid = true;
        const messageContainer = document.getElementById('messageContainer');
        messageContainer.innerHTML = '';

        if (step === '1') {
          const name = document.getElementById('name').value.trim();
          const email = document.getElementById('email').value.trim();
          const password = document.getElementById('password').value;

          if (name === '') {
            showMessage('Please enter your full name', 'danger');
            isValid = false;
          } else if (email === '') {
            showMessage('Please enter your email address', 'danger');
            isValid = false;
          } else if (!isValidEmail(email)) {
            showMessage('Please enter a valid email address', 'danger');
            isValid = false;
          } else if (password === '') {
            showMessage('Please enter a password', 'danger');
            isValid = false;
          } else if (password.length < 6) {
            showMessage('Password must be at least 6 characters long', 'danger');
            isValid = false;
          }
        }

        return isValid;
      }

      // Email validation
      function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
      }

      // Show message function
      function showMessage(message, type) {
        const messageContainer = document.getElementById('messageContainer');
        const alertClass = type === 'danger' ? 'alert-danger' : 'alert-success';
        messageContainer.innerHTML = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
          ${message}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
      }

      // Form submission validation
      document.getElementById('registrationForm').addEventListener('submit', function(e) {
        const agreeTerms = document.getElementById('agreeTerms');
        if (!agreeTerms.checked) {
          e.preventDefault();
          showMessage('Please agree to the Terms of Service', 'danger');
          // Scroll to terms checkbox
          agreeTerms.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
          });
          return false;
        }

        // Simulate successful registration
        e.preventDefault();
        showMessage('Registration submitted successfully! Your account is pending admin approval.', 'success');

        // Reset form after successful submission
        setTimeout(() => {
          document.getElementById('registrationForm').reset();
          // Go back to step 1
          steps.forEach(step => {
            if (step.getAttribute('data-step') === '1') {
              step.classList.add('active');
            } else {
              step.classList.remove('active');
            }
          });

          formSections.forEach(section => {
            if (section.id === 'step1') {
              section.classList.add('active');
            } else {
              section.classList.remove('active');
            }
          });

          // Reset preview image
          previewImg.src = '';
          previewImg.classList.add('d-none');
          uploadArea.querySelector('p').textContent = 'Click to upload photo';
        }, 2000);
      });

      // Department suggestions
      const departmentInput = document.getElementById('department');
      const departmentOptions = document.getElementById('departmentOptions').options;

      departmentInput.addEventListener('input', function() {
        const value = this.value.toLowerCase();
        if (value.length > 1) {
          // Filter and show matching options
          let matches = [];
          for (let i = 0; i < departmentOptions.length; i++) {
            if (departmentOptions[i].value.toLowerCase().includes(value)) {
              matches.push(departmentOptions[i].value);
            }
          }

          // Update datalist with filtered options
          const datalist = document.getElementById('departmentOptions');
          datalist.innerHTML = '';
          matches.forEach(match => {
            const option = document.createElement('option');
            option.value = match;
            datalist.appendChild(option);
          });
        }
      });
    });
  </script>
</body>

</html>