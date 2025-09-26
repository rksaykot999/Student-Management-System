<?php
// public/admin_dashboard.php
session_start();
require_once __DIR__ . '/../includes/db.php';

// check auth
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// fetch counts and lists
$counts = [];
$counts['pending_students'] = $pdo->query("SELECT COUNT(*) FROM pending_students")->fetchColumn();
$counts['pending_teachers'] = $pdo->query("SELECT COUNT(*) FROM pending_teachers")->fetchColumn();
$counts['students'] = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$counts['teachers'] = $pdo->query("SELECT COUNT(*) FROM teachers")->fetchColumn();
$counts['notices'] = $pdo->query("SELECT COUNT(*) FROM notices")->fetchColumn();

// fetch small lists (latest 10)
$pendingStudents = $pdo->query("SELECT * FROM pending_students ORDER BY created_at DESC LIMIT 10")->fetchAll();
$pendingTeachers = $pdo->query("SELECT * FROM pending_teachers ORDER BY created_at DESC LIMIT 10")->fetchAll();
$students = $pdo->query("SELECT id,name,roll,department,semester FROM students ORDER BY id DESC LIMIT 10")->fetchAll();
$teachers = $pdo->query("SELECT id,name,email,department FROM teachers ORDER BY id DESC LIMIT 10")->fetchAll();

