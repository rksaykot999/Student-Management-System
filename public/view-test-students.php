<?php
include_once '../includes/db.php';
session_start();

// Check if teacher is logged in
if(!isset($_SESSION['teacher_id'])){
    header("Location: teacher-login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch all tests created by this teacher
$stmt = $pdo->prepare("
    SELECT t.*, s.name as subject_name
    FROM tests t
    JOIN subjects s ON t.subject_id = s.id
    WHERE t.created_by = ?
    ORDER BY t.date_time ASC
");
$stmt->execute([$teacher_id]);
$tests = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Created Tests</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.container { max-width: 1000px; margin: 40px auto; }
.card-header { background: linear-gradient(135deg, #1abc9c, #16a085); color: #fff; font-size: 1.2rem; font-weight: bold; }
.table thead th { background: #1abc9c; color: #fff; }
.table tbody tr:hover { background: #f0fdfb; }
.back-btn {
    padding: 8px 15px; background: #e67e22; color: #fff; border: none;
    border-radius: 6px; cursor: pointer; margin-bottom: 15px;
}
.back-btn:hover { background: #d35400; }
</style>
</head>
<body>
<?php include_once '../includes/header.php'; ?>

<div class="container">
    <button class="back-btn" onclick="window.history.back()">← Back</button>
    
    <div class="card">
        <div class="card-header">
            My Created Tests
        </div>
        <div class="card-body">
            <?php if(!empty($tests)): ?>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Department</th>
                            <th>Semester</th>
                            <th>Shift</th>
                            <th>Subject</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Date & Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($tests as $i => $t): ?>
                            <tr>
                                <td><?= $i+1; ?></td>
                                <td><?= htmlspecialchars($t['department']); ?></td>
                                <td><?= htmlspecialchars($t['semester']); ?></td>
                                <td><?= htmlspecialchars($t['shift']); ?></td>
                                <td><?= htmlspecialchars($t['subject_name']); ?></td>
                                <td><?= htmlspecialchars($t['title']); ?></td>
                                <td><?= htmlspecialchars($t['type']); ?></td>
                                <td><?= date("d-M-Y H:i", strtotime($t['date_time'])); ?></td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="manage-tests.php?edit=<?= $t['id']; ?>">Edit</a>
                                    <a class="btn btn-sm btn-danger" href="manage-tests.php?delete=<?= $t['id']; ?>" onclick="return confirm('Delete this test?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No tests created yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
