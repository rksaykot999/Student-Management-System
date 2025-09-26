<?php
include_once '../includes/db.php';
session_start();
if(!isset($_SESSION['admin_id'])){ header("Location: admin_login.php"); exit(); }

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM students WHERE id=?");
$stmt->execute([$id]);
header("Location: students_view.php");
exit();

