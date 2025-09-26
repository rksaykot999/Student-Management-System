<?php
include_once '../includes/db.php';
session_start();

// Check if student is logged in
if(!isset($_SESSION['student_id'])){
    header("Location: student-login.php");
    exit();
}

// Get student info
$student_id = $_SESSION['student_id'];
$stmt = $pdo->prepare("SELECT * FROM students WHERE id=?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

// Marks per subject
$stmt = $pdo->prepare("
SELECT s.name AS subject, m.test_type, m.obtain AS marks_obtained, m.total AS total_marks
FROM marks m
JOIN subjects s ON m.subject_id = s.id
WHERE m.roll=?
ORDER BY m.id DESC
");
$stmt->execute([$student['roll']]);
$marks = $stmt->fetchAll();

// Upcoming tests for this student (by department, semester, shift)
$upcoming_stmt = $pdo->prepare("
SELECT t.title, t.type, s.name as subject, t.date_time
FROM tests t
JOIN subjects s ON t.subject_id = s.id
WHERE t.department = ? AND t.semester = ? AND t.shift = ? AND t.date_time >= NOW()
ORDER BY t.date_time ASC
");
$upcoming_stmt->execute([$student['department'], $student['semester'], $student['shift']]);
$upcoming_tests = $upcoming_stmt->fetchAll();

// General Notices
$stmt = $pdo->prepare("SELECT * FROM notices WHERE target_department=? OR target_department='all' ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$student['department']]);
$notices = $stmt->fetchAll();

// Student-specific notices
$stmt = $pdo->prepare("
    SELECT sn.title, sn.content, t.name as teacher_name, sn.created_at
    FROM student_notices sn
    JOIN teachers t ON sn.teacher_id = t.id
    WHERE sn.student_roll=?
    ORDER BY sn.created_at DESC
");
$stmt->execute([$student['roll']]);
$student_notices = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    </script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f4f8;
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
        <!-- Student Profile Section -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="p-6 flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
                <div class="flex-shrink-0">
                    <img src="<?php echo !empty($student['image']) ? '../uploads/students/'.$student['image'] : '../assets/images/mypic3.jpg'; ?>" 
                         alt="Student Image" 
                         class="h-32 w-32 rounded-full object-cover border-4 border-primary-100">
                </div>
                <div class="text-center md:text-left">
                    <h2 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($student['name']); ?></h2>
                    <p class="text-gray-600">Roll: <?php echo htmlspecialchars($student['roll']); ?> | Registration: <?php echo htmlspecialchars($student['registration']); ?></p>
                    
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-graduation-cap mr-2 text-primary-500"></i>
                            <span><strong>Department:</strong> <?php echo htmlspecialchars($student['department']); ?></span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-layer-group mr-2 text-primary-500"></i>
                            <span><strong>Semester:</strong> <?php echo htmlspecialchars($student['semester']); ?></span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-clock mr-2 text-primary-500"></i>
                            <span><strong>Shift:</strong> <?php echo htmlspecialchars($student['shift']); ?></span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-calendar mr-2 text-primary-500"></i>
                            <span><strong>Session:</strong> <?php echo htmlspecialchars($student['session']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Personal Information</h3>
                <i class="fas fa-user text-2xl text-primary-500"></i>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-700 mb-4">Family Information</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Father's Name:</span>
                            <span class="font-medium"><?php echo htmlspecialchars($student['father_name']); ?></span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Mother's Name:</span>
                            <span class="font-medium"><?php echo htmlspecialchars($student['mother_name']); ?></span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Father's Phone:</span>
                            <span class="font-medium"><?php echo htmlspecialchars($student['father_phone']); ?></span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Mother's Phone:</span>
                            <span class="font-medium"><?php echo htmlspecialchars($student['mother_phone']); ?></span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-medium text-gray-700 mb-4">Contact Information</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Student Phone:</span>
                            <span class="font-medium"><?php echo htmlspecialchars($student['phone']); ?></span>
                        </div>
                        <div class="border-b pb-2">
                            <div class="text-gray-600 mb-1">Present Address:</div>
                            <div class="font-medium"><?php echo htmlspecialchars($student['present_address']); ?></div>
                        </div>
                        <div>
                            <div class="text-gray-600 mb-1">Permanent Address:</div>
                            <div class="font-medium"><?php echo htmlspecialchars($student['permanent_address']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-primary-100">Upcoming Tests</p>
                        <h3 class="text-3xl font-bold mt-2"><?php echo count($upcoming_tests); ?></h3>
                    </div>
                    <div class="bg-primary-400 p-4 rounded-full">
                        <i class="fas fa-clipboard-list text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-green-100">Total Subjects</p>
                        <h3 class="text-3xl font-bold mt-2"><?php 
                            $subjects_count = $pdo->prepare("SELECT COUNT(DISTINCT subject_id) FROM marks WHERE roll=?");
                            $subjects_count->execute([$student['roll']]);
                            echo $subjects_count->fetchColumn();
                        ?></h3>
                    </div>
                    <div class="bg-green-400 p-4 rounded-full">
                        <i class="fas fa-book text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-purple-100">General Notices</p>
                        <h3 class="text-3xl font-bold mt-2"><?php echo count($notices); ?></h3>
                    </div>
                    <div class="bg-purple-400 p-4 rounded-full">
                        <i class="fas fa-bullhorn text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-yellow-100">Personal Notices</p>
                        <h3 class="text-3xl font-bold mt-2"><?php echo count($student_notices); ?></h3>
                    </div>
                    <div class="bg-yellow-400 p-4 rounded-full">
                        <i class="fas fa-sticky-note text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Marks Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Marks Overview</h3>
                <i class="fas fa-chart-line text-2xl text-primary-500"></i>
            </div>

            <?php
            if ($marks):
                $grouped = [];

                // Normalize test type mapping
                $type_map = [
                    'quiz-test-1' => 'QT-1',
                    'class-test-1' => 'CT-1',
                    'mid'          => 'MID',
                    'quiz-test-2' => 'QT-2',
                    'class-test-2' => 'CT-2',
                    'attendance'   => 'ATTENDANCE'
                ];

                foreach ($marks as $m) {
                    $subject = $m['subject'];

                    // Normalize and map test_type
                    $raw_type = strtolower(str_replace([' ', '_'], '-', $m['test_type']));
                    $type = $type_map[$raw_type] ?? null;

                    if (!$type) continue; // Skip unknown types

                    if (!isset($grouped[$subject])) {
                        $grouped[$subject] = [
                            'QT-1' => 0,
                            'QT-2' => 0,
                            'CT-1' => 0,
                            'CT-2' => 0,
                            'MID' => 0,
                            'ATTENDANCE' => 0,
                        ];
                    }

                    $grouped[$subject][$type] = $m['marks_obtained'];
                }
            ?>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">QT-1</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">CT-1</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Mid</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">QT-2</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">CT-2</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($grouped as $subject => $data): 
                            $total = array_sum($data);
                        ?>
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($subject); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-center"><?= $data['QT-1']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-center"><?= $data['CT-1']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-center"><?= $data['MID']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-center"><?= $data['QT-2']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-center"><?= $data['CT-2']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-center"><?= $data['ATTENDANCE']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-center"><?= $total; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-chart-bar text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No marks available yet.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Upcoming Tests Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Upcoming Tests</h3>
                <i class="fas fa-calendar-alt text-2xl text-primary-500"></i>
            </div>

            <?php if(!empty($upcoming_tests)): ?>
                <div class="space-y-4">
                    <?php foreach($upcoming_tests as $t): ?>
                        <div class="border rounded-lg p-4 hover:shadow-md transition duration-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="font-medium text-gray-800"><?php echo htmlspecialchars($t['title']); ?></h4>
                                    <div class="flex flex-wrap gap-4 mt-2">
                                        <span class="text-sm text-gray-600"><i class="fas fa-book mr-1"></i> <?php echo htmlspecialchars($t['subject']); ?></span>
                                        <span class="text-sm text-gray-600"><i class="fas fa-tag mr-1"></i> <?php echo htmlspecialchars($t['type']); ?></span>
                                        <span class="text-sm text-gray-600"><i class="fas fa-clock mr-1"></i> <?php echo date("d-M-Y H:i", strtotime($t['date_time'])); ?></span>
                                    </div>
                                </div>
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Upcoming</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No upcoming tests scheduled for your group.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Notices Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- General Notices -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">General Notices</h3>
                    <i class="fas fa-bullhorn text-2xl text-primary-500"></i>
                </div>

                <?php if($notices): ?>
                    <div class="space-y-4">
                        <?php foreach($notices as $n): ?>
                            <div class="border rounded-lg p-4 hover:shadow-md transition duration-200">
                                <h4 class="font-medium text-gray-800"><?php echo htmlspecialchars($n['title']); ?></h4>
                                <p class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($n['content']); ?></p>
                                <p class="text-xs text-gray-500 mt-2"><?php echo date("M j, Y", strtotime($n['created_at'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-bullhorn text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No general notices available.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Student-Specific Notices -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Personal Notices</h3>
                    <i class="fas fa-sticky-note text-2xl text-primary-500"></i>
                </div>

                <?php if($student_notices): ?>
                    <div class="space-y-4">
                        <?php foreach($student_notices as $n): ?>
                            <div class="border rounded-lg p-4 hover:shadow-md transition duration-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-gray-800"><?php echo htmlspecialchars($n['title']); ?></h4>
                                        <p class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($n['content']); ?></p>
                                        <p class="text-xs text-gray-500 mt-2">By: <?php echo htmlspecialchars($n['teacher_name']); ?></p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2"><?php echo date("M j, Y", strtotime($n['created_at'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-sticky-note text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No personal notices available.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include_once '../includes/footer.php'; ?>
</body>
</html>