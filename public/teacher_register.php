<?php
// public/teacher_register.php
include_once '../includes/db.php';
session_start();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $department = trim($_POST['department'] ?? '');
  $shift = trim($_POST['shift'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $qualification = trim($_POST['qualification'] ?? '');
  $designation = trim($_POST['designation'] ?? '');

  if ($name === '' || $email === '' || $password === '') {
    $errors[] = "Name, Email ও Password অবশ্যই দিতে হবে।";
  }

  // email duplicate check in main teachers & pending_teachers
  $chk = $pdo->prepare("SELECT COUNT(*) FROM teachers WHERE email = ?");
  $chk->execute([$email]);
  if ($chk->fetchColumn() > 0) $errors[] = "এই ইমেইল ইতোমধ্যে প্রধান teachers টেবিলে আছে।";

  $chk2 = $pdo->prepare("SELECT COUNT(*) FROM pending_teachers WHERE email = ?");
  $chk2->execute([$email]);
  if ($chk2->fetchColumn() > 0) $errors[] = "তুমি আগেই রেজিস্টার করেছো — সেটা pending আছে।";

  // handle image upload
  $teacher_image_name = null;
  if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $f = $_FILES['image'];
    if ($f['error'] !== UPLOAD_ERR_OK) $errors[] = "ছবি আপলোডে সমস্যা।";
    else {
      if ($f['size'] > 2 * 1024 * 1024) $errors[] = "ছবির সাইজ 2MB এর বেশি হতে পারবে না।";
      $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
      if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) $errors[] = "ছবির অনুমোদিত ফরম্যাট: jpg,jpeg,png,gif।";

      if (empty($errors)) {
        $teacher_image_name = 'tch_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

        // Save path: public/uploads/teachers/
        $upload_dir = __DIR__ . '/../uploads/teachers/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $dest = $upload_dir . $teacher_image_name;
        if (!move_uploaded_file($f['tmp_name'], $dest)) $errors[] = "ছবি সার্ভারে সেভ করা যায়নি।";
      }
    }
  }

  if (empty($errors)) {
    // password hash বন্ধ
    $ins = $pdo->prepare("INSERT INTO pending_teachers (name, email, password, department, shift, phone, qualification, designation, image)
                              VALUES (?,?,?,?,?,?,?,?,?)");
    $ins->execute([$name, $email, $password, $department, $shift, $phone, $qualification, $designation, $teacher_image_name]);
    $success = "Registration জমা হয়েছে — Admin যাচাই করে Approve করবে।";
    $_POST = [];
  }
}
?>
<!doctype html>
<html lang="bn">

<head>
  <meta charset="utf-8">
  <title>Teacher Registration</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f0f4f8;
    }

    .preview-img {
      max-width: 120px;
      max-height: 120px;
      object-fit: cover;
      border-radius: 8px;
    }
  </style>
</head>

<body class="bg-light">
  <div class="container py-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h3 class="card-title mb-4">Teacher Registration</h3>

        <?php if (!empty($errors)): ?>
          <div class="alert alert-danger">
            <ul class="mb-0"><?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?></ul>
          </div>
        <?php endif; ?>
        <?php if ($success): ?>
          <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Name *</label>
              <input name="name" class="form-control" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email *</label>
              <input name="email" type="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Password *</label>
              <input name="password" type="text" class="form-control" value="<?= htmlspecialchars($_POST['password'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Department</label>
              <input name="department" class="form-control" value="<?= htmlspecialchars($_POST['department'] ?? '') ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Shift</label>
              <input name="shift" class="form-control" value="<?= htmlspecialchars($_POST['shift'] ?? '') ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Phone</label>
              <input name="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Qualification</label>
              <input name="qualification" class="form-control" value="<?= htmlspecialchars($_POST['qualification'] ?? '') ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Designation</label>
              <input name="designation" class="form-control" value="<?= htmlspecialchars($_POST['designation'] ?? '') ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Photo (jpg/png) - max 2MB</label>
              <input type="file" name="image" accept="image/*" class="form-control" id="tchImage">
              <div class="mt-2">
                <img id="tchPreview" class="preview-img" style="display:none">
              </div>
            </div>

            <div class="col-12 text-end">
              <button class="btn btn-primary">Submit Registration</button>
              <a href="teacher-login.php" class="text-teal-600 hover:underline font-medium">Back to LoginPage</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('tchImage').addEventListener('change', function(e) {
      const f = e.target.files[0];
      const img = document.getElementById('tchPreview');
      if (!f) {
        img.style.display = 'none';
        return;
      }
      img.src = URL.createObjectURL(f);
      img.style.display = 'inline-block';
    });
  </script>
</body>

</html>