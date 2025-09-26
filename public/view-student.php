<?php
include_once '../includes/db.php';
session_start();

// check login
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher-login.php");
    exit();
}

$searchRoll = "";
if (isset($_POST['search'])) {
    $searchRoll = $_POST['roll'];
    $stmt = $pdo->prepare("SELECT * FROM students WHERE roll LIKE ? ORDER BY roll ASC");
    $stmt->execute(["%$searchRoll%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM students ORDER BY roll ASC");
}
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Students</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/view-student.css">
</head>
<body>

<div class="container">
    <a href="teacher-dashboard.php" class="back-btn">← Back to Dashboard</a>
    <h2>Student List</h2>

    <!-- search form -->
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-10">
            <input type="text" name="roll" class="form-control" placeholder="Enter Roll No..." 
                   value="<?php echo htmlspecialchars($searchRoll); ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" name="search" class="btn btn-primary w-100">Search</button>
        </div>
    </form>

    <?php if ($students && count($students) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Roll</th>
                    <th>Dept</th>
                    <th>Semester</th>
                    <th>Session</th>
                    <th>Shift</th>
                    <th>Phone</th>
                 
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td>
                        <img src="<?php echo !empty($s['image']) 
                            ? '../uploads/students/'.$s['image'] 
                            : '../assets/images/default-student.png'; ?>" 
                            width="50" height="50" style="border-radius:50%;">

                    </td>
                    <td><?php echo htmlspecialchars($s['name']); ?></td>
                    <td><?php echo htmlspecialchars($s['roll']); ?></td>
                    <td><?php echo htmlspecialchars($s['department']); ?></td>
                    <td><?php echo htmlspecialchars($s['semester']); ?></td>
                    <td><?php echo htmlspecialchars($s['session']); ?></td>
                    <td><?php echo htmlspecialchars($s['shift']); ?></td>
                    <td><?php echo htmlspecialchars($s['phone']); ?></td>
              
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No student found.</p>
    <?php endif; ?>
</div>

</body>
</html>
