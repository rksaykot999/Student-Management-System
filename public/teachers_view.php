<?php
// public/teachers_view.php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// fetch all approved teachers with designation order
$stmt = $pdo->query("
    SELECT * FROM teachers 
    ORDER BY 
        CASE 
            WHEN designation = 'Chief Instructor & Head of the Department' THEN 1
            WHEN designation = 'Instructor' THEN 2
            WHEN designation = 'Workshop Super' THEN 3
            WHEN designation = 'Junior Instructor' THEN 4
            ELSE 5
        END ASC, id DESC
");
$teachers = $stmt->fetchAll();
?>
<!doctype html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <title>Teachers - Admin</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {background:#f7f8fb;}
    .table-responsive {max-height:75vh; overflow-y:auto;}
    .teacher-img {width:50px; height:50px; border-radius:50%; object-fit:cover; cursor:pointer;}
    .modal-img {width:100%; height:auto;}
  </style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>All Teachers</h3>
    <div>
      <a href="admin_dashboard.php" class="btn btn-outline-secondary btn-sm">Dashboard</a>
      <a href="add-teacher.php" class="btn btn-primary btn-sm">Add New Teacher</a>
    </div>
  </div>

  <?php if(empty($teachers)): ?>
    <div class="alert alert-info">No teachers found.</div>
  <?php else: ?>
    <div class="card shadow-sm">
      <div class="card-body p-0 table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Department</th>
              <th>Shift</th>
              <th>Phone</th>
              <th>Qualification</th>
              <th>Designation</th>
              <th>Image</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($teachers as $t): ?>
              <tr>
                <td><?=htmlspecialchars($t['id'])?></td>
                <td><?=htmlspecialchars($t['name'])?></td>
                <td><?=htmlspecialchars($t['email'])?></td>
                <td><?=htmlspecialchars($t['department'])?></td>
                <td><?=htmlspecialchars($t['shift'])?></td>
                <td><?=htmlspecialchars($t['phone'])?></td>
                <td><?=htmlspecialchars($t['qualification'])?></td>
                <td><?=htmlspecialchars($t['designation'])?></td>
                <td>
                  <?php 
                    $imgPath = __DIR__ . '/../uploads/teachers/' . $t['image'];
                    if(!empty($t['image']) && file_exists($imgPath)):
                  ?>
                    <img src="../uploads/teachers/<?=htmlspecialchars($t['image'])?>" 
                         class="teacher-img"
                         data-bs-toggle="modal" 
                         data-bs-target="#imageModal" 
                         data-src="../uploads/teachers/<?=htmlspecialchars($t['image'])?>">
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>
                <td class="text-end">
                  <a class="btn btn-sm btn-warning" href="edit-teacher.php?id=<?=urlencode($t['id'])?>">Edit</a>
                  <a class="btn btn-sm btn-danger" href="delete-teacher.php?id=<?=urlencode($t['id'])?>" onclick="return confirm('Delete this teacher?')">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>
</div>

<!-- Modal for teacher image -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body p-0">
        <img src="" id="modalImage" class="modal-img">
      </div>
      <div class="modal-footer p-2">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
var imageModal = document.getElementById('imageModal')
imageModal.addEventListener('show.bs.modal', function (event) {
  var img = event.relatedTarget
  var src = img.getAttribute('data-src')
  document.getElementById('modalImage').src = src
})
</script>
</body>
</html>
