<?php
include_once '../includes/db.php';
session_start();

if(!isset($_SESSION['teacher_id'])){
    header("Location: teacher-login.php");
    exit();
}

// সার্চের জন্য
$teachers = [];
$search_name = '';
if(isset($_POST['search'])){
    $search_name = trim($_POST['search']);
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE name LIKE ? ORDER BY name ASC");
    $stmt->execute(["%$search_name%"]);
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $teachers = $pdo->query("SELECT * FROM teachers ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Teachers</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/view-teacher.css">
</head>
<body>

<div class="container">
    <a href="teacher-dashboard.php" class="back-btn">← Back to Dashboard</a>
    <h2>Teacher List</h2>

    <!-- Search Form -->
    <form method="POST">
        <input type="text" name="search" placeholder="Enter Teacher Name..." 
               value="<?php echo htmlspecialchars($search_name); ?>">
        <button type="submit">Search</button>
    </form>

    <?php if ($teachers && count($teachers) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Department</th>
                    <th>shift</th>
                    <th>Qualification</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teachers as $t): ?>
                <tr>
                    <td>
                        <img src="<?php echo !empty($t['image']) 
                                        ? '../public/uploads/'.$t['image'] 
                                        : '../assets/images/default-teacher.png'; ?>" 
                             width="50" height="50" style="border-radius:50%;">
                    </td>
                    <td><?php echo htmlspecialchars($t['name']); ?></td>
                    <td><?php echo htmlspecialchars($t['designation']); ?></td>
                    <td><?php echo htmlspecialchars($t['department']); ?></td>
                    <td><?php echo htmlspecialchars($t['shift']); ?></td>
                    <td><?php echo htmlspecialchars($t['qualification']); ?></td>
                    <td><?php echo htmlspecialchars($t['phone']); ?></td>
                    <td><?php echo htmlspecialchars($t['email']); ?></td>
                    <td>
                        <button class="edit" 
                                onclick="window.location.href='edit-teacher.php?id=<?php echo $t['id']; ?>'">
                                Edit</button>
                        <button class="delete" 
                                onclick="if(confirm('Are you sure to delete this teacher?')) window.location.href='delete-teacher.php?id=<?php echo $t['id']; ?>'">
                                Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No teacher found.</p>
    <?php endif; ?>
</div>
</body>
</html>
