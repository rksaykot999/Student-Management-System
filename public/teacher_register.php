<?php
// public/teacher_register.php
include_once '../includes/db.php';
session_start();

$errors = [];
$success = '';

// make PDO throw exceptions (helps debugging)
if (isset($pdo)) {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanitize inputs
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $shift = trim($_POST['shift'] ?? '');
    // read designation from form (you had this field in the HTML)
    $designation = trim($_POST['designation'] ?? '');
    // qualification may be multiple-select; normalize to comma string
    if (isset($_POST['qualification']) && is_array($_POST['qualification'])) {
        $qualification = implode(',', array_map('trim', $_POST['qualification']));
    } else {
        $qualification = trim($_POST['qualification'] ?? '');
    }

    // basic validation
    if ($name === '' || $email === '' || $password === '') {
        $errors[] = "Name, Email এবং Password অবশ্যই দিতে হবে।";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "সঠিক ইমেইল ঠিকানা দিন।";
        }
    }

    // duplicate checks (optional)
    if (empty($errors)) {
        try {
            $chk = $pdo->prepare("SELECT COUNT(*) FROM teachers WHERE email = ?");
            $chk->execute([$email]);
            if ($chk->fetchColumn() > 0) {
                $errors[] = "এই ইমেইল already registered as teacher।";
            }

            $chk2 = $pdo->prepare("SELECT COUNT(*) FROM pending_teachers WHERE email = ?");
            $chk2->execute([$email]);
            if ($chk2->fetchColumn() > 0) {
                $errors[] = "তুমি আগেই রেজিস্টার করেছো — সেটা pending আছে।";
            }
        } catch (PDOException $e) {
            $errors[] = "Database check error: " . $e->getMessage();
        }
    }

    // image upload (same as before)
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
    $max_size = 2 * 1024 * 1024; // 2MB
    $image_name = null;

    if (empty($errors) && !empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $f = $_FILES['image'];
        if ($f['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "ছবির আপলোডে সমস্যা।";
        } else {
            if ($f['size'] > $max_size) $errors[] = "ছবির সাইজ 2MB এর বেশি হতে পারবে না।";
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed_ext)) $errors[] = "ছবির অনুমোদিত ফরম্যাট: jpg, jpeg, png, gif।";

            if (empty($errors)) {
                $image_name = 'tch_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $dest_dir = __DIR__ . '/../uploads/teachers/';
                if (!is_dir($dest_dir)) {
                    if (!mkdir($dest_dir, 0777, true)) {
                        $errors[] = "ইমেজ আপলোড ডিরেক্টরি তৈরি করা যায়নি।";
                    }
                }
                if (empty($errors)) {
                    $dest = $dest_dir . $image_name;
                    if (!move_uploaded_file($f['tmp_name'], $dest)) {
                        $errors[] = "ছবি সার্ভারে সেভ করা যায়নি।";
                    }
                }
            }
        }
    }

    // Insert into pending_teachers (now includes designation)
    if (empty($errors)) {
        try {
            $ins = $pdo->prepare("INSERT INTO pending_teachers
                (name, email, password, department, shift, phone, qualification, designation, image, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

            $ok = $ins->execute([
                $name,
                $email,
                $password,      // তুমি চাওলে এখানে password_hash() ব্যবহার করো
                $department,
                $shift,
                $phone,
                $qualification,
                $designation,
                $image_name
            ]);

            if ($ok) {
                $success = "Registration জমা হয়েছে — Admin পরবর্তী যাচাই করে Approve করবে।";
                $_POST = [];
            } else {
                $info = $ins->errorInfo();
                $errors[] = "Insert failed: " . ($info[2] ?? 'Unknown error');
            }
        } catch (PDOException $e) {
            $errors[] = "Database Error: " . $e->getMessage();
        }
    }
}
?>




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
                    <option value="CT">
                    <option value="ET">
                    <option value="CST">
                    <option value="MT">
                    <option value="ENT">
                    <option value="PT">
                    <option value="EMT">
                    <option value="THT">
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
                    <option value="Principle">Principle</option>
                    <option value="Vice Principle">Vice Principle</option>
                    <option value="Chief Instructor & Head of the Department">Chief Instructor & Head of the Department</option>
                    <option value="Instructor">Instructor</option>
                    <option value="Workhop Super">Workhop Super</option>
                    <option value="Junior Instructor">Junior Instructor</option>
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
document.addEventListener('DOMContentLoaded', function() {
  // short helper
  const $ = id => document.getElementById(id);

  // Top UI (login/mobile) guards
  const loginButton = $('logoButton');
  const loginDropdown = $('loginDropdown');
  const mobileMenuButton = $('menuButton');
  const mobileMenu = $('mobileMenu');
  const mobileLoginButton = $('mobileLoginButton');

  if (loginButton && loginDropdown) {
    loginButton.addEventListener('click', (e) => {
      loginDropdown.classList.toggle('show');
      e.stopPropagation();
    });
    window.addEventListener('click', (e) => {
      if (!loginDropdown.contains(e.target) && !loginButton.contains(e.target)) {
        loginDropdown.classList.remove('show');
      }
    });
  }
  if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener('click', () => mobileMenu.classList.toggle('show'));
    mobileMenu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => mobileMenu.classList.remove('show')));
  }
  if (mobileLoginButton && loginDropdown) {
    mobileLoginButton.addEventListener('click', (e) => { e.preventDefault(); loginDropdown.classList.toggle('show'); });
  }

  // Multi-step form elements (teacher HTML uses 'next-step' / 'prev-step' / 'step' / 'form-section' ids)
  const steps = document.querySelectorAll('.step');
  const sections = document.querySelectorAll('.form-section');
  const nextButtons = document.querySelectorAll('.next-step');
  const prevButtons = document.querySelectorAll('.prev-step');

  // initialize: show first section if none active
  if (sections.length) {
    let anyActive = Array.from(sections).some(s => s.classList.contains('active'));
    if (!anyActive) sections[0].classList.add('active');
  }
  if (steps.length) {
    let anyActiveStep = Array.from(steps).some(s => s.classList.contains('active'));
    if (!anyActiveStep) steps[0].classList.add('active');
  }

  // Validate step (only step 1 required here)
  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function showMessage(msg, type='danger') {
    const container = $('messageContainer');
    if (!container) return;
    const cls = (type === 'danger') ? 'alert-danger' : 'alert-success';
    container.innerHTML = `<div class="alert ${cls} alert-dismissible fade show" role="alert">
      ${msg}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`;
  }

  function validateStep(step) {
    // step can be string/number
    const s = String(step);
    if (s === '1') {
      const name = $('name') ? $('name').value.trim() : '';
      const email = $('email') ? $('email').value.trim() : '';
      const password = $('password') ? $('password').value : '';
      if (!name) { showMessage('Please enter your full name','danger'); return false; }
      if (!email) { showMessage('Please enter your email address','danger'); return false; }
      if (!isValidEmail(email)) { showMessage('Please enter a valid email address','danger'); return false; }
      if (!password) { showMessage('Please enter a password','danger'); return false; }
      if (password.length < 6) { showMessage('Password must be at least 6 characters','danger'); return false; }
    }
    // other steps can have additional checks if needed
    return true;
  }

  // Change step helper
  function goToStep(targetStep) {
    // hide all sections, show matching id 'stepX'
    sections.forEach(sec => sec.classList.toggle('active', sec.id === 'step' + targetStep));
    steps.forEach(st => {
      const ds = st.getAttribute('data-step');
      st.classList.toggle('active', ds === String(targetStep));
      st.classList.toggle('completed', Number(ds) < Number(targetStep));
    });
    // scroll to form top
    const body = document.querySelector('.card-body') || document.querySelector('.registration-body') || document.body;
    if (body) body.scrollIntoView({behavior:'smooth', block:'start'});
  }

  // Next buttons
  nextButtons.forEach(btn => {
    btn.addEventListener('click', function(e) {
      const next = this.getAttribute('data-next');
      // find current active step (from .step.active)
      const currentStepEl = document.querySelector('.step.active');
      const current = currentStepEl ? currentStepEl.getAttribute('data-step') : '1';
      if (validateStep(current)) {
        // if next is '3' (review), update review fields first
        if (String(next) === '3') updateReviewSection();
        goToStep(next);
      }
    });
  });

  // Prev buttons
  prevButtons.forEach(btn => {
    btn.addEventListener('click', function() {
      const prev = this.getAttribute('data-prev');
      goToStep(prev);
    });
  });

  // Image upload preview
  const uploadArea = $('uploadArea');
  const imageInput = $('tchImage');
  const previewImg = $('tchPreview');
  if (uploadArea && imageInput) {
    uploadArea.addEventListener('click', () => imageInput.click());
  }
  if (imageInput) {
    imageInput.addEventListener('change', function(e){
      const f = e.target.files && e.target.files[0];
      if (!f) return;
      const reader = new FileReader();
      reader.onload = function(ev){
        if (previewImg) {
          previewImg.src = ev.target.result;
          previewImg.classList.remove('d-none');
        }
        const p = uploadArea ? uploadArea.querySelector('p') : null;
        if (p) p.textContent = 'Click to change photo';
      };
      reader.readAsDataURL(f);
    });
  }

  // Password strength & toggle
  const passwordInput = $('password');
  const strengthBar = $('passwordStrength');
  const strengthText = $('passwordText');
  const togglePassword = $('togglePassword');

  if (passwordInput && strengthBar && strengthText) {
    passwordInput.addEventListener('input', function(){
      const pwd = this.value || '';
      let score = 0;
      if (pwd.length >= 8) score++;
      if (/[a-z]/.test(pwd) && /[A-Z]/.test(pwd)) score++;
      if (/\d/.test(pwd)) score++;
      if (/[^a-zA-Z\d]/.test(pwd)) score++;
      strengthBar.className = 'password-strength';
      if (pwd.length === 0) { strengthText.textContent = 'Not set'; }
      else if (score <= 1) { strengthBar.classList.add('strength-weak'); strengthText.textContent = 'Weak'; }
      else if (score === 2) { strengthBar.classList.add('strength-fair'); strengthText.textContent = 'Fair'; }
      else if (score === 3) { strengthBar.classList.add('strength-good'); strengthText.textContent = 'Good'; }
      else { strengthBar.classList.add('strength-strong'); strengthText.textContent = 'Strong'; }
    });
  }
  if (togglePassword && passwordInput) {
    togglePassword.addEventListener('click', function(){
      const t = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', t);
      this.innerHTML = t === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
  }

  // Update review section (step 3)
  function updateReviewSection() {
    const reviewName = $('reviewName'), reviewEmail = $('reviewEmail'), reviewPhone = $('reviewPhone');
    const reviewDept = $('reviewDept'), reviewShift = $('reviewShift'), reviewQual = $('reviewQual'), reviewDesig = $('reviewDesig'), reviewPhoto = $('reviewPhoto');

    if (reviewName && $('name')) reviewName.textContent = $('name').value || '';
    if (reviewEmail && $('email')) reviewEmail.textContent = $('email').value || '';
    if (reviewPhone && $('phone')) reviewPhone.textContent = $('phone').value || 'Not provided';
    if (reviewDept && $('department')) reviewDept.textContent = $('department').value || 'Not provided';
    if (reviewShift && $('shift')) reviewShift.textContent = $('shift').value || 'Not provided';
    if (reviewQual && $('qualification')) {
      const sel = Array.from($('qualification').selectedOptions || []).map(o => o.value);
      reviewQual.textContent = sel.length ? sel.join(', ') : 'Not provided';
    }
    if (reviewDesig && $('designation')) reviewDesig.textContent = $('designation').value || 'Not provided';
    if (reviewPhoto) {
      if (previewImg && previewImg.src) reviewPhoto.innerHTML = `<img src="${previewImg.src}" class="preview-img">`;
      else reviewPhoto.innerHTML = '<p class="text-muted">No photo selected</p>';
    }
  }
  window.updateReviewSection = updateReviewSection; // expose if needed

  // Department datalist filter (optional)
  const departmentInput = $('department');
  const departmentOptionsEl = $('departmentOptions');
  if (departmentInput && departmentOptionsEl) {
    departmentInput.addEventListener('input', function() {
      const v = this.value.toLowerCase();
      const all = ['Computer Science','Mathematics','Physics','Chemistry','Biology','English','History','Economics'];
      if (v.length > 1) {
        departmentOptionsEl.innerHTML = '';
        all.filter(x => x.toLowerCase().includes(v)).forEach(x => {
          const opt = document.createElement('option'); opt.value = x; departmentOptionsEl.appendChild(opt);
        });
      }
    });
  }

  // Final submit handler: only block if terms not agreed; otherwise allow normal POST
  const form = $('registrationForm');
  if (form) {
    form.addEventListener('submit', function(e){
      const agree = $('agreeTerms');
      if (agree && !agree.checked) {
        e.preventDefault();
        showMessage('Please agree to the Terms of Service', 'danger');
        agree.scrollIntoView({behavior:'smooth', block:'center'});
        return false;
      }
      // allow default submit to server (don't prevent)
      const submitBtn = $('submitBtn');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
      }
    });
  }

  // remove 'is-invalid' on input
  document.querySelectorAll('input, select, textarea').forEach(f => {
    f.addEventListener('input', function(){
      this.classList.remove('is-invalid');
      const err = this.parentNode ? this.parentNode.querySelector('.invalid-feedback') : null;
      if (err) err.remove();
    });
  });

}); // DOMContentLoaded end
</script>

</body>

</html>