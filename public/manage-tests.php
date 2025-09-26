
<?php
// manage-tests.php
include_once '../includes/db.php';
session_start();

if(!isset($_SESSION['teacher_id'])){
    header("Location: teacher-login.php");
    exit();
}
$teacher_id = $_SESSION['teacher_id'];
$message = "";

// get distinct departments & semesters from subjects table
$departments = $pdo->query("SELECT DISTINCT department FROM subjects ORDER BY department ASC")->fetchAll(PDO::FETCH_COLUMN);
$semesters_all = $pdo->query("SELECT DISTINCT semester FROM subjects ORDER BY semester ASC")->fetchAll(PDO::FETCH_COLUMN);

// --- Edit mode?
$edit_id = $_GET['edit'] ?? null;
$editing = false;
$edit_test = null;
if ($edit_id) {
    $stmt = $pdo->prepare("SELECT * FROM tests WHERE id=?");
    $stmt->execute([$edit_id]);
    $edit_test = $stmt->fetch();
    if (!$edit_test) {
        $message = "<p style='color:red;'>Test not found.</p>";
        $edit_id = null;
    } else {
        // only creator can edit
        if ($edit_test['created_by'] != $teacher_id) {
            die("Permission denied.");
        }
        $editing = true;
    }
}

// --- Handle Save (create or update)
if (isset($_POST['save_test'])) {
    $department = $_POST['department'];
    $semester = $_POST['semester'];
    $shift = $_POST['shift'];
    $subject_id = $_POST['subject_id'];
    $title = trim($_POST['title']);
    $type = $_POST['type'];
    $dt = $_POST['date_time']; // datetime-local format: "YYYY-MM-DDTHH:MM"
    // Normalize to MySQL DATETIME
    $date_time = str_replace('T',' ',$dt) . ':00'; // add seconds

    if (empty($department) || empty($semester) || empty($shift) || empty($subject_id) || empty($title) || empty($date_time)) {
        $message = "<p style='color:red;'>All fields are required.</p>";
    } else {
        if (!empty($_POST['edit_id'])) {
            // update
            $edit_id_post = intval($_POST['edit_id']);
            // check ownership
            $chk = $pdo->prepare("SELECT created_by FROM tests WHERE id=?");
            $chk->execute([$edit_id_post]);
            $row = $chk->fetch();
            if (!$row || $row['created_by'] != $teacher_id) {
                $message = "<p style='color:red;'>Permission denied.</p>";
            } else {
                $u = $pdo->prepare("UPDATE tests SET department=?, semester=?, shift=?, subject_id=?, title=?, type=?, date_time=? WHERE id=?");
                $u->execute([$department, $semester, $shift, $subject_id, $title, $type, $date_time, $edit_id_post]);
                $message = "<p style='color:green;'>✅ Test updated.</p>";
                header("Location: manage-tests.php"); exit;
            }
        } else {
            // insert
            $i = $pdo->prepare("INSERT INTO tests (department, semester, shift, subject_id, title, type, date_time, created_by) VALUES (?,?,?,?,?,?,?,?)");
            $i->execute([$department, $semester, $shift, $subject_id, $title, $type, $date_time, $teacher_id]);
            $message = "<p style='color:green;'>✅ Test created.</p>";
            header("Location: manage-tests.php"); exit;
        }
    }
}

// --- Handle Delete
if (isset($_GET['delete'])) {
    $del = intval($_GET['delete']);
    // check owner
    $chk = $pdo->prepare("SELECT created_by FROM tests WHERE id=?");
    $chk->execute([$del]);
    $row = $chk->fetch();
    if ($row && $row['created_by'] == $teacher_id) {
        $pdo->prepare("DELETE FROM tests WHERE id=?")->execute([$del]);
        header("Location: manage-tests.php");
        exit;
    } else {
        $message = "<p style='color:red;'>Permission denied for delete.</p>";
    }
}

// --- Fetch teacher's tests (list)
$tests_list = $pdo->prepare("SELECT t.*, s.name AS subject_name FROM tests t JOIN subjects s ON t.subject_id = s.id WHERE t.created_by=? ORDER BY t.date_time ASC");
$tests_list->execute([$teacher_id]);
$tests = $tests_list->fetchAll();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Manage Upcoming Tests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../assets/css/manage-test.css">
 </head>
<body>
<?php include_once '../includes/header.php'; ?>

<div class="container">

    <a href="teacher-dashboard.php" class="back-btn">
    <img src="../assets/images/back.png" alt="Back"> Back
    </a>

    <h2>Upcoming Tests — Manage</h2>

    <!-- Form Section Centered -->
    <div class="form-container">
        <div class="card">
            <h5 class="mb-4"><?php echo $editing ? "Edit Test" : "Create New Test"; ?></h5>
            <form method="post">
                <div class="mb-2">
                    <label>Department</label>
                    <select id="department" name="department" class="form-control" required>
                        <option value="">--Select Dept--</option>
                        <?php foreach($departments as $d): 
                            $sel = ($editing && $edit_test['department']==$d)? 'selected':''; ?>
                            <option value="<?= htmlspecialchars($d) ?>" <?= $sel; ?>><?= htmlspecialchars($d) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Semester</label>
                    <select id="semester" name="semester" class="form-control" required>
                        <option value="">--Select Semester--</option>
                        <?php foreach($semesters_all as $s): 
                            $sel = ($editing && $edit_test['semester']==$s)? 'selected':''; ?>
                            <option value="<?= htmlspecialchars($s) ?>" <?= $sel; ?>><?= htmlspecialchars($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Shift</label>
                    <select name="shift" id="shift" class="form-control" required>
                        <option value="Day" <?= ($editing && $edit_test['shift']=='Day')?'selected':''; ?>>Day</option>
                        <option value="Morning" <?= ($editing && $edit_test['shift']=='Morning')?'selected':''; ?>>Morning</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Subject</label>
                    <select id="subject" name="subject_id" class="form-control" required>
                        <option value="">--Select Subject--</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" required value="<?= $editing ? htmlspecialchars($edit_test['title']) : ''; ?>">
                </div>
                <div class="mb-2">
                    <label>Type (optional)</label>
                    <input type="text" name="type" class="form-control" required value="<?= $editing ? htmlspecialchars($edit_test['type']) : 'quiz-test-1'; ?>">
                </div>
                <div class="mb-2">
                    <label>Date & Time</label>
                    <?php
                    $dtval = '';
                    if ($editing) {
                        $dt = new DateTime($edit_test['date_time']);
                        $dtval = $dt->format('Y-m-d\TH:i');
                    }
                    ?>
                    <input type="datetime-local" name="date_time" class="form-control" required value="<?= $dtval; ?>">
                </div>
                <?php if($editing): ?>
                    <input type="hidden" name="edit_id" value="<?= htmlspecialchars($edit_test['id']); ?>">
                <?php endif; ?>
                <button class="btn btn-primary w-100" name="save_test" type="submit"><?= $editing ? 'Update Test' : 'Create Test' ?></button>
                <?php if($editing): ?>
                    <a class="btn btn-secondary w-100 mt-2" href="manage-tests.php">Cancel</a>
                <?php endif; ?>
            </form>
            <!-- Message below form -->
            <?php if($message): ?>
                <div class="message"><?= $message ?></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-card">
        <h5 class="mb-3">Your Created Tests</h5>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Dept</th>
                    <th>Sem</th>
                    <th>Shift</th>
                    <th>Subject</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Date & Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($tests as $t): ?>
                <tr>
                    <td><?= $t['id']; ?></td>
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
            <?php if(empty($tests)): ?><tr><td colspan="9">No tests yet.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(function(){
    function loadSubjects(){
        var dept = $("#department").val();
        var sem = $("#semester").val();
        if(!dept || !sem) {
            $("#subject").html('<option value="">--Select Subject--</option>');
            return;
        }
        $.getJSON('get_subjects.php', {department:dept, semester:sem}, function(data){
            var html = '<option value="">--Select Subject--</option>';
            $.each(data, function(i, s){
                html += '<option value="'+s.id+'">'+s.name+'</option>';
            });
            $("#subject").html(html);
            // if editing and subject already set, keep it
        });
    }
    $("#department, #semester").on('change', loadSubjects);
    // initial load if values present
    loadSubjects();
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
