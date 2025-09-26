<?php
// public/admin_dashboard.php
session_start();
require_once __DIR__ . '/../includes/db.php';

// Authentication check
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Handle actions
$action = $_GET['action'] ?? '';
$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? 0;
$msg = $_GET['msg'] ?? '';
$tab = $_GET['tab'] ?? 'dashboard';
$subtab = $_GET['subtab'] ?? 'students';
$edit_id = $_GET['edit_id'] ?? null;
$add_new = $_GET['add_new'] ?? '';

// Process actions
if ($action && $id) {
    switch($action) {
        case 'approve':
            if ($type === 'student') {
                approveStudent($id);
            } elseif ($type === 'teacher') {
                approveTeacher($id);
            }
            break;
        case 'reject':
            if ($type === 'student' || $type === 'teacher') {
                rejectPending($type, $id);
            }
            break;
        case 'delete':
            if ($type === 'student') {
                deleteStudent($id);
            } elseif ($type === 'teacher') {
                deleteTeacher($id);
            } elseif ($type === 'notice') {
                deleteNotice($id);
            }
            break;
    }
}

// Function to approve student
function approveStudent($id) {
    global $pdo;
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("SELECT * FROM pending_students WHERE id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch();
        
        if (!$student) {
            throw new Exception("Student not found");
        }
        
        // Check for duplicates
        $check = $pdo->prepare("SELECT COUNT(*) FROM students WHERE roll = ? OR registration = ?");
        $check->execute([$student['roll'], $student['registration']]);
        
        if ($check->fetchColumn() > 0) {
            $pdo->prepare("DELETE FROM pending_students WHERE id = ?")->execute([$id]);
            header('Location: admin_dashboard.php?msg=' . urlencode('Duplicate found - entry removed'));
            exit;
        }
        
        // Insert into students table
        $insert = $pdo->prepare("INSERT INTO students (name, roll, registration, department, semester, session, shift, father_name, mother_name, father_phone, mother_phone, present_address, permanent_address, phone, image, reg_paper_image) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $insert->execute([
            $student['name'], $student['roll'], $student['registration'], $student['department'],
            $student['semester'], $student['session'], $student['shift'], $student['father_name'],
            $student['mother_name'], $student['father_phone'], $student['mother_phone'],
            $student['present_address'], $student['permanent_address'], $student['phone'],
            $student['image'], $student['reg_paper_image']
        ]);
        
        // Remove from pending
        $pdo->prepare("DELETE FROM pending_students WHERE id = ?")->execute([$id]);
        $pdo->commit();
        
        header('Location: admin_dashboard.php?msg=' . urlencode('Student approved successfully'));
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        header('Location: admin_dashboard.php?msg=' . urlencode('Error: ' . $e->getMessage()));
        exit;
    }
}

// Function to approve teacher
function approveTeacher($id) {
    global $pdo;
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("SELECT * FROM pending_teachers WHERE id = ?");
        $stmt->execute([$id]);
        $teacher = $stmt->fetch();
        
        if (!$teacher) {
            throw new Exception("Teacher not found");
        }
        
        // Check for duplicates
        $check = $pdo->prepare("SELECT COUNT(*) FROM teachers WHERE email = ?");
        $check->execute([$teacher['email']]);
        
        if ($check->fetchColumn() > 0) {
            $pdo->prepare("DELETE FROM pending_teachers WHERE id = ?")->execute([$id]);
            header('Location: admin_dashboard.php?msg=' . urlencode('Duplicate email found - entry removed'));
            exit;
        }
        
        // Insert into teachers table
        $insert = $pdo->prepare("INSERT INTO teachers (name, email, password, department, shift, phone, qualification, designation, image) VALUES (?,?,?,?,?,?,?,?,?)");
        $insert->execute([
            $teacher['name'], $teacher['email'], $teacher['password'], $teacher['department'],
            $teacher['shift'], $teacher['phone'], $teacher['qualification'], $teacher['designation'],
            $teacher['image']
        ]);
        
        // Remove from pending
        $pdo->prepare("DELETE FROM pending_teachers WHERE id = ?")->execute([$id]);
        $pdo->commit();
        
        header('Location: admin_dashboard.php?msg=' . urlencode('Teacher approved successfully'));
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        header('Location: admin_dashboard.php?msg=' . urlencode('Error: ' . $e->getMessage()));
        exit;
    }
}

// Function to reject pending registration
function rejectPending($type, $id) {
    global $pdo;
    
    if ($type === 'student') {
        $pdo->prepare("DELETE FROM pending_students WHERE id = ?")->execute([$id]);
        header('Location: admin_dashboard.php?msg=' . urlencode('Student registration rejected'));
    } elseif ($type === 'teacher') {
        $pdo->prepare("DELETE FROM pending_teachers WHERE id = ?")->execute([$id]);
        header('Location: admin_dashboard.php?msg=' . urlencode('Teacher registration rejected'));
    }
    exit;
}

