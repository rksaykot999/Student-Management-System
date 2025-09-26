<?php
include_once '../includes/db.php';
session_start();

if(!isset($_SESSION['teacher_id'])){
    header("Location: teacher-dashboard.php");
    exit();
}

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM student_notices WHERE id=?");
    $stmt->execute([$id]);
    header("Location: teacher-dashboard.php?msg=Notice+deleted+successfully");
    exit();
} else {
    header("Location: teacher-dashboard.php");
    exit();
}
?>

