<?php
include_once '../includes/db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

if(!isset($_GET['id'])){
    header("Location: teachers_view.php");
    exit();
}

$id = $_GET['id'];

// fetch teacher info
$stmt = $pdo->prepare("SELECT * FROM teachers WHERE id=?");
$stmt->execute([$id]);
$teacher = $stmt->fetch();

if(!$teacher){
    header("Location: teachers_view.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['name'];
    $designation = $_POST['designation'];
    $department = $_POST['department'];
    $shift = $_POST['shift'];
    $qualification = $_POST['qualification'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $image_name = $teacher['image'];
        if(!empty($_FILES['image']['name'])){
            $image_name = time().'_'.basename($_FILES['image']['name']);
            $uploadDir = __DIR__.'/../uploads/teachers/';
            if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir.$image_name);
        }

    $stmt = $pdo->prepare("UPDATE teachers SET name=?, designation=?, department=?, shift=?, qualification=?, phone=?, email=?, password=?, image=? WHERE id=?");
    $stmt->execute([$name, $designation, $department, $shift, $qualification, $phone, $email, $password, $image_name, $id]);

    header("Location: teachers_view.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Teacher</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/edit-teacher.css">
<style>
    .current-img { width:100px; height:100px; border-radius:50%; object-fit:cover; display:block; margin-bottom:10px; }
    .container { max-width:600px; margin:30px auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1); }
    .back-btn { display:inline-block; margin-bottom:15px; text-decoration:none; color:#555; }
    input, button { width:100%; margin-bottom:10px; padding:8px; }
</style>
</head>
<body>

<a href="teachers_view.php" class="back-btn">← Back to Teacher List</a>

<div class="container">
    <h2>Edit Teacher</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Current Image:</label>
        <img src="<?php 
            echo !empty($teacher['image']) && file_exists(__DIR__.'/../uploads/teachers/'.$teacher['image']) 
                ? '../uploads/teachers/'.htmlspecialchars($teacher['image']) 
                : '../assets/images/default-teacher.png'; 
        ?>" class="current-img" alt="Teacher Image">


        <input type="file" name="image" accept="image/*">

        <input type="text" name="name" placeholder="Name" value="<?=htmlspecialchars($teacher['name'])?>" required>
        <input type="text" name="designation" placeholder="Designation" value="<?=htmlspecialchars($teacher['designation'])?>" required>
        <input type="text" name="department" placeholder="Department" value="<?=htmlspecialchars($teacher['department'])?>" required>
        <input type="text" name="shift" placeholder="Shift" value="<?=htmlspecialchars($teacher['shift'])?>" required>
        <input type="text" name="qualification" placeholder="Qualification" value="<?=htmlspecialchars($teacher['qualification'])?>" required>
        <input type="text" name="phone" placeholder="Phone" value="<?=htmlspecialchars($teacher['phone'])?>" required>
        <input type="email" name="email" placeholder="Email" value="<?=htmlspecialchars($teacher['email'])?>" required>
        <input type="password" name="password" placeholder="Password" value="<?=htmlspecialchars($teacher['password'])?>" required>

        <button type="submit">Update Teacher</button>
    </form>
</div>

</body>
</html>
