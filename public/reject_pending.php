<?php
// public/reject_pending.php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['admin_id'])) { header('Location: admin_login.php'); exit; }

$type = $_GET['type'] ?? '';
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: admin_dashboard.php'); exit; }

if ($type === 'student') {
    $stmt = $pdo->prepare("DELETE FROM pending_students WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: admin_dashboard.php?msg=' . urlencode('Pending student rejected/removed.'));
    exit;
} elseif ($type === 'teacher') {
    $stmt = $pdo->prepare("DELETE FROM pending_teachers WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: admin_dashboard.php?msg=' . urlencode('Pending teacher rejected/removed.'));
    exit;
} else {
    header('Location: admin_dashboard.php');
    exit;
}

