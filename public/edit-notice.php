<?php
include_once '../includes/db.php';
session_start();

// Check if notice id is provided
if(!isset($_GET['id'])){
    header("Location: teacher-dashboard.php");
    exit();
}

$notice_id = $_GET['id'];

// Fetch existing notice
$stmt = $pdo->prepare("SELECT * FROM notices WHERE id=?");
$stmt->execute([$notice_id]);
$notice = $stmt->fetch();

if(!$notice){
    echo "Notice not found!";
    exit();
}

// Update notice
if(isset($_POST['update'])){
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare("UPDATE notices SET title=?, content=? WHERE id=?");
    $stmt->execute([$title, $content, $notice_id]);

    header("Location: teacher-dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Notice</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/edit-notice.css">
</head>
<body>
    <!-- Back Button -->
    <a href="teacher-dashboard.php" class="back-btn">
        <img src="../assets/images/back.png" alt="Back"> Back
    </a>

<div class="container">

    <h2>Edit Notice</h2>
    <form method="post">
        <input type="text" name="title" value="<?php echo htmlspecialchars($notice['title']); ?>" placeholder="Notice Title" required>
        <textarea name="content" placeholder="Notice Content" rows="5" required><?php echo htmlspecialchars($notice['content']); ?></textarea>
        <button type="submit" name="update">Update Notice</button>
    </form>
</div>

</body>
</html>

