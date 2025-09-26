<?php
// public/notice_list.php (fixed)
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

$notices = [];
$fallback_note = false;

try {
    // try join with admins (works only if notices.created_by exists)
    $sql = "SELECT n.*, a.name AS admin_name
            FROM notices n
            LEFT JOIN admins a ON n.created_by = a.id
            ORDER BY n.created_at DESC";
    $stmt = $pdo->query($sql);
    $notices = $stmt->fetchAll();
} catch (PDOException $e) {
    // fallback: maybe column doesn't exist — just fetch notices
    $fallback_note = true;
    $stmt = $pdo->query("SELECT * FROM notices ORDER BY created_at DESC");
    $notices = $stmt->fetchAll();
}
?>
<!doctype html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <title>Notices - Admin</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body{background:#f7f8fb}</style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Notices</h3>
    <div>
      <a href="admin_dashboard.php" class="btn btn-outline-secondary btn-sm">Dashboard</a>
      <a href="notice_add.php" class="btn btn-primary btn-sm">Add New Notice</a>
    </div>
  </div>



  <?php if(empty($notices)): ?>
    <div class="alert alert-info">কোনো নোটিস নেই।</div>
  <?php else: ?>
    <div class="card shadow-sm">
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
             
              <th>Title</th>
              <th>Excerpt</th>
              <th>Created By</th>
              <th>Created At</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($notices as $n): ?>
              <tr>
               
                <td><?=htmlspecialchars($n['title'] ?? '')?></td>
                <td style="max-width:360px;">
                  <?=nl2br(htmlspecialchars(strlen($n['content'] ?? '') > 200 ? substr($n['content'],0,200) . '...' : ($n['content'] ?? '')) )?>
                </td>
                <td>
                  <?php
                    // যদি join সফল হয়ে থাকে, admin_name থাকবে
                    if (!empty($n['admin_name'])) {
                        echo htmlspecialchars($n['admin_name']);
                    } elseif (array_key_exists('created_by', $n) && !empty($n['created_by'])) {
                        echo 'Admin ID: ' . htmlspecialchars($n['created_by']);
                    } else {
                        echo 'Admin';
                    }
                  ?>
                </td>
                <td><?=htmlspecialchars($n['created_at'] ?? '')?></td>
                <td class="text-end">
                  <a class="btn btn-sm btn-success" href="notice_view.php?id=<?=urlencode($n['id'] ?? '')?>">View</a>
                  <a class="btn btn-sm btn-warning" href="notice_edit.php?id=<?=urlencode($n['id'] ?? '')?>">Edit</a>
                  <a class="btn btn-sm btn-danger" href="notice_delete.php?id=<?=urlencode($n['id'] ?? '')?>" onclick="return confirm('Delete this notice?')">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>

</div>
</body>
</html>
