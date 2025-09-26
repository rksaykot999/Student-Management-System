<?php
// public/student_register.php
include_once '../includes/db.php';
session_start();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // sanitize inputs
  $name = trim($_POST['name'] ?? '');
  $roll = trim($_POST['roll'] ?? '');
  $registration = trim($_POST['registration'] ?? '');
  $department = trim($_POST['department'] ?? '');
  $semester = trim($_POST['semester'] ?? '');
  $session = trim($_POST['session'] ?? '');
  $shift = trim($_POST['shift'] ?? '');
  $father_name = trim($_POST['father_name'] ?? '');
  $mother_name = trim($_POST['mother_name'] ?? '');
  $father_phone = trim($_POST['father_phone'] ?? '');
  $mother_phone = trim($_POST['mother_phone'] ?? '');
  $present_address = trim($_POST['present_address'] ?? '');
  $permanent_address = trim($_POST['permanent_address'] ?? '');
  $phone = trim($_POST['phone'] ?? '');

  // basic validation
  if ($name === '' || $roll === '') {
    $errors[] = "Name ও Roll অবশ্যই দিতে হবে।";
  }

  // duplicate check in main students & pending_students (by roll or registration)
  $chk = $pdo->prepare("SELECT COUNT(*) FROM students WHERE roll = ? OR registration = ?");
  $chk->execute([$roll, $registration]);
  if ($chk->fetchColumn() > 0) {
    $errors[] = "এই রোল/রেজিস্ট্রেশন নম্বর ইতোমধ্যে students টেবিলে আছে।";
  }

  $chk2 = $pdo->prepare("SELECT COUNT(*) FROM pending_students WHERE roll = ? OR registration = ?");
  $chk2->execute([$roll, $registration]);
  if ($chk2->fetchColumn() > 0) {
    $errors[] = "তুমি আগেই রজিস্টার করেছো — সেটা pending আছে।";
  }

  // handle image uploads
  $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
  $max_size = 2 * 1024 * 1024; // 2MB

  // student photo
  $student_image_name = null;
  if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $f = $_FILES['image'];
    if ($f['error'] !== UPLOAD_ERR_OK) $errors[] = "ছবির আপলোডে সমস্যা।";
    else {
      if ($f['size'] > $max_size) $errors[] = "ছবির সাইজ 2MB এর বেশি হতে পারবে না।";
      $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
      if (!in_array($ext, $allowed_ext)) $errors[] = "ছবির অনুমোদিত ফরম্যাট: jpg, jpeg, png, gif।";
      if (empty($errors)) {
        $student_image_name = 'stu_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = __DIR__ . '/../uploads/students/' . $student_image_name;
        if (!move_uploaded_file($f['tmp_name'], $dest)) $errors[] = "ছবি সার্ভারে সেভ করা যায়নি।";
      }
    }
  }

  // registration paper image (pdf/png/jpg allowed)
  $reg_paper_name = null;
  if (!empty($_FILES['reg_paper']) && $_FILES['reg_paper']['error'] !== UPLOAD_ERR_NO_FILE) {
    $f = $_FILES['reg_paper'];
    if ($f['error'] !== UPLOAD_ERR_OK) $errors[] = "Registration paper আপলোডে সমস্যা।";
    else {
      if ($f['size'] > 5 * 1024 * 1024) $errors[] = "Registration paper সাইজ 5MB এর বেশি হতে পারবে না।";
      $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
      if (!in_array($ext, $allowed_ext)) $errors[] = "Registration paper ফর্ম্যাট: jpg,jpeg,png,pdf।";
      if (empty($errors)) {
        $reg_paper_name = 'rpaper_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = __DIR__ . '/../uploads/students/' . $reg_paper_name;
        if (!move_uploaded_file($f['tmp_name'], $dest)) $errors[] = "Registration paper সার্ভারে সেভ করা যায়নি।";
      }
    }
  }

  if (empty($errors)) {
    $ins = $pdo->prepare("INSERT INTO pending_students
          (name, roll, registration, department, semester, session, shift, father_name, mother_name, father_phone, mother_phone, present_address, permanent_address, phone, image, reg_paper_image)
          VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $ins->execute([
      $name,
      $roll,
      $registration,
      $department,
      $semester,
      $session,
      $shift,
      $father_name,
      $mother_name,
      $father_phone,
      $mother_phone,
      $present_address,
      $permanent_address,
      $phone,
      $student_image_name,
      $reg_paper_name
    ]);
    $success = "Registration জমা হয়েছে — Admin পরবর্তী যাচাই করে Approve করবে।";
    // clear POST to avoid resubmit
    $_POST = [];
  }
}
?>

