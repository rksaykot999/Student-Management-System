<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// fetch all approved students
$students = $pdo->query("SELECT * FROM students ORDER BY roll ASC")->fetchAll();
?>
<!doctype html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <title>Students - Admin</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{background:#f7f8fb;} 
    td, th { vertical-align: middle; }
    .img-thumb { width:50px; height:50px; object-fit:cover; cursor:pointer; border-radius:6px; }
    .table-responsive { max-height:70vh; overflow-y:auto; }
    .modal-body table td { padding:5px; }
    .modal-body img { max-width:100%; border-radius:6px; margin-top:5px; }
  </style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>All Students</h3>
    <div>
      <a href="admin_dashboard.php" class="btn btn-outline-secondary btn-sm">Dashboard</a>
      <a href="add-student.php" class="btn btn-primary btn-sm">Add New Student</a>
    </div>
  </div>

  <?php if(empty($students)): ?>
    <div class="alert alert-info">No students found.</div>
  <?php else: ?>
    <div class="card shadow-sm table-responsive">
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Roll</th>
              <th>Dept</th>
              <th>Phone</th>
              <th>Image</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($students as $index=>$s): ?>
              <tr>
                <td><?= $index+1 ?></td>
                <td><?= htmlspecialchars($s['name']) ?></td>
                <td><?= htmlspecialchars($s['roll']) ?></td>
                <td><?= htmlspecialchars($s['department']) ?></td>
                <td><?= htmlspecialchars($s['phone']) ?></td>
                <td>
                  <?php if(!empty($s['image']) && file_exists(__DIR__.'/../uploads/students/'.$s['image'])): ?>
                    <img src="../uploads/students/<?=htmlspecialchars($s['image'])?>" 
                         class="img-thumb" data-bs-toggle="modal" data-bs-target="#imageModal" data-img="../uploads/students/<?=htmlspecialchars($s['image'])?>">
                  <?php else: ?>-<?php endif; ?>
                </td>
                <td class="text-end">
                  <a href="#" class="btn btn-sm btn-secondary view-btn" 
                     data-bs-toggle="modal" data-bs-target="#studentModal"
                     data-student='<?=json_encode($s)?>'>View</a>
                  <a href="/SMS/public/edit-student.php?id=<?= urlencode($s['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a class="btn btn-sm btn-danger" href="delete-student.php?id=<?=urlencode($s['id'])?>" onclick="return confirm('Delete this student?')">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <img id="modalImage" src="" class="img-fluid rounded shadow" alt="Student Image">
    </div>
  </div>
</div>

<!-- Student Data Modal -->
<div class="modal fade" id="studentModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Student Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-borderless">
          <tbody>
            <tr><th>Name</th><td id="s_name"></td></tr>
            <tr><th>Roll</th><td id="s_roll"></td></tr>
            <tr><th>Registration</th><td id="s_registration"></td></tr>
            <tr><th>Department</th><td id="s_department"></td></tr>
            <tr><th>Semester</th><td id="s_semester"></td></tr>
            <tr><th>Session</th><td id="s_session"></td></tr>
            <tr><th>Shift</th><td id="s_shift"></td></tr>
            <tr><th>Father Name</th><td id="s_father_name"></td></tr>
            <tr><th>Mother Name</th><td id="s_mother_name"></td></tr>
            <tr><th>Father Phone</th><td id="s_father_phone"></td></tr>
            <tr><th>Mother Phone</th><td id="s_mother_phone"></td></tr>
            <tr><th>Present Address</th><td id="s_present_address"></td></tr>
            <tr><th>Permanent Address</th><td id="s_permanent_address"></td></tr>
            <tr><th>Phone</th><td id="s_phone"></td></tr>
            <tr><th>Result</th><td id="s_result"></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Thumbnail click for image modal
  const imageModal = document.getElementById('imageModal');
  imageModal.addEventListener('show.bs.modal', event => {
    const thumb = event.relatedTarget;
    const imgSrc = thumb.getAttribute('data-img');
    document.getElementById('modalImage').setAttribute('src', imgSrc);
  });

  // View button click for student modal
  const studentModal = document.getElementById('studentModal');
  studentModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const student = JSON.parse(button.getAttribute('data-student'));

    document.getElementById('s_name').innerText = student.name;
    document.getElementById('s_roll').innerText = student.roll;
    document.getElementById('s_registration').innerText = student.registration;
    document.getElementById('s_department').innerText = student.department;
    document.getElementById('s_semester').innerText = student.semester;
    document.getElementById('s_session').innerText = student.session;
    document.getElementById('s_shift').innerText = student.shift;
    document.getElementById('s_father_name').innerText = student.father_name;
    document.getElementById('s_mother_name').innerText = student.mother_name;
    document.getElementById('s_father_phone').innerText = student.father_phone;
    document.getElementById('s_mother_phone').innerText = student.mother_phone;
    document.getElementById('s_present_address').innerText = student.present_address;
    document.getElementById('s_permanent_address').innerText = student.permanent_address;
    document.getElementById('s_phone').innerText = student.phone;
    document.getElementById('s_result').innerText = student.result;

    // Image
    // let imgHtml = '-';
    // if(student.image) {
    //   imgHtml = `<img src="../uploads${student.image}" class="img-fluid" />`;
    // }
    // document.getElementById('s_image').innerHTML = imgHtml;

    // // Registration Paper
    // let regHtml = '-';
    // if(student.reg_paper_image) {
    //   regHtml = `<img src="../uploads${student.reg_paper_image}" class="img-fluid" />`;
    // }
    document.getElementById('s_reg_paper').innerHTML = regHtml;
  });
</script>
</body>
</html>
