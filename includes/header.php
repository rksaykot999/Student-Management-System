<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    
    <!-- Use Tailwind CSS for rapid styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
    </style>
</head>

<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white shadow-lg py-[10px] px-4 md:px-8 flex justify-between items-center">

        <!-- Logo -->
        <a href="index.php" class="flex items-center space-x-2 cursor-pointer">
            <img src="Images/Logo.png" alt="BPI Logo" class="h-12 w-12">
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-6">
            <a href="index.php" class="text-gray-600 font-bold hover:text-blue-600 transition">Home</a>
            <a href="about.php" class="text-gray-600 font-bold hover:text-blue-600 transition">About</a>
            <a href="academic.php" class="text-gray-600 font-bold hover:text-blue-600 transition">Academic</a>
            <a href="department.php" class="text-gray-600 font-bold hover:text-blue-600 transition">Departments</a>
            <a href="teachers.php" class="text-gray-600 font-bold hover:text-blue-600 transition">Teachers</a>
            <a href="students.php" class="text-gray-600 font-bold hover:text-blue-600 transition">Students</a>
            <a href="notice.php" class="text-gray-600 font-bold hover:text-blue-600 transition">Notice</a>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-4 relative">
            <!-- Desktop Login -->
            <button id="logoButton"
                class="hidden md:flex items-center space-x-2 text-gray-600 hover:text-blue-600 focus:outline-none">
                <span class="font-semibold">Login</span>
                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white">
                    <i class="fas fa-user"></i>
                </div>
            </button>

            <!-- Mobile Menu Button -->
            <button id="menuButton" class="md:hidden text-gray-600 hover:text-blue-600 focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="loginDropdown"
                class="dropdown-menu absolute top-full right-0 mt-3 w-48 bg-white rounded-lg shadow-xl py-2 z-50">
                <a href="student-login.php"
                    class="block px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 text-center">Student Login</a>
                <a href="teacher-login.php"
                    class="block px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 text-center">Teacher Login</a>
                <a href="admin_login.php"
                    class="block px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 text-center">Admin Login</a>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="mobile-menu-container fixed top-16 left-0 w-full bg-white shadow-lg p-6 z-40 md:hidden">
        <a href="index.php" class="block py-2 text-gray-600 hover:text-blue-600">Home</a>
        <a href="academic.php" class="block py-2 text-gray-600 font-semibold">Academics</a>
        <a href="department.php" class="block py-2 text-gray-600 hover:text-blue-600">Departments</a>
        <a href="notice.php" class="block py-2 text-gray-600 hover:text-blue-600">Notice</a>
        <a href="teachers.php" class="block py-2 text-gray-600 hover:text-blue-600">Teachers</a>
        <a href="students.php" class="block py-2 text-gray-600 hover:text-blue-600">Students</a>
        <a href="about.php" class="block py-2 text-gray-600 hover:text-blue-600">About</a>

        <!-- Mobile Login Button -->
        <button id="mobileLoginButton" class="w-full mt-4 bg-blue-600 text-white py-2 rounded-md">Login</button>
    </div>

    <!-- JS -->
    <script>
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
    </script>
</body>

</html>