<!DOCTYPE html>
<html lang="bn">

<head>
  <meta charset="utf-8">
  <title>Student Registration - Barishal Polytechnic Institute</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #4361ee;
      --primary-dark: #3a56d4;
      --secondary: #7209b7;
      --accent: #06d6a0;
      --light: #f8f9fa;
      --dark: #212529;
    }

    main {
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      padding: 20px 0;
    }

    .registration-container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .registration-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
      overflow: hidden;
    }

    .registration-header {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      color: white;
      padding: 30px;
      text-align: center;
    }

    .registration-body {
      padding: 30px;
    }

    .form-section {
      background: white;
      border-radius: 15px;
      padding: 25px;
      margin-bottom: 25px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      border-left: 4px solid var(--primary);
    }

    .section-title {
      color: var(--primary);
      font-weight: 600;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      font-size: 1.2rem;
    }

    .section-title i {
      margin-right: 10px;
      font-size: 1.4rem;
    }

    .form-label {
      font-weight: 500;
      color: #495057;
      margin-bottom: 8px;
    }

    .form-control,
    .form-select {
      border-radius: 10px;
      padding: 12px 15px;
      border: 1px solid #e2e8f0;
      transition: all 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .preview-container {
      border: 2px dashed #dee2e6;
      border-radius: 10px;
      padding: 15px;
      text-align: center;
      margin-top: 10px;
      transition: all 0.3s;
      background: #f8f9fa;
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

    .file-input-label {
      display: block;
      background: var(--light);
      border: 2px dashed #ced4da;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s;
      margin-bottom: 10px;
    }

    .file-input-label:hover {
      border-color: var(--primary);
      background: #f0f2f5;
    }

    .file-input-label i {
      font-size: 2rem;
      color: var(--primary);
      margin-bottom: 10px;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      border: none;
      border-radius: 10px;
      padding: 12px 30px;
      font-weight: 600;
      transition: all 0.3s;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 7px 14px rgba(67, 97, 238, 0.3);
    }

    .alert {
      border-radius: 10px;
      border: none;
      padding: 15px 20px;
    }

    .progress-bar {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
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
      top: 20px;
      left: 0;
      right: 0;
      height: 3px;
      background: #e9ecef;
      z-index: 1;
    }

    .step {
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;
      z-index: 2;
    }

    .step-number {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #e9ecef;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      margin-bottom: 10px;
      transition: all 0.3s;
    }

    .step.active .step-number {
      background: var(--primary);
      color: white;
    }

    .step.completed .step-number {
      background: var(--accent);
      color: white;
    }

    .step-label {
      font-size: 0.9rem;
      font-weight: 500;
      color: #6c757d;
    }

    .step.active .step-label {
      color: var(--primary);
    }

    .form-section {
      display: none;
    }

    .form-section.active {
      display: block;
      animation: fadeIn 0.5s ease;
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

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .form-navigation {
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
    }

    @media (max-width: 768px) {
      .registration-body {
        padding: 20px;
      }

      .form-section {
        padding: 20px;
      }

      .step-label {
        font-size: 0.8rem;
      }
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <?php include_once '../includes/header.php'; ?>
  <main>
    <div class="container registration-container">
      <div class="registration-card">
        <div class="registration-header">
          <h1><i class="fas fa-user-graduate me-2"></i>Student Registration</h1>
          <p class="mb-0">Barishal Polytechnic Institute</p>
        </div>

        <div class="registration-body">
          <!-- Step Indicator -->
          <div class="step-indicator">
            <div class="step active" id="step1">
              <div class="step-number">1</div>
              <div class="step-label">Personal Info</div>
            </div>
            <div class="step" id="step2">
              <div class="step-number">2</div>
              <div class="step-label">Academic Info</div>
            </div>
            <div class="step" id="step3">
              <div class="step-number">3</div>
              <div class="step-label">Family Info</div>
            </div>
            <div class="step" id="step4">
              <div class="step-number">4</div>
              <div class="step-label">Documents</div>
            </div>
          </div>

          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
              <h5><i class="fas fa-exclamation-triangle me-2"></i>Registration Errors</h5>
              <ul class="mb-0 mt-2">
                <?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?>
              </ul>
            </div>
          <?php endif; ?>

          <?php if ($success): ?>
            <div class="alert alert-success">
              <h5><i class="fas fa-check-circle me-2"></i>Registration Successful</h5>
              <p class="mb-0 mt-2"><?= htmlspecialchars($success) ?></p>
            </div>
          <?php endif; ?>

          <form method="post" enctype="multipart/form-data" id="registrationForm">
            <!-- Step 1: Personal Information -->
            <div class="form-section active" id="section1">
              <div class="section-title">
                <i class="fas fa-user"></i> Personal Information
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Full Name *</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Roll Number *</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                    <input type="text" name="roll" class="form-control" value="<?= htmlspecialchars($_POST['roll'] ?? '') ?>" required>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Phone Number</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Registration Number</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                    <input type="text" name="registration" class="form-control" value="<?= htmlspecialchars($_POST['registration'] ?? '') ?>">
                  </div>
                </div>
              </div>

              <div class="form-navigation">
                <button type="button" class="btn btn-outline-primary" disabled>Previous</button>
                <button type="button" class="btn btn-primary next-btn" data-next="2">Next <i class="fas fa-arrow-right ms-2"></i></button>
              </div>
            </div>

            <!-- Step 2: Academic Information -->
            <div class="form-section" id="section2">
              <div class="section-title">
                <i class="fas fa-graduation-cap"></i> Academic Information
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Department</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                    <select name="department" class="form-select">
                      <option value="">Select Department</option>
                      <option value="Computer Technology" <?= (($_POST['department'] ?? '') == 'Computer Technology') ? 'selected' : '' ?>>Computer Technology</option>
                      <option value="Civil Technology" <?= (($_POST['department'] ?? '') == 'Civil Technology') ? 'selected' : '' ?>>Civil Technology</option>
                      <option value="Electrical Technology" <?= (($_POST['department'] ?? '') == 'Electrical Technology') ? 'selected' : '' ?>>Electrical Technology</option>
                      <option value="Mechanical Technology" <?= (($_POST['department'] ?? '') == 'Mechanical Technology') ? 'selected' : '' ?>>Mechanical Technology</option>
                      <option value="Electronics Technology" <?= (($_POST['department'] ?? '') == 'Electronics Technology') ? 'selected' : '' ?>>Electronics Technology</option>
                      <option value="Power Technology" <?= (($_POST['department'] ?? '') == 'Power Technology') ? 'selected' : '' ?>>Power Technology</option>
                    </select>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Semester</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    <select name="semester" class="form-select">
                      <option value="">Select Semester</option>
                      <?php for ($i = 1; $i <= 8; $i++): ?>
                        <option value="Semester <?= $i ?>" <?= (($_POST['semester'] ?? '') == "Semester $i") ? 'selected' : '' ?>>Semester <?= $i ?></option>
                      <?php endfor; ?>
                    </select>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Session</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                    <input type="text" name="session" class="form-control" placeholder="e.g., 2022-2023" value="<?= htmlspecialchars($_POST['session'] ?? '') ?>">
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Shift</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-sun"></i></span>
                    <select name="shift" class="form-select">
                      <option value="">Select Shift</option>
                      <option value="Morning" <?= (($_POST['shift'] ?? '') == 'Morning') ? 'selected' : '' ?>>Morning</option>
                      <option value="Day" <?= (($_POST['shift'] ?? '') == 'Day') ? 'selected' : '' ?>>Day</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-navigation">
                <button type="button" class="btn btn-outline-primary prev-btn" data-prev="1"><i class="fas fa-arrow-left me-2"></i> Previous</button>
                <button type="button" class="btn btn-primary next-btn" data-next="3">Next <i class="fas fa-arrow-right ms-2"></i></button>
              </div>
            </div>

            <!-- Step 3: Family Information -->
            <div class="form-section" id="section3">
              <div class="section-title">
                <i class="fas fa-users"></i> Family Information
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Father's Name</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-male"></i></span>
                    <input type="text" name="father_name" class="form-control" value="<?= htmlspecialchars($_POST['father_name'] ?? '') ?>">
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Mother's Name</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-female"></i></span>
                    <input type="text" name="mother_name" class="form-control" value="<?= htmlspecialchars($_POST['mother_name'] ?? '') ?>">
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Father's Phone</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="tel" name="father_phone" class="form-control" value="<?= htmlspecialchars($_POST['father_phone'] ?? '') ?>">
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Mother's Phone</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="tel" name="mother_phone" class="form-control" value="<?= htmlspecialchars($_POST['mother_phone'] ?? '') ?>">
                  </div>
                </div>

                <div class="col-12">
                  <label class="form-label">Present Address</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                    <textarea name="present_address" class="form-control" rows="3"><?= htmlspecialchars($_POST['present_address'] ?? '') ?></textarea>
                  </div>
                </div>

                <div class="col-12">
                  <label class="form-label">Permanent Address</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    <textarea name="permanent_address" class="form-control" rows="3"><?= htmlspecialchars($_POST['permanent_address'] ?? '') ?></textarea>
                  </div>
                </div>
              </div>

              <div class="form-navigation">
                <button type="button" class="btn btn-outline-primary prev-btn" data-prev="2"><i class="fas fa-arrow-left me-2"></i> Previous</button>
                <button type="button" class="btn btn-primary next-btn" data-next="4">Next <i class="fas fa-arrow-right ms-2"></i></button>
              </div>
            </div>

            <!-- Step 4: Documents Upload -->
            <div class="form-section" id="section4">
              <div class="section-title">
                <i class="fas fa-file-upload"></i> Documents Upload
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Student Photo (jpg/png) - max 2MB</label>
                  <label for="stuImage" class="file-input-label">
                    <i class="fas fa-camera"></i>
                    <div>Click to upload photo</div>
                    <small class="text-muted">Max size: 2MB</small>
                  </label>
                  <input type="file" name="image" accept="image/*" class="form-control d-none" id="stuImage">
                  <div class="preview-container">
                    <img id="stuPreview" class="preview-img" style="display:none">
                    <div id="stuPreviewText" class="text-muted">No photo selected</div>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Registration Paper (jpg/png/pdf) - max 5MB</label>
                  <label for="regPaper" class="file-input-label">
                    <i class="fas fa-file-pdf"></i>
                    <div>Click to upload document</div>
                    <small class="text-muted">Max size: 5MB</small>
                  </label>
                  <input type="file" name="reg_paper" accept="image/*,.pdf" class="form-control d-none" id="regPaper">
                  <div class="preview-container">
                    <div id="regPreviewArea"></div>
                    <div id="regPreviewText" class="text-muted">No document selected</div>
                  </div>
                </div>
              </div>

              <div class="form-navigation">
                <button type="button" class="btn btn-outline-primary prev-btn" data-prev="3"><i class="fas fa-arrow-left me-2"></i> Previous</button>
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-paper-plane me-2"></i> Submit Registration
                </button>
              </div>
            </div>
          </form>

          <div class="login-link">
            Already have an account? <a href="student-login.php">Login here</a>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <?php include_once '../includes/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Multi-step form functionality
    document.addEventListener('DOMContentLoaded', function() {
      const steps = document.querySelectorAll('.step');
      const sections = document.querySelectorAll('.form-section');
      const nextButtons = document.querySelectorAll('.next-btn');
      const prevButtons = document.querySelectorAll('.prev-btn');

      // Initialize first step as active
      let currentStep = 1;

      // Next button functionality
      nextButtons.forEach(button => {
        button.addEventListener('click', function() {
          const nextStep = parseInt(this.getAttribute('data-next'));

          // Validate current step before proceeding
          if (validateStep(currentStep)) {
            changeStep(nextStep);
          }
        });
      });

      // Previous button functionality
      prevButtons.forEach(button => {
        button.addEventListener('click', function() {
          const prevStep = parseInt(this.getAttribute('data-prev'));
          changeStep(prevStep);
        });
      });

      // Function to change step
      function changeStep(step) {
        // Hide all sections
        sections.forEach(section => {
          section.classList.remove('active');
        });

        // Show current section
        document.getElementById(`section${step}`).classList.add('active');

        // Update step indicators
        steps.forEach((s, index) => {
          s.classList.remove('active', 'completed');
          if (index + 1 < step) {
            s.classList.add('completed');
          } else if (index + 1 === step) {
            s.classList.add('active');
          }
        });

        currentStep = step;

        // Scroll to top of form
        document.querySelector('.registration-body').scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }

      // Function to validate current step
      function validateStep(step) {
        let isValid = true;

        if (step === 1) {
          const name = document.querySelector('input[name="name"]').value.trim();
          const roll = document.querySelector('input[name="roll"]').value.trim();

          if (!name) {
            showFieldError('input[name="name"]', 'Name is required');
            isValid = false;
          }

          if (!roll) {
            showFieldError('input[name="roll"]', 'Roll number is required');
            isValid = false;
          }
        }

        return isValid;
      }

      // Function to show field error
      function showFieldError(selector, message) {
        const field = document.querySelector(selector);
        field.classList.add('is-invalid');

        // Remove existing error message if any
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
          existingError.remove();
        }

        // Add error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);

        // Scroll to error field
        field.scrollIntoView({
          behavior: 'smooth',
          block: 'center'
        });
      }

      // Remove error styling when user starts typing
      document.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('input', function() {
          this.classList.remove('is-invalid');
          const errorMsg = this.parentNode.querySelector('.invalid-feedback');
          if (errorMsg) {
            errorMsg.remove();
          }
        });
      });

      // File preview functionality
      document.getElementById('stuImage').addEventListener('change', function(e) {
        const f = e.target.files[0];
        const img = document.getElementById('stuPreview');
        const text = document.getElementById('stuPreviewText');

        if (!f) {
          img.style.display = 'none';
          text.style.display = 'block';
          return;
        }

        const url = URL.createObjectURL(f);
        img.src = url;
        img.style.display = 'inline-block';
        text.style.display = 'none';
      });

      document.getElementById('regPaper').addEventListener('change', function(e) {
        const f = e.target.files[0];
        const area = document.getElementById('regPreviewArea');
        const text = document.getElementById('regPreviewText');

        area.innerHTML = '';
        if (!f) {
          text.style.display = 'block';
          return;
        }

        text.style.display = 'none';
        const ext = f.name.split('.').pop().toLowerCase();

        if (ext === 'pdf') {
          area.innerHTML = `
            <div class="d-flex align-items-center justify-content-center p-3 border rounded bg-light">
              <i class="fas fa-file-pdf text-danger me-3" style="font-size: 2rem;"></i>
              <div>
                <div class="fw-bold">${f.name}</div>
                <small class="text-muted">PDF Document</small>
              </div>
            </div>
          `;
        } else {
          const img = document.createElement('img');
          img.className = 'preview-img';
          img.src = URL.createObjectURL(f);
          area.appendChild(img);
        }
      });

      // Click on file input labels
      document.querySelectorAll('.file-input-label').forEach(label => {
        label.addEventListener('click', function() {
          const inputId = this.getAttribute('for');
          document.getElementById(inputId).click();
        });
      });
    });
  </script>
</body>

</html>