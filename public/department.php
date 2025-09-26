<?php
// This is the header content, moved here for a self-contained, single file.
if (session_status() == PHP_SESSION_NONE)
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments</title>

    <!-- Use Tailwind CSS for rapid styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f4f8;
        }

        .header-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url("Images/Hero.jpg") no-repeat center center;
            background-size: cover;
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

        /* Modal specific styling */
        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .dropdown-menu {
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }
    </style>
</head>

<body class="text-gray-800">

    <!-- Navbar -->
    <!-- The header content from header.php is included here for a single file solution -->
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

    <!-- Department data is defined here -->
    <?php
    $departments = [
        [
            'name' => 'Civil Technology',
            'short_name' => 'CVL',
            'icon' => 'fas fa-hard-hat',
            'description' => 'Design & construction of infrastructure — roads, bridges & buildings. Strong structural labs.',
            'image' => 'https://placehold.co/400x300/d1d5db/374151?text=Civil+Dept',
            'full_details' => 'Civil Technology focuses on the design, construction, and maintenance of the physical and naturally built environment, including public works such as roads, bridges, canals, dams, and buildings. Our program includes coursework in structural analysis, geotechnical engineering, and construction management, complemented by hands-on experience in well-equipped structural and soil mechanics labs.',
            'curriculum_highlights' => [
                'Structural Analysis & Design',
                'Geotechnical & Soil Mechanics',
                'Construction Management',
                'Highway & Transportation Engineering',
                'Water & Environmental Systems'
            ],
            'career_paths' => [
                'Civil Engineer',
                'Construction Manager',
                'Surveyor',
                'Urban Planner',
                'Structural Designer'
            ]
        ],
        [
            'name' => 'Electrical Technology',
            'short_name' => 'ET',
            'icon' => 'fas fa-bolt',
            'description' => 'Electricity, electronics, control systems & renewable energy technologies with lab practice.',
            'image' => 'https://placehold.co/400x300/fecaca/dc2626?text=ET+Dept',
            'full_details' => 'This department provides comprehensive hands-on training in the core principles of electrical engineering. The curriculum covers electrical circuits, power systems, electronics, and control systems. Students gain practical experience in our advanced labs, working with high-voltage equipment and gaining valuable skills in areas like renewable energy and industrial automation.',
            'curriculum_highlights' => [
                'Electrical Circuit Theory',
                'Power Systems & Generation',
                'Industrial Automation & Robotics',
                'Renewable Energy Sources',
                'Microprocessor Systems'
            ],
            'career_paths' => [
                'Electrical Engineer',
                'Power Systems Technician',
                'Electronics Designer',
                'Industrial Electrician',
                'Control Systems Specialist'
            ]
        ],
        [
            'name' => 'Mechanical Technology',
            'short_name' => 'MCT',
            'icon' => 'fas fa-gears',
            'description' => 'Machines, thermodynamics, fluid mechanics & manufacturing — strong workshop and CAD practice.',
            'image' => 'https://placehold.co/400x300/dbeafe/1e40af?text=MCT+Dept',
            'full_details' => 'Students in Mechanical Technology learn the principles of motion, energy, and the design of mechanical systems. Our program combines theoretical knowledge with practical skills in areas such as thermodynamics, fluid mechanics, and manufacturing processes. We have a fully-equipped workshop and offer extensive practice with Computer-Aided Design (CAD) software.',
            'curriculum_highlights' => [
                'Thermodynamics & Heat Transfer',
                'Fluid Mechanics',
                'Machine Design & Manufacturing',
                'Computer-Aided Design (CAD)',
                'Materials Science'
            ],
            'career_paths' => [
                'Mechanical Engineer',
                'CAD Designer',
                'Quality Control Inspector',
                'Maintenance Technician',
                'HVAC Technician'
            ]
        ],
        [
            'name' => 'Computer Technology',
            'short_name' => 'CT',
            'icon' => 'fas fa-laptop-code',
            'description' => 'Modern computing, software development, networking & hardware design. Hands-on labs & projects.',
            'image' => 'https://placehold.co/400x300/e0e7ff/6366f1?text=CT+Dept',
            'full_details' => 'The Computer Technology department provides a robust curriculum in the foundational principles of computing. Students explore topics in software engineering, database management, web development, and cybersecurity. Our program emphasizes practical skills through hands-on labs and real-world projects, preparing graduates for high-demand roles in the tech industry. Facilities include modern computer labs with the latest software and equipment.',
            'curriculum_highlights' => [
                'Algorithms & Data Structures',
                'Web & Mobile Application Development',
                'Database Management Systems',
                'Network Security & Administration',
                'Cloud Computing Fundamentals'
            ],
            'career_paths' => [
                'Software Developer',
                'Network Administrator',
                'Cybersecurity Analyst',
                'Database Manager',
                'IT Support Specialist'
            ]
        ],
        [
            'name' => 'Automobile Technology',
            'short_name' => 'AT',
            'icon' => 'fas fa-car-side',
            'description' => 'The Automobile Technology program covers everything from engine diagnostics to vehicle repair and maintenance.',
            'image' => 'https://placehold.co/400x300/fce7f3/9d174d?text=AT+Dept',
            'full_details' => 'The Automobile Technology program covers everything from engine diagnostics to vehicle repair and maintenance. It is designed to prepare students for a career in the automotive industry. The curriculum includes hands-on training with modern diagnostic tools and repair techniques, focusing on both traditional and electric vehicle systems.',
            'curriculum_highlights' => [
                'Engine Diagnosis & Repair',
                'Brake & Suspension Systems',
                'Automotive Electronics',
                'Vehicle Maintenance & Servicing',
                'Hybrid & Electric Vehicle Technology'
            ],
            'career_paths' => [
                'Automotive Technician',
                'Service Advisor',
                'Shop Foreman',
                'Automotive Parts Manager',
                'Vehicle Inspector'
            ]
        ],
        [
            'name' => 'Electronics Technology',
            'short_name' => 'ELT',
            'icon' => 'fas fa-microchip',
            'description' => 'Microcontrollers, circuit design & communication systems — ideal for embedded systems projects.',
            'image' => 'https://placehold.co/400x300/fce7f3/9d174d?text=AT+Dept',
            'full_details' => 'This department delves into the world of microelectronics and circuit design. Students learn about microcontrollers, digital and analog circuit design, and communication systems. The curriculum is project-based, giving students the skills needed for careers in embedded systems, telecommunications, and robotics.',
            'curriculum_highlights' => [
                'Digital & Analog Circuit Design',
                'Microcontroller Programming',
                'Embedded Systems',
                'Robotics Fundamentals',
                'Data Communication Systems'
            ],
            'career_paths' => [
                'Electronics Technician',
                'Embedded Systems Developer',
                'Robotics Engineer',
                'Telecommunications Specialist',
                'Field Service Engineer'
            ]
        ],
        [
            'name' => 'Power Technology',
            'short_name' => 'PT',
            'icon' => 'fas fa-solar-panel',
            'description' => 'Power generation, transmission & smart grid topics; practical labs with high-voltage safety training.',
            'image' => 'https://placehold.co/400x300/dbeafe/1e40af?text=MCT+Dept',
            'full_details' => 'The Power Technology program focuses on the principles of power generation, transmission, and distribution. Students will study topics like smart grid technologies and power system analysis. The department is equipped with labs for practical experience, including essential safety training for working with high-voltage systems.',
            'curriculum_highlights' => [
                'Power Generation & Distribution',
                'Smart Grid Technology',
                'Power System Analysis',
                'High Voltage Safety',
                'Electrical Machine Operation'
            ],
            'career_paths' => [
                'Power Plant Operator',
                'Electrical Utility Technician',
                'Grid System Analyst',
                'Renewable Energy Specialist',
                'Substation Technician'
            ]
        ],
        [
            'name' => 'Tourism & Hospitality',
            'short_name' => 'TH',
            'icon' => 'fas fa-hotel',
            'description' => 'Hotel management, event planning, travel operations & customer service — real-world internships available.',
            'image' => 'https://placehold.co/400x300/d1d5db/374151?text=Civil+Dept',
            'full_details' => 'The Tourism & Hospitality program prepares students for careers in the service industry. The curriculum covers hotel management, event planning, travel operations, and customer service. Students gain valuable experience through real-world internships and practical exercises, ensuring they are ready for the global tourism market.',
            'curriculum_highlights' => [
                'Hotel & Resort Management',
                'Event & Conference Planning',
                'Tourism Marketing',
                'Customer Relationship Management',
                'Culinary Arts Basics'
            ],
            'career_paths' => [
                'Hotel Manager',
                'Event Planner',
                'Tour Guide',
                'Travel Agent',
                'Restaurant Manager'
            ]
        ],
        [
            'name' => 'Electromedical Technology',
            'short_name' => 'EMT',
            'icon' => 'fas fa-heart-pulse',
            'description' => 'Biomedical instruments, hospital equipment maintenance & diagnostics — prepares students for healthcare tech roles.',
            'image' => 'https://placehold.co/400x300/e0e7ff/6366f1?text=CT+Dept',
            'full_details' => 'This specialized program trains students in the maintenance and operation of biomedical instruments and hospital equipment. Coursework covers medical device principles, diagnostics, and regulatory standards. Graduates are prepared for technical roles in hospitals, clinics, and medical equipment manufacturing companies.',
            'curriculum_highlights' => [
                'Medical Equipment Maintenance',
                'Biomedical Instrumentation',
                'Diagnostic Imaging Systems',
                'Patient Monitoring Technologies',
                'Health & Safety Regulations'
            ],
            'career_paths' => [
                'Biomedical Technician',
                'Hospital Equipment Specialist',
                'Medical Device Repairer',
                'Clinical Engineer',
                'Healthcare IT Specialist'
            ]
        ]
    ];
    ?>

    <!-- Hero Section -->
    <header class="header-hero pt-12 text-white">
        <div class="p-4 md:p-12 rounded-3xl h-500px] mx-auto max-w-3xl text-center transform transition-transform">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">Our Departments</h1>
            <p class="text-lg md:text-xl text-gray-200 mb-8">
                Explore a wide range of technical disciplines and start your career journey.
            </p>
        </div>
    </header>

    <!-- Main Content Sections -->
    <main class="container mx-auto p-6 md:p-12 space-y-16">
        <section class="bg-white p-8 md:p-12 rounded-3xl shadow-xl">
            <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Explore Our Technical Departments</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($departments as $dept) { ?>
                    <!-- Department Card -->
                    <div class="bg-gray-50 p-6 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 cursor-pointer view-details-btn"
                        data-short-name="<?= htmlspecialchars($dept['short_name']); ?>">
                        <div class="h-48 w-full rounded-lg mb-4 overflow-hidden flex items-center justify-center bg-gradient-to-br from-indigo-600 to-blue-500">
                            <!-- Dynamically set icon based on department -->
                            <i class="<?= htmlspecialchars($dept['icon']); ?> text-6xl text-white" aria-hidden="true"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($dept['name']); ?></h3>
                        <p class="text-gray-600 text-sm mb-4">
                            <?= htmlspecialchars($dept['description']); ?>
                        </p>

                        <div class="flex items-center gap-3 justify-between">
                            <span class="inline-block px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">Diploma</span>
                            <span class="inline-block px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">4 Years</span>
                        </div>

                    </div>
                <?php } ?>
            </div>
        </section>

        <!-- Why Choose Our Departments Section -->
        <section class="bg-blue-50 p-8 md:p-12 rounded-3xl shadow-xl">
            <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Why Choose Our Departments?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-6 bg-white rounded-2xl shadow-md">
                    <div class="text-5xl text-blue-600 mb-4"><i class="fas fa-handshake"></i></div>
                    <h3 class="text-xl font-semibold mb-2">Industry-Relevant Curriculum</h3>
                    <p class="text-gray-600 text-sm">Our courses are designed with input from industry experts to ensure students are well-prepared for market demands.</p>
                </div>
                <div class="p-6 bg-white rounded-2xl shadow-md">
                    <div class="text-5xl text-blue-600 mb-4"><i class="fas fa-flask"></i></div>
                    <h3 class="text-xl font-semibold mb-2">State-of-the-Art Lab Facilities</h3>
                    <p class="text-gray-600 text-sm">We provide hands-on learning with advanced equipment and high-quality lab facilities.</p>
                </div>
                <div class="p-6 bg-white rounded-2xl shadow-md">
                    <div class="text-5xl text-blue-600 mb-4"><i class="fas fa-users-gear"></i></div>
                    <h3 class="text-xl font-semibold mb-2">Experienced Faculty</h3>
                    <p class="text-gray-600 text-sm">Our teachers are highly experienced and dedicated to preparing students for their future careers.</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal Pop-up -->
    <div id="details-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
        <!-- Overlay -->
        <div class="modal-overlay absolute inset-0 bg-black bg-opacity-50"></div>

        <!-- Modal Content -->
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl p-8 relative z-10 transform transition-transform duration-300 scale-95
                max-h-[90vh] overflow-y-auto">
            <button id="close-modal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-2xl"></i>
            </button>

            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-full flex items-center justify-center bg-blue-600 text-white text-3xl">
                    <i id="modal-icon" class="fas fa-question"></i>
                </div>
                <h3 id="modal-title" class="text-3xl font-bold text-gray-800"></h3>
            </div>

            <!-- About Section -->
            <h4 class="text-xl font-semibold text-gray-800 mt-4 mb-2">About the Program</h4>
            <p id="modal-about" class="text-gray-700 leading-relaxed mb-6"></p>

            <!-- Curriculum Highlights Section -->
            <h4 class="text-xl font-semibold text-gray-800 mb-2">Program Highlights</h4>
            <ul id="modal-curriculum" class="list-disc list-inside space-y-1 text-gray-700 mb-6"></ul>

            <!-- Career Paths Section -->
            <h4 class="text-xl font-semibold text-gray-800 mb-2">Career Opportunities</h4>
            <ul id="modal-careers" class="list-disc list-inside space-y-1 text-gray-700"></ul>
        </div>
    </div>


    <!-- The footer file is commented out, as it is not provided -->
    <!-- <?php include_once '../includes/footer.php'; ?> -->

    <script>
        const loginButton = document.getElementById('logoButton');
        const loginDropdown = document.getElementById('loginDropdown');
        const mobileMenuButton = document.getElementById('menuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileLoginButton = document.getElementById('mobileLoginButton');

        // Toggle the dropdown menu on button click
        if (loginButton) {
            loginButton.addEventListener('click', (event) => {
                loginDropdown.classList.toggle('show');
                event.stopPropagation();
            });
        }

        // Hide the dropdown when clicking outside of it
        window.addEventListener('click', (event) => {
            if (!loginDropdown.contains(event.target) && loginButton && !loginButton.contains(event.target)) {
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

        // --- New JavaScript for Modal functionality ---
        const departmentsData = <?= json_encode($departments); ?>;

        const detailsModal = document.getElementById('details-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalIcon = document.getElementById('modal-icon');
        const modalAbout = document.getElementById('modal-about');
        const modalCurriculum = document.getElementById('modal-curriculum');
        const modalCareers = document.getElementById('modal-careers');
        const closeModalBtn = document.getElementById('close-modal');
        const modalOverlay = document.querySelector('.modal-overlay');

        // Event listener for opening the modal
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', (event) => {
                const card = event.target.closest('[data-short-name]');
                const shortName = card.dataset.shortName;

                // Find the department data that matches the short name
                const department = departmentsData.find(d => d.short_name === shortName);

                if (department) {
                    modalTitle.textContent = department.name;
                    modalIcon.className = department.icon;
                    modalAbout.textContent = department.full_details;

                    // Populate curriculum highlights
                    modalCurriculum.innerHTML = ''; // Clear previous list
                    department.curriculum_highlights.forEach(item => {
                        const li = document.createElement('li');
                        li.textContent = item;
                        modalCurriculum.appendChild(li);
                    });

                    // Populate career paths
                    modalCareers.innerHTML = ''; // Clear previous list
                    department.career_paths.forEach(item => {
                        const li = document.createElement('li');
                        li.textContent = item;
                        modalCareers.appendChild(li);
                    });

                    detailsModal.classList.remove('hidden');
                    // Add a scale-up animation
                    setTimeout(() => {
                        detailsModal.querySelector('.bg-white').style.transform = 'scale(1)';
                    }, 10);
                }
            });
        });

        // Event listener for closing the modal
        const closeModal = () => {
            detailsModal.querySelector('.bg-white').style.transform = 'scale(0.95)';
            setTimeout(() => {
                detailsModal.classList.add('hidden');
            }, 300);
        };

        closeModalBtn.addEventListener('click', closeModal);
        modalOverlay.addEventListener('click', closeModal);
    </script>
</body>

</html>