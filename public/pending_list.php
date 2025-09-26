<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

$tab = $_GET['tab'] ?? 'students';

// Fetch pending students (roll ascending)
$pendingStudents = $pdo->query("SELECT * FROM pending_students ORDER BY roll ASC")->fetchAll();

// Fetch pending teachers (designation order)
$pendingTeachers = $pdo->query("
    SELECT * FROM pending_teachers
    ORDER BY
    CASE
        WHEN designation = 'Chief Instructor & Head of the Department' THEN 1
        WHEN designation = 'Instructor' THEN 2
        WHEN designation = 'Workshop Super' THEN 3
        WHEN designation = 'Junior Instructor' THEN 4
        ELSE 5
    END ASC
")->fetchAll();
?>
<!doctype html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <title>Pending Requests - Admin</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {background:#f7f8fb}
    .table-responsive {max-height:75vh; overflow-y:auto;}
    .modal-img {max-height:100vh; max-width:100vw; object-fit:contain;}
  </style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Pending Registrations</h3>
    <div>
      <a href="admin_dashboard.php" class="btn btn-outline-secondary btn-sm">Dashboard</a>
    </div>
  </div>

  <ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link <?= $tab==='students'?'active':'' ?>" href="?tab=students">Students (<?=count($pendingStudents)?>)</a></li>
    <li class="nav-item"><a class="nav-link <?= $tab==='teachers'?'active':'' ?>" href="?tab=teachers">Teachers (<?=count($pendingTeachers)?>)</a></li>
  </ul>

  <?php if($tab==='students'): ?>
    <div class="card shadow-sm">
      <div class="card-body p-0 table-responsive">
        <table class="table table-sm table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th><th>Name</th><th>Roll</th><th>Registration</th><th>Department</th>
              <th>Semester</th><th>Session</th><th>Shift</th><th>Father Name</th><th>Mother Name</th>
              <th>Father Phone</th><th>Mother Phone</th><th>Present Address</th><th>Permanent Address</th>
              <th>Phone</th><th>Image</th><th>Reg Paper</th><th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($pendingStudents)): ?>
              <tr><td colspan="18" class="text-center py-3">No pending student registrations.</td></tr>
            <?php else: foreach($pendingStudents as $index=>$p): ?>
              <tr>
                <td><?= $index+1 ?></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['roll']) ?></td>
                <td><?= htmlspecialchars($p['registration']) ?></td>
                <td><?= htmlspecialchars($p['department']) ?></td>
                <td><?= htmlspecialchars($p['semester']) ?></td>
                <td><?= htmlspecialchars($p['session']) ?></td>
                <td><?= htmlspecialchars($p['shift']) ?></td>
                <td><?= htmlspecialchars($p['father_name']) ?></td>
                <td><?= htmlspecialchars($p['mother_name']) ?></td>
                <td><?= htmlspecialchars($p['father_phone']) ?></td>
                <td><?= htmlspecialchars($p['mother_phone']) ?></td>
                <td><?= htmlspecialchars($p['present_address']) ?></td>
                <td><?= htmlspecialchars($p['permanent_address']) ?></td>
                <td><?= htmlspecialchars($p['phone']) ?></td>
                <td>
                  <?php if(!empty($p['image']) && file_exists(__DIR__.'/../uploads/students/'.$p['image'])): ?>
                    <img src="../uploads/students/<?=htmlspecialchars($p['image'])?>"
                        data-bs-toggle="modal"
                        data-bs-target="#imageModal"
                        data-src="../uploads/students/<?=htmlspecialchars($p['image'])?>"
                        width="50" height="50" style="border-radius:50%;cursor:pointer;">
                  <?php else: ?>-<?php endif; ?>
                </td>
                <td>
                  <?php if(!empty($p['reg_paper_image']) && file_exists(__DIR__.'/../uploads/students/'.$p['reg_paper_image'])): ?>
                    <img src="../uploads/students/<?=htmlspecialchars($p['reg_paper_image'])?>"
                        data-bs-toggle="modal"
                        data-bs-target="#imageModal"
                        data-src="../uploads/students/<?=htmlspecialchars($p['reg_paper_image'])?>"
                        width="50" height="50" style="border-radius:5px;cursor:pointer;">
                  <?php else: ?>-<?php endif; ?>
                </td>
                <td class="text-end">
                  <a class="btn btn-sm btn-success" href="approve_student.php?id=<?=urlencode($p['id'])?>" onclick="return confirm('Approve this student?')">Approve</a>
                  <a class="btn btn-sm btn-danger" href="reject_pending.php?type=student&id=<?=urlencode($p['id'])?>" onclick="return confirm('Reject?')">Reject</a>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  <?php else: ?>
    <div class="card shadow-sm">
      <div class="card-body p-0 table-responsive">
        <table class="table table-sm table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th><th>Name</th><th>Email</th><th>Department</th>
              <th>Shift</th><th>Phone</th><th>Qualification</th><th>Designation</th><th>Image</th><th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($pendingTeachers)): ?>
              <tr><td colspan="10" class="text-center py-3">No pending teacher registrations.</td></tr>
            <?php else: foreach($pendingTeachers as $index=>$p): ?>
              <tr>
                <td><?= $index+1 ?></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['email']) ?></td>
                <td><?= htmlspecialchars($p['department']) ?></td>
                <td><?= htmlspecialchars($p['shift']) ?></td>
                <td><?= htmlspecialchars($p['phone']) ?></td>
                <td><?= htmlspecialchars($p['qualification']) ?></td>
                <td><?= htmlspecialchars($p['designation']) ?></td>
                <td>
                 <td>
                      <?php if(!empty($p['image']) && file_exists(__DIR__.'/../uploads/teachers/'.$p['image'])): ?>
                            <img src="../uploads/teachers/<?=htmlspecialchars($p['image'])?>"
                                data-bs-toggle="modal"
                                data-bs-target="#imageModal"
                                data-src="../uploads/teachers/<?=htmlspecialchars($p['image'])?>"
                                width="50" height="50" style="border-radius:50%;cursor:pointer;">
                        <?php else: ?>-<?php endif; ?>

                </td>

                </td>
                <td class="text-end">
                  <a class="btn btn-sm btn-success" href="approve_teacher.php?id=<?=urlencode($p['id'])?>" onclick="return confirm('Approve this teacher?')">Approve</a>
                  <a class="btn btn-sm btn-danger" href="reject_pending.php?type=teacher&id=<?=urlencode($p['id'])?>" onclick="return confirm('Reject?')">Reject</a>
                 
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>

</div>

<!-- Modal for images (fullscreen, no scroll) -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content bg-transparent border-0 shadow-none">
      <div class="modal-body d-flex justify-content-center align-items-center p-0">
        <img src="" id="modalImage" class="modal-img">
      </div>
      <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
var imageModal = document.getElementById('imageModal');
imageModal.addEventListener('show.bs.modal', function (event) {
  var link = event.relatedTarget;
  var src = link.getAttribute('data-src');
  var modalImg = document.getElementById('modalImage');
  modalImg.src = src;
});
</script>
</body>
</html>
