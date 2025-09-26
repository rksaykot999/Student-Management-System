<?php
// public/approve_student.php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['admin_id'])) { header('Location: admin_login.php'); exit; }

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: admin_dashboard.php'); exit; }

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT * FROM pending_students WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $p = $stmt->fetch();

    if (!$p) {
        $pdo->rollBack();
        header('Location: admin_dashboard.php?msg=' . urlencode('Pending student not found.'));
        exit;
    }

    // check duplicate in students table (roll or registration or phone/email)
    $chk = $pdo->prepare("SELECT COUNT(*) FROM students WHERE roll = ? OR registration = ?");
    $chk->execute([$p['roll'], $p['registration']]);
    if ($chk->fetchColumn() > 0) {
        // remove pending to avoid clog (or you may keep it) - here we delete and notify
        $del = $pdo->prepare("DELETE FROM pending_students WHERE id = ?");
        $del->execute([$id]);
        $pdo->commit();
        header('Location: admin_dashboard.php?msg=' . urlencode('Duplicate found — pending entry removed.'));
        exit;
    }

    // insert into students (ensure columns exist in your main students table)
    $ins = $pdo->prepare("INSERT INTO students
      (name, roll, registration, department, semester, session, shift, father_name, mother_name, father_phone, mother_phone, present_address, permanent_address, phone, image)
      VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $ins->execute([
        $p['name'],$p['roll'],$p['registration'],$p['department'],$p['semester'],$p['session'],$p['shift'],
        $p['father_name'],$p['mother_name'],$p['father_phone'],$p['mother_phone'],$p['present_address'],$p['permanent_address'],$p['phone'],
        $p['image']
    ]);

    // delete pending
    $del = $pdo->prepare("DELETE FROM pending_students WHERE id = ?");
    $del->execute([$id]);

    $pdo->commit();
    header('Location: admin_dashboard.php?msg=' . urlencode('Student approved successfully.'));
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    header('Location: admin_dashboard.php?msg=' . urlencode('Error: ' . $e->getMessage()));
    exit;
}