$msg = $_GET['msg'] ?? '';
?>
<!doctype html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f7f8fb; }
    .sidebar { min-height:100vh; background:#0d6efd; color:#fff; }
    .sidebar a { color:#fff; text-decoration:none; display:block; padding:10px 15px; }
    .sidebar a:hover { background:rgba(255,255,255,0.05); }
    .stat-card { border-radius:10px; }
    .small-table td, .small-table th { vertical-align:middle; }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar p-0">
      <div class="p-3 border-bottom">
        <h5>Admin Panel</h5>
        <div class="small">Welcome, <?=htmlspecialchars($_SESSION['admin_name'])?></div>
      </div>
      <nav class="mt-3">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="students_view.php">All Students</a>
        <a href="teachers_view.php">All Teachers</a>
        <a href="pending_list.php">Pending Requests</a>
        <a href="notice_list.php">Notices</a>
        <a href="admin-logout.php">Logout</a>
      </nav>
    </div>

    <!-- Main -->
    <div class="col-md-10 p-4">
      <?php if($msg): ?>
        <div class="alert alert-success"><?=htmlspecialchars($msg)?></div>
      <?php endif; ?>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Dashboard</h3>
        <div class="text-end">
          <small class="text-muted">Admin: <?=htmlspecialchars($_SESSION['admin_name'])?></small>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-2">
          <div class="card p-3 stat-card">
            <div class="h5"><?=$counts['pending_students']?></div>
            <div class="small text-muted">Pending Students</div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="card p-3 stat-card">
            <div class="h5"><?=$counts['pending_teachers']?></div>
            <div class="small text-muted">Pending Teachers</div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="card p-3 stat-card">
            <div class="h5"><?=$counts['students']?></div>
            <div class="small text-muted">Approved Students</div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="card p-3 stat-card">
            <div class="h5"><?=$counts['teachers']?></div>
            <div class="small text-muted">Approved Teachers</div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="card p-3 stat-card">
            <div class="h5"><?=$counts['notices']?></div>
            <div class="small text-muted">Notices</div>
          </div>
        </div>
      </div>

      <!-- Pending Students -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <strong>Pending Student Registrations (latest)</strong>
          <a href="pending_list.php" class="btn btn-sm btn-outline-primary">View all</a>
        </div>
        <div class="card-body p-0">
          <table class="table table-sm mb-0 small-table">
            <thead class="table-light">
              <tr><th>Name</th><th>Roll</th><th>Dept</th><th>Submitted</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php if(empty($pendingStudents)): ?>
              <tr><td colspan="6" class="text-center py-3">No pending student registrations</td></tr>
            <?php else: foreach($pendingStudents as $p): ?>
              <tr>
                
                <td><?=htmlspecialchars($p['name'])?></td>
                <td><?=htmlspecialchars($p['roll'])?></td>
                <td><?=htmlspecialchars($p['department'])?></td>
                <td><?=htmlspecialchars($p['created_at'])?></td>
                <td>
                  <a class="btn btn-sm btn-success" href="approve_student.php?id=<?=urlencode($p['id'])?>" onclick="return confirm('Approve student #<?=htmlspecialchars($p['id'])?>?')">Approve</a>
                  <a class="btn btn-sm btn-danger" href="reject_pending.php?type=student&id=<?=urlencode($p['id'])?>" onclick="return confirm('Reject and delete this pending registration?')">Reject</a>
                </td>
              </tr>
            <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pending Teachers -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <strong>Pending Teacher Registrations (latest)</strong>
          <a href="pending_list.php?tab=teachers" class="btn btn-sm btn-outline-primary">View all</a>
        </div>
        <div class="card-body p-0">
          <table class="table table-sm mb-0 small-table">
            <thead class="table-light">
              <tr><th>Name</th><th>Email</th><th>Dept</th><th>Submitted</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php if(empty($pendingTeachers)): ?>
              <tr><td colspan="6" class="text-center py-3">No pending teacher registrations</td></tr>
            <?php else: foreach($pendingTeachers as $p): ?>
              <tr>
                
                <td><?=htmlspecialchars($p['name'])?></td>
                <td><?=htmlspecialchars($p['email'])?></td>
                <td><?=htmlspecialchars($p['department'])?></td>
                <td><?=htmlspecialchars($p['created_at'])?></td>
                <td>
                  <a class="btn btn-sm btn-success" href="approve_teacher.php?id=<?=urlencode($p['id'])?>" onclick="return confirm('Approve teacher #<?=htmlspecialchars($p['id'])?>?')">Approve</a>
                  <a class="btn btn-sm btn-danger" href="reject_pending.php?type=teacher&id=<?=urlencode($p['id'])?>" onclick="return confirm('Reject and delete this pending registration?')">Reject</a>
                </td>
              </tr>
            <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Recent Approved -->
      <div class="row">
        <div class="col-md-6">
          <div class="card mb-4">
            <div class="card-header"><strong>Recent Approved Students</strong></div>
            <div class="card-body p-0">
              <table class="table table-sm mb-0">
                <thead class="table-light"><tr><th>Name</th><th>Roll</th><th>Dept</th></tr></thead>
                <tbody>
                  <?php if(empty($students)): ?>
                    <tr><td colspan="4" class="text-center py-3">No approved students yet</td></tr>
                  <?php else: foreach($students as $s): ?>
                    <tr>
                      
                      <td><?=htmlspecialchars($s['name'])?></td>
                      <td><?=htmlspecialchars($s['roll'])?></td>
                      <td><?=htmlspecialchars($s['department'])?></td>
                    </tr>
                  <?php endforeach; endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card mb-4">
            <div class="card-header"><strong>Recent Approved Teachers</strong></div>
            <div class="card-body p-0">
              <table class="table table-sm mb-0">
                <thead class="table-light"><tr><th>Name</th><th>Email</th><th>Dept</th></tr></thead>
                <tbody>
                  <?php if(empty($teachers)): ?>
                    <tr><td colspan="4" class="text-center py-3">No approved teachers yet</td></tr>
                  <?php else: foreach($teachers as $t): ?>
                    <tr>
                     
                      <td><?=htmlspecialchars($t['name'])?></td>
                      <td><?=htmlspecialchars($t['email'])?></td>
                      <td><?=htmlspecialchars($t['department'])?></td>
                    </tr>
                  <?php endforeach; endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div> <!-- main col -->
  </div>
</div>
</body>
</html>

