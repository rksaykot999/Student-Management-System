<?php
include_once '../includes/db.php';
session_start();

// Teacher logged in check
if(!isset($_SESSION['teacher_id'])){
    header("Location: teacher-login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id']; // <-- এটা সবচেয়ে গুরুত্বপূর্ণ

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '' || $content === '') {
        $error = "Title and Content are required!";
    } else {
        // Teacher-specific notice insert
        $stmt = $pdo->prepare("INSERT INTO notices(title, content, teacher_id) VALUES (?, ?, ?)");
        if ($stmt->execute([$title, $content, $teacher_id])) {
            $success = "Notice created successfully!";
        } else {
            $error = "Failed to create notice!";
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Notice</title>
<link rel="stylesheet" href="../assets/css/add-notice.css">

</head>
<body>

<div class="container">

    <a href="teacher-dashboard.php" class="back-btn">
        <img src="../assets/images/back.png" alt="Back"> Back
    </a>
    <h2>Add Notice</h2>

    <?php if($error) echo "<p class='message error'>$error</p>"; ?>
    <?php if($success) echo "<p class='message success'>$success</p>"; ?>

    <form method="post">
        <input type="text" name="title" placeholder="Notice Title" required>
        <textarea name="content" placeholder="Notice Content" required></textarea>

        <button type="submit">Create Notice</button>
    </form>
</div>

</body>
</html>
