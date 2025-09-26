<?php
include_once '../includes/db.php';
session_start();

if(!isset($_SESSION['teacher_id'])){
    header("Location: teacher-login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: teacher-dashboard.php");
    exit();
}

$id = $_GET['id'];

// Fetch existing notice
$stmt = $pdo->prepare("SELECT * FROM student_notices WHERE id=?");
$stmt->execute([$id]);
$notice = $stmt->fetch();

if(!$notice){
    header("Location: teacher-dashboard.php?error=Notice+not+found");
    exit();
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $student_roll = trim($_POST['student_roll']);
    $title = $_POST['title'];
    $content = $_POST['content'];

    if(!empty($student_roll) && !empty($title) && !empty($content)){
        // Check if roll exists in DB
        $stmt = $pdo->prepare("SELECT * FROM students WHERE roll=?");
        $stmt->execute([$student_roll]);
        $student = $stmt->fetch();

        if($student){
            // Update notice
            $stmt = $pdo->prepare("UPDATE student_notices SET student_roll=?, title=?, content=? WHERE id=?");
            $stmt->execute([$student_roll, $title, $content, $id]);
            header("Location: teacher-dashboard.php?msg=Notice+updated+successfully");
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
<html lang="bn">
<head>
<meta charset="UTF-8">
<title>Edit Student Notice</title>
<link rel="stylesheet" href="../assets/css/edit-student-notice.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

</head>
<body>
    <!-- Back Button -->
    <a href="teacher-dashboard.php" class="back-btn">
        <img src="../assets/images/back.png" alt="Back"> Back
    </a>

<div class="container">
    <h2>✏️ Edit Student Notice</h2>
    <?php if(isset($error)) echo '<p class="error">'.$error.'</p>'; ?>

    <form method="post">
        <label>Student:</label>
        <label>Student Roll:</label>
        <input type="text" name="student_roll" value="<?php echo htmlspecialchars($notice['student_roll']); ?>" required>

        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($notice['title']); ?>" required>

        <label>Content:</label>
        <textarea name="content" rows="5" required><?php echo htmlspecialchars($notice['content']); ?></textarea>

        <button type="submit">Update Notice ✅</button>
    </form>
</div>
</body>
</html>