// Function to delete student
function deleteStudent($id) {
    global $pdo;
    $pdo->prepare("DELETE FROM students WHERE id = ?")->execute([$id]);
    header('Location: admin_dashboard.php?msg=' . urlencode('Student deleted successfully'));
    exit;
}

// Function to delete teacher
function deleteTeacher($id) {
    global $pdo;
    $pdo->prepare("DELETE FROM teachers WHERE id = ?")->execute([$id]);
    header('Location: admin_dashboard.php?msg=' . urlencode('Teacher deleted successfully'));
    exit;
}

// Function to delete notice
function deleteNotice($id) {
    global $pdo;
    $pdo->prepare("DELETE FROM notices WHERE id = ?")->execute([$id]);
    header('Location: admin_dashboard.php?msg=' . urlencode('Notice deleted successfully'));
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = "";
    $success = "";
    
    // Add/Edit Student
    if (isset($_POST['student_form'])) {
        $name = trim($_POST['name']);
        $roll = trim($_POST['roll']);
        $registration = trim($_POST['registration']);
        $department = trim($_POST['department']);
        $semester = trim($_POST['semester']);
        $sessionName = trim($_POST['session']);
        $shift = trim($_POST['shift']);
        $father_name = trim($_POST['father_name']);
        $mother_name = trim($_POST['mother_name']);
        $father_phone = trim($_POST['father_phone']);
        $mother_phone = trim($_POST['mother_phone']);
        $present_address = trim($_POST['present_address']);
        $permanent_address = trim($_POST['permanent_address']);
        $phone = trim($_POST['phone']);
        $result = trim($_POST['result']);

        // Uploads directory
        $uploadDir = __DIR__ . '/../uploads/students/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Student image upload
        $img_name = NULL;
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
            $filename = basename($_FILES['image']['name']);
            $filename = preg_replace("/[^A-Za-z0-9\.\-_]/", "", $filename);
            $img_name = time() . '_' . $filename;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $img_name);
        }

        // Registration paper upload
        $reg_paper = NULL;
        if (!empty($_FILES['reg_paper_image']['name']) && $_FILES['reg_paper_image']['error'] === 0) {
            $filename = basename($_FILES['reg_paper_image']['name']);
            $filename = preg_replace("/[^A-Za-z0-9\.\-_]/", "", $filename);
            $reg_paper = time() . '_' . $filename;
            move_uploaded_file($_FILES['reg_paper_image']['tmp_name'], $uploadDir . $reg_paper);
        }

        if ($edit_id) {
            // Update existing student
            $stmt = $pdo->prepare("
                UPDATE students SET 
                name=?, roll=?, registration=?, department=?, semester=?, session=?, shift=?, 
                father_name=?, mother_name=?, father_phone=?, mother_phone=?, present_address=?, 
                permanent_address=?, phone=?, result=?, image=?, reg_paper_image=?
                WHERE id=?
            ");
            
            // Get current image names to preserve if not updated - FIXED THIS SECTION
            $current_stmt = $pdo->prepare("SELECT image, reg_paper_image FROM students WHERE id=?");
            $current_stmt->execute([$edit_id]);
            $current_data = $current_stmt->fetch();
            
            $img_name = $img_name ?: ($current_data ? $current_data['image'] : NULL);
            $reg_paper = $reg_paper ?: ($current_data ? $current_data['reg_paper_image'] : NULL);
            
            if ($stmt->execute([
                $name, $roll, $registration, $department, $semester, $sessionName,
                $shift, $father_name, $mother_name, $father_phone, $mother_phone,
                $present_address, $permanent_address, $phone, $result,
                $img_name, $reg_paper, $edit_id
            ])) {
                $success = "✅ Student updated successfully!";
                $edit_id = null; // Reset edit mode
            } else {
                $error = "❌ Failed to update student!";
            }
        } else {
            // Insert new student
            $stmt = $pdo->prepare("
                INSERT INTO students
                (name, roll, registration, department, semester, session, shift, father_name, mother_name, father_phone, mother_phone, present_address, permanent_address, phone, result, image, reg_paper_image) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
            ");

            if ($stmt->execute([
                $name, $roll, $registration, $department, $semester, $sessionName,
                $shift, $father_name, $mother_name, $father_phone, $mother_phone,
                $present_address, $permanent_address, $phone, $result,
                $img_name, $reg_paper
            ])) {
                $success = "✅ Student added successfully!";
            } else {
                $error = "❌ Failed to add student!";
            }
        }
    }
    
    // Add/Edit Teacher
    if (isset($_POST['teacher_form'])) {
        $name = trim($_POST['name']);
        $designation = trim($_POST['designation']);
        $department = trim($_POST['department']);
        $shift = trim($_POST['shift']);
        $qualification = trim($_POST['qualification']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $image_name = null;

        // Teacher image upload
        if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
            $filename = basename($_FILES['image']['name']);
            $filename = preg_replace("/[^A-Za-z0-9\.\-_]/", "", $filename);
            $image_name = time().'_'.$filename;

           $uploadDir = __DIR__ . '/../uploads/teachers/';
                if(!is_dir($uploadDir)){
                    mkdir($uploadDir, 0777, true);
                }
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir.$image_name);
        }

        if ($edit_id) {
            // Update teacher - FIXED THIS SECTION TOO
            if (!$image_name) {
                // Keep current image if not updating
                $current_stmt = $pdo->prepare("SELECT image FROM teachers WHERE id=?");
                $current_stmt->execute([$edit_id]);
                $current_data = $current_stmt->fetch();
                $image_name = $current_data ? $current_data['image'] : NULL;
            }
            
            $stmt = $pdo->prepare("
                UPDATE teachers SET 
                name=?, designation=?, department=?, shift=?, qualification=?, phone=?, email=?, password=?, image=?
                WHERE id=?
            ");
            
            if ($stmt->execute([$name, $designation, $department, $shift, $qualification, $phone, $email, $password, $image_name, $edit_id])) {
                $success = "✅ Teacher updated successfully!";
                $edit_id = null;
            } else {
                $error = "❌ Failed to update teacher!";
            }
        } else {
            // Insert new teacher
            $stmt = $pdo->prepare("INSERT INTO teachers (name, designation, department, shift, qualification, phone, email, password, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $designation, $department, $shift, $qualification, $phone, $email, $password, $image_name])) {
                $success = "✅ Teacher added successfully!";
            } else {
                $error = "❌ Failed to add teacher!";
            }
        }
    }
    
    // Add/Edit Notice
    if (isset($_POST['notice_form'])) {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if ($title === '' || $content === '') {
            $error = "Title and Content are required!";
        } else {
            if ($edit_id) {
                // Update notice
                $stmt = $pdo->prepare("UPDATE notices SET title=?, content=? WHERE id=?");
                if ($stmt->execute([$title, $content, $edit_id])) {
                    $success = "✅ Notice updated successfully!";
                    $edit_id = null;
                } else {
                    $error = "❌ Failed to update notice!";
                }
            } else {
                // Insert new notice
                $stmt = $pdo->prepare("INSERT INTO notices(title, content) VALUES (?, ?)");
                if ($stmt->execute([$title, $content])) {
                    $success = "✅ Notice created successfully!";
                } else {
                    $error = "❌ Failed to create notice!";
                }
            }
        }
    }
    
    // Set message for redirect
    if ($success) {
        header('Location: admin_dashboard.php?tab=' . $tab . '&msg=' . urlencode($success));
        exit;
    }
}

