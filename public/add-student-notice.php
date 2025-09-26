<?php
include_once '../includes/db.php';
session_start();

if(!isset($_SESSION['teacher_id'])){
    header("Location: teacher-login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $student_roll = trim($_POST['student_roll']);
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if(!empty($student_roll) && !empty($title) && !empty($content)){
        // Roll check in database
        $stmt = $pdo->prepare("SELECT * FROM students WHERE roll=?");
        $stmt->execute([$student_roll]);
        $student = $stmt->fetch();

        if($student){
            // Insert notice if roll exists
            $teacher_id = $_SESSION['teacher_id'];
            $stmt = $pdo->prepare("INSERT INTO student_notices (student_roll, title, content, teacher_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$student_roll, $title, $content, $teacher_id]);

            header("Location: teacher-dashboard.php?msg=Notice+added+successfully");
            exit();
        } else {
            $error = "❌ Roll number not found in database!";
        }
    } else {
        $error = "⚠ Please fill all fields";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student Notice</title>
    <link rel="stylesheet" href="../assets/css/add-student-notice.css">
</head>
<body>
    <div class="container">
        <!-- Back Button -->
        <a href="teacher-dashboard.php" class="back-btn">
            <img src="../assets/images/back.png" alt="Back"> Back
        </a>

        <h2>➕ Add Student Notice</h2>

        <!-- Error Message -->
        <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="post">
            <label>Student Roll:</label>
            <input type="text" name="student_roll" required>

            <label>Title:</label>
            <input type="text" name="title" required>

            <label>Content:</label>
            <textarea name="content" required></textarea>

            <div style="display:flex; justify-content: space-between;">
                <button type="submit" class="btn btn-submit">Add Notice</button>
                <button type="button" class="btn btn-cancel" onclick="window.location.href='teacher-dashboard.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
