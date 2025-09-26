<?php
// public/admin_login.php
session_start();
require_once __DIR__ . '/../includes/db.php';

// If already logged in -> redirect
if (isset($_SESSION['admin_id'])) {
    header('Location: admin_dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username এবং Password উভয়ই দরকার।';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        // যদি admin পাওয়া যায় এবং plain-text password মিলে যায়:
        if ($admin && isset($admin['password']) && hash_equals((string)$admin['password'], (string)$password)) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'] ?? $admin['username'];
            header('Location: admin_dashboard.php');
            exit;
        } else {
            $error = 'Invalid credentials। পুনরায় চেষ্টা করো।';
        }
    }
}
?>
<!doctype html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f4f6f9; }
    .login-card { max-width:420px; margin:80px auto; }
  </style>
</head>
<body>
  <div class="card login-card shadow-sm">
    <div class="card-body p-4">
      <h4 class="mb-3">Admin Login</h4>
      <?php if($error): ?>
        <div class="alert alert-danger"><?=htmlspecialchars($error)?></div>
      <?php endif; ?>
      <form method="post" novalidate>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input name="username" class="form-control" value="<?=htmlspecialchars($_POST['username'] ?? '')?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input name="password" type="password" class="form-control" required>
        </div>
        <div class="d-grid">
          <button class="btn btn-primary">Login</button>
          <a href="index.php" class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline">Back to
                Homepage</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>

