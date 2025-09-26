<?php
include_once '../includes/db.php';
session_start();
if(!isset($_SESSION['admin_id'])) header("Location: admin_login.php");

if(isset($_GET['id'])){
    $stmt = $pdo->prepare("DELETE FROM teachers WHERE id=?");
    $stmt->execute([$_GET['id']]);
}
header("Location: teachers_view.php");
exit();

