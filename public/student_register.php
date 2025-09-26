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
    $allowed_ext = ['jpg','jpeg','png','gif','pdf'];
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
            $name, $roll, $registration, $department, $semester, $session, $shift,
            $father_name, $mother_name, $father_phone, $mother_phone, $present_address, $permanent_address, $phone,
            $student_image_name, $reg_paper_name
        ]);
        $success = "Registration জমা হয়েছে — Admin পরবর্তী যাচাই করে Approve করবে।";
        // clear POST to avoid resubmit
        $_POST = [];
    }
}
?>
<!doctype html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <title>Student Registration</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .preview-img { max-width:120px; max-height:120px; object-fit:cover; border-radius:8px; }
  </style>
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="card shadow-sm">
    <div class="card-body">
      <h3 class="card-title mb-4">Student Registration</h3>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>";?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="alert alert-success"><?=htmlspecialchars($success)?></div>
      <?php endif; ?>

      <form method="post" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Name *</label>
            <input name="name" class="form-control" value="<?=htmlspecialchars($_POST['name'] ?? '')?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Roll *</label>
            <input name="roll" class="form-control" value="<?=htmlspecialchars($_POST['roll'] ?? '')?>" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">Registration</label>
            <input name="registration" class="form-control" value="<?=htmlspecialchars($_POST['registration'] ?? '')?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Department</label>
            <input name="department" class="form-control" value="<?=htmlspecialchars($_POST['department'] ?? '')?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Semester</label>
            <input name="semester" class="form-control" value="<?=htmlspecialchars($_POST['semester'] ?? '')?>">
          </div>

          <div class="col-md-3">
            <label class="form-label">Session</label>
            <input name="session" class="form-control" value="<?=htmlspecialchars($_POST['session'] ?? '')?>">
          </div>
          <div class="col-md-3">
            <label class="form-label">Shift</label>
            <input name="shift" class="form-control" value="<?=htmlspecialchars($_POST['shift'] ?? '')?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input name="phone" class="form-control" value="<?=htmlspecialchars($_POST['phone'] ?? '')?>">
          </div>

          <div class="col-md-6">
            <label class="form-label">Father Name</label>
            <input name="father_name" class="form-control" value="<?=htmlspecialchars($_POST['father_name'] ?? '')?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Mother Name</label>
            <input name="mother_name" class="form-control" value="<?=htmlspecialchars($_POST['mother_name'] ?? '')?>">
          </div>
          <div class="col-md-3">
            <label class="form-label">Father Phone</label>
            <input name="father_phone" class="form-control" value="<?=htmlspecialchars($_POST['father_phone'] ?? '')?>">
          </div>
          <div class="col-md-3">
            <label class="form-label">Mother Phone</label>
            <input name="mother_phone" class="form-control" value="<?=htmlspecialchars($_POST['mother_phone'] ?? '')?>">
          </div>

          <div class="col-12">
            <label class="form-label">Present Address</label>
            <textarea name="present_address" class="form-control"><?=htmlspecialchars($_POST['present_address'] ?? '')?></textarea>
          </div>
          <div class="col-12">
            <label class="form-label">Permanent Address</label>
            <textarea name="permanent_address" class="form-control"><?=htmlspecialchars($_POST['permanent_address'] ?? '')?></textarea>
          </div>

          <div class="col-md-6">
            <label class="form-label">Student Photo (jpg/png) - max 2MB</label>
            <input type="file" name="image" accept="image/*" class="form-control" id="stuImage">
            <div class="mt-2"><img id="stuPreview" class="preview-img" style="display:none"></div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Registration Paper (jpg/png/pdf) - max 5MB</label>
            <input type="file" name="reg_paper" accept="image/*,.pdf" class="form-control" id="regPaper">
            <div class="mt-2" id="regPreviewArea"></div>
          </div>

          <div class="col-12 text-end">
            <button class="btn btn-primary">Submit Registration</button>
            <a href="student-login.php" class="text-teal-600 hover:underline font-medium">Back to LoginPage</a>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>

<script>
  document.getElementById('stuImage').addEventListener('change', function(e){
    const f = e.target.files[0];
    const img = document.getElementById('stuPreview');
    if (!f) { img.style.display='none'; return; }
    const url = URL.createObjectURL(f);
    img.src = url; img.style.display='inline-block';
  });
  document.getElementById('regPaper').addEventListener('change', function(e){
    const f = e.target.files[0];
    const area = document.getElementById('regPreviewArea');
    area.innerHTML = '';
    if (!f) return;
    const ext = f.name.split('.').pop().toLowerCase();
    if (ext === 'pdf') {
      area.innerHTML = '<div class="border p-2">PDF selected: '+f.name+'</div>';
    } else {
      const img = document.createElement('img');
      img.className = 'preview-img';
      img.src = URL.createObjectURL(f);
      area.appendChild(img);
    }
  });
</script>
</body>
</html>

