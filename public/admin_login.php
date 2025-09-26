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
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Inter', sans-serif; }
    .error { color: red; text-align: center; margin-bottom: 10px; font-weight: bold; }
</style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-br from-purple-500 via-pink-600 to-red-500 p-4 sm:p-6">

<div class="bg-white p-12 rounded-3xl shadow-2xl w-full max-w-sm sm:max-w-md hover:scale-105 duration-300">
    <div class="flex flex-col items-center mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-pink-600 mb-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
        </svg>
        <h2 class="text-3xl font-bold text-center text-gray-800">Admin Login</h2>
    </div>

    <?php if($error): ?>
        <div class="error"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>

    <form method="post" class="space-y-6">
        <div>
            <label for="admin-username" class="block text-gray-700 font-medium mb-2">Username</label>
            <input type="text" id="admin-username" name="username" placeholder="Enter Username"
                value="<?=htmlspecialchars($_POST['username'] ?? '')?>"
                class="w-full p-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent" required>
        </div>
        <div>
            <label for="admin-password" class="block text-gray-700 font-medium mb-2">Password</label>
            <input type="password" id="admin-password" name="password" placeholder="Enter Password"
                class="w-full p-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent" required>
        </div>
        <button type="submit"
            class="w-full bg-pink-600 text-white py-3 rounded-xl font-semibold hover:bg-pink-700 transition-colors transform hover:scale-105 shadow-lg">
            Login
        </button>
    </form>

    <div class="text-center mt-6 text-sm">
        <a href="index.php" class="text-pink-600 hover:underline font-medium">Back to Homepage</a>
    </div>
</div>

</body>
</html>
