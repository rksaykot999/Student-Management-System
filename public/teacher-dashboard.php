<?php
include_once '../includes/db.php';
session_start();

if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher-login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Teacher info
$stmt = $pdo->prepare("SELECT * FROM teachers WHERE id=?");
$stmt->execute([$teacher_id]);
$teacher = $stmt->fetch();

// Summary counts
$total_students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();

// Upcoming tests count (only tests created by this teacher and in future)
$upcoming_tests_count_stmt = $pdo->prepare("SELECT COUNT(*) FROM tests WHERE created_by = ? AND date_time >= NOW()");
$upcoming_tests_count_stmt->execute([$teacher_id]);
$upcoming_tests_count = $upcoming_tests_count_stmt->fetchColumn();

// Fetch teacher's upcoming tests
$teacher_tests_stmt = $pdo->prepare("
    SELECT t.*, s.name AS subject_name
    FROM tests t
    JOIN subjects s ON t.subject_id = s.id
    WHERE t.created_by = ?
    ORDER BY t.date_time ASC
");
$teacher_tests_stmt->execute([$teacher_id]);
$teacher_tests = $teacher_tests_stmt->fetchAll();

// Fetch teacher-specific Student Notices
$student_notices_stmt = $pdo->prepare("
    SELECT sn.*, s.name as student_name
    FROM student_notices sn
    JOIN students s ON sn.student_roll = s.roll
    WHERE sn.teacher_id = ?
    ORDER BY sn.created_at DESC
");
$student_notices_stmt->execute([$teacher_id]);
$student_notices = $student_notices_stmt->fetchAll();

// Fetch teacher-specific General Notices
$general_notices_stmt = $pdo->prepare("
    SELECT * FROM notices
    WHERE teacher_id = ?
    ORDER BY created_at DESC
");
$general_notices_stmt->execute([$teacher_id]);
$general_notices = $general_notices_stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f4f8;
        }
        .dropdown-menu {
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }

        .mobile-menu-container {
            transition: all 0.3s ease-in-out;
            transform: translateY(-100%);
            opacity: 0;
            pointer-events: none;
        }

        .mobile-menu-container.show {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }
        .header-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url("Images/Hero.jpg") no-repeat center center;
            background-size: cover;
            height: 50vh;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white shadow-lg py-[10px] px-4 md:px-8 flex justify-between items-center">

        <!-- Logo -->
        <a href="index.php" class="flex items-center space-x-2 cursor-pointer">
            <img src="Images/Logo.png" alt="BPI Logo" class="h-12 w-12">
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-6">
            <a href="index.php" class="text-gray-600 font-semibold hover:text-blue-600 transition">Home</a>
            <a href="about.php" class="text-gray-600 font-semibold hover:text-blue-600 transition">About</a>
            <a href="academic.php" class="text-gray-600 font-semibold hover:text-blue-600 transition">Academic</a>
            <a href="department.php" class="text-gray-600 font-semibold hover:text-blue-600 transition">Departments</a>
            <a href="teachers.php" class="text-gray-600 font-semibold hover:text-blue-600 transition">Teachers</a>
            <a href="students.php" class="text-gray-600 font-semibold hover:text-blue-600 transition">Students</a>
            <a href="notice.php" class="text-gray-600 font-semibold hover:text-blue-600 transition">Notice</a>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-4 relative">
            <!-- Desktop Login -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <form method="post" action="logout.php">
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Teacher Profile Section -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="p-6 flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
                <div class="flex-shrink-0">
                    <img src="<?php echo !empty($teacher['image']) ? '../uploads/teachers/' . $teacher['image'] : '../assets/images/default-teacher.png'; ?>"
                        alt="Teacher Image" class="h-32 w-32 rounded-full object-cover border-4 border-primary-100">
                </div>
                <div class="text-center md:text-left">
                    <h2 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($teacher['name']); ?></h2>
                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-graduation-cap mr-2 text-primary-500"></i>
                            <span><strong>Department:</strong>
                                <?php echo htmlspecialchars($teacher['department']); ?></span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-clock mr-2 text-primary-500"></i>
                            <span><strong>Shift:</strong> <?php echo htmlspecialchars($teacher['shift']); ?></span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-phone mr-2 text-primary-500"></i>
                            <span><strong>Phone:</strong> <?php echo htmlspecialchars($teacher['phone']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-primary-100">Total Students</p>
                        <h3 class="text-3xl font-bold mt-2"><?php echo $total_students; ?></h3>
                    </div>
                    <div class="bg-primary-400 p-4 rounded-full">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-green-100">Upcoming Tests</p>
                        <h3 class="text-3xl font-bold mt-2"><?php echo $upcoming_tests_count; ?></h3>
                    </div>
                    <div class="bg-green-400 p-4 rounded-full">
                        <i class="fas fa-clipboard-list text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-purple-100">Active Notices</p>
                        <h3 class="text-3xl font-bold mt-2">
                            <?php echo count($student_notices) + count($general_notices); ?>
                        </h3>
                    </div>
                    <div class="bg-purple-400 p-4 rounded-full">
                        <i class="fas fa-bullhorn text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Student Management -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Student Management</h3>
                    <i class="fas fa-user-graduate text-2xl text-primary-500"></i>
                </div>
                <p class="text-gray-600 mb-4">Manage student records and information</p>
                <div class="flex space-x-3">
               
                    <button onclick="window.location.href='view-student.php'"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-list mr-2"></i>View Students
                    </button>
                </div>
            </div>

             <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Student Notices</h3>
                    <div class="flex items-center space-x-3">
                        <button onclick="window.location.href='add-student-notice.php'"
                            class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                            <i class="fas fa-plus mr-2"></i>Create Notice
                        </button>
                    </div>
                </div>

                <?php if ($student_notices): ?>
                    <div class="space-y-4">
                        <?php foreach ($student_notices as $n): ?>
                            <div class="border rounded-lg p-4 hover:shadow-md transition duration-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-gray-800"><?php echo htmlspecialchars($n['title']); ?></h4>
                                        <p class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($n['content']); ?></p>
                                        <p class="text-xs text-gray-500 mt-2">Student:
                                            <?php echo htmlspecialchars($n['student_name'] . ' (' . $n['student_roll'] . ')'); ?>
                                        </p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button
                                            onclick="window.location.href='edit-student-notice.php?id=<?php echo $n['id']; ?>'"
                                            class="text-indigo-600 hover:text-indigo-900 p-1 rounded">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button
                                            onclick="if(confirm('Are you sure to delete this notice?')) { window.location.href='delete-student-notice.php?id=<?php echo $n['id']; ?>'; }"
                                            class="text-red-600 hover:text-red-900 p-1 rounded">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-sticky-note text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No student notices yet.</p>
                    </div>
                <?php endif; ?>
            </div>
         
        </div>

        <!-- Marks Management Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Marks Management</h3>
                <i class="fas fa-chart-bar text-2xl text-primary-500"></i>
            </div>

            <div class="space-y-4">
                <?php
                // department list from subjects table
                $departments = $pdo->query("SELECT DISTINCT department FROM subjects ORDER BY department ASC")->fetchAll(PDO::FETCH_COLUMN);

                foreach ($departments as $dept): ?>
                    <div class="border rounded-lg overflow-hidden">
                        <button
                            class="w-full text-left p-4 bg-gray-50 hover:bg-gray-100 transition duration-200 flex justify-between items-center"
                            onclick="toggleAccordion('dept-<?php echo $dept; ?>')">
                            <span class="font-medium text-gray-700">Department:
                                <?php echo htmlspecialchars($dept); ?></span>
                            <i class="fas fa-chevron-down transition-transform duration-300"
                                id="icon-dept-<?php echo $dept; ?>"></i>
                        </button>
                        <div class="hidden p-4 bg-white" id="dept-<?php echo $dept; ?>">
                            <?php
                            // semester list from subjects table
                            $semesters = $pdo->prepare("SELECT DISTINCT semester FROM subjects WHERE department=? ORDER BY semester ASC");
                            $semesters->execute([$dept]);
                            $semesters = $semesters->fetchAll(PDO::FETCH_COLUMN);

                            foreach ($semesters as $sem): ?>
                                <div class="mb-4 last:mb-0">
                                    <h4 class="font-medium text-gray-700 mb-2">Semester: <?php echo htmlspecialchars($sem); ?>
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                        <?php
                                        $stmt = $pdo->prepare("SELECT * FROM subjects WHERE department=? AND semester=? ORDER BY name ASC");
                                        $stmt->execute([$dept, $sem]);
                                        $subjs = $stmt->fetchAll();

                                        if ($subjs) {
                                            foreach ($subjs as $subj) {
                                                echo '<a href="add-marks.php?subject_id=' . $subj['id'] . '&department=' . $dept . '&semester=' . $sem . '" 
                                                       class="block p-3 border rounded-lg hover:bg-primary-50 hover:border-primary-300 transition duration-200 text-gray-700 hover:text-primary-700">
                                                       <i class="fas fa-book mr-2 text-primary-500"></i>' . htmlspecialchars($subj['name']) . '
                                                    </a>';
                                            }
                                        } else {
                                            echo "<p class='text-gray-500'><em>No subjects found</em></p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Upcoming Tests Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Upcoming Tests</h3>
                <div class="flex items-center space-x-3">
                    <button onclick="window.location.href='manage-tests.php'"
                        class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-plus mr-2"></i>Create New Test
                    </button>
                </div>
            </div>

            <?php if (!empty($teacher_tests)): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dept</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sem</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($teacher_tests as $t): ?>
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <?php echo htmlspecialchars($t['department']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <?php echo htmlspecialchars($t['semester']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <?php echo htmlspecialchars($t['subject_name']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <?php echo htmlspecialchars($t['title']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <?php echo htmlspecialchars($t['type']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <?php echo date("d-M-Y H:i", strtotime($t['date_time'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button
                                                onclick="window.location.href='manage-tests.php?edit=<?php echo $t['id']; ?>'"
                                                class="text-indigo-600 hover:text-indigo-900 flex items-center">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </button>
                                            <button
                                                onclick="if(confirm('Delete this test?')){ window.location.href='manage-tests.php?delete=<?php echo $t['id']; ?>'; }"
                                                class="text-red-600 hover:text-red-900 flex items-center">
                                                <i class="fas fa-trash mr-1"></i>Delete
                                            </button>
                                            <button
                                                onclick="window.location.href='view-test-students.php?test_id=<?php echo $t['id']; ?>'"
                                                class="text-green-600 hover:text-green-900 flex items-center">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No tests created yet. Click <strong>Create New Test</strong> to add one.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Notices Section -->
        
    </main>

    <!-- Footer -->
    <?php include_once '../includes/footer.php'; ?>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
        const loginButton = document.getElementById('logoButton');
        const loginDropdown = document.getElementById('loginDropdown');
        const mobileMenuButton = document.getElementById('menuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileLoginButton = document.getElementById('mobileLoginButton');

        // Desktop login dropdown
        if (loginButton) {
            loginButton.addEventListener('click', (e) => {
                loginDropdown.classList.toggle('show');
                e.stopPropagation();
            });
        }

        // Hide dropdown when clicking outside
        window.addEventListener('click', (e) => {
            if (!loginDropdown.contains(e.target) && !loginButton.contains(e.target)) {
                loginDropdown.classList.remove('show');
            }
        });

        // Toggle mobile menu
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('show');
        });

        // Mobile login dropdown toggle
        mobileLoginButton.addEventListener('click', (e) => {
            e.preventDefault();
            loginDropdown.classList.toggle('show');
        });

        // Hide mobile menu on link click
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('show');
            });
        });
        // Accordion functionality for marks management
        function toggleAccordion(id) {
            const element = document.getElementById(id);
            const icon = document.getElementById('icon-' + id);

            if (element.classList.contains('hidden')) {
                element.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                element.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }

        // Initialize tooltips if needed
        document.addEventListener('DOMContentLoaded', function () {
            // Any additional initialization code can go here
        });
    </script>
    <?php include_once '../includes/footer.php'; ?>
</body>

</html>