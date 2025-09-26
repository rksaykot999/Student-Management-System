<?php
include_once '../includes/db.php';
session_start();

// check login
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']);
    $designation = trim($_POST['designation']);
    $department = trim($_POST['department']);
    $shift = trim($_POST['shift']);
    $qualification = trim($_POST['qualification']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $image_name = null;

    // teacher image upload
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        $filename = basename($_FILES['image']['name']);
        $filename = preg_replace("/[^A-Za-z0-9\.\-_]/", "", $filename); // sanitize filename
        $image_name = time().'_'.$filename;

       $uploadDir = __DIR__ . '/../uploads/teachers/';
            if(!is_dir($uploadDir)){
                mkdir($uploadDir, 0777, true);
            }
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir.$image_name);

    }

    $stmt = $pdo->prepare("INSERT INTO teachers (name, designation, department, shift, qualification, phone, email, password, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $designation, $department, $shift, $qualification, $phone, $email, $password, $image_name]);

    header("Location: view-teacher.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Teacher</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/add-teacher.css">
</head>
<body>

<div class="container">

 <!-- Back Button -->
    <a href="teachers_view.php" class="back-btn">
        <img src="../assets/images/back.png" alt="Back"> Back
    </a>
    
    <h2>Add Teacher</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="designation" placeholder="Designation" required>
        <input type="text" name="department" placeholder="Department" required>
        <input type="text" name="shift" placeholder="Shift" required>
        <input type="text" name="qualification" placeholder="Qualification" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="file" name="image" accept="image/*">
        <button type="submit" class="add">Add Teacher</button>
    </form>
</div>

</body>
</html>
