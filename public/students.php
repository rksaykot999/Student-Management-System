<?php
include '../includes/db.php'; // PDO connection file

// --- MODIFIED PHP ---
// Get search and sort parameters from the request
$searchTerm = $_GET['search'] ?? ""; // Changed from 'roll' to 'search'
$sortBy = $_GET['sort'] ?? 'roll';

// Whitelist allowed column names for sorting to prevent SQL injection
$allowedSortColumns = ['roll', 'name', 'department', 'shift'];
$orderByClause = 'roll'; // Default to a safe value
if (in_array($sortBy, $allowedSortColumns)) {
    $orderByClause = $sortBy;
}

// Prepare and execute the database query
if (!empty($searchTerm)) {
    // Updated query to search in both 'roll' and 'name' columns
    $sql = "SELECT * FROM students WHERE roll LIKE ? OR name LIKE ? ORDER BY $orderByClause ASC";
    $stmt = $pdo->prepare($sql);
    // Bind the search term to both placeholders
    $stmt->execute(["%$searchTerm%", "%$searchTerm%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM students ORDER BY $orderByClause ASC");
}
$students = $stmt->fetchAll();

// If it's an AJAX request, return only the student grid HTML and exit
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    if ($students && count($students) > 0) {
        foreach ($students as $student) {
?>
            <div class="student-card bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300"
                onclick='openStudentModal(<?php echo json_encode($student); ?>)'>
                <div class="p-4 md:p-5">
                    <div class="flex items-center mb-3 md:mb-4">
                        <?php if (!empty($student['image'])): ?>
                            <img src="../uploads/students/<?php echo htmlspecialchars($student['image']); ?>"
                                alt="Profile Picture"
                                class="w-10 h-10 md:w-12 md:h-12 rounded-full object-cover border-2 border-blue-400 shadow-sm">
                        <?php else: ?>
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-base md:text-lg">
                                <?php echo substr($student['name'], 0, 1); ?>
                            </div>
                        <?php endif; ?>
                        <div class="ml-3 md:ml-4">
                            <h3 class="font-bold text-base md:text-lg truncate max-w-[150px] md:max-w-none"><?php echo htmlspecialchars($student['name']); ?></h3>
                            <p class="text-gray-600 text-sm md:text-base"><?php echo htmlspecialchars($student['roll']); ?></p>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 pt-2 md:pt-3">
                        <div class="flex justify-between text-xs md:text-sm text-gray-600 mb-1">
                            <span>Department:</span>
                            <span class="font-medium truncate max-w-[100px] md:max-w-none"><?php echo htmlspecialchars($student['department']); ?></span>
                        </div>
                        <div class="flex justify-between text-xs md:text-sm text-gray-600 mb-1">
                            <span>Semester:</span>
                            <span class="font-medium"><?php echo htmlspecialchars($student['semester']); ?></span>
                        </div>
                        <div class="flex justify-between text-xs md:text-sm text-gray-600">
                            <span>Session:</span>
                            <span class="font-medium"><?php echo htmlspecialchars($student['session']); ?></span>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 md:px-5 py-2 md:py-3 flex justify-between items-center">
                    <span class="text-xs font-medium px-2 py-1 rounded-full 
                        <?php echo $student['shift'] == 'Morning' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'; ?>">
                        <?php echo htmlspecialchars($student['shift']); ?>
                    </span>
                    <span class="text-xs font-bold px-2 py-1 rounded-full 
                        <?php
                        $result = floatval($student['result']);
                        if ($result >= 3.5) echo 'bg-green-100 text-green-800';
                        elseif ($result >= 3.0) echo 'bg-yellow-100 text-yellow-800';
                        else echo 'bg-red-100 text-red-800';
                        ?>">
                        GPA: <?php echo htmlspecialchars($student['result']); ?>
                    </span>
                </div>
            </div>
<?php
        }
    } else {
        echo '
        <div class="col-span-full text-center py-8 md:py-12">
            <i class="fas fa-user-slash text-4xl md:text-6xl text-gray-300 mb-3 md:mb-4"></i>
            <h3 class="text-lg md:text-xl font-semibold text-gray-700">No students found</h3>
            <p class="text-gray-500 mt-1 md:mt-2 text-sm md:text-base">Try adjusting your search or sort criteria</p>
        </div>';
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Students - Student Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        /* Mobile menu specific styling */
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
            min-height: 40vh;
            display: flex;
            align-items: center;
        }

        .student-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            overflow: hidden;
        }

        .student-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            background: linear-gradient(45deg, #2575fc, #6a11cb);
            color: white;
            border-radius: 15px 15px 0 0;
            border-bottom: none;
        }

        .detail-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        /* Style for new sections */
        .info-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive table styles */
        @media (max-width: 640px) {
            .table-responsive {
                font-size: 0.875rem;
            }
        }
    </style>
