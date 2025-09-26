<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPI Portal</title>
    <!-- Use Tailwind CSS for rapid styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f4f8;
        }

        .header-hero {
            background-size: cover;
        }

        .dropdown-menu {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }

        .dropdown-menu.show {
            display: block;
        }

        .mobile-menu-container {
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
            transform: translateY(-100%);
            opacity: 0;
            pointer-events: none;
        }

        .mobile-menu-container.show {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Background slider animation */
        @keyframes slider {
            0% {
                background-image: url('https://studybarta.wordpress.com/wp-content/uploads/2018/07/bpi-barisal-polytechni-institute.jpg');
            }

            33% {
                background-image: url('https://barishalpoly.gov.bd//sites/default/files/files/barishalpoly.portal.gov.bd/home_slider/cd466ce8_d681_4763_b6bc_6b8ccfe1c13e/2021-09-09-04-16-d9e54d0878c6a2b54c31d85429a77db4.jpg');
            }

            66% {
                background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/c/c7/Barisal_Polytechnic_Institute_5.jpg/2560px-Barisal_Polytechnic_Institute_5.jpg');
            }

            100% {
                background-image: url('https://studybarta.files.wordpress.com/2018/07/bpi-barisal-polytechni-institute.jpg');
            }
        }

        .bg-slider {
            animation: slider 15s infinite linear;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        /* Animation classes that will be triggered by Intersection Observer */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s ease-out;
        }

        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }

        .animate-on-scroll-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: all 0.8s ease-out;
        }

        .animate-on-scroll-left.animated {
            opacity: 1;
            transform: translateX(0);
        }

        .animate-on-scroll-right {
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.8s ease-out;
        }

        .animate-on-scroll-right.animated {
            opacity: 1;
            transform: translateX(0);
        }

        .animate-on-scroll-scale {
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.8s ease-out;
        }

        .animate-on-scroll-scale.animated {
            opacity: 1;
            transform: scale(1);
        }

        /* Hero section animations (load immediately) */
        .fade-up {
            opacity: 0;
            animation: fadeUp 1.2s ease forwards;
        }

        .fade-up-delay {
            opacity: 0;
            animation: fadeUp 1.2s ease forwards;
            animation-delay: 0.5s;
        }

        .fade-up-delay-2 {
            opacity: 0;
            animation: fadeUp 1.2s ease forwards;
            animation-delay: 1s;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Bounce animation for continuous elements */
        @keyframes bounceSlow {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .animate-bounce-slow {
            animation: bounceSlow 3s infinite ease-in-out;
        }

        /* Stagger animations for grid items */
        .animate-stagger>* {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease-out;
        }

        .animate-stagger.animated>* {
            opacity: 1;
            transform: translateY(0);
        }

        .animate-stagger.animated>*:nth-child(1) {
            transition-delay: 0.1s;
        }

        .animate-stagger.animated>*:nth-child(2) {
            transition-delay: 0.2s;
        }

        .animate-stagger.animated>*:nth-child(3) {
            transition-delay: 0.3s;
        }

        .animate-stagger.animated>*:nth-child(4) {
            transition-delay: 0.4s;
        }

        .animate-stagger.animated>*:nth-child(5) {
            transition-delay: 0.5s;
        }

        .animate-stagger.animated>*:nth-child(6) {
            transition-delay: 0.6s;
        }
    </style>
</head>

<body class="text-gray-800">

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
        <a href="academic.php" class="block py-2 text-gray-600 hover:text-blue-600">Academics</a>
        <a href="department.php" class="block py-2 text-gray-600 hover:text-blue-600">Departments</a>
        <a href="notice.php" class="block py-2 text-gray-600 hover:text-blue-600">Notice</a>
        <a href="teachers.php" class="block py-2 text-gray-600 hover:text-blue-600">Teachers</a>
        <a href="students.php" class="block py-2 text-gray-600 hover:text-blue-600">Students</a>
        <a href="about.php" class="block py-2 text-gray-600 hover:text-blue-600">About</a>

        <!-- Mobile Login Button -->
        <button id="mobileLoginButton" class="w-full mt-4 bg-blue-600 text-white py-2 rounded-md">Login</button>
    </div>

    <!-- Hero Section -->
    <header class="header-hero pt-12 md:pt-4 py-8 text-white relative overflow-hidden">
        <div class="bg-slider absolute inset-0"></div>
        <div class="absolute inset-0 bg-black/60"></div>

        <div class="relative px-6 md:px-12 lg:px-16 py-16 md:py-20 rounded-3xl mx-auto max-w-6xl text-center">

            <!-- Title -->
            <h1 class="fade-up text-3xl sm:text-4xl md:text-6xl font-extrabold mb-6 leading-snug md:leading-tight">
                Welcome to <br class="hidden sm:block">
                Barisal Polytechnic Institute
            </h1>

            <!-- Subtitle -->
            <p
                class="fade-up-delay text-base sm:text-lg md:text-2xl text-gray-200 mb-10 max-w-3xl leading-relaxed mx-auto ">
                At Barisal Polytechnic Institute, we don't just provide education — we create opportunities.
                With a legacy of excellence in technical and vocational training, our mission is to empower
                students with the knowledge, practical skills, and innovative mindset needed to thrive in a
                fast-changing world.
            </p>

            <!-- CTA Buttons -->
            <div class="fade-up-delay-2 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#about"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-4 sm:px-8 sm:py-5 rounded-2xl shadow-xl font-semibold text-base sm:text-lg transition">
                    Discover More
                </a>
                <a href="#admission"
                    class="border-2 border-white text-white px-6 py-4 sm:px-8 sm:py-5 rounded-2xl font-semibold text-base sm:text-lg hover:bg-white hover:text-indigo-700 transition">
                    Apply Now
                </a>
            </div>
        </div>
    </header>


    <!-- Main Content Sections -->
    <main class="container mx-auto p-6 md:p-12 space-y-16">
        <!-- About BPI Section -->
        <section id="about"
            class="relative bg-gradient-to-r from-gray-50 to-white p-8 md:p-16 rounded-3xl shadow-xl overflow-hidden">

            <!-- Decorative Shapes -->
            <div class="absolute top-0 left-0 w-64 h-64 bg-indigo-100 rounded-full blur-3xl opacity-30"></div>
            <div class="absolute bottom-0 right-0 w-72 h-72 bg-pink-100 rounded-full blur-3xl opacity-30"></div>

            <h2
                class="animate-on-scroll text-3xl md:text-4xl font-extrabold mb-12 text-center text-gray-900 tracking-tight relative z-10">
                About Barisal Polytechnic Institute
            </h2>

            <div class="grid md:grid-cols-2 gap-12 items-center relative z-10">
                <!-- Image Block -->
                <div
                    class="animate-on-scroll-left p-4 rounded-2xl bg-white shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition duration-500">
                    <img src="Images/About.jpeg" alt="BPI Campus" class="rounded-2xl w-full object-cover">
                </div>

                <!-- Content Block -->
                <div class="animate-on-scroll-right">
                    <p class="text-gray-700 leading-relaxed mb-4 text-lg text-justify">
                        Barisal Polytechnic Institute (<span class="font-semibold text-indigo-700">BPI</span>) is a
                        premier
                        technical education institution committed to nurturing skilled professionals who can thrive in
                        today's
                        fast-evolving world. With decades of experience in delivering high-quality education, we have
                        built a
                        strong reputation for academic excellence, practical training, and innovation.
                    </p>
                    <p class="text-gray-700 leading-relaxed mb-4 text-lg text-justify">
                        At BPI, students have access to a wide range of diploma programs across various technical
                        fields,
                        supported by state-of-the-art laboratories, modern classrooms, and a highly experienced faculty
                        team.
                        Our curriculum is designed not just to teach theory, but to provide hands-on experience that
                        prepares
                        students for real-world challenges. Beyond academics, we focus on holistic development through
                        extracurricular activities, workshops, and industry collaborations.
                    </p>
                    <!-- Optional Call to Action -->
                    <div class="mt-8">
                        <a href="about.php"
                            class="inline-block bg-indigo-600 text-white px-8 py-4 rounded-2xl shadow-lg hover:bg-indigo-700 hover:shadow-2xl transition font-semibold text-lg">
                            Read more
                        </a>
                    </div>
                </div>
            </div>
        </section>


        <!-- Our Departments Section -->
<section id="departments" class="relative bg-blue-50 p-8 md:p-16 rounded-3xl shadow-xl overflow-hidden">

    <!-- Decorative Shape -->
    <div class="absolute top-0 right-0 w-72 h-72 bg-indigo-100 rounded-full blur-3xl opacity-30"></div>

    <div class="relative z-10">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Our Departments</h2>

        <!-- Departments Grid -->
        <div class="animate-stagger grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            <!-- Computer Science -->
            <div
                class="bg-white p-6 rounded-2xl shadow-md text-center transition transform hover:scale-105 hover:shadow-xl">
                <i class="fas fa-laptop-code text-5xl text-blue-600 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800">Computer Science</h3>
                <p class="text-gray-600 mt-2 text-sm">
                    Learn programming, software engineering, database, AI & modern web technologies.
                </p>
            </div>

            <!-- Civil Engineering -->
            <div
                class="bg-white p-6 rounded-2xl shadow-md text-center transition transform hover:scale-105 hover:shadow-xl">
                <i class="fas fa-building text-5xl text-green-600 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800">Civil Engineering</h3>
                <p class="text-gray-600 mt-2 text-sm">
                    Focus on construction, design, bridge building, and sustainable infrastructure.
                </p>
            </div>

            <!-- Electrical Technology -->
            <div
                class="bg-white p-6 rounded-2xl shadow-md text-center transition transform hover:scale-105 hover:shadow-xl">
                <i class="fas fa-plug text-5xl text-yellow-500 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800">Electrical Technology</h3>
                <p class="text-gray-600 mt-2 text-sm">
                    Study power generation, electrical circuits, electronics & renewable energy.
                </p>
            </div>

            <!-- Mechanical Technology -->
            <div
                class="bg-white p-6 rounded-2xl shadow-md text-center transition transform hover:scale-105 hover:shadow-xl">
                <i class="fas fa-cogs text-5xl text-red-600 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800">Mechanical Technology</h3>
                <p class="text-gray-600 mt-2 text-sm">
                    Learn about machines, thermodynamics, robotics & advanced manufacturing.
                </p>
            </div>

            <!-- Electronics Engineering -->
            <div
                class="bg-white p-6 rounded-2xl shadow-md text-center transition transform hover:scale-105 hover:shadow-xl">
                <i class="fas fa-microchip text-5xl text-indigo-600 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800">Electronics Engineering</h3>
                <p class="text-gray-600 mt-2 text-sm">
                    Explore microcontrollers, communication systems & modern electronic devices.
                </p>
            </div>

            <!-- Power Technology -->
            <div
                class="bg-white p-6 rounded-2xl shadow-md text-center transition transform hover:scale-105 hover:shadow-xl">
                <i class="fas fa-bolt text-5xl text-orange-500 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800">Power Technology</h3>
                <p class="text-gray-600 mt-2 text-sm">
                    Specialize in power plants, high-voltage systems, and smart grid technologies.
                </p>
            </div>

            <!-- Tourism & Hospitality -->
            <div
                class="bg-white p-6 rounded-2xl shadow-md text-center transition transform hover:scale-105 hover:shadow-xl">
                <i class="fas fa-utensils text-5xl text-pink-500 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800">Tourism & Hospitality</h3>
                <p class="text-gray-600 mt-2 text-sm">
                    Learn hotel management, event planning, travel operations & customer service.
                </p>
            </div>

            <!-- Electromedical Technology -->
            <div
                class="bg-white p-6 rounded-2xl shadow-md text-center transition transform hover:scale-105 hover:shadow-xl">
                <i class="fas fa-stethoscope text-5xl text-teal-500 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800">Electromedical Technology</h3>
                <p class="text-gray-600 mt-2 text-sm">
                    Work with biomedical instruments, hospital equipment & healthcare technology.
                </p>
            </div>
        </div>
    </div>
</section>



        <!-- Research & Innovation Section -->
        <section
            class="relative bg-gradient-to-r from-white via-blue-50 to-white p-8 md:p-12 rounded-3xl shadow-xl overflow-hidden">

            <!-- Decorative Shape -->
            <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-100 rounded-full blur-3xl opacity-30"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 bg-indigo-100 rounded-full blur-3xl opacity-30"></div>

            <h2 class="animate-on-scroll text-3xl md:text-4xl font-bold mb-12 text-center text-gray-800 relative z-10">
                Research & Innovation
            </h2>

            <div class="grid md:grid-cols-2 gap-12 items-center relative z-10">
                <!-- Image -->
                <div class="animate-on-scroll-left">
                    <img src="Images/innovation.png" alt="Research & Innovation"
                        class="rounded-2xl shadow-lg w-full hover:scale-105 transform transition duration-500">
                </div>

                <!-- Content -->
                <div class="animate-on-scroll-right">
                    <p class="text-gray-700 leading-relaxed mb-6 text-lg">
                        BPI is a leader in both education and research. Our students and faculty work on cutting-edge
                        technologies, innovative solutions, and groundbreaking projects. We provide students with all
                        the support and opportunities they need to unleash their creativity.
                    </p>
                    <ul class="space-y-4 text-gray-700">
                        <li class="animate-on-scroll flex items-center space-x-3">
                            <i class="fas fa-check-circle text-blue-500 text-xl"></i>
                            <span>State-of-the-art research labs</span>
                        </li>
                        <li class="animate-on-scroll flex items-center space-x-3" style="transition-delay: 0.1s">
                            <i class="fas fa-check-circle text-blue-500 text-xl"></i>
                            <span>Industry-relevant projects</span>
                        </li>
                        <li class="animate-on-scroll flex items-center space-x-3" style="transition-delay: 0.2s">
                            <i class="fas fa-check-circle text-blue-500 text-xl"></i>
                            <span>Innovation competitions</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Why Choose BPI? Section -->
        <section class="bg-white p-8 md:p-12 rounded-3xl shadow-xl border-t-4 border-blue-500">
            <!-- Heading -->
            <h2 class="animate-on-scroll text-3xl font-bold mb-6 text-center text-gray-800">
                Why Choose BPI?
            </h2>
            <p class="animate-on-scroll text-center text-gray-600 max-w-3xl mx-auto mb-10"
                style="transition-delay: 0.1s">
                Barisal Polytechnic Institute is more than just an academic institution — it's a hub of innovation,
                creativity, and opportunity. From expert faculty to advanced learning resources, BPI ensures
                that every student is prepared for a successful career and a bright future.
            </p>

            <!-- Cards -->
            <div class="animate-stagger grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-6 bg-gray-50 rounded-xl shadow-md transition transform hover:scale-105">
                    <i class="fas fa-chalkboard-teacher text-5xl text-blue-500 mx-auto mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Experienced Faculty</h3>
                    <p class="text-gray-600 text-sm">
                        Our teachers are not only highly qualified but also bring real-world experience into
                        the classroom. They guide students with personalized attention, mentorship, and
                        practical insights to ensure academic and professional growth.
                    </p>
                </div>

                <div class="p-6 bg-gray-50 rounded-xl shadow-md transition transform hover:scale-105">
                    <i class="fas fa-flask text-5xl text-blue-500 mx-auto mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Modern Labs</h3>
                    <p class="text-gray-600 text-sm">
                        Equipped with the latest technology and tools, our laboratories provide students
                        with hands-on training that bridges theory with practice. This ensures they gain
                        confidence and industry-ready skills before graduation.
                    </p>
                </div>

                <div class="p-6 bg-gray-50 rounded-xl shadow-md transition transform hover:scale-105">
                    <i class="fas fa-briefcase text-5xl text-blue-500 mx-auto mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Career Opportunities</h3>
                    <p class="text-gray-600 text-sm">
                        With strong industry partnerships and a dedicated career support cell, students
                        gain access to internships, job placements, and networking opportunities.
                        BPI graduates are highly sought-after in both local and global industries.
                    </p>
                </div>
            </div>
        </section>

        <!-- Student Life Section (New Section) -->
        <section
            class="relative bg-gradient-to-r from-gray-50 to-white p-8 md:p-16 rounded-3xl shadow-xl overflow-hidden">
            <!-- Decorative Background Shape -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-100 rounded-full blur-3xl opacity-40"></div>

            <!-- Title -->
            <h2
                class="animate-on-scroll text-3xl md:text-4xl font-extrabold mb-12 text-center text-gray-900 tracking-tight">
                Student Life at BPI
            </h2>

            <div class="grid md:grid-cols-2 gap-12 items-center relative z-10">
                <!-- Image Block -->
                <div
                    class="animate-on-scroll-left p-4 rounded-2xl bg-white shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition duration-500">
                    <img src="Images/Student-life.png" alt="Student Activities" class="rounded-2xl w-full object-cover">
                </div>

                <!-- Content Block -->
                <div class="animate-on-scroll-right">
                    <p class="text-gray-700 leading-relaxed mb-6 text-lg text-justify">
                        At <span class="font-semibold text-indigo-700">Barisal Polytechnic Institute</span>, student
                        life goes far beyond traditional classrooms. We believe that holistic development is key to
                        preparing students for a dynamic world. Our campus offers a vibrant ecosystem where learning,
                        creativity, and personal growth go hand in hand.
                        Students can participate in a wide range of extracurricular activities, from academic clubs and
                        debate competitions to sports tournaments, cultural festivals, and music ensembles. We also
                        provide opportunities for leadership, community service, and skill-building workshops, ensuring
                        that every student not only excels academically but also grows socially and professionally.
                        At BPI, we strive to create a supportive and inclusive environment where every student feels
                        empowered to explore their passions, connect with peers, and develop the confidence and skills
                        needed to succeed in their future careers and life beyond college.
                    </p>

                    <ul class="space-y-5 text-gray-800">
                        <li class="animate-on-scroll flex items-center space-x-4">
                            <span
                                class="flex items-center justify-center w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 shadow-md">
                                <i class="fas fa-futbol text-xl"></i>
                            </span>
                            <span class="text-lg font-medium">Sports Clubs & Competitions</span>
                        </li>
                        <li class="animate-on-scroll flex items-center space-x-4" style="transition-delay: 0.1s">
                            <span
                                class="flex items-center justify-center w-12 h-12 rounded-full bg-pink-100 text-pink-600 shadow-md">
                                <i class="fas fa-music text-xl"></i>
                            </span>
                            <span class="text-lg font-medium">Cultural & Music Groups</span>
                        </li>
                        <li class="animate-on-scroll flex items-center space-x-4" style="transition-delay: 0.2s">
                            <span
                                class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100 text-green-600 shadow-md">
                                <i class="fas fa-brain text-xl"></i>
                            </span>
                            <span class="text-lg font-medium">Debate & Science Clubs</span>
                        </li>
                    </ul>

                    <!-- Call to Action -->
                    <div class="mt-8">
                        <a href="#activities"
                            class="inline-block bg-indigo-600 text-white px-8 py-4 rounded-2xl shadow-lg hover:bg-indigo-700 hover:shadow-2xl transition font-semibold text-lg">
                            Explore Activities
                        </a>
                    </div>
                </div>
            </div>
        </section>


        <!-- Our Achievements Section -->
        <section
            class="relative bg-gradient-to-r from-blue-100 to-blue-50 p-8 md:p-12 rounded-3xl shadow-xl overflow-hidden">
            <h2 class="animate-on-scroll text-3xl font-bold mb-12 text-center text-gray-800">
                Our Achievements
            </h2>

            <div class="animate-stagger grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <!-- Card 1 -->
                <div
                    class="p-6 bg-white rounded-xl shadow-lg transform transition duration-500 hover:-translate-y-3 hover:shadow-2xl">
                    <i class="fas fa-medal text-5xl text-yellow-500 mb-4 animate-bounce-slow"></i>
                    <h3 class="text-xl font-semibold text-gray-800">Award-Winning Students</h3>
                    <p class="text-gray-600 text-sm">Our students consistently win national and international
                        competitions.</p>
                </div>

                <!-- Card 2 -->
                <div
                    class="p-6 bg-white rounded-xl shadow-lg transform transition duration-500 hover:-translate-y-3 hover:shadow-2xl">
                    <i class="fas fa-chart-line text-5xl text-green-500 mb-4 animate-bounce-slow"></i>
                    <h3 class="text-xl font-semibold text-gray-800">High Placement Rate</h3>
                    <p class="text-gray-600 text-sm">Over 90% of our graduates secure jobs within six months.</p>
                </div>

                <!-- Card 3 -->
                <div
                    class="p-6 bg-white rounded-xl shadow-lg transform transition duration-500 hover:-translate-y-3 hover:shadow-2xl">
                    <i class="fas fa-project-diagram text-5xl text-blue-500 mb-4 animate-bounce-slow"></i>
                    <h3 class="text-xl font-semibold text-gray-800">Successful Projects</h3>
                    <p class="text-gray-600 text-sm">We are known for our innovative and impactful student projects.</p>
                </div>
            </div>
        </section>

        <!-- Principal's Message Section -->
        <section class="bg-gray-100 p-8 md:p-12 rounded-3xl shadow-xl text-center">
            <div class="max-w-4xl mx-auto">
                <!-- Principal Image -->
                <div
                    class="animate-on-scroll-scale w-32 h-32 mx-auto mb-6 rounded-full overflow-hidden border-4 border-blue-500 shadow-lg">
                    <img src="Images/Principle.jpg" alt="Principal" class="w-full h-full object-cover">
                </div>

                <!-- Heading -->
                <h2 class="animate-on-scroll text-3xl font-bold mb-4 text-gray-800">Message from the Principal
                </h2>

                <!-- Quote -->
                <p class="animate-on-scroll italic text-gray-600 mb-6" style="transition-delay: 0.1s">
                    "Education is the most powerful weapon which you can use to change the world."
                </p>

                <!-- Message -->
                <p class="animate-on-scroll text-gray-700 leading-relaxed" style="transition-delay: 0.2s">
                    "It is my great pleasure to welcome you to Barisal Polytechnic Institute, a place where dreams take
                    flight and futures are shaped. We are dedicated to providing an environment that encourages
                    intellectual curiosity, fosters creativity, and promotes a strong sense of community. Our faculty
                    members are not just teachers; they are mentors and guides who are committed to your success. Join
                    us, and let's build a brighter future together."
                </p>

                <!-- Signature -->
                <p class="animate-on-scroll font-semibold mt-4" style="transition-delay: 0.3s">
                    - Engineer Md. Rakib Ullah
                </p>
            </div>
        </section>

        <!-- Contact Us Section -->
        <section class="bg-gray-100 p-8 md:p-12 rounded-3xl shadow-xl">
            <h2 class="animate-on-scroll text-3xl font-bold mb-8 text-center text-gray-800">Contact Us</h2>
            <div class="animate-stagger grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="flex flex-col items-center p-6 bg-white rounded-lg shadow-sm">
                    <h3 class="text-xl font-semibold mb-2">Address</h3>
                    <p class="text-gray-600 text-center">Barisal Polytechnic Institute, Barisal Sadar-8200, Barishal,
                        Bangladesh</p>
                </div>
                <div class="flex flex-col items-center p-6 bg-white rounded-lg shadow-sm">
                    <h3 class="text-xl font-semibold mb-2">Get in Touch</h3>
                    <ul class="space-y-2 text-gray-600 text-center">
                        <li><a href="tel:+8801711234567" class="hover:text-blue-600 transition duration-300">Phone: +880
                                1711-234567</a></li>
                        <li><a href="mailto:info@bpi.edu.bd" class="hover:text-blue-600 transition duration-300">Email:
                                info@bpi.edu.bd</a></li>
                    </ul>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer Section -->
    <?php include_once '../includes/footer.php'; ?>

    <!-- Back to Top Button -->
    <button id="backToTop"
        class="fixed bottom-6 right-6 bg-indigo-600 hover:bg-indigo-700 text-white p-4 rounded-full shadow-lg transition duration-300 hidden z-50">
        <!-- Up Arrow Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>
    </button>

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

        const backToTop = document.getElementById("backToTop");

        window.addEventListener("scroll", () => {
            if (window.scrollY > 300) {
                backToTop.classList.remove("hidden");
            } else {
                backToTop.classList.add("hidden");
            }
        });

        // Smooth Scroll to Top
        backToTop.addEventListener("click", () => {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });

        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, observerOptions);

        // Observe all elements with animation classes
        document.querySelectorAll('.animate-on-scroll, .animate-on-scroll-left, .animate-on-scroll-right, .animate-on-scroll-scale').forEach(el => {
            observer.observe(el);
        });

        // Observe stagger animation containers
        document.querySelectorAll('.animate-stagger').forEach(container => {
            observer.observe(container);
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>