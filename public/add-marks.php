<?php
include_once '../includes/db.php';
session_start();
if(!isset($_SESSION['teacher_id'])) header("Location: teacher-dashboard.php");

$message = "";

// GET params
$department = $_GET['department'] ?? '';
$semester   = $_GET['semester'] ?? '';
$subject_id = $_GET['subject_id'] ?? '';

if(!$department || !$semester || !$subject_id){
    die("⚠ Invalid access. Please select department, semester, and subject first.");
}

// Fetch subject name
$stmt = $pdo->prepare("SELECT name FROM subjects WHERE id=?");
$stmt->execute([$subject_id]);
$subject = $stmt->fetchColumn();

// Test types
$test_types = ['quiz-test-1','quiz-test-2','class-test-1','class-test-2','mid','attendance'];

// ------------------ Delete Marks -------------------
if(isset($_GET['delete_roll'])){
    $del_roll = $_GET['delete_roll'];
    $stmt_del = $pdo->prepare("DELETE FROM marks WHERE roll=? AND department=? AND semester=? AND subject_id=?");
    $stmt_del->execute([$del_roll,$department,$semester,$subject_id]);
    $message = "<p style='color:red;'>✅ All marks for roll $del_roll have been deleted!</p>";
}

// ------------------ Insert/Update Marks -------------------
if(isset($_POST['save_all'])){
    $roll = $_POST['roll'];
    $obtains = $_POST['obtain'];
    $totals  = $_POST['total'];

    // Check student exists
    $check = $pdo->prepare("SELECT * FROM students WHERE roll=? AND department=? AND semester=?");
    $check->execute([$roll, $department, $semester]);

    if($check->rowCount() == 0){
        $message = "<p style='color:red;'>⚠ Student not found in this department/semester!</p>";
    } else {
        foreach($test_types as $tt){
            $obtain = isset($obtains[$tt]) ? $obtains[$tt] : 0;
            $total  = isset($totals[$tt]) ? $totals[$tt] : 0;

            // Check if mark exists
            $stmt_check = $pdo->prepare("SELECT id FROM marks WHERE roll=? AND department=? AND semester=? AND subject_id=? AND test_type=?");
            $stmt_check->execute([$roll,$department,$semester,$subject_id,$tt]);

            if($stmt_check->rowCount() > 0){
                $id = $stmt_check->fetchColumn();
                $stmt_update = $pdo->prepare("UPDATE marks SET obtain=?, total=? WHERE id=?");
                $stmt_update->execute([$obtain,$total,$id]);
            } else {
                $stmt_insert = $pdo->prepare("INSERT INTO marks (roll, department, semester, subject_id, test_type, obtain, total) VALUES (?,?,?,?,?,?,?)");
                $stmt_insert->execute([$roll,$department,$semester,$subject_id,$tt,$obtain,$total]);
            }
        }
        $message = "<p style='color:#fff; text-align:center'>✅ Marks Saved Successfully!</p>";
    }
}

// ------------------ Fetch All Marks -------------------
$searchRoll = $_POST['search_roll'] ?? '';
$sql = "SELECT * FROM marks WHERE department=? AND semester=? AND subject_id=?";
$params = [$department, $semester, $subject_id];

if($searchRoll){
    $sql .= " AND roll LIKE ?";
    $params[] = "%$searchRoll%";
}

$sql .= " ORDER BY roll ASC";
$stmt_marks = $pdo->prepare($sql);
$stmt_marks->execute($params);
$all_marks = $stmt_marks->fetchAll(PDO::FETCH_ASSOC);

// Pivot array
$pivot = [];
foreach($all_marks as $m){
    $pivot[$m['roll']][$m['test_type']] = ['obtain'=>$m['obtain'],'total'=>$m['total'],'id'=>$m['id']];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Marks - <?= htmlspecialchars($subject); ?></title>
    <link rel="stylesheet" href="../assets/css/add-marks.css">

</head>
<body>

<a href="teacher-dashboard.php" class= "back-btn" >⬅ Back</a>
<h2>➤ Add Marks for Subject: <?= htmlspecialchars($subject); ?></h2>
<?= $message; ?>

<!-- Add Marks Form -->
<form method="post">
<label>Roll:</label>
<input type="text" name="roll" required>

<table>
<tr>
<th>Test Type</th>
<th>Obtain</th>
<th>Total</th>
</tr>
<?php foreach($test_types as $tt): ?>
<tr>
<td><?= $tt ?></td>
<td><input type="number" step="0.01" name="obtain[<?= $tt ?>]" value="0" required></td>
<td><input type="number" step="0.01" name="total[<?= $tt ?>]" value="0" required></td>
</tr>
<?php endforeach; ?>
</table>
<br>
<button type="submit" name="save_all" class="btn-save">💾 Save All</button>
</form>

<!-- Search Form -->
<div class="search-bar">
<form method="post">
<input type="text" name="search_roll" placeholder="Search by Roll" value="<?= htmlspecialchars($searchRoll); ?>">
<button type="submit">🔍 Search</button>
</form>
</div>

<!-- Pivot Table -->
<table>
<tr>
<th>Roll</th>
<?php foreach($test_types as $tt){ echo "<th>$tt</th>"; } ?>
<th>Action</th>
</tr>

<?php foreach($pivot as $roll=>$tests): ?>
<tr>
<td><?= htmlspecialchars($roll); ?></td>
<?php foreach($test_types as $tt): ?>
    <?php if(isset($tests[$tt])): ?>
        <td><?= $tests[$tt]['obtain']; ?>/<?= $tests[$tt]['total']; ?></td>
    <?php else: ?>
        <td>-</td>
    <?php endif; ?>
<?php endforeach; ?>
<td>
    <a class="btn-edit" href="edit-mark.php?roll=<?= urlencode($roll); ?>&subject_id=<?= $subject_id; ?>">Edit</a>
    <a class="btn-delete" href="add-marks.php?department=<?= urlencode($department); ?>&semester=<?= urlencode($semester); ?>&subject_id=<?= $subject_id; ?>&delete_roll=<?= urlencode($roll); ?>" onclick="return confirm('Are you sure you want to delete all marks for this roll?')">Delete</a>
</td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>