</head>

<body class="text-gray-800 font-inter">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white shadow-md py-3 px-4 md:px-8 flex justify-between items-center">
        <!-- Logo -->
        <a href="index.php" class="flex items-center space-x-2 cursor-pointer">
            <img src="Images/Logo.png" alt="BPI Logo" class="h-10 w-10 md:h-12 md:w-12">
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-6">
            <a href="index.php" class="text-gray-600 font-medium hover:text-blue-600 transition">Home</a>
            <a href="about.php" class="text-gray-600 font-medium hover:text-blue-600 transition">About</a>
            <a href="academic.php" class="text-gray-600 font-medium hover:text-blue-600 transition">Academic</a>
            <a href="department.php" class="text-gray-600 font-medium hover:text-blue-600 transition">Departments</a>
            <a href="teachers.php" class="text-gray-600 font-medium hover:text-blue-600 transition">Teachers</a>
            <a href="students.php" class="text-blue-600 font-medium transition">Students</a>
            <a href="notice.php" class="text-gray-600 font-medium hover:text-blue-600 transition">Notice</a>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-4 relative">
            <!-- Desktop Login -->
            <button id="logoButton"
                class="hidden md:flex items-center space-x-2 text-gray-600 hover:text-blue-600 focus:outline-none">
                <span class="font-medium">Login</span>
                <div class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-600 text-white">
                    <i class="fas fa-user text-sm"></i>
                </div>
            </button>

            <!-- Mobile Menu Button -->
            <button id="menuButton" class="md:hidden text-gray-600 hover:text-blue-600 focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="loginDropdown"
                class="dropdown-menu absolute top-full right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-50 hidden">
                <a href="student-login.php"
                    class="block px-4 py-2 text-gray-600 font-medium hover:bg-gray-100 text-center">Student Login</a>
                <a href="teacher-login.php"
                    class="block px-4 py-2 text-gray-600 font-medium hover:bg-gray-100 text-center">Teacher Login</a>
                <a href="admin_login.php"
                    class="block px-4 py-2 text-gray-600 font-medium hover:bg-gray-100 text-center">Admin Login</a>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="mobile-menu-container fixed top-14 left-0 w-full bg-white shadow-lg p-5 z-40 md:hidden">
        <a href="index.php" class="block py-3 text-gray-600 hover:text-blue-600 border-b border-gray-100">Home</a>
        <a href="academic.php" class="block py-3 text-gray-600 hover:text-blue-600 border-b border-gray-100">Academics</a>
        <a href="department.php" class="block py-3 text-gray-600 hover:text-blue-600 border-b border-gray-100">Departments</a>
        <a href="notice.php" class="block py-3 text-gray-600 hover:text-blue-600 border-b border-gray-100">Notice</a>
        <a href="teachers.php" class="block py-3 text-gray-600 hover:text-blue-600 border-b border-gray-100">Teachers</a>
        <a href="students.php" class="block py-3 text-blue-600 font-medium border-b border-gray-100">Students</a>
        <a href="about.php" class="block py-3 text-gray-600 hover:text-blue-600 border-b border-gray-100">About</a>

        <!-- Mobile Login Button -->
        <button id="mobileLoginButton" class="w-full mt-4 bg-blue-600 text-white py-2 rounded-md font-medium">Login</button>
    </div>

    <!-- Hero Section -->
    <header class="header-hero text-white">
        <div class="p-6 md:p-12 rounded-3xl mx-auto max-w-5xl text-center w-full">
            <h1 class="text-3xl md:text-5xl font-bold mb-4">Student Information & Resources</h1>
            <p class="text-base md:text-lg text-gray-200 mb-6 md:mb-8 max-w-3xl mx-auto">
                Your one-stop destination for all academic, extracurricular, and administrative information.
            </p>
            <a href="#students" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-300">
                Browse Students
            </a>
        </div>
    </header>

    <!-- Institute Info Section -->
    <div class="bg-white py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-center">
                <div class="rounded-lg overflow-hidden shadow-lg order-2 md:order-1">
                    <img src="Images/Building-1.jpg" alt="Barishal Polytechnic Institute Campus" class="w-full h-full object-cover">
                </div>
                <div class="order-1 md:order-2">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">Welcome to Barishal Polytechnic Institute</h2>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Established with a vision to create skilled technical professionals, Barishal Polytechnic Institute has been a center of excellence in technical education in southern Bangladesh for decades. We are committed to providing state-of-the-art facilities and quality education to nurture the next generation of engineers and innovators.
                    </p>
                    <div class="grid grid-cols-2 gap-4 text-center mt-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-xl md:text-2xl font-bold text-blue-600">8+</p>
                            <p class="text-sm text-gray-700">Departments</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-xl md:text-2xl font-bold text-green-600">5,000+</p>
                            <p class="text-sm text-gray-700">Enrolled Students</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Section -->
    <div id="students" class="container mx-auto px-4 py-12 md:py-16">
        <div class="bg-white rounded-2xl shadow-lg p-5 md:p-8 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <h2 class="text-xl md:text-3xl font-bold text-gray-800 mb-4 md:mb-0 flex items-center">
                    <i class="fas fa-user-graduate mr-2 text-blue-500"></i>All Students
                </h2>
                <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
                    <div class="relative w-full md:w-48">
                        <select id="sortSelect" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none bg-white text-sm md:text-base">
                            <option value="roll">Sort by Roll</option>
                            <option value="name">Sort by Name</option>
                            <option value="department">Sort by Department</option>
                            <option value="shift">Sort by Shift</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
                    </div>
                    <div class="relative w-full md:w-80">
                        <input type="text" id="searchInput"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base"
                            placeholder="Search by Roll or Name...">
                        <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                    </div>
                </div>
            </div>
            <div id="studentGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                <?php foreach ($students as $student): ?>
                    <div class="student-card bg-gray-50 rounded-xl shadow-md hover:shadow-lg transition-all duration-300"
                        onclick='openStudentModal(<?php echo json_encode($student); ?>)'>
                        <div class="p-4 md:p-5">
                            <div class="flex items-center mb-3 md:mb-4">

                                <!-- Profile Picture -->
                                <?php if (!empty($student['image'])): ?>
                                    <img src="../uploads/students/<?php echo htmlspecialchars($student['image']); ?>"
                                        alt="Profile Picture"
                                        class="w-10 h-10 md:w-12 md:h-12 rounded-full object-cover border-2 border-blue-400 shadow-sm">
                                <?php else: ?>
                                    <!-- If no image, use first letter of name -->
                                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-base md:text-lg">
                                        <?php echo substr($student['name'], 0, 1); ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Name & Roll -->
                                <div class="ml-3 md:ml-4">
                                    <h3 class="font-bold text-base md:text-lg truncate max-w-[150px] md:max-w-none"><?php echo htmlspecialchars($student['name']); ?></h3>
                                    <p class="text-gray-600 text-sm md:text-base"><?php echo htmlspecialchars($student['roll']); ?></p>
                                </div>
                            </div>

                            <!-- Other Info -->
                            <div class="border-t border-gray-200 pt-2 md:pt-3">
                                <div class="flex justify-between text-xs md:text-sm text-gray-600 mb-1">
                                    <span>Department:</span>
                                    <span class="font-medium truncate max-w-[100px] md:max-w-none"><?php echo htmlspecialchars($student['department']); ?></span>
                                </div>
                                <div class="flex justify-between text-xs md:text-sm text-gray-600 mb-1">
                                    <span>Semester:</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($student['semester']); ?></span>
                                </div>
                                <div class="flex justify-between text-xs md:text-sm text-gray-600">
                                    <span>Session:</span>
                                    <span class="font-medium"><?php echo htmlspecialchars($student['session']); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Shift & GPA -->
                        <div class="bg-gray-100 px-4 md:px-5 py-2 md:py-3 flex justify-between items-center">
                            <span class="text-xs font-medium px-2 py-1 rounded-full 
                    <?php echo $student['shift'] == 'Morning' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'; ?>">
                                <?php echo htmlspecialchars($student['shift']); ?>
                            </span>

                            <span class="text-xs font-bold px-2 py-1 rounded-full 
                    <?php
                    $result = floatval($student['result']);
                    if ($result >= 3.5) echo 'bg-green-100 text-green-800';
                    elseif ($result >= 3.0) echo 'bg-yellow-100 text-yellow-800';
                    else echo 'bg-red-100 text-red-800';
                    ?>">
                                GPA: <?php echo htmlspecialchars($student['result']); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Campus Resources Section -->
    <div class="container mx-auto px-4 py-12 md:py-16">
        <div class="text-center mb-8 md:mb-12">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Campus Resources</h2>
            <p class="text-gray-600 mt-2 text-sm md:text-base">Quick access to essential student services and information.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 text-center">
            <a href="#" class="info-card block bg-white p-4 md:p-6 rounded-xl shadow-lg border border-gray-100 hover:border-blue-300 transition-all duration-300">
                <i class="fas fa-book-open text-3xl md:text-4xl text-blue-500 mb-3 md:mb-4"></i>
                <h4 class="font-semibold text-base md:text-lg">Digital Library</h4>
            </a>
            <a href="#" class="info-card block bg-white p-4 md:p-6 rounded-xl shadow-lg border border-gray-100 hover:border-green-300 transition-all duration-300">
                <i class="fas fa-poll text-3xl md:text-4xl text-green-500 mb-3 md:mb-4"></i>
                <h4 class="font-semibold text-base md:text-lg">Online Results</h4>
            </a>
            <a href="#" class="info-card block bg-white p-4 md:p-6 rounded-xl shadow-lg border border-gray-100 hover:border-purple-300 transition-all duration-300">
                <i class="fas fa-building text-3xl md:text-4xl text-purple-500 mb-3 md:mb-4"></i>
                <h4 class="font-semibold text-base md:text-lg">Hostel Info</h4>
            </a>
            <a href="#" class="info-card block bg-white p-4 md:p-6 rounded-xl shadow-lg border border-gray-100 hover:border-red-300 transition-all duration-300">
                <i class="fas fa-envelope text-3xl md:text-4xl text-red-500 mb-3 md:mb-4"></i>
                <h4 class="font-semibold text-base md:text-lg">Contact Admin</h4>
            </a>
        </div>
    </div>

    <!-- Student Details Modal -->
    <div class="modal fade" id="studentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-2xl overflow-hidden">
                <div class="modal-header bg-gradient-to-r from-blue-500 to-purple-600 text-white py-4 px-5">
                    <h5 class="modal-title text-lg md:text-xl font-bold">Student Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4 md:p-5">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Avatar + Name: image if available, otherwise letter -->
                        <div class="md:w-1/3 flex flex-col items-center">
                            <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden mb-4">
                                <!-- image element (hidden when no image) -->
                                <img id="studentModalImg" src="" alt="Student Photo" class="w-full h-full object-cover hidden cursor-pointer">
                                <!-- letter fallback -->
                                <div id="studentModalLetter" class="w-full h-full flex items-center justify-center text-white font-bold text-2xl md:text-4xl bg-gradient-to-r from-blue-500 to-purple-600"></div>
                            </div>

                            <h3 id="studentName" class="text-lg md:text-xl font-bold text-center"></h3>
                            <p id="studentRoll" class="text-gray-600 text-center text-sm md:text-base"></p>
                        </div>

                        <!-- Details -->
                        <div class="md:w-2/3">
                            <div class="space-y-3 md:space-y-4">
                                <div><label class="font-semibold text-gray-700 text-sm md:text-base">Department:</label>
                                    <p class="mt-1 text-sm md:text-base" id="studentDepartment"></p>
                                </div>
                                <div><label class="font-semibold text-gray-700 text-sm md:text-base">Semester:</label>
                                    <p class="mt-1 text-sm md:text-base" id="studentSemester"></p>
                                </div>
                                <div><label class="font-semibold text-gray-700 text-sm md:text-base">Session:</label>
                                    <p class="mt-1 text-sm md:text-base" id="studentSession"></p>
                                </div>
                                <div><label class="font-semibold text-gray-700 text-sm md:text-base">Shift:</label>
                                    <p class="mt-1 text-sm md:text-base" id="studentShift"></p>
                                </div>
                                <div><label class="font-semibold text-gray-700 text-sm md:text-base">Result (GPA):</label>
                                    <p class="mt-1 font-bold text-sm md:text-base" id="studentResult"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-gray-50 rounded-b-2xl py-3 px-5">
                    <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 mx-auto rounded-lg transition duration-300" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal for enlarged view -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body p-0 text-center">
                    <img id="fullStudentImage" src="" alt="Full student image" class="w-full h-auto rounded-lg object-contain">
                </div>
            </div>
        </div>
    </div>

    <?php include_once '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const loginButton = document.getElementById('logoButton');
        const loginDropdown = document.getElementById('loginDropdown');
        const mobileMenuButton = document.getElementById('menuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileLoginButton = document.getElementById('mobileLoginButton');
        const searchInput = document.getElementById('searchInput');
        const sortSelect = document.getElementById('sortSelect');
        const studentGrid = document.getElementById('studentGrid');
        
        let searchTimeout;

        // Toggle the dropdown menu on button click
        if (loginButton) {
            loginButton.addEventListener('click', (event) => {
                loginDropdown.classList.toggle('hidden');
                event.stopPropagation();
            });
        }

        // Hide the dropdown when clicking outside of it
        window.addEventListener('click', (event) => {
            if (!loginDropdown.contains(event.target) && loginButton && !loginButton.contains(event.target)) {
                loginDropdown.classList.add('hidden');
            }
        });

        // Toggle mobile menu visibility
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('show');
            loginDropdown.classList.add('hidden');
        });

        // Toggle login dropdown for mobile button
        mobileLoginButton.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            loginDropdown.classList.toggle('hidden');
        });

        // Hide mobile menu on link click
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('show');
            });
        });

        // Search functionality with debounce
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(updateStudents, 300);
        });

        // Sort functionality
        sortSelect.addEventListener('change', updateStudents);

        // Update students based on search and sort
        function updateStudents() {
            const searchTerm = searchInput.value;
            const sortBy = sortSelect.value;
            
            // Show loading indicator
            studentGrid.innerHTML = '<div class="col-span-full text-center py-8"><div class="loading mx-auto mb-4"></div><p>Loading students...</p></div>';
            
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `?ajax=1&search=${encodeURIComponent(searchTerm)}&sort=${encodeURIComponent(sortBy)}`, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    studentGrid.innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        function openStudentModal(student) {
            const imgEl = document.getElementById('studentModalImg');
            const letterEl = document.getElementById('studentModalLetter');

            // text fields
            document.getElementById('studentName').textContent = student.name;
            document.getElementById('studentRoll').textContent = 'Roll: ' + student.roll;
            document.getElementById('studentDepartment').textContent = student.department;
            document.getElementById('studentSemester').textContent = student.semester;
            document.getElementById('studentSession').textContent = student.session;
            document.getElementById('studentShift').textContent = student.shift;
            document.getElementById('studentResult').textContent = student.result;

            // GPA color
            const resultElement = document.getElementById('studentResult');
            const result = parseFloat(student.result);
            if (result >= 3.5) resultElement.className = 'mt-1 font-bold text-green-600 text-sm md:text-base';
            else if (result >= 3.0) resultElement.className = 'mt-1 font-bold text-yellow-600 text-sm md:text-base';
            else resultElement.className = 'mt-1 font-bold text-red-600 text-sm md:text-base';

            if (student.image && student.image.trim() !== "") {
                const src = "../uploads/students/" + student.image;
                imgEl.src = src;
                imgEl.classList.remove('hidden');
                letterEl.classList.add('hidden');

                // Image click = enlarge modal
                imgEl.onclick = function() {
                    const fullImg = document.getElementById('fullStudentImage');
                    fullImg.src = src;
                    new bootstrap.Modal(document.getElementById('imageModal')).show();
                };
            } else {
                // fallback letter
                letterEl.textContent = student.name ? student.name.charAt(0) : "?";
                letterEl.classList.remove('hidden');
                imgEl.classList.add('hidden');
            }

            new bootstrap.Modal(document.getElementById('studentModal')).show();
        }
    </script>
</body>

</html>