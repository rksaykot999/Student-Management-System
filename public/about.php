
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About BPI</title>
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
        <div class="p-8 md:p-12 rounded-3xl h-500px] mx-auto max-w-3xl text-center transform transition-transform">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">About Our Institute</h1>
            <p class="text-lg md:text-xl text-gray-200 mb-8">
                Leading the way in technical education and innovation since its inception.
            </p>
        </div>
    </header>

    <!-- Main Content Sections -->
    <main class="container mx-auto p-6 md:p-12 space-y-16">
        
        <!-- Welcome Section -->
        <section class="bg-white p-8 md:p-12 rounded-3xl shadow-xl">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="p-4 rounded-lg bg-gray-50">
                    <img src="https://github.com/rksaykot999/BPI-Student-Portal/blob/main/public/images/2022-10-05.jpg?raw=true" alt="Welcome to BPI" class="rounded-2xl shadow-lg w-full">
                </div>
                <div>
                    <h2 class="text-3xl font-bold mb-4 text-gray-800">Welcome to Barisal Polytechnic Institute</h2>
                    <p class="text-gray-700 leading-relaxed mb-4 text-justify">
                        Barisal Polytechnic Institute (BPI) is a beacon of technical excellence in Bangladesh. Established with the vision to foster innovation and practical skills, we have been shaping the future of young engineers and technicians for decades. Our commitment to providing a high-quality, hands-on learning experience sets us apart.
                    </p>
                    <p class="text-gray-700 leading-relaxed text-justify">
                        We believe in a holistic education that not only equips students with technical expertise but also instills strong ethical values and leadership qualities. Our campus is a vibrant community where students can thrive both academically and personally.
                    </p>
                </div>
            </div>
        </section>

        <!-- Mission & Vision Section -->
        <section class="bg-blue-50 p-8 md:p-12 rounded-3xl shadow-xl">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold mb-4 text-gray-800">Our Mission & Vision</h2>
                    <p class="text-gray-700 leading-relaxed mb-4 text-justify">
                        Our **mission** is to provide an inclusive and dynamic learning environment that prepares students for the challenges of a rapidly evolving technological world. We aim to produce highly skilled, competent, and socially responsible graduates who can contribute significantly to the national and global economy.
                    </p>
                    <p class="text-gray-700 leading-relaxed text-justify">
                        Our **vision** is to be a nationally recognized leader in technical and vocational education, known for our innovative curriculum, cutting-edge research, and strong industry partnerships. We aspire to empower every student to achieve their full potential and become an agent of positive change.
                    </p>
                </div>
                <div class="p-4 rounded-lg bg-white">
                    <img src="https://github.com/rksaykot999/BPI-Student-Portal/blob/main/public/images/2021-08-03.jpg?raw=true" alt="Mission & Vision" class="rounded-2xl shadow-lg w-full">
                </div>
            </div>
        </section>

        <!-- Our History Section -->
        <section class="bg-white p-8 md:p-12 rounded-3xl shadow-xl border-t-4 border-blue-500">
            <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Our History</h2>
            <div class="max-w-4xl mx-auto text-gray-700 leading-relaxed">
                <p class="mb-4 text-justify">
                    Barisal Polytechnic Institute was established in 1962 and has since played a pivotal role in the technical education landscape of Bangladesh. Starting with a few departments, the institute has grown significantly over the decades, adding more courses and modern facilities to meet the growing demands of the industry.
                </p>
                <p class="mb-4 text-justify">
                    Throughout its history, BPI has been committed to adapting its curriculum to stay ahead of technological advancements. Our alumni have gone on to achieve great success in various fields, both locally and internationally, a testament to the quality of education provided here.
                </p>
                <p class="text-justify ">
                    Today, we stand as a premier institution, proud of our heritage and excited about the future. We continue to innovate and expand our offerings to provide the best possible education for our students.
                </p>
            </div>
        </section>
        
        <!-- Contact Section from Homepage -->
        <section class="bg-gray-100 p-8 md:p-12 rounded-3xl shadow-xl">
            <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Contact Us</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="flex flex-col items-center p-6 bg-white rounded-lg shadow-sm">
                    <h3 class="text-xl font-semibold mb-2">Address</h3>
                    <p class="text-gray-600 text-center">Barisal Polytechnic Institute, Barisal Sadar-8200, Barishal,
                        Bangladesh</p>
                </div>
                <div class="flex flex-col items-center p-6 bg-white rounded-lg shadow-sm">
                    <h3 class="text-xl font-semibold mb-2">Get in Touch</h3>
                    <ul class="space-y-2 text-gray-600 text-center">
                        <li><a href="tel:+8801711234567" class="hover:text-blue-600 transition duration-300">Phone: +880 1711-234567</a></li>
                        <li><a href="mailto:info@bpi.edu.bd" class="hover:text-blue-600 transition duration-300">Email: info@bpi.edu.bd</a></li>
                    </ul>
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
