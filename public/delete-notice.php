<?php
include_once '../includes/db.php';
session_start();

if(!isset($_GET['id'])){
    header("Location: teacher-dashboard.php");
    exit();
}

$notice_id = $_GET['id'];

// Delete notice
$stmt = $pdo->prepare("DELETE FROM notices WHERE id=?");
$stmt->execute([$notice_id]);

header("Location: teacher-dashboard.php");
exit();

