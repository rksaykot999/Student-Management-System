<?php
// get_subjects.php
include_once '../includes/db.php';
if(!isset($_GET['department']) || !isset($_GET['semester'])){
    echo json_encode([]);
    exit;
}
$dept = $_GET['department'];
$sem = $_GET['semester'];

$stmt = $pdo->prepare("SELECT id, name FROM subjects WHERE department=? AND semester=? ORDER BY name ASC");
$stmt->execute([$dept, $sem]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);

