<?php
include_once '../includes/db.php';
session_start();
if(!isset($_SESSION['teacher_id'])) header("Location: teacher-login.php");

$subject_id = $_GET['subject_id'] ?? '';
$roll = $_GET['roll'] ?? '';

if(!$subject_id || !$roll) die("⚠ Invalid request");

// Fetch subject name
$stmt = $pdo->prepare("SELECT name FROM subjects WHERE id=?");
$stmt->execute([$subject_id]);
$subject = $stmt->fetchColumn();

// Fetch student info for back button
$stmt_student = $pdo->prepare("SELECT department, semester FROM students WHERE roll=? LIMIT 1");
$stmt_student->execute([$roll]);
$student_info = $stmt_student->fetch(PDO::FETCH_ASSOC);


// Test types
$test_types = ['quiz-test-1','quiz-test-2','class-test-1','class-test-2','mid','attendance'];

// Fetch existing marks
$stmt_marks = $pdo->prepare("SELECT * FROM marks WHERE roll=? AND subject_id=? ORDER BY test_type ASC");
$stmt_marks->execute([$roll,$subject_id]);
$marks_data = [];
while($m = $stmt_marks->fetch(PDO::FETCH_ASSOC)){
    $marks_data[$m['test_type']] = ['obtain'=>$m['obtain'],'total'=>$m['total'],'id'=>$m['id']];
}

// Update marks
$message = "";
if(isset($_POST['update_all'])){
    foreach($test_types as $tt){
        $obtain = $_POST['obtain'][$tt];
        $total  = $_POST['total'][$tt];
        if(isset($marks_data[$tt])){
            $stmt = $pdo->prepare("UPDATE marks SET obtain=?, total=? WHERE id=?");
            $stmt->execute([$obtain,$total,$marks_data[$tt]['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO marks (roll, department, semester, subject_id, test_type, obtain, total)
                                   VALUES (?,?,?,?,?,?,?)");
            // fetch department, semester from students table
            $stmt_student = $pdo->prepare("SELECT department, semester FROM students WHERE roll=? LIMIT 1");
            $stmt_student->execute([$roll]);
            $stu = $stmt_student->fetch(PDO::FETCH_ASSOC);
            $stmt->execute([$roll, $stu['department'], $stu['semester'], $subject_id, $tt, $obtain, $total]);
        }
    }
    $message = "<p style='color:#fff; text-align:center'>✅ Marks Updated Successfully!</p>";
    // refresh marks_data
    $stmt_marks->execute([$roll,$subject_id]);
    $marks_data = [];
    while($m = $stmt_marks->fetch(PDO::FETCH_ASSOC)){
        $marks_data[$m['test_type']] = ['obtain'=>$m['obtain'],'total'=>$m['total'],'id'=>$m['id']];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Marks - <?= htmlspecialchars($subject); ?></title>
    <link rel="stylesheet" href="../assets/css/edit-mark.css">
</head>
<body>

<a class="back-btn" href="add-marks.php?department=<?= urlencode($student_info['department']); ?>&semester=<?= urlencode($student_info['semester']); ?>&subject_id=<?= $subject_id; ?>">⬅ Back</a>
<h2>Edit Marks for Roll: <?= htmlspecialchars($roll); ?> (<?= htmlspecialchars($subject); ?>)</h2>
<?= $message; ?>

<form method="post">
<table>
<tr><th>Test Type</th><th>Obtain</th><th>Total</th></tr>
<?php foreach($test_types as $tt): 
    $obtain = $marks_data[$tt]['obtain'] ?? '';
    $total  = $marks_data[$tt]['total'] ?? '';
?>
<tr>
    <td><?= $tt ?></td>
    <td><input type="number" step="0.01" name="obtain[<?= $tt ?>]" value="<?= $obtain ?>"></td>
    <td><input type="number" step="0.01" name="total[<?= $tt ?>]" value="<?= $total ?>"></td>
</tr>
<?php endforeach; ?>
</table>
<br>
<button type="submit" name="update_all">💾 Update All</button>
</form>

</body>
</html>
