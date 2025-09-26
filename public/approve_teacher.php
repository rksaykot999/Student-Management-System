<?php
// public/approve_teacher.php
session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($_SESSION['admin_id'])) { header('Location: admin_login.php'); exit; }

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: admin_dashboard.php'); exit; }

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT * FROM pending_teachers WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $p = $stmt->fetch();

    if (!$p) {
        $pdo->rollBack();
        header('Location: admin_dashboard.php?msg=' . urlencode('Pending teacher not found.'));
        exit;
    }

    // check duplicate email
    $chk = $pdo->prepare("SELECT COUNT(*) FROM teachers WHERE email = ?");
    $chk->execute([$p['email']]);
    if ($chk->fetchColumn() > 0) {
        $del = $pdo->prepare("DELETE FROM pending_teachers WHERE id = ?");
        $del->execute([$id]);
        $pdo->commit();
        header('Location: admin_dashboard.php?msg=' . urlencode('Duplicate email found — pending removed.'));
        exit;
    }

    $ins = $pdo->prepare("INSERT INTO teachers (name,email,password,department,shift,phone,qualification,designation,image)
                          VALUES (?,?,?,?,?,?,?,?,?)");
    $ins->execute([
        $p['name'],$p['email'],$p['password'],$p['department'],$p['shift'],$p['phone'],$p['qualification'],$p['designation'],$p['image']
    ]);

    $del = $pdo->prepare("DELETE FROM pending_teachers WHERE id = ?");
    $del->execute([$id]);

    $pdo->commit();
    header('Location: admin_dashboard.php?msg=' . urlencode('Teacher approved successfully.'));
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    header('Location: admin_dashboard.php?msg=' . urlencode('Error: ' . $e->getMessage()));
    exit;
}

