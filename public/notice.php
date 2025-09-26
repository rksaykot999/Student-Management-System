<?php
include_once '../includes/db.php';
session_start();

// সব notice fetch করা
$notices = $pdo->query("SELECT * FROM notices ORDER BY created_at DESC")->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notices</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f4f8;
        }

        .header-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url("Images/Hero.jpg") no-repeat center center;
            background-size: cover;
            /* height: 50vh; */
        }

        .dropdown-menu {
            display: none;
        }

        .dropdown-menu.show {
            display: block;
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
    </style>
</head>

<body class="text-gray-800">

    <!-- Navbar -->
    <?php include_once '../includes/header.php'; ?>

    <!-- Hero Section -->
    <header class="header-hero pt-12 text-white">
        <div class="p-4 md:p-12 rounded-3xl h-500px] mx-auto max-w-3xl text-center transform transition-transform">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">Official Notice Board</h1>
            <p class="text-lg md:text-xl text-gray-200 mb-8">
                Stay updated with the latest news, announcements, and circulars from the institute.
            </p>
        </div>
    </header>

    <!-- Main Content Sections -->


    <main class="container mx-auto p-6 md:p-12 space-y-16">
        <section>
            <div class="main-content px-6 py-10 bg-gray-100">
                <div class="notice-container max-w-8xl mx-auto bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-center text-indigo-600 mb-6 border-b pb-3">
                        📢 Latest Notices
                    </h2>

                    <?php if ($notices) { ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <?php foreach ($notices as $n) { ?>
                                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer">
                                    <div class="flex items-center mb-4">
                                        <!-- Note: I'm using a static icon, but you could add a column to your notices table to dynamically select the icon class. -->
                                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-4">
                                            <i class="fas fa-bullhorn text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-800"><?= htmlspecialchars($n['title']); ?></h3>
                                            <!-- Assuming your notices table has a 'created_at' column to display the date -->
                                            <p class="text-sm text-gray-500">Published: <?= date('F j, Y', strtotime($n['created_at'])); ?></p>
                                        </div>
                                    </div>
                                    <p class="text-gray-600 text-sm mb-4">
                                        <?= htmlspecialchars($n['content']); ?>
                                    </p>
                                    <a href="#" class="inline-block text-blue-600 hover:underline font-semibold transition duration-300">
                                        Read More <i class="fas fa-arrow-right ml-1 text-sm"></i>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <p class="text-center text-gray-500 italic">No notices yet.</p>
                    <?php } ?>
                </div>
            </div>
        </section>

        <section class="bg-white p-8 md:p-12 rounded-3xl shadow-xl">
            <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">All Notices</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Notice Card 1 -->
                <div
                    class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-bullhorn text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Semester Final Exam Schedule</h3>
                            <p class="text-sm text-gray-500">Published: October 26, 2025</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">
                        The final examination schedule for all departments has been published. Students are advised to
                        check the details and prepare accordingly.
                    </p>
                    <a href="#"
                        class="inline-block text-blue-600 hover:underline font-semibold transition duration-300">
                        Read More <i class="fas fa-arrow-right ml-1 text-sm"></i>
                    </a>
                </div>

                <!-- Notice Card 2 -->
                <div
                    class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-check text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Holiday Announcement: Eid-ul-Fitr</h3>
                            <p class="text-sm text-gray-500">Published: October 20, 2025</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">
                        All classes and administrative activities will remain closed from [Date] to [Date] on the
                        occasion of Eid-ul-Fitr.
                    </p>
                    <a href="#"
                        class="inline-block text-blue-600 hover:underline font-semibold transition duration-300">
                        Read More <i class="fas fa-arrow-right ml-1 text-sm"></i>
                    </a>
                </div>

                <!-- Notice Card 3 -->
                <div
                    class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-microphone text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Seminar on AI & Machine Learning</h3>
                            <p class="text-sm text-gray-500">Published: October 15, 2025</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">
                        A seminar on "Applications of AI in Modern Industry" will be held on [Date] at [Time] in the
                        main auditorium.
                    </p>
                    <a href="#"
                        class="inline-block text-blue-600 hover:underline font-semibold transition duration-300">
                        Read More <i class="fas fa-arrow-right ml-1 text-sm"></i>
                    </a>
                </div>

                <!-- Notice Card 4 -->
                <div
                    class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user-plus text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Admission Circular for 2025-26</h3>
                            <p class="text-sm text-gray-500">Published: September 28, 2025</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">
                        The admission circular for the academic year 2025-26 has been published. Interested candidates
                        can apply online.
                    </p>
                    <a href="#"
                        class="inline-block text-blue-600 hover:underline font-semibold transition duration-300">
                        Read More <i class="fas fa-arrow-right ml-1 text-sm"></i>
                    </a>
                </div>

                <!-- Notice Card 5 -->
                <div
                    class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Annual Sports Day Postponed</h3>
                            <p class="text-sm text-gray-500">Published: September 22, 2025</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">
                        Due to unavoidable circumstances, the Annual Sports Day has been postponed to a new date, which
                        will be announced soon.
                    </p>
                    <a href="#"
                        class="inline-block text-blue-600 hover:underline font-semibold transition duration-300">
                        Read More <i class="fas fa-arrow-right ml-1 text-sm"></i>
                    </a>
                </div>

                <!-- Notice Card 6 -->
                <div
                    class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-graduation-cap text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Scholarship Deadline Extended</h3>
                            <p class="text-sm text-gray-500">Published: September 15, 2025</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">
                        The deadline for scholarship applications has been extended to November 10, 2025. Students are
                        encouraged to apply.
                    </p>
                    <a href="#"
                        class="inline-block text-blue-600 hover:underline font-semibold transition duration-300">
                        Read More <i class="fas fa-arrow-right ml-1 text-sm"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Upcoming Events Section -->
        <section class="bg-white p-8 md:p-12 rounded-3xl shadow-xl mb-12">
            <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Upcoming Events</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Event Card 1 -->
                <div
                    class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-day text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Annual Cultural Fest</h3>
                            <p class="text-sm text-gray-500">Date: November 5, 2025</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">
                        Join us for a day of music, dance, and cultural performances. All students and faculty are
                        welcome to attend.
                    </p>
                    <a href="#"
                        class="inline-block text-orange-600 hover:underline font-semibold transition duration-300">
                        Register Now <i class="fas fa-arrow-right ml-1 text-sm"></i>
                    </a>
                </div>

                <!-- Event Card 2 -->
                <div
                    class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 bg-cyan-100 text-cyan-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-map-marker-alt text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Job Fair 2025</h3>
                            <p class="text-sm text-gray-500">Date: November 15, 2025</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">
                        Meet with top employers and explore career opportunities. Don't miss this chance to network!
                    </p>
                    <a href="#"
                        class="inline-block text-cyan-600 hover:underline font-semibold transition duration-300">
                        View Companies <i class="fas fa-arrow-right ml-1 text-sm"></i>
                    </a>
                </div>

                <!-- Event Card 3 -->
                <div
                    class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-book-reader text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Guest Lecture: The Future of Biotech</h3>
                            <p class="text-sm text-gray-500">Date: November 20, 2025</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">
                        A renowned scientist will discuss the latest trends and innovations in biotechnology.
                    </p>
                    <a href="#"
                        class="inline-block text-pink-600 hover:underline font-semibold transition duration-300">
                        Read Bio <i class="fas fa-arrow-right ml-1 text-sm"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Our Departments Section -->
        <section class="bg-white p-8 md:p-12 rounded-3xl shadow-xl">
            <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Our Departments</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Department Card 1 -->
                <div
                    class="flex flex-col items-center text-center p-6 rounded-2xl border border-gray-100 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer">
                    <div class="w-16 h-16 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-laptop-code text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Computer Science</h3>
                    <p class="text-gray-600 text-sm">
                        Shaping the future with cutting-edge technology and innovation.
                    </p>
                </div>

                <!-- Department Card 2 -->
                <div
                    class="flex flex-col items-center text-center p-6 rounded-2xl border border-gray-100 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer">
                    <div class="w-16 h-16 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-flask text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Natural Sciences</h3>
                    <p class="text-gray-600 text-sm">
                        Exploring the universe, from the smallest particles to the largest galaxies.
                    </p>
                </div>

                <!-- Department Card 3 -->
                <div
                    class="flex flex-col items-center text-center p-6 rounded-2xl border border-gray-100 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer">
                    <div class="w-16 h-16 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-balance-scale text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Law & Justice</h3>
                    <p class="text-gray-600 text-sm">
                        Fostering a deep understanding of legal principles and systems.
                    </p>
                </div>

                <!-- Department Card 4 -->
                <div
                    class="flex flex-col items-center text-center p-6 rounded-2xl border border-gray-100 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 cursor-pointer">
                    <div class="w-16 h-16 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-palette text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Fine Arts</h3>
                    <p class="text-gray-600 text-sm">
                        Cultivating creativity and artistic expression in various mediums.
                    </p>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer Section -->
    <?php include_once '../includes/footer.php'; ?>

    <script>
        // Get the button and the dropdown menu elements
        const loginButton = document.getElementById('logoButton');
        const loginDropdown = document.getElementById('loginDropdown');
        const mobileMenuButton = document.getElementById('menuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileLoginButton = document.getElementById('mobileLoginButton');

        // Toggle the dropdown menu on button click
        loginButton.addEventListener('click', (event) => {
            loginDropdown.classList.toggle('show');
            event.stopPropagation();
        });

        // Hide the dropdown when clicking outside of it
        window.addEventListener('click', (event) => {
            if (!loginDropdown.contains(event.target) && !loginButton.contains(event.target)) {
                loginDropdown.classList.remove('show');
            }
        });

        // Toggle mobile menu visibility
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('show');
        });

        // Toggle login dropdown for mobile button
        mobileLoginButton.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
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