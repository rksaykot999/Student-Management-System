<?php
include_once '../includes/db.php';
session_start();

// Admin login check
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

$error = "";
$success = "";

// Form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data safely
    $name = trim($_POST['name']);
    $roll = trim($_POST['roll']);
    $registration = trim($_POST['registration']);
    $department = trim($_POST['department']);
    $semester = trim($_POST['semester']);
    $sessionName = trim($_POST['session']); 
    $shift = trim($_POST['shift']);
    $father_name = trim($_POST['father_name']);
    $mother_name = trim($_POST['mother_name']);
    $father_phone = trim($_POST['father_phone']);
    $mother_phone = trim($_POST['mother_phone']);
    $present_address = trim($_POST['present_address']);
    $permanent_address = trim($_POST['permanent_address']);
    $phone = trim($_POST['phone']);
    $result = trim($_POST['result']);

    // uploads directory
    $uploadDir = __DIR__ . '/../uploads/students/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Student image upload
    $img_name = NULL;
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
        $filename = basename($_FILES['image']['name']);
        $filename = preg_replace("/[^A-Za-z0-9\.\-_]/", "", $filename);
        $img_name = time() . '_' . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $img_name);
    }

    // Registration paper upload
    $reg_paper = NULL;
    if (!empty($_FILES['reg_paper_image']['name']) && $_FILES['reg_paper_image']['error'] === 0) {
        $filename = basename($_FILES['reg_paper_image']['name']);
        $filename = preg_replace("/[^A-Za-z0-9\.\-_]/", "", $filename);
        $reg_paper = time() . '_' . $filename;
        move_uploaded_file($_FILES['reg_paper_image']['tmp_name'], $uploadDir . $reg_paper);
    }

    // Insert student
    $stmt = $pdo->prepare("
        INSERT INTO students
        (name, roll, registration, department, semester, session, shift, father_name, mother_name, father_phone, mother_phone, present_address, permanent_address, phone, result, image, reg_paper_image) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ");

    if ($stmt->execute([
        $name, $roll, $registration, $department, $semester, $sessionName,
        $shift, $father_name, $mother_name, $father_phone, $mother_phone,
        $present_address, $permanent_address, $phone, $result,
        $img_name, $reg_paper
    ])) {
        $success = "✅ Student added successfully!";
    } else {
        $error = "❌ Failed to add student!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Student</title>
<link rel="stylesheet" href="../assets/css/add-student.css">
</head>
<body>

<div class="container">

    <!-- Back Button -->
    <a href="students_view.php" class="back-btn">
        <img src="../assets/images/back.png" alt="Back"> Back
    </a>

    <h2>Add Student</h2>

    <?php if ($error): ?>
        <p class="message error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="message success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="roll" placeholder="Roll" required>
        <input type="text" name="registration" placeholder="Registration" required>
        <input type="text" name="department" placeholder="Department" required>
        <input type="text" name="semester" placeholder="Semester" required>
        <input type="text" name="session" placeholder="Session" required>
        <input type="text" name="shift" placeholder="Shift" required>

        <input type="text" name="father_name" placeholder="Father Name" required>
        <input type="text" name="mother_name" placeholder="Mother Name" required>
        <input type="text" name="father_phone" placeholder="Father Phone" required>
        <input type="text" name="mother_phone" placeholder="Mother Phone" required>

        <input type="text" name="present_address" placeholder="Present Address" required>
        <input type="text" name="permanent_address" placeholder="Permanent Address" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <input type="text" name="result" placeholder="Result" required>

        <label>Registration Paper Image:</label>
        <input type="file" name="reg_paper_image" accept="image/*" required>

        <label>Student Image:</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit">Add Student</button>
    </form>
</div>

</body>
</html>