// Fetch data for editing
$edit_student = null;
$edit_teacher = null;
$edit_notice = null;

if ($edit_id) {
    if ($tab === 'students') {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id=?");
        $stmt->execute([$edit_id]);
        $edit_student = $stmt->fetch();
    } elseif ($tab === 'teachers') {
        $stmt = $pdo->prepare("SELECT * FROM teachers WHERE id=?");
        $stmt->execute([$edit_id]);
        $edit_teacher = $stmt->fetch();
    } elseif ($tab === 'notices') {
        $stmt = $pdo->prepare("SELECT * FROM notices WHERE id=?");
        $stmt->execute([$edit_id]);
        $edit_notice = $stmt->fetch();
    }
}

// Fetch dashboard statistics
$stats = [];
$stats['pending_students'] = $pdo->query("SELECT COUNT(*) FROM pending_students")->fetchColumn();
$stats['pending_teachers'] = $pdo->query("SELECT COUNT(*) FROM pending_teachers")->fetchColumn();
$stats['students'] = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$stats['teachers'] = $pdo->query("SELECT COUNT(*) FROM teachers")->fetchColumn();
$stats['notices'] = $pdo->query("SELECT COUNT(*) FROM notices")->fetchColumn();

// Fetch latest records
$pendingStudents = $pdo->query("SELECT * FROM pending_students ORDER BY created_at DESC LIMIT 5")->fetchAll();
$pendingTeachers = $pdo->query("SELECT * FROM pending_teachers ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recentStudents = $pdo->query("SELECT id, name, roll, department, semester FROM students ORDER BY id DESC LIMIT 5")->fetchAll();
$recentTeachers = $pdo->query("SELECT id, name, email, department, designation FROM teachers ORDER BY id DESC LIMIT 5")->fetchAll();
$recentNotices = $pdo->query("SELECT id, title, created_at FROM notices ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Fetch all data for management tabs
$students = $pdo->query("SELECT * FROM students ORDER BY roll ASC")->fetchAll();
$teachers = $pdo->query("
    SELECT * FROM teachers 
    ORDER BY 
        CASE 
            WHEN designation = 'Chief Instructor & Head of the Department' THEN 1
            WHEN designation = 'Instructor' THEN 2
            WHEN designation = 'Workshop Super' THEN 3
            WHEN designation = 'Junior Instructor' THEN 4
            ELSE 5
        END ASC
")->fetchAll();
$notices = $pdo->query("SELECT * FROM notices ORDER BY created_at DESC")->fetchAll();
$pendingStudentsAll = $pdo->query("SELECT * FROM pending_students ORDER BY roll ASC")->fetchAll();
$pendingTeachersAll = $pdo->query("
    SELECT * FROM pending_teachers
    ORDER BY
    CASE
        WHEN designation = 'Chief Instructor & Head of the Department' THEN 1
        WHEN designation = 'Instructor' THEN 2
        WHEN designation = 'Workshop Super' THEN 3
        WHEN designation = 'Junior Instructor' THEN 4
        ELSE 5
    END ASC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --info: #4895ef;
            --warning: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --sidebar-width: 260px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fb;
            color: #333;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary) 0%, var(--secondary) 100%);
            color: #fff;
            z-index: 1000;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .sidebar-brand {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sidebar-brand i {
            font-size: 24px;
        }
        
        .sidebar-nav {
            padding: 15px 0;
        }
        
        .sidebar-nav a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        
        .sidebar-nav a i {
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
        }
        
        /* Header */
        .header {
            background: #fff;
            border-radius: 10px;
            padding: 15px 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-title h1 {
            font-weight: 600;
            margin: 0;
            color: var(--dark);
            font-size: 1.8rem;
        }
        
        .header-title p {
            margin: 0;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 4px solid var(--primary);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card.pending {
            border-left-color: #ffc107;
        }
        
        .stat-card.approved {
            border-left-color: #28a745;
        }
        
        .stat-card.notice {
            border-left-color: #17a2b8;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }
        
        .stat-card.pending .stat-icon {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
        }
        
        .stat-card.approved .stat-icon {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }
        
        .stat-card.notice .stat-icon {
            background: rgba(23, 162, 184, 0.2);
            color: #17a2b8;
        }
        
        .stat-number {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        /* Content Cards */
        .content-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            overflow: hidden;
        }
        
        .card-header {
            background: #fff;
            border-bottom: 1px solid #eaeaea;
            padding: 18px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
        }
        
        .card-body {
            padding: 0;
        }
        
        /* Tables */
        .table-container {
            overflow-x: auto;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th {
            background: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            font-weight: 500;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
        }
        
        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eaeaea;
        }
        
        .data-table tr:last-child td {
            border-bottom: none;
        }
        
        .data-table tr:hover {
            background: #f8f9fa;
        }
        
        /* Buttons */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 6px 12px;
            font-size: 0.85rem;
        }
        
        .btn-view-all {
            background: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
        }
        
        .btn-view-all:hover {
            background: var(--primary);
            color: white;
        }
        
        /* Alert */
        .alert-custom {
            border-radius: 8px;
            border: none;
            padding: 12px 20px;
            margin-bottom: 20px;
        }
        
        /* Tabs */
        .nav-tabs .nav-link {
            border: none;
            padding: 12px 20px;
            color: #6c757d;
            font-weight: 500;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary);
            border-bottom: 3px solid var(--primary);
            background: transparent;
        }
        
        /* Modal */
        .modal-img {
            max-width: 100%;
            border-radius: 8px;
        }
        
        /* Form Styles */
        .form-container {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: border 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
        }
        
        .btn-primary {
            background: var(--primary);
            border: none;
            padding: 10px 20px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background: var(--secondary);
        }
        
        .current-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin-bottom: 10px;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }
            
            .sidebar-brand span, .sidebar-nav a span {
                display: none;
            }
            
            .sidebar-nav a {
                justify-content: center;
                padding: 12px;
            }
            
            .main-content {
                margin-left: 70px;
            }
        }
        
        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .form-container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-university"></i>
            <span>Admin Panel</span>
        </div>
        <div class="sidebar-nav">
            <a href="?tab=dashboard" class="<?= $tab === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="?tab=students" class="<?= $tab === 'students' ? 'active' : '' ?>">
                <i class="fas fa-user-graduate"></i>
                <span>All Students</span>
            </a>
            <a href="?tab=teachers" class="<?= $tab === 'teachers' ? 'active' : '' ?>">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>All Teachers</span>
            </a>
            <a href="?tab=pending" class="<?= $tab === 'pending' ? 'active' : '' ?>">
                <i class="fas fa-clock"></i>
                <span>Pending Requests</span>
            </a>
            <a href="?tab=notices" class="<?= $tab === 'notices' ? 'active' : '' ?>">
                <i class="fas fa-bullhorn"></i>
                <span>Notices</span>
            </a>
            <a href="admin-logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="header-title">
                <h1>
                    <?php
                    switch($tab) {
                        case 'students': echo 'Student Management'; break;
                        case 'teachers': echo 'Teacher Management'; break;
                        case 'pending': echo 'Pending Registrations'; break;
                        case 'notices': echo 'Notice Management'; break;
                        default: echo 'Dashboard Overview';
                    }
                    ?>
                </h1>
                <p>Welcome back, <?= htmlspecialchars($_SESSION['admin_name']) ?>. 
                   <?= $tab === 'dashboard' ? "Here's what's happening today." : "Manage your system efficiently." ?>
                </p>
            </div>
            <div class="user-info">
                <div class="user-avatar">
                    <?= strtoupper(substr(htmlspecialchars($_SESSION['admin_name']), 0, 1)) ?>
                </div>
                <div>
                    <div class="fw-bold"><?= htmlspecialchars($_SESSION['admin_name']) ?></div>
                    <small class="text-muted">Administrator</small>
                </div>
            </div>
        </div>

        <!-- Message Alert -->
        <?php if($msg): ?>
            <div class="alert alert-success alert-custom">
                <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($msg) ?>
            </div>
        <?php endif; ?>

        <!-- Dashboard Tab -->
        <?php if($tab === 'dashboard'): ?>
            <!-- Stats Cards -->
            <div class="stats-container">
                <div class="stat-card pending">
                    <div class="stat-icon">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="stat-number"><?= $stats['pending_students'] ?></div>
                    <div class="stat-label">Pending Students</div>
                </div>
                
                <div class="stat-card pending">
                    <div class="stat-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-number"><?= $stats['pending_teachers'] ?></div>
                    <div class="stat-label">Pending Teachers</div>
                </div>
                
                <div class="stat-card approved">
                    <div class="stat-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-number"><?= $stats['students'] ?></div>
                    <div class="stat-label">Approved Students</div>
                </div>
                
                <div class="stat-card approved">
                    <div class="stat-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="stat-number"><?= $stats['teachers'] ?></div>
                    <div class="stat-label">Approved Teachers</div>
                </div>
                
                <div class="stat-card notice">
                    <div class="stat-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div class="stat-number"><?= $stats['notices'] ?></div>
                    <div class="stat-label">Active Notices</div>
                </div>
            </div>

            <!-- Pending Students -->
            <div class="content-card">
                <div class="card-header">
                    <h3><i class="fas fa-user-clock me-2"></i>Pending Student Registrations</h3>
                    <a href="?tab=pending" class="btn btn-view-all">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Roll</th>
                                    <th>Department</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(empty($pendingStudents)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        No pending student registrations
                                    </td>
                                </tr>
                            <?php else: foreach($pendingStudents as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['name']) ?></td>
                                    <td><?= htmlspecialchars($p['roll']) ?></td>
                                    <td><?= htmlspecialchars($p['department']) ?></td>
                                    <td><?= date('M j, Y', strtotime($p['created_at'])) ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-success" href="?action=approve&type=student&id=<?= $p['id'] ?>" onclick="return confirm('Approve this student?')">
                                            <i class="fas fa-check me-1"></i>Approve
                                        </a>
                                        <a class="btn btn-sm btn-danger" href="?action=reject&type=student&id=<?= $p['id'] ?>" onclick="return confirm('Reject this registration?')">
                                            <i class="fas fa-times me-1"></i>Reject
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pending Teachers -->
            <div class="content-card">
                <div class="card-header">
                    <h3><i class="fas fa-chalkboard-teacher me-2"></i>Pending Teacher Registrations</h3>
                    <a href="?tab=pending" class="btn btn-view-all">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Department</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(empty($pendingTeachers)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        No pending teacher registrations
                                    </td>
                                </tr>
                            <?php else: foreach($pendingTeachers as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['name']) ?></td>
                                    <td><?= htmlspecialchars($p['email']) ?></td>
                                    <td><?= htmlspecialchars($p['department']) ?></td>
                                    <td><?= date('M j, Y', strtotime($p['created_at'])) ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-success" href="?action=approve&type=teacher&id=<?= $p['id'] ?>" onclick="return confirm('Approve this teacher?')">
                                            <i class="fas fa-check me-1"></i>Approve
                                        </a>
                                        <a class="btn btn-sm btn-danger" href="?action=reject&type=teacher&id=<?= $p['id'] ?>" onclick="return confirm('Reject this registration?')">
                                            <i class="fas fa-times me-1"></i>Reject
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="content-card">
                        <div class="card-header">
                            <h3><i class="fas fa-user-graduate me-2"></i>Recent Students</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-container">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Roll</th>
                                            <th>Department</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($recentStudents)): ?>
                                            <tr>
                                                <td colspan="3" class="text-center py-4 text-muted">
                                                    <i class="fas fa-user-graduate fa-2x mb-2 d-block"></i>
                                                    No students found
                                                </td>
                                            </tr>
                                        <?php else: foreach($recentStudents as $s): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($s['name']) ?></td>
                                                <td><?= htmlspecialchars($s['roll']) ?></td>
                                                <td><?= htmlspecialchars($s['department']) ?></td>
                                            </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="content-card">
                        <div class="card-header">
                            <h3><i class="fas fa-user-tie me-2"></i>Recent Teachers</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-container">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Department</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($recentTeachers)): ?>
                                            <tr>
                                                <td colspan="3" class="text-center py-4 text-muted">
                                                    <i class="fas fa-user-tie fa-2x mb-2 d-block"></i>
                                                    No teachers found
                                                </td>
                                            </tr>
                                        <?php else: foreach($recentTeachers as $t): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($t['name']) ?></td>
                                                <td><?= htmlspecialchars($t['email']) ?></td>
                                                <td><?= htmlspecialchars($t['department']) ?></td>
                                            </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif($tab === 'students'): ?>
            <!-- Students Management -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Student Management</h3>
                <div>
                    <?php if(!$add_new && !$edit_id): ?>
                        <a href="?tab=students&add_new=1" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Add New Student
                        </a>
                    <?php else: ?>
                        <a href="?tab=students" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($add_new || $edit_id): ?>
                <!-- Add/Edit Student Form -->
                <div class="form-container">
                    <h4><?= $edit_id ? 'Edit Student' : 'Add New Student' ?></h4>
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="student_form" value="1">
                        <?php if($edit_id): ?>
                            <input type="hidden" name="edit_id" value="<?= $edit_id ?>">
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" 
                                           value="<?= $edit_student ? htmlspecialchars($edit_student['name']) : '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Roll</label>
                                    <input type="text" name="roll" class="form-control" 
                                           value="<?= $edit_student ? htmlspecialchars($edit_student['roll']) : '' ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Registration</label>
                                    <input type="text" name="registration" class="form-control" 
                                           value="<?= $edit_student ? htmlspecialchars($edit_student['registration']) : '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Department</label>
                                    <input type="text" name="department" class="form-control" 
                                           value="<?= $edit_student ? htmlspecialchars($edit_student['department']) : '' ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Semester</label>
                                    <input type="text" name="semester" class="form-control" 
                                           value="<?= $edit_student ? htmlspecialchars($edit_student['semester']) : '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Session</label>
                                    <input type="text" name="session" class="form-control" 
                                           value="<?= $edit_student ? htmlspecialchars($edit_student['session']) : '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Shift</label>
                                    <input type="text" name="shift" class="form-control" 
                                           value="<?= $edit_student ? htmlspecialchars($edit_student['shift']) : '' ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Father Name</label>
                                    <input type="text" name="father_name" class="form-control" 
                                           value="<?= $edit_student ? htmlspecialchars($edit_student['father_name']) : '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Mother Name</label>
                                    <input type="text" name="mother_name" class="form-control" 
                                           value="<?= $edit_student ? htmlspecialchars($edit_student['mother_name']) : '' ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Father Phone</label>
                                    <input type="text" name="father_phone" class="form-control" 
                                           value="<?= $edit_student ? htmlspecialchars($edit_student['father_phone']) : '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Mother Phone</label>
                                    <input type="text" name="mother_phone" class="form-control" 
                                           value="<?= $edit_student ? htmlspecialchars($edit_student['mother_phone']) : '' ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Present Address</label>
                                    <input type="text" name="present_address" class="form-control" 
                                           value="<?= $edit_student ? htmlspecialchars($edit_student['present_address']) : '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Permanent Address</label>
                                    <input type="text" name="permanent_address" class="form-control" 
                                           value="<?= $edit_student ? htmlspecialchars($edit_student['permanent_address']) : '' ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" 
                                           value="<?= $edit_student ? htmlspecialchars($edit_student['phone']) : '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Result</label>
                                    <input type="text" name="result" class="form-control" 
                                           value="<?= $edit_student ? htmlspecialchars($edit_student['result']) : '' ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Registration Paper Image</label>
                                    <input type="file" name="reg_paper_image" class="form-control" <?= !$edit_id ? 'required' : '' ?>>
                                    <?php if($edit_id && $edit_student['reg_paper_image']): ?>
                                        <small class="text-muted">Current file: <?= htmlspecialchars($edit_student['reg_paper_image']) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Student Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <?php if($edit_id && $edit_student['image']): ?>
                                        <small class="text-muted">Current file: <?= htmlspecialchars($edit_student['image']) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary"><?= $edit_id ? 'Update Student' : 'Add Student' ?></button>
                    </form>
                </div>
            <?php else: ?>
                <!-- Students List -->
                <div class="content-card">
                    <div class="card-body">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Roll</th>
                                        <th>Department</th>
                                        <th>Semester</th>
                                        <th>Phone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($students)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="fas fa-user-graduate fa-2x mb-2 d-block"></i>
                                                No students found
                                            </td>
                                        </tr>
                                    <?php else: foreach($students as $index => $s): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($s['name']) ?></td>
                                            <td><?= htmlspecialchars($s['roll']) ?></td>
                                            <td><?= htmlspecialchars($s['department']) ?></td>
                                            <td><?= htmlspecialchars($s['semester']) ?></td>
                                            <td><?= htmlspecialchars($s['phone']) ?></td>
                                            <td>
                                                <a href="?tab=students&edit_id=<?= $s['id'] ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?action=delete&type=student&id=<?= $s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this student?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        <?php elseif($tab === 'teachers'): ?>
            <!-- Teachers Management -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Teacher Management</h3>
                <div>
                    <?php if(!$add_new && !$edit_id): ?>
                        <a href="?tab=teachers&add_new=1" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Add New Teacher
                        </a>
                    <?php else: ?>
                        <a href="?tab=teachers" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($add_new || $edit_id): ?>
                <!-- Add/Edit Teacher Form -->
                <div class="form-container">
                    <h4><?= $edit_id ? 'Edit Teacher' : 'Add New Teacher' ?></h4>
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="teacher_form" value="1">
                        <?php if($edit_id): ?>
                            <input type="hidden" name="edit_id" value="<?= $edit_id ?>">
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Current Image</label><br>
                                    <?php if($edit_teacher && $edit_teacher['image']): ?>
                                        <img src="<?= '../uploads/teachers/'.htmlspecialchars($edit_teacher['image']) ?>" 
                                             class="current-img" alt="Teacher Image">
                                    <?php else: ?>
                                        <div class="current-img bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-user-tie fa-2x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" name="image" class="form-control mt-2" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" 
                                           value="<?= $edit_teacher ? htmlspecialchars($edit_teacher['name']) : '' ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Designation</label>
                                    <input type="text" name="designation" class="form-control" 
                                           value="<?= $edit_teacher ? htmlspecialchars($edit_teacher['designation']) : '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Department</label>
                                    <input type="text" name="department" class="form-control" 
                                           value="<?= $edit_teacher ? htmlspecialchars($edit_teacher['department']) : '' ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Shift</label>
                                    <input type="text" name="shift" class="form-control" 
                                           value="<?= $edit_teacher ? htmlspecialchars($edit_teacher['shift']) : '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Qualification</label>
                                    <input type="text" name="qualification" class="form-control" 
                                           value="<?= $edit_teacher ? htmlspecialchars($edit_teacher['qualification']) : '' ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" 
                                           value="<?= $edit_teacher ? htmlspecialchars($edit_teacher['phone']) : '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" 
                                           value="<?= $edit_teacher ? htmlspecialchars($edit_teacher['email']) : '' ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" 
                                           value="<?= $edit_teacher ? htmlspecialchars($edit_teacher['password']) : '' ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary"><?= $edit_id ? 'Update Teacher' : 'Add Teacher' ?></button>
                    </form>
                </div>
            <?php else: ?>
                <!-- Teachers List -->
                <div class="content-card">
                    <div class="card-body">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Department</th>
                                        <th>Designation</th>
                                        <th>Phone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($teachers)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="fas fa-user-tie fa-2x mb-2 d-block"></i>
                                                No teachers found
                                            </td>
                                        </tr>
                                    <?php else: foreach($teachers as $index => $t): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($t['name']) ?></td>
                                            <td><?= htmlspecialchars($t['email']) ?></td>
                                            <td><?= htmlspecialchars($t['department']) ?></td>
                                            <td><?= htmlspecialchars($t['designation']) ?></td>
                                            <td><?= htmlspecialchars($t['phone']) ?></td>
                                            <td>
                                                <a href="?tab=teachers&edit_id=<?= $t['id'] ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?action=delete&type=teacher&id=<?= $t['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this teacher?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        <?php elseif($tab === 'pending'): ?>
            <!-- Pending Registrations -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Pending Registrations</h3>
            </div>

            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a class="nav-link <?= $subtab === 'students' ? 'active' : '' ?>" href="?tab=pending&subtab=students">
                        Students (<?= $stats['pending_students'] ?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $subtab === 'teachers' ? 'active' : '' ?>" href="?tab=pending&subtab=teachers">
                        Teachers (<?= $stats['pending_teachers'] ?>)
                    </a>
                </li>
            </ul>

            <?php if($subtab === 'students'): ?>
                <!-- Pending Students -->
                <div class="content-card">
                    <div class="card-body">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Roll</th>
                                        <th>Department</th>
                                        <th>Semester</th>
                                        <th>Phone</th>
                                        <th>Submitted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($pendingStudentsAll)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <i class="fas fa-user-clock fa-2x mb-2 d-block"></i>
                                                No pending student registrations
                                            </td>
                                        </tr>
                                    <?php else: foreach($pendingStudentsAll as $index => $p): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($p['name']) ?></td>
                                            <td><?= htmlspecialchars($p['roll']) ?></td>
                                            <td><?= htmlspecialchars($p['department']) ?></td>
                                            <td><?= htmlspecialchars($p['semester']) ?></td>
                                            <td><?= htmlspecialchars($p['phone']) ?></td>
                                            <td><?= date('M j, Y', strtotime($p['created_at'])) ?></td>
                                            <td>
                                                <a class="btn btn-sm btn-success" href="?action=approve&type=student&id=<?= $p['id'] ?>" onclick="return confirm('Approve this student?')">
                                                    <i class="fas fa-check me-1"></i>Approve
                                                </a>
                                                <a class="btn btn-sm btn-danger" href="?action=reject&type=student&id=<?= $p['id'] ?>" onclick="return confirm('Reject this registration?')">
                                                    <i class="fas fa-times me-1"></i>Reject
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- Pending Teachers -->
                <div class="content-card">
                    <div class="card-body">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Department</th>
                                        <th>Designation</th>
                                        <th>Phone</th>
                                        <th>Submitted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($pendingTeachersAll)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <i class="fas fa-chalkboard-teacher fa-2x mb-2 d-block"></i>
                                                No pending teacher registrations
                                            </td>
                                        </tr>
                                    <?php else: foreach($pendingTeachersAll as $index => $p): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($p['name']) ?></td>
                                            <td><?= htmlspecialchars($p['email']) ?></td>
                                            <td><?= htmlspecialchars($p['department']) ?></td>
                                            <td><?= htmlspecialchars($p['designation']) ?></td>
                                            <td><?= htmlspecialchars($p['phone']) ?></td>
                                            <td><?= date('M j, Y', strtotime($p['created_at'])) ?></td>
                                            <td>
                                                <a class="btn btn-sm btn-success" href="?action=approve&type=teacher&id=<?= $p['id'] ?>" onclick="return confirm('Approve this teacher?')">
                                                    <i class="fas fa-check me-1"></i>Approve
                                                </a>
                                                <a class="btn btn-sm btn-danger" href="?action=reject&type=teacher&id=<?= $p['id'] ?>" onclick="return confirm('Reject this registration?')">
                                                    <i class="fas fa-times me-1"></i>Reject
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        <?php elseif($tab === 'notices'): ?>
            <!-- Notices Management -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Notice Management</h3>
                <div>
                    <?php if(!$add_new && !$edit_id): ?>
                        <a href="?tab=notices&add_new=1" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Add New Notice
                        </a>
                    <?php else: ?>
                        <a href="?tab=notices" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($add_new || $edit_id): ?>
                <!-- Add/Edit Notice Form -->
                <div class="form-container">
                    <h4><?= $edit_id ? 'Edit Notice' : 'Add New Notice' ?></h4>
                    <form method="post">
                        <input type="hidden" name="notice_form" value="1">
                        <?php if($edit_id): ?>
                            <input type="hidden" name="edit_id" value="<?= $edit_id ?>">
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" 
                                   value="<?= $edit_notice ? htmlspecialchars($edit_notice['title']) : '' ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Content</label>
                            <textarea name="content" class="form-control" rows="6" required><?= $edit_notice ? htmlspecialchars($edit_notice['content']) : '' ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary"><?= $edit_id ? 'Update Notice' : 'Create Notice' ?></button>
                    </form>
                </div>
            <?php else: ?>
                <!-- Notices List -->
                <div class="content-card">
                    <div class="card-body">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Content</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($notices)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="fas fa-bullhorn fa-2x mb-2 d-block"></i>
                                                No notices found
                                            </td>
                                        </tr>
                                    <?php else: foreach($notices as $index => $n): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($n['title']) ?></td>
                                            <td><?= strlen($n['content']) > 100 ? substr(htmlspecialchars($n['content']), 0, 100) . '...' : htmlspecialchars($n['content']) ?></td>
                                            <td><?= date('M j, Y', strtotime($n['created_at'])) ?></td>
                                            <td>
                                                <a href="?tab=notices&edit_id=<?= $n['id'] ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?action=delete&type=notice&id=<?= $n['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this notice?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add active class to current page in sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = new URLSearchParams(window.location.search).get('tab') || 'dashboard';
            const navLinks = document.querySelectorAll('.sidebar-nav a');
            
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href.includes('tab=' + currentPage)) {
                    link.classList.add('active');
                }
            });
        });

        // Auto-hide success message after 5 seconds
        <?php if($msg): ?>
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            }, 5000);
        <?php endif; ?>
    </script>
</body>
</html>