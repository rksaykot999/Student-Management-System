<?php
// public/edit-teacher.php
include_once '../includes/db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Invalid request");
}

// fetch teacher
$stmt = $pdo->prepare("SELECT * FROM teachers WHERE id=?");
$stmt->execute([$id]);
$teacher = $stmt->fetch();
if (!$teacher) {
    die("Teacher not found!");
}

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $designation = trim($_POST['designation']);
    $department = trim($_POST['department']);
    $shift = trim($_POST['shift']);
    $qualification = trim($_POST['qualification']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // plaintext, consider hashing

    $image_name = $teacher['image'];

    // image upload
    if (!empty($_FILES['image']['name'])) {
        $filename = basename($_FILES['image']['name']);
        $filename = preg_replace("/[^A-Za-z0-9\.\-_]/", "", $filename);
        $image_name = time().'_'.$filename;

        $uploadDir = __DIR__ . '/../uploads/teachers/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir.$image_name);
    }

    // update teacher
    $stmt = $pdo->prepare("
        UPDATE teachers SET 
            name=?, designation=?, department=?, shift=?, qualification=?, phone=?, email=?, password=?, image=?
        WHERE id=?
    ");
    $stmt->execute([$name, $designation, $department, $shift, $qualification, $phone, $email, $password, $image_name, $id]);

    $success = true;

    // refresh teacher data
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE id=?");
    $stmt->execute([$id]);
    $teacher = $stmt->fetch();
}
?>

<!doctype html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <title>Edit Teacher</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h3 class="mb-3">Edit Teacher</h3>

  <?php if($success): ?>
    <script>alert("✅ Update সফল হয়েছে!");</script>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="card p-3 shadow-sm">
    <div class="row g-3">

      <div class="col-md-6">
        <label class="form-label">Current Image</label><br>
        <img src="<?=!empty($teacher['image']) ? '../uploads/teachers/'.$teacher['image'] : '../assets/images/default-teacher.png'?>" class="img-thumbnail mb-2" style="max-width:150px;">
        <input type="file" name="image" class="form-control">
      </div>

      <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="<?=htmlspecialchars($teacher['name'])?>" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Designation</label>
        <input type="text" name="designation" class="form-control" value="<?=htmlspecialchars($teacher['designation'])?>" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Department</label>
        <input type="text" name="department" class="form-control" value="<?=htmlspecialchars($teacher['department'])?>" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Shift</label>
        <input type="text" name="shift" class="form-control" value="<?=htmlspecialchars($teacher['shift'])?>" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Qualification</label>
        <input type="text" name="qualification" class="form-control" value="<?=htmlspecialchars($teacher['qualification'])?>" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="<?=htmlspecialchars($teacher['phone'])?>" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?=htmlspecialchars($teacher['email'])?>" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Password</label>
        <input type="text" name="password" class="form-control" value="<?=htmlspecialchars($teacher['password'])?>" required>
      </div>

    </div>

    <div class="mt-3">
      <button type="submit" class="btn btn-success">Update</button>
      <a href="view-teacher.php" class="btn btn-secondary">Back</a>
    </div>
  </form>
</div>
</body>
</html>
