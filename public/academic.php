<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Department Book List - Complete</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f0f4f8;
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

    .header-hero {
      background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url("Images/Hero.jpg") no-repeat center center;
      background-size: cover;
      /* height: 50vh; */
    }

    .accordion-button:not(.collapsed) {
      color: #fff;
      background: linear-gradient(90deg, #3498db, #2ecc71);
      box-shadow: none;
    }

    .table-scroll {
      overflow: auto;
      -webkit-overflow-scrolling: touch;
      padding: 18px;
    }

    .table-scroll::-webkit-scrollbar {
      height: 10px;
      width: 12px;
    }

    .table-scroll::-webkit-scrollbar-track {
      background: transparent;
    }

    .table-scroll::-webkit-scrollbar-thumb {
      background: linear-gradient(180deg, rgba(30, 136, 229, 0.9), rgba(0, 200, 83, 0.9));
      border-radius: 10px;
      border: 2px solid rgba(255, 255, 255, 0.15);
    }

    table.markstable {
      border-collapse: separate;
      border-spacing: 0;
      width: 100%;
      min-width: 1200px;
      table-layout: fixed;
      font-size: 14px;
    }

    table.markstable th,
    table.markstable td {
      border: 1px solid #222;
      padding: 8px 10px;
      text-align: center;
      vertical-align: middle;
    }

    table.markstable thead th {
      background: #fafafa;
      font-weight: 800;
      color: #111827;
    }

    .group-head {
      background: linear-gradient(180deg, #3498db, #1b6fb0);
      color: #fff;
      font-weight: 800;
    }

    .col-credit {
      background: #fff08a;
      font-weight: 700;
    }

    .col-theory {
      background: #d7eefc;
    }

    .col-practical {
      background: #e9f8f1;
    }

    .col-grand {
      background: #ffd8c2;
      font-weight: 800;
    }

    .sl {
      width: 56px;
      font-weight: 700;
    }

    .code {
      width: 90px;
    }

    .name {
      text-align: left;
      padding-left: 12px;
    }

    table.markstable thead th {
      position: sticky;
      top: 0;
      z-index: 5;
    }

    tr.totals td {
      background: #d9f0e6;
      font-weight: 800;
    }

    tr.footer-row td {
      background: #f3fbff;
      font-weight: 700;
    }

    table.markstable tbody tr:hover {
      background: rgba(30, 136, 229, 0.04);
    }

    @media (max-width:700px) {
      table.markstable {
        min-width: 1000px;
        font-size: 13px;
      }
    }
  </style>
</head>


<body class="bg-gray-50 font-roboto">

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
        <span class="font-bold">Login</span>
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


  <!-- Header -->
  <!-- Hero Section -->
  <header class="header-hero pt-12 text-white">
    <div class="p-4 md:p-12 rounded-3xl h-500px] mx-auto max-w-4xl text-center transform transition-transform">
      <h1 class="text-4xl md:text-6xl font-bold mb-4">Academic Programs</h1>
      <p class="text-lg md:text-xl text-gray-200 mb-8">
        Explore the Diploma in Engineering courses offered at Barisal Polytechnic Institute.
      </p>
    </div>
  </header>

  <!-- Main Content Sections -->
  <main class="container mx-auto p-6 md:p-12 space-y-16">

    <!-- About Diploma in Engineering Section -->
    <section class="bg-white p-8 md:p-12 rounded-3xl shadow-xl">
      <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Diploma in Engineering</h2>
      <div class="grid md:grid-cols-2 gap-12 items-center">
        <div class="p-4 rounded-lg bg-gray-50">
          <img src="Images/Diploma-in-Engineering.png" alt="Diploma in Engineering"
            class="rounded-2xl shadow-lg w-full">
        </div>
        <div>
          <p class="text-gray-700 leading-relaxed mb-4 text-justify">
            The Diploma in Engineering program at Barisal Polytechnic Institute is a 4-year, 8-semester course designed
            to provide students with the foundational knowledge and practical skills required for a successful career in
            various engineering fields. The curriculum is developed by the Bangladesh Technical Education Board (BTEB)
            to ensure industry relevance and high academic standards.
          </p>
          <ul class="space-y-4 text-gray-700">
            <li class="flex items-start space-x-2">
              <i class="fas fa-check-circle text-blue-500 mt-1"></i>
              <span>Comprehensive curriculum with a focus on practical application.</span>
            </li>
            <li class="flex items-start space-x-2">
              <i class="fas fa-check-circle text-blue-500 mt-1"></i>
              <span>State-of-the-art laboratories and workshops for hands-on learning.</span>
            </li>
            <li class="flex items-start space-x-2">
              <i class="fas fa-check-circle text-blue-500 mt-1"></i>
              <span>Experienced faculty with both academic and industrial knowledge.</span>
            </li>
            <li class="flex items-start space-x-2">
              <i class="fas fa-check-circle text-blue-500 mt-1"></i>
              <span>Strong emphasis on research and innovation.</span>
            </li>
          </ul>
        </div>
      </div>
    </section>

    <!-- Academic Departments Section -->
    <section id="courses" class="bg-blue-50 p-8 md:p-12 rounded-3xl shadow-xl">
      <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Our Diploma Programs</h2>
      <div class="animate-stagger grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

        <!-- Civil Engineering -->
        <div class="bg-white p-6 rounded-2xl shadow-md text-center transition transform hover:scale-105 hover:shadow-xl">
          <i class="fas fa-drafting-compass text-5xl text-green-600 mb-4"></i>
          <h3 class="text-xl font-semibold text-gray-800">Civil Technology</h3>
          <p class="text-gray-600 mt-2 text-sm">
            Learn structural design, surveying, bridge construction, urban planning, and sustainable infrastructure development for modern cities.
          </p>
        </div>

        <!-- Electrical Technology -->
        <div class="bg-white p-6 rounded-2xl shadow-md text-center transition transform hover:scale-105 hover:shadow-xl">
          <i class="fas fa-bolt text-5xl text-yellow-500 mb-4"></i>
          <h3 class="text-xl font-semibold text-gray-800">Electrical Technology</h3>
          <p class="text-gray-600 mt-2 text-sm">
            Gain expertise in electrical circuits, power systems, electronics, renewable energy, and smart electrical grids for the future.
          </p>
        </div>

        <!-- Mechanical Technology -->
        <div class="bg-white p-6 rounded-2xl shadow-md text-center transition transform hover:scale-105 hover:shadow-xl">
          <i class="fas fa-industry text-5xl text-red-600 mb-4"></i>
          <h3 class="text-xl font-semibold text-gray-800">Mechanical Technology</h3>
          <p class="text-gray-600 mt-2 text-sm">
            Explore thermodynamics, robotics, advanced manufacturing, automobile engineering, and machine design to shape modern industries.
          </p>
        </div>

        <!-- Computer Science -->
        <div class="bg-white p-6 rounded-2xl shadow-md text-center transition transform hover:scale-105 hover:shadow-xl">
          <i class="fas fa-code text-5xl text-blue-600 mb-4"></i>
          <h3 class="text-xl font-semibold text-gray-800">Computer Science</h3>
          <p class="text-gray-600 mt-2 text-sm">
            Study programming, database management, artificial intelligence, software development, and cutting-edge web technologies to build the digital future.
          </p>
        </div>

        <!-- Electronics Engineering -->
        <div class="bg-white p-6 rounded-2xl shadow-md text-center transition transform hover:scale-105 hover:shadow-xl">
          <i class="fas fa-satellite-dish text-5xl text-indigo-600 mb-4"></i>
          <h3 class="text-xl font-semibold text-gray-800">Electronics Engineering</h3>
          <p class="text-gray-600 mt-2 text-sm">
            Work with microcontrollers, embedded systems, communication devices, and electronic circuit design for modern innovations.
          </p>
        </div>

        <!-- Power Technology -->
        <div class="bg-white p-6 rounded-2xl shadow-md text-center transition transform hover:scale-105 hover:shadow-xl">
          <i class="fas fa-charging-station text-5xl text-orange-500 mb-4"></i>
          <h3 class="text-xl font-semibold text-gray-800">Power Technology</h3>
          <p class="text-gray-600 mt-2 text-sm">
            Specialize in power generation, transmission, distribution, high-voltage engineering, and smart energy solutions.
          </p>
        </div>

        <!-- Tourism & Hospitality -->
        <div class="bg-white p-6 rounded-2xl shadow-md text-center transition transform hover:scale-105 hover:shadow-xl">
          <i class="fas fa-hotel text-5xl text-pink-500 mb-4"></i>
          <h3 class="text-xl font-semibold text-gray-800">Tourism & Hospitality</h3>
          <p class="text-gray-600 mt-2 text-sm">
            Build a career in hotel management, travel operations, event planning, food services, and international hospitality industries.
          </p>
        </div>

        <!-- Electromedical Technology -->
        <div class="bg-white p-6 rounded-2xl shadow-md text-center transition transform hover:scale-105 hover:shadow-xl">
          <i class="fas fa-heartbeat text-5xl text-teal-500 mb-4"></i>
          <h3 class="text-xl font-semibold text-gray-800">Electromedical Technology</h3>
          <p class="text-gray-600 mt-2 text-sm">
            Focus on biomedical engineering, diagnostic equipment, hospital machines, and medical technology that saves lives.
          </p>
        </div>

      </div>
    </section>


    <!-- Student Life & Academics -->
    <section class="bg-white p-8 md:p-12 rounded-3xl shadow-xl">
      <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Academic & Extracurricular Life</h2>
      <div class="grid md:grid-cols-2 gap-12 items-center">
        <div>
          <img src="https://img.lovepik.com/desgin_photo/45003/8608_list.jpg" alt="Student Activities"
            class="rounded-2xl shadow-lg w-full">
        </div>
        <div>
          <p class="text-gray-700 leading-relaxed mb-4 text-justify">
            Beyond the classroom, BPI encourages students to participate in academic clubs and competitions. Our
            students regularly take part in programming contests, science fairs, and engineering projects, often
            bringing home top prizes and accolades.
          </p>
          <ul class="space-y-4 text-gray-700">
            <li class="flex items-center space-x-2">
              <i class="fas fa-microchip text-blue-500"></i>
              <span>Robotics and Electronics Club</span>
            </li>
            <li class="flex items-center space-x-2">
              <i class="fas fa-code text-blue-500"></i>
              <span>BPI Programming Society</span>
            </li>
            <li class="flex items-center space-x-2">
              <i class="fas fa-chart-bar text-blue-500"></i>
              <span>Science and Innovation Club</span>
            </li>
          </ul>
        </div>
      </div>
    </section>

  </main>
  <!-- Depertment Book list -->
  <section class="container mx-auto px-4 bg-white p-8 md:p-12 mt-12 rounded-3xl">
    <div class="flex-grow">
      <h1 class="text-3xl font-bold text-center mb-3">Department Course Structures</h1>
      <p class="text-center text-gray-600 mb-8">All semesters each department's books information.</p>

      <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8" id="departmentAccordion">

        <!-- COMPUTER SCIENCE & TECHNOLOGY (CST) -->
        <div class="border-b">
          <div class="accordion-header" id="hdr-cst">
            <button
              class="accordion-button w-full text-left p-4 font-semibold text-lg text-gray-800 bg-gray-50 hover:bg-gray-100 flex justify-between items-center"
              type="button" data-bs-toggle="collapse" data-bs-target="#deptCST">
              <span>Computer Science & Technology (CST) <span
                  class="ml-3 bg-blue-100 text-blue-600 text-sm font-medium px-3 py-1 rounded-full">CST</span></span>
              <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
          </div>
          <div id="deptCST" class="accordion-collapse hidden" aria-labelledby="hdr-cst">
            <div class="p-4 bg-white">
              <div class="space-y-2" id="cstSemesters">
                <!-- CST Sem 1 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#cstSem1">Semester 1</button>
                  </div>
                  <div id="cstSem1" class="accordion-collapse hidden">
                    <div class="p-3">
                      <div class="table-scroll">
                        <table class="markstable" role="table" aria-label="Marks distribution">
                          <thead>
                            <tr>
                              <th class="sl" rowspan="3">Sl.<br>No.</th>
                              <th colspan="2" rowspan="2">Subject Code &amp; Name</th>
                              <th colspan="2" rowspan="2">Period Per Week</th>
                              <th class="col-credit" rowspan="3">Credit</th>
                              <th colspan="6" class="group-head">Marks Distribution</th>
                              <th class="col-grand" rowspan="3">Grand<br />Total</th>
                            </tr>

                            <tr>
                              <th colspan="3" class="group-head">Theory Assessment</th>
                              <th colspan="3" class="group-head">Practical Assessment</th>
                            </tr>

                            <tr>
                              <th class="code">Code</th>
                              <th class="name">Name</th>

                              <th>Theory</th>
                              <th>Practical</th>

                              <th class="col-theory">Continuous</th>
                              <th class="col-theory">Final</th>
                              <th class="col-theory">Total</th>

                              <th class="col-practical">Continuous</th>
                              <th class="col-practical">Final</th>
                              <th class="col-practical">Total</th>
                            </tr>
                          </thead>

                          <tbody>
                            <!-- rows (sample) -->
                            <tr>
                              <td>1</td>
                              <td class="code">21011</td>
                              <td class="name">Engineering Drawing</td>
                              <td>–</td>
                              <td>6</td>
                              <td class="col-credit">2</td>
                              <td class="col-theory">-</td>
                              <td class="col-theory">-</td>
                              <td class="col-theory">-</td>
                              <td class="col-practical">50</td>
                              <td class="col-practical">50</td>
                              <td class="col-practical">100</td>
                              <td class="col-grand">100</td>
                            </tr>

                            <tr>
                              <td>2</td>
                              <td class="code">25711</td>
                              <td class="name">Bangla-I</td>
                              <td>2</td>
                              <td>–</td>
                              <td class="col-credit">2</td>
                              <td class="col-theory">40</td>
                              <td class="col-theory">60</td>
                              <td class="col-theory">100</td>
                              <td class="col-practical">-</td>
                              <td class="col-practical">-</td>
                              <td class="col-practical">-</td>
                              <td class="col-grand">100</td>
                            </tr>

                            <tr>
                              <td>3</td>
                              <td class="code">25712</td>
                              <td class="name">English-I</td>
                              <td>2</td>
                              <td>–</td>
                              <td class="col-credit">2</td>
                              <td class="col-theory">40</td>
                              <td class="col-theory">60</td>
                              <td class="col-theory">100</td>
                              <td class="col-practical">-</td>
                              <td class="col-practical">-</td>
                              <td class="col-practical">-</td>
                              <td class="col-grand">100</td>
                            </tr>

                            <tr>
                              <td>4</td>
                              <td class="code">25911</td>
                              <td class="name">Mathematics -I</td>
                              <td>3</td>
                              <td>3</td>
                              <td class="col-credit">4</td>
                              <td class="col-theory">60</td>
                              <td class="col-theory">90</td>
                              <td class="col-theory">150</td>
                              <td class="col-practical">25</td>
                              <td class="col-practical">25</td>
                              <td class="col-practical">50</td>
                              <td class="col-grand">200</td>
                            </tr>

                            <tr>
                              <td>5</td>
                              <td class="code">25912</td>
                              <td class="name">Physics -I</td>
                              <td>3</td>
                              <td>3</td>
                              <td class="col-credit">4</td>
                              <td class="col-theory">60</td>
                              <td class="col-theory">90</td>
                              <td class="col-theory">150</td>
                              <td class="col-practical">25</td>
                              <td class="col-practical">25</td>
                              <td class="col-practical">50</td>
                              <td class="col-grand">200</td>
                            </tr>

                            <tr>
                              <td>6</td>
                              <td class="code">28511</td>
                              <td class="name">Computer Office Application</td>
                              <td>–</td>
                              <td>6</td>
                              <td class="col-credit">2</td>
                              <td class="col-theory">-</td>
                              <td class="col-theory">-</td>
                              <td class="col-theory">-</td>
                              <td class="col-practical">50</td>
                              <td class="col-practical">50</td>
                              <td class="col-practical">100</td>
                              <td class="col-grand">100</td>
                            </tr>

                            <tr>
                              <td>7</td>
                              <td class="code">26711</td>
                              <td class="name">Basic Electricity</td>
                              <td>3</td>
                              <td>3</td>
                              <td class="col-credit">4</td>
                              <td class="col-theory">60</td>
                              <td class="col-theory">90</td>
                              <td class="col-theory">150</td>
                              <td class="col-practical">25</td>
                              <td class="col-practical">25</td>
                              <td class="col-practical">50</td>
                              <td class="col-grand">200</td>
                            </tr>

                            <tr class="totals">
                              <td colspan="3">Total</td>
                              <td>13</td>
                              <td>21</td>
                              <td class="col-credit">20</td>
                              <td class="col-theory">260</td>
                              <td class="col-theory">390</td>
                              <td class="col-theory">650</td>
                              <td class="col-practical">175</td>
                              <td class="col-practical">175</td>
                              <td class="col-practical">350</td>
                              <td class="col-grand">1000</td>
                            </tr>

                            <tr class="footer-row">
                              <td colspan="4" style="text-align:left; padding-left:10px;">Total Period</td>
                              <td>34</td>
                              <td colspan="2" style="text-align:left; padding-left:10px; font-weight:700;">
                                Theory:Practical (%)</td>
                              <td colspan="6" style="text-align:left; padding-left:10px;"><strong>38.2% : 61.8%</strong>
                              </td>
                            </tr>


                          </tbody>
                        </table>

                      </div>
                    </div>
                  </div>
                </div>
                <!-- CST Sem 2 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#cstSem2">Semester 2</button>
                  </div>
                  <div id="cstSem2" class="accordion-collapse hidden">
                    <div class="p-3">
                      <div class="table-scroll">
                        <table class="markstable" role="table" aria-label="Marks distribution">
                          <thead>
                            <tr>
                              <th class="sl" rowspan="3">Sl.<br>No.</th>
                              <th colspan="2" rowspan="2">Subject Code &amp; Name</th>
                              <th colspan="2" rowspan="2">Period Per Week</th>
                              <th class="col-credit" rowspan="3">Credit</th>
                              <th colspan="6" class="group-head">Marks Distribution</th>
                              <th class="col-grand" rowspan="3">Grand<br />Total</th>
                            </tr>

                            <tr>
                              <th colspan="3" class="group-head">Theory Assessment</th>
                              <th colspan="3" class="group-head">Practical Assessment</th>
                            </tr>

                            <tr>
                              <th class="code">Code</th>
                              <th class="name">Name</th>

                              <th>Theory</th>
                              <th>Practical</th>

                              <th class="col-theory">Continuous</th>
                              <th class="col-theory">Final</th>
                              <th class="col-theory">Total</th>

                              <th class="col-practical">Continuous</th>
                              <th class="col-practical">Final</th>
                              <th class="col-practical">Total</th>
                            </tr>
                          </thead>

                          <tbody>
                            <tr>
                              <td>1</td>
                              <td class="code">25721</td>
                              <td class="name">Bangla -II</td>
                              <td>2</td>
                              <td>-</td>
                              <td class="col-credit">2</td>
                              <td class="col-theory">40</td>
                              <td class="col-theory">60</td>
                              <td class="col-theory">100</td>
                              <td class="col-practical">-</td>
                              <td class="col-practical">-</td>
                              <td class="col-practical">-</td>
                              <td class="col-grand">100</td>
                            </tr>

                            <tr>
                              <td>2</td>
                              <td class="code">25722</td>
                              <td class="name">English-II</td>
                              <td>2</td>
                              <td>-</td>
                              <td class="col-credit">2</td>
                              <td class="col-theory">40</td>
                              <td class="col-theory">60</td>
                              <td class="col-theory">100</td>
                              <td class="col-practical">-</td>
                              <td class="col-practical">-</td>
                              <td class="col-practical">-</td>
                              <td class="col-grand">100</td>
                            </tr>

                            <tr>
                              <td>3</td>
                              <td class="code">25812</td>
                              <td class="name">Physical Education & Life Skills Development</td>
                              <td>3</td>
                              <td>1</td>
                              <td class="col-credit">1</td>
                              <td class="col-theory">-</td>
                              <td class="col-theory">-</td>
                              <td class="col-theory">-</td>
                              <td class="col-practical">25</td>
                              <td class="col-practical">25</td>
                              <td class="col-practical">50</td>
                              <td class="col-grand">50</td>
                            </tr>

                            <tr>
                              <td>4</td>
                              <td class="code">25921</td>
                              <td class="name">Mathematics-II</td>
                              <td>3</td>
                              <td>3</td>
                              <td class="col-credit">4</td>
                              <td class="col-theory">60</td>
                              <td class="col-theory">90</td>
                              <td class="col-theory">150</td>
                              <td class="col-practical">25</td>
                              <td class="col-practical">25</td>
                              <td class="col-practical">50</td>
                              <td class="col-grand">200</td>
                            </tr>

                            <tr>
                              <td>5</td>
                              <td class="code">25922</td>
                              <td class="name">Physics-II</td>
                              <td>3</td>
                              <td>3</td>
                              <td class="col-credit">4</td>
                              <td class="col-theory">60</td>
                              <td class="col-theory">90</td>
                              <td class="col-theory">150</td>
                              <td class="col-practical">25</td>
                              <td class="col-practical">25</td>
                              <td class="col-practical">50</td>
                              <td class="col-grand">200</td>
                            </tr>

                            <tr>
                              <td>6</td>
                              <td class="code">26621</td>
                              <td class="name">Python Programming</td>
                              <td>2</td>
                              <td>3</td>
                              <td class="col-credit">3</td>
                              <td class="col-theory">40</td>
                              <td class="col-theory">60</td>
                              <td class="col-theory">100</td>
                              <td class="col-practical">25</td>
                              <td class="col-practical">25</td>
                              <td class="col-practical">50</td>
                              <td class="col-grand">150</td>
                            </tr>

                            <tr>
                              <td>7</td>
                              <td class="code">26622</td>
                              <td class="name">Computer Graphics Design-I</td>
                              <td>-</td>
                              <td>6</td>
                              <td class="col-credit">2</td>
                              <td class="col-theory">-</td>
                              <td class="col-theory">-</td>
                              <td class="col-theory">-</td>
                              <td class="col-practical">50</td>
                              <td class="col-practical">50</td>
                              <td class="col-practical">100</td>
                              <td class="col-grand">100</td>
                            </tr>

                            <tr>
                              <td>8</td>
                              <td class="code">26811</td>
                              <td class="name">Basic Electronics</td>
                              <td>2</td>
                              <td>3</td>
                              <td class="col-credit">3</td>
                              <td class="col-theory">40</td>
                              <td class="col-theory">60</td>
                              <td class="col-theory">100</td>
                              <td class="col-practical">25</td>
                              <td class="col-practical">25</td>
                              <td class="col-practical">50</td>
                              <td class="col-grand">150</td>
                            </tr>

                            <tr class="totals">
                              <td colspan="3">Total</td>
                              <td>14</td>
                              <td>21</td>
                              <td class="col-credit">21</td>
                              <td class="col-theory">280</td>
                              <td class="col-theory">420</td>
                              <td class="col-theory">700</td>
                              <td class="col-practical">175</td>
                              <td class="col-practical">175</td>
                              <td class="col-practical">350</td>
                              <td class="col-grand">1050</td>
                            </tr>

                            <tr class="footer-row">
                              <td colspan="4" style="text-align:left; padding-left:10px;">Total Period</td>
                              <td>35</td>
                              <td colspan="2" style="text-align:left; padding-left:10px; font-weight:700;">
                                Theory:Practical (%)</td>
                              <td colspan="6" style="text-align:left; padding-left:10px;"><strong>40.0% : 60.0%</strong>
                              </td>
                            </tr>

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- CST Sem 3 -->

                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#cstSem3">Semester 3</button>
                  </div>
                  <div id="cstSem3" class="accordion-collapse hidden">
                    <div class="p-3">
                      <div class="table-scroll">
                        <table class="markstable" role="table" aria-label="Marks distribution">
                          <thead>
                            <tr>
                              <th rowspan="3">Sl.<br>No.</th>
                              <th colspan="2" rowspan="2">Subject Code &amp; Name</th>
                              <th colspan="2" rowspan="2">Period Per Week</th>
                              <th rowspan="3">Credit</th>
                              <th colspan="6">Marks Distribution</th>
                              <th rowspan="3">Grand<br />Total</th>
                            </tr>
                            <tr>
                              <th colspan="3">Theory Assessment</th>
                              <th colspan="3">Practical Assessment</th>
                            </tr>
                            <tr>
                              <th>Code</th>
                              <th>Name</th>
                              <th>Theory</th>
                              <th>Practical</th>
                              <th>Continuous</th>
                              <th>Final</th>
                              <th>Total</th>
                              <th>Continuous</th>
                              <th>Final</th>
                              <th>Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1</td>
                              <td>25811</td>
                              <td>Social Science</td>
                              <td>2</td>
                              <td>-</td>
                              <td>2</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>100</td>
                            </tr>
                            <tr>
                              <td>2</td>
                              <td>25913</td>
                              <td>Chemistry</td>
                              <td>3</td>
                              <td>3</td>
                              <td>4</td>
                              <td>60</td>
                              <td>90</td>
                              <td>150</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>200</td>
                            </tr>
                            <tr>
                              <td>3</td>
                              <td>25931</td>
                              <td>Mathematics-III</td>
                              <td>3</td>
                              <td>3</td>
                              <td>4</td>
                              <td>60</td>
                              <td>90</td>
                              <td>150</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>200</td>
                            </tr>
                            <tr>
                              <td>4</td>
                              <td>26631</td>
                              <td>Application Development Using Python</td>
                              <td>2</td>
                              <td>3</td>
                              <td>3</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>150</td>
                            </tr>
                            <tr>
                              <td>5</td>
                              <td>26632</td>
                              <td>Computer Graphics Design-II</td>
                              <td>-</td>
                              <td>3</td>
                              <td>1</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>50</td>
                            </tr>
                            <tr>
                              <td>6</td>
                              <td>26633</td>
                              <td>IT Support Services</td>
                              <td>2</td>
                              <td>6</td>
                              <td>4</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>50</td>
                              <td>50</td>
                              <td>100</td>
                              <td>200</td>
                            </tr>
                            <tr>
                              <td>7</td>
                              <td>26831</td>
                              <td>Digital Electronics-I</td>
                              <td>2</td>
                              <td>3</td>
                              <td>3</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>150</td>
                            </tr>
                            <tr class="totals">
                              <td colspan="5">Total</td>
                              <td>21</td>
                              <td>280</td>
                              <td>420</td>
                              <td>700</td>
                              <td>175</td>
                              <td>175</td>
                              <td>350</td>
                              <td>1050</td>
                            </tr>
                            <tr class="footer-row">
                              <td colspan="5">Total Period</td>
                              <td>35</td>
                              <td colspan="6"><strong>40.0% : 60.0%</strong></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- CST Sem 4 -->
                <div class="border rounded-lg mb-4">
                  <div class="accordion-header">
                    <button class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#cstSem4">Semester 4</button>
                  </div>
                  <div id="cstSem4" class="accordion-collapse hidden">
                    <div class="p-3">
                      <div class="table-scroll">
                        <table class="markstable">
                          <thead>
                            <tr>
                              <th rowspan="3">Sl.<br>No.</th>
                              <th colspan="2" rowspan="2">Subject Code & Name</th>
                              <th colspan="2" rowspan="2">Period Per Week</th>
                              <th rowspan="3">Credit</th>
                              <th colspan="6">Marks Distribution</th>
                              <th rowspan="3">Grand Total</th>
                            </tr>
                            <tr>
                              <th colspan="3">Theory Assessment</th>
                              <th colspan="3">Practical Assessment</th>
                            </tr>
                            <tr>
                              <th>Code</th>
                              <th>Name</th>
                              <th>Theory</th>
                              <th>Practical</th>
                              <th>Continuous</th>
                              <th>Final</th>
                              <th>Total</th>
                              <th>Continuous</th>
                              <th>Final</th>
                              <th>Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1</td>
                              <td>25831</td>
                              <td>Business Communication</td>
                              <td>2</td>
                              <td>-</td>
                              <td>2</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>100</td>
                            </tr>
                            <tr>
                              <td>2</td>
                              <td>26641</td>
                              <td>Java Programming</td>
                              <td>2</td>
                              <td>3</td>
                              <td>3</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>150</td>
                            </tr>
                            <tr>
                              <td>3</td>
                              <td>26642</td>
                              <td>Data Structure & Algorithm</td>
                              <td>2</td>
                              <td>3</td>
                              <td>3</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>150</td>
                            </tr>
                            <tr>
                              <td>4</td>
                              <td>26643</td>
                              <td>Computer Peripherals & Interfacing</td>
                              <td>3</td>
                              <td>3</td>
                              <td>4</td>
                              <td>60</td>
                              <td>90</td>
                              <td>150</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>200</td>
                            </tr>
                            <tr>
                              <td>5</td>
                              <td>26644</td>
                              <td>Web Design & Development-I</td>
                              <td>1</td>
                              <td>6</td>
                              <td>3</td>
                              <td>20</td>
                              <td>30</td>
                              <td>50</td>
                              <td>50</td>
                              <td>50</td>
                              <td>100</td>
                              <td>150</td>
                            </tr>
                            <tr>
                              <td>6</td>
                              <td>26841</td>
                              <td>Digital Electronics-II</td>
                              <td>2</td>
                              <td>3</td>
                              <td>3</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>150</td>
                            </tr>
                            <tr>
                              <td>7</td>
                              <td>29041</td>
                              <td>Environmental Studies</td>
                              <td>2</td>
                              <td>3</td>
                              <td>3</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>150</td>
                            </tr>
                            <tr class="totals">
                              <td colspan="5">Total</td>
                              <td>21</td>
                              <td>280</td>
                              <td>420</td>
                              <td>700</td>
                              <td>175</td>
                              <td>175</td>
                              <td>350</td>
                              <td>1050</td>
                            </tr>
                            <tr class="footer-row">
                              <td colspan="5">Total Period</td>
                              <td>35</td>
                              <td colspan="6"><strong>40.0% : 60.0%</strong></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <!--  CST Sem 5 -->
                <div class="border rounded-lg mb-4">
                  <div class="accordion-header">
                    <button class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#cstSem5">Semester 5</button>
                  </div>
                  <div id="cstSem5" class="accordion-collapse hidden">
                    <div class="p-3">
                      <div class="table-scroll">
                        <table class="markstable">
                          <thead>
                            <tr>
                              <th rowspan="3">Sl.<br>No.</th>
                              <th colspan="2" rowspan="2">Subject Code & Name</th>
                              <th colspan="2" rowspan="2">Period Per Week</th>
                              <th rowspan="3">Credit</th>
                              <th colspan="6">Marks Distribution</th>
                              <th rowspan="3">Grand Total</th>
                            </tr>
                            <tr>
                              <th colspan="3">Theory Assessment</th>
                              <th colspan="3">Practical Assessment</th>
                            </tr>
                            <tr>
                              <th>Code</th>
                              <th>Name</th>
                              <th>Theory</th>
                              <th>Practical</th>
                              <th>Continuous</th>
                              <th>Final</th>
                              <th>Total</th>
                              <th>Continuous</th>
                              <th>Final</th>
                              <th>Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1</td>
                              <td>25841</td>
                              <td>Accounting</td>
                              <td>2</td>
                              <td>-</td>
                              <td>2</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>100</td>
                            </tr>
                            <tr>
                              <td>2</td>
                              <td>26651</td>
                              <td>Application Development Using Java</td>
                              <td>2</td>
                              <td>3</td>
                              <td>3</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>150</td>
                            </tr>
                            <tr>
                              <td>3</td>
                              <td>26652</td>
                              <td>Web Design & Development-II</td>
                              <td>1</td>
                              <td>6</td>
                              <td>3</td>
                              <td>20</td>
                              <td>30</td>
                              <td>50</td>
                              <td>50</td>
                              <td>50</td>
                              <td>100</td>
                              <td>150</td>
                            </tr>
                            <tr>
                              <td>4</td>
                              <td>26653</td>
                              <td>Computer Architecture & Microprocessor</td>
                              <td>3</td>
                              <td>3</td>
                              <td>4</td>
                              <td>60</td>
                              <td>90</td>
                              <td>150</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>200</td>
                            </tr>
                            <tr>
                              <td>5</td>
                              <td>26654</td>
                              <td>Data Communication</td>
                              <td>3</td>
                              <td>3</td>
                              <td>4</td>
                              <td>60</td>
                              <td>90</td>
                              <td>150</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>200</td>
                            </tr>
                            <tr>
                              <td>6</td>
                              <td>26655</td>
                              <td>Operating System</td>
                              <td>2</td>
                              <td>3</td>
                              <td>3</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>150</td>
                            </tr>
                            <tr>
                              <td>7</td>
                              <td>26656</td>
                              <td>Project Work-I</td>
                              <td>-</td>
                              <td>3</td>
                              <td>1</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>50</td>
                            </tr>
                            <tr class="totals">
                              <td colspan="5">Total</td>
                              <td>20</td>
                              <td>260</td>
                              <td>390</td>
                              <td>650</td>
                              <td>175</td>
                              <td>175</td>
                              <td>350</td>
                              <td>1000</td>
                            </tr>
                            <tr class="footer-row">
                              <td colspan="5">Total Period</td>
                              <td>34</td>
                              <td colspan="6"><strong>38.2% : 61.8%</strong></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <!--  CST Sem 6 -->
                <div class="border rounded-lg mb-4">
                  <div class="accordion-header">
                    <button class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#cstSem6">Semester 6</button>
                  </div>
                  <div id="cstSem6" class="accordion-collapse hidden">
                    <div class="p-3">
                      <div class="table-scroll">
                        <table class="markstable">
                          <thead>
                            <tr>
                              <th rowspan="3">Sl.<br>No.</th>
                              <th colspan="2" rowspan="2">Subject Code & Name</th>
                              <th colspan="2" rowspan="2">Period Per Week</th>
                              <th rowspan="3">Credit</th>
                              <th colspan="6">Marks Distribution</th>
                              <th rowspan="3">Grand Total</th>
                            </tr>
                            <tr>
                              <th colspan="3">Theory Assessment</th>
                              <th colspan="3">Practical Assessment</th>
                            </tr>
                            <tr>
                              <th>Code</th>
                              <th>Name</th>
                              <th>Theory</th>
                              <th>Practical</th>
                              <th>Continuous</th>
                              <th>Final</th>
                              <th>Total</th>
                              <th>Continuous</th>
                              <th>Final</th>
                              <th>Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1</td>
                              <td>25851</td>
                              <td>Principles of Marketing</td>
                              <td>2</td>
                              <td>–</td>
                              <td>2</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>100</td>
                            </tr>
                            <tr>
                              <td>2</td>
                              <td>25852</td>
                              <td>Industrial Management</td>
                              <td>2</td>
                              <td>–</td>
                              <td>2</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>100</td>
                            </tr>
                            <tr>
                              <td>3</td>
                              <td>26661</td>
                              <td>Database Management System</td>
                              <td>2</td>
                              <td>3</td>
                              <td>3</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>150</td>
                            </tr>
                            <tr>
                              <td>4</td>
                              <td>26662</td>
                              <td>Computer Networking</td>
                              <td>2</td>
                              <td>3</td>
                              <td>3</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>150</td>
                            </tr>
                            <tr>
                              <td>5</td>
                              <td>26663</td>
                              <td>Sensor & IoT System</td>
                              <td>2</td>
                              <td>3</td>
                              <td>3</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>150</td>
                            </tr>
                            <tr>
                              <td>6</td>
                              <td>26664</td>
                              <td>Microcontroller Based System Design & Development</td>
                              <td>2</td>
                              <td>6</td>
                              <td>4</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>50</td>
                              <td>50</td>
                              <td>100</td>
                              <td>200</td>
                            </tr>
                            <tr>
                              <td>7</td>
                              <td>26665</td>
                              <td>Surveillance Security System</td>
                              <td>1</td>
                              <td>3</td>
                              <td>2</td>
                              <td>20</td>
                              <td>30</td>
                              <td>50</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>100</td>
                            </tr>
                            <tr>
                              <td>8</td>
                              <td>26666</td>
                              <td>Web Development Project</td>
                              <td>–</td>
                              <td>3</td>
                              <td>1</td>
                              <td>–</td>
                              <td>–</td>
                              <td>–</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>50</td>
                            </tr>
                            <tr class="totals">
                              <td colspan="5">Total</td>
                              <td>20</td>
                              <td>260</td>
                              <td>390</td>
                              <td>650</td>
                              <td>175</td>
                              <td>175</td>
                              <td>350</td>
                              <td>1000</td>
                            </tr>
                            <tr class="footer-row">
                              <td colspan="5">Total Period</td>
                              <td>34</td>
                              <td colspan="6"><strong>38.2% : 61.8%</strong></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- CST Sem 7 -->
                <div class="border rounded-lg mb-4">
                  <div class="accordion-header">
                    <button class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#cstSem7">Semester 7</button>
                  </div>
                  <div id="cstSem7" class="accordion-collapse hidden">
                    <div class="p-3">
                      <div class="table-scroll">
                        <table class="markstable">
                          <thead>
                            <tr>
                              <th rowspan="3">Sl.<br>No.</th>
                              <th colspan="2" rowspan="2">Subject Code & Name</th>
                              <th colspan="2" rowspan="2">Period Per Week</th>
                              <th rowspan="3">Credit</th>
                              <th colspan="6">Marks Distribution</th>
                              <th rowspan="3">Grand Total</th>
                            </tr>
                            <tr>
                              <th colspan="3">Theory Assessment</th>
                              <th colspan="3">Practical Assessment</th>
                            </tr>
                            <tr>
                              <th>Code</th>
                              <th>Name</th>
                              <th>Theory</th>
                              <th>Practical</th>
                              <th>Continuous</th>
                              <th>Final</th>
                              <th>Total</th>
                              <th>Continuous</th>
                              <th>Final</th>
                              <th>Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1</td>
                              <td>25853</td>
                              <td>Innovation & Entrepreneurship</td>
                              <td>2</td>
                              <td>-</td>
                              <td>2</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>100</td>
                            </tr>
                            <tr>
                              <td>2</td>
                              <td>26671</td>
                              <td>Digital Marketing Technique</td>
                              <td>2</td>
                              <td>3</td>
                              <td>3</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>150</td>
                            </tr>
                            <tr>
                              <td>3</td>
                              <td>26672</td>
                              <td>Network Administration & Services</td>
                              <td>3</td>
                              <td>3</td>
                              <td>4</td>
                              <td>60</td>
                              <td>90</td>
                              <td>150</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>200</td>
                            </tr>
                            <tr>
                              <td>4</td>
                              <td>26673</td>
                              <td>Cyber Security & Ethics</td>
                              <td>2</td>
                              <td>3</td>
                              <td>3</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>150</td>
                            </tr>
                            <tr>
                              <td>5</td>
                              <td>26674</td>
                              <td>Apps Development Project</td>
                              <td>1</td>
                              <td>3</td>
                              <td>2</td>
                              <td>20</td>
                              <td>30</td>
                              <td>50</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>100</td>
                            </tr>
                            <tr>
                              <td>6</td>
                              <td>26675</td>
                              <td>Multimedia & Animation</td>
                              <td>2</td>
                              <td>3</td>
                              <td>3</td>
                              <td>40</td>
                              <td>60</td>
                              <td>100</td>
                              <td>25</td>
                              <td>25</td>
                              <td>50</td>
                              <td>150</td>
                            </tr>
                            <tr>
                              <td>7</td>
                              <td>26676</td>
                              <td>Project Work-II</td>
                              <td>-</td>
                              <td>6</td>
                              <td>2</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>50</td>
                              <td>50</td>
                              <td>100</td>
                              <td>100</td>
                            </tr>
                            <tr class="totals">
                              <td colspan="5">Total</td>
                              <td>19</td>
                              <td>240</td>
                              <td>360</td>
                              <td>600</td>
                              <td>175</td>
                              <td>175</td>
                              <td>350</td>
                              <td>950</td>
                            </tr>
                            <tr class="footer-row">
                              <td colspan="5">Total Period</td>
                              <td>33</td>
                              <td colspan="6"><strong>36.4% : 63.6%</strong></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- CST Sem 8 -->
                <div class="border rounded-lg mb-4">
                  <div class="accordion-header">
                    <button class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#cstSem8">Semester 8</button>
                  </div>
                  <div id="cstSem8" class="accordion-collapse hidden">
                    <div class="p-3">
                      <div class="table-scroll">
                        <table class="markstable">
                          <thead>
                            <tr>
                              <th rowspan="3">Sl.<br>No.</th>
                              <th colspan="2" rowspan="2">Subject Code & Name</th>
                              <th colspan="2" rowspan="2">Period Per Week</th>
                              <th rowspan="3">Credit</th>
                              <th colspan="6">Marks Distribution</th>
                              <th rowspan="3">Grand Total</th>
                            </tr>
                            <tr>
                              <th colspan="3">Theory Assessment</th>
                              <th colspan="3">Practical Assessment</th>
                            </tr>
                            <tr>
                              <th>Code</th>
                              <th>Name</th>
                              <th>Theory</th>
                              <th>Practical</th>
                              <th>Continuous</th>
                              <th>Final</th>
                              <th>Total</th>
                              <th>Continuous</th>
                              <th>Final</th>
                              <th>Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1</td>
                              <td>–</td>
                              <td>Industrial Attachment</td>
                              <td>-</td>
                              <td>24</td>
                              <td>8</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>200</td>
                              <td>200</td>
                              <td>400</td>
                              <td>400</td>
                            </tr>
                            <tr>
                              <td>2</td>
                              <td>–</td>
                              <td>Project Presentation</td>
                              <td>-</td>
                              <td>12</td>
                              <td>4</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>100</td>
                              <td>100</td>
                              <td>200</td>
                              <td>200</td>
                            </tr>
                            <tr class="totals">
                              <td colspan="5">Total</td>
                              <td>12</td>
                              <td>-</td>
                              <td>-</td>
                              <td>-</td>
                              <td>300</td>
                              <td>300</td>
                              <td>600</td>
                              <td>600</td>
                            </tr>
                            <tr class="footer-row">
                              <td colspan="5">Total Period</td>
                              <td>36</td>
                              <td colspan="6"><strong>0% : 100%</strong></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>


              </div> <!-- end cstSemesters -->
            </div>
          </div>
        </div>

        <!-- CIVIL TECHNOLOGY (CT) -->
        <div class="border-b">
          <div class="accordion-header" id="hdr-ct">
            <button
              class="accordion-button w-full text-left p-4 font-semibold text-lg text-gray-800 bg-gray-50 hover:bg-gray-100 flex justify-between items-center"
              type="button" data-bs-toggle="collapse" data-bs-target="#deptCT" aria-expanded="false"
              aria-controls="deptCT">
              <span>Civil Technology (CT) <span
                  class="ml-3 bg-blue-100 text-blue-600 text-sm font-medium px-3 py-1 rounded-full">CT</span></span>
              <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
          </div>
          <div id="deptCT" class="accordion-collapse hidden" aria-labelledby="hdr-ct">
            <div class="p-4 bg-white">
              <div class="space-y-2" id="ctSemesters">

                <!-- CT Sem 1 -->
                <div class="border rounded-lg">
                  <div class="accordion-header" id="ct1-hdr">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#ctSem1" aria-expanded="false"
                      aria-controls="ctSem1">
                      <span>Semester 1</span>
                      <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                      </svg>
                    </button>
                  </div>
                  <div id="ctSem1" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Engineering Mechanics</td>
                            <td class="p-2 text-center">CT-101</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Surveying Fundamentals</td>
                            <td class="p-2 text-center">CT-102</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- CT Sem 2 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#ctSem2">Semester 2</button>
                  </div>
                  <div id="ctSem2" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Strength of Materials</td>
                            <td class="p-2 text-center">CT-201</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Building Materials & Construction</td>
                            <td class="p-2 text-center">CT-202</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- CT Sem 3 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#ctSem3">Semester 3</button>
                  </div>
                  <div id="ctSem3" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Fluid Mechanics</td>
                            <td class="p-2 text-center">CT-301</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Concrete Technology</td>
                            <td class="p-2 text-center">CT-302</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- CT Sem 4 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#ctSem4">Semester 4</button>
                  </div>
                  <div id="ctSem4" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Soil Mechanics</td>
                            <td class="p-2 text-center">CT-401</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Structural Analysis I</td>
                            <td class="p-2 text-center">CT-402</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- CT Sem 5 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#ctSem5">Semester 5</button>
                  </div>
                  <div id="ctSem5" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Design of Concrete Structures</td>
                            <td class="p-2 text-center">CT-501</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Hydraulics & Irrigation</td>
                            <td class="p-2 text-center">CT-502</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- CT Sem 6 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#ctSem6">Semester 6</button>
                  </div>
                  <div id="ctSem6" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Transportation Engineering</td>
                            <td class="p-2 text-center">CT-601</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Geotechnical Engineering</td>
                            <td class="p-2 text-center">CT-602</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- CT Sem 7 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#ctSem7">Semester 7</button>
                  </div>
                  <div id="ctSem7" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Advanced Structural Design</td>
                            <td class="p-2 text-center">CT-701</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Construction Project Management</td>
                            <td class="p-2 text-center">CT-702</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- CT Sem 8 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#ctSem8">Semester 8</button>
                  </div>
                  <div id="ctSem8" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Capstone Project</td>
                            <td class="p-2 text-center">CT-801</td>
                            <td class="p-2 text-center">6.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Environmental Engineering</td>
                            <td class="p-2 text-center">CT-802</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

              </div> <!-- end ctSemesters -->
            </div>
          </div>
        </div>

        <!-- ELECTRICAL TECHNOLOGY (ET) -->
        <div class="border-b">
          <div class="accordion-header" id="hdr-et">
            <button
              class="accordion-button w-full text-left p-4 font-semibold text-lg text-gray-800 bg-gray-50 hover:bg-gray-100 flex justify-between items-center"
              type="button" data-bs-toggle="collapse" data-bs-target="#deptET" aria-expanded="false">
              <span>Electrical Technology (ET) <span
                  class="ml-3 bg-blue-100 text-blue-600 text-sm font-medium px-3 py-1 rounded-full">ET</span></span>
              <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
          </div>
          <div id="deptET" class="accordion-collapse hidden" aria-labelledby="hdr-et">
            <div class="p-4 bg-white">
              <div class="space-y-2" id="etSemesters">

                <!-- ET Sem 1 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#etSem1">Semester 1</button>
                  </div>
                  <div id="etSem1" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Basic Electrical</td>
                            <td class="p-2 text-center">ET-101</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Electric Circuits</td>
                            <td class="p-2 text-center">ET-102</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- ET Sem 2 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#etSem2">Semester 2</button>
                  </div>
                  <div id="etSem2" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Electrical Machines</td>
                            <td class="p-2 text-center">ET-201</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Power Systems</td>
                            <td class="p-2 text-center">ET-202</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- ET Sem 3 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#etSem3">Semester 3</button>
                  </div>
                  <div id="etSem3" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Electronics Devices</td>
                            <td class="p-2 text-center">ET-301</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Control Systems</td>
                            <td class="p-2 text-center">ET-302</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- ET Sem 4 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#etSem4">Semester 4</button>
                  </div>
                  <div id="etSem4" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Power Electronics</td>
                            <td class="p-2 text-center">ET-401</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Electrical Installation</td>
                            <td class="p-2 text-center">ET-402</td>
                            <td class="p-2 text-center">2.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- ET Sem 5 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#etSem5">Semester 5</button>
                  </div>
                  <div id="etSem5" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Power System Protection</td>
                            <td class="p-2 text-center">ET-501</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Electrical Measurement</td>
                            <td class="p-2 text-center">ET-502</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- ET Sem 6 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#etSem6">Semester 6</button>
                  </div>
                  <div id="etSem6" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">High Voltage Engineering</td>
                            <td class="p-2 text-center">ET-601</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Renewable Energy Systems</td>
                            <td class="p-2 text-center">ET-602</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- ET Sem 7 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#etSem7">Semester 7</button>
                  </div>
                  <div id="etSem7" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Electrical System Design</td>
                            <td class="p-2 text-center">ET-701</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Industrial Automation</td>
                            <td class="p-2 text-center">ET-702</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <!-- ET Sem 8 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#etSem8">Semester 8</button>
                  </div>
                  <div id="etSem8" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Final Project</td>
                            <td class="p-2 text-center">ET-801</td>
                            <td class="p-2 text-center">6.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Smart Grid Technology</td>
                            <td class="p-2 text-center">ET-802</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

              </div> <!-- end etSemesters -->
            </div>
          </div>
        </div>

        <!-- MECHANICAL TECHNOLOGY (MT) -->
        <div class="border-b">
          <div class="accordion-header" id="hdr-mt">
            <button
              class="accordion-button w-full text-left p-4 font-semibold text-lg text-gray-800 bg-gray-50 hover:bg-gray-100 flex justify-between items-center"
              type="button" data-bs-toggle="collapse" data-bs-target="#deptMT">
              <span>Mechanical Technology (MT) <span
                  class="ml-3 bg-blue-100 text-blue-600 text-sm font-medium px-3 py-1 rounded-full">MT</span></span>
              <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
          </div>
          <div id="deptMT" class="accordion-collapse hidden" aria-labelledby="hdr-mt">
            <div class="p-4 bg-white">
              <div class="space-y-2" id="mtSemesters">
                <!-- MT Sem 1 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#mtSem1">Semester 1</button>
                  </div>
                  <div id="mtSem1" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Engineering Drawing</td>
                            <td class="p-2 text-center">MT-101</td>
                            <td class="p-2 text-center">2.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Workshop Practice</td>
                            <td class="p-2 text-center">MT-102</td>
                            <td class="p-2 text-center">2.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- MT Sem 2 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#mtSem2">Semester 2</button>
                  </div>
                  <div id="mtSem2" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Thermodynamics</td>
                            <td class="p-2 text-center">MT-201</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Materials Science</td>
                            <td class="p-2 text-center">MT-202</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- MT Sem 3 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#mtSem3">Semester 3</button>
                  </div>
                  <div id="mtSem3" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Fluid Mechanics</td>
                            <td class="p-2 text-center">MT-301</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Machine Drawing</td>
                            <td class="p-2 text-center">MT-302</td>
                            <td class="p-2 text-center">2.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- MT Sem 4 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#mtSem4">Semester 4</button>
                  </div>
                  <div id="mtSem4" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Machine Design</td>
                            <td class="p-2 text-center">MT-401</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Thermal Engineering</td>
                            <td class="p-2 text-center">MT-402</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- MT Sem 5 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#mtSem5">Semester 5</button>
                  </div>
                  <div id="mtSem5" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Heat Transfer</td>
                            <td class="p-2 text-center">MT-501</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Manufacturing Processes</td>
                            <td class="p-2 text-center">MT-502</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- MT Sem 6 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#mtSem6">Semester 6</button>
                  </div>
                  <div id="mtSem6" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">CAD/CAM</td>
                            <td class="p-2 text-center">MT-601</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Maintenance Engineering</td>
                            <td class="p-2 text-center">MT-602</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- MT Sem 7 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#mtSem7">Semester 7</button>
                  </div>
                  <div id="mtSem7" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Advanced Thermodynamics</td>
                            <td class="p-2 text-center">MT-701</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Industrial Engineering</td>
                            <td class="p-2 text-center">MT-702</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- MT Sem 8 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#mtSem8">Semester 8</button>
                  </div>
                  <div id="mtSem8" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Final Project</td>
                            <td class="p-2 text-center">MT-801</td>
                            <td class="p-2 text-center">6.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Energy Management</td>
                            <td class="p-2 text-center">MT-802</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

              </div> <!-- end mtSemesters -->
            </div>
          </div>
        </div>

        <!-- POWER TECHNOLOGY (PT) -->
        <div class="border-b">
          <div class="accordion-header" id="hdr-pt">
            <button
              class="accordion-button w-full text-left p-4 font-semibold text-lg text-gray-800 bg-gray-50 hover:bg-gray-100 flex justify-between items-center"
              type="button" data-bs-toggle="collapse" data-bs-target="#deptPT">
              <span>Power Technology (PT) <span
                  class="ml-3 bg-blue-100 text-blue-600 text-sm font-medium px-3 py-1 rounded-full">PT</span></span>
              <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
          </div>
          <div id="deptPT" class="accordion-collapse hidden" aria-labelledby="hdr-pt">
            <div class="p-4 bg-white">
              <div class="space-y-2" id="ptSemesters">
                <!-- PT Sem 1 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#ptSem1">Semester 1</button>
                  </div>
                  <div id="ptSem1" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Fundamentals of Power</td>
                            <td class="p-2 text-center">PT-101</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">DC Machines Basics</td>
                            <td class="p-2 text-center">PT-102</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- PT Sem 2 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#ptSem2">Semester 2</button>
                  </div>
                  <div id="ptSem2" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">AC Machines</td>
                            <td class="p-2 text-center">PT-201</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Power Transmission</td>
                            <td class="p-2 text-center">PT-202</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- PT Sem 3 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#ptSem3">Semester 3</button>
                  </div>
                  <div id="ptSem3" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Power System Analysis</td>
                            <td class="p-2 text-center">PT-301</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Protective Relays</td>
                            <td class="p-2 text-center">PT-302</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- PT Sem 4 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#ptSem4">Semester 4</button>
                  </div>
                  <div id="ptSem4" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Power Electronics</td>
                            <td class="p-2 text-center">PT-401</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Power Quality</td>
                            <td class="p-2 text-center">PT-402</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- PT Sem 5 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#ptSem5">Semester 5</button>
                  </div>
                  <div id="ptSem5" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Smart Grid</td>
                            <td class="p-2 text-center">PT-501</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Energy Management</td>
                            <td class="p-2 text-center">PT-502</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- PT Sem 6 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#ptSem6">Semester 6</button>
                  </div>
                  <div id="ptSem6" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Distributed Generation</td>
                            <td class="p-2 text-center">PT-601</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Power System Operation</td>
                            <td class="p-2 text-center">PT-602</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- PT Sem 7 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#ptSem7">Semester 7</button>
                  </div>
                  <div id="ptSem7" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Power System Planning</td>
                            <td class="p-2 text-center">PT-701</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">HVDC Transmission</td>
                            <td class="p-2 text-center">PT-702</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- PT Sem 8 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#ptSem8">Semester 8</button>
                  </div>
                  <div id="ptSem8" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Final Project</td>
                            <td class="p-2 text-center">PT-801</td>
                            <td class="p-2 text-center">6.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Advanced Power Systems</td>
                            <td class="p-2 text-center">PT-802</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

              </div> <!-- end ptSemesters -->
            </div>
          </div>
        </div>

        <!-- ELECTRONICS TECHNOLOGY (ENT) -->
        <div class="border-b">
          <div class="accordion-header" id="hdr-ent">
            <button
              class="accordion-button w-full text-left p-4 font-semibold text-lg text-gray-800 bg-gray-50 hover:bg-gray-100 flex justify-between items-center"
              type="button" data-bs-toggle="collapse" data-bs-target="#deptENT">
              <span>Electronics Technology (ENT) <span
                  class="ml-3 bg-blue-100 text-blue-600 text-sm font-medium px-3 py-1 rounded-full">ENT</span></span>
              <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
          </div>
          <div id="deptENT" class="accordion-collapse hidden" aria-labelledby="hdr-ent">
            <div class="p-4 bg-white">
              <div class="space-y-2" id="entSemesters">
                <!-- ENT Sem 1 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#entSem1">Semester 1</button>
                  </div>
                  <div id="entSem1" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Basic Electronics</td>
                            <td class="p-2 text-center">ENT-101</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Electronic Devices</td>
                            <td class="p-2 text-center">ENT-102</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- ENT Sem 2 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#entSem2">Semester 2</button>
                  </div>
                  <div id="entSem2" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Analog Circuits</td>
                            <td class="p-2 text-center">ENT-201</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Signals & Systems</td>
                            <td class="p-2 text-center">ENT-202</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- ENT Sem 3 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#entSem3">Semester 3</button>
                  </div>
                  <div id="entSem3" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Digital Electronics</td>
                            <td class="p-2 text-center">ENT-301</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Microcontrollers</td>
                            <td class="p-2 text-center">ENT-302</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- ENT Sem 4 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#entSem4">Semester 4</button>
                  </div>
                  <div id="entSem4" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Embedded Systems</td>
                            <td class="p-2 text-center">ENT-401</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Communication Systems</td>
                            <td class="p-2 text-center">ENT-402</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- ENT Sem 5 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#entSem5">Semester 5</button>
                  </div>
                  <div id="entSem5" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">VLSI Design</td>
                            <td class="p-2 text-center">ENT-501</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Digital Signal Processing</td>
                            <td class="p-2 text-center">ENT-502</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- ENT Sem 6 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#entSem6">Semester 6</button>
                  </div>
                  <div id="entSem6" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Optoelectronics</td>
                            <td class="p-2 text-center">ENT-601</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Wireless Communication</td>
                            <td class="p-2 text-center">ENT-602</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- ENT Sem 7 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#entSem7">Semester 7</button>
                  </div>
                  <div id="entSem7" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Advanced Electronics</td>
                            <td class="p-2 text-center">ENT-701</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">IoT Applications</td>
                            <td class="p-2 text-center">ENT-702</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- ENT Sem 8 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#entSem8">Semester 8</button>
                  </div>
                  <div id="entSem8" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Capstone Project</td>
                            <td class="p-2 text-center">ENT-801</td>
                            <td class="p-2 text-center">6.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Advanced Communication</td>
                            <td class="p-2 text-center">ENT-802</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div> <!-- end entSemesters -->
            </div>
          </div>
        </div>

        <!-- ELECTRO MEDICAL TECHNOLOGY (EMT) -->
        <div class="border-b">
          <div class="accordion-header" id="hdr-emt">
            <button
              class="accordion-button w-full text-left p-4 font-semibold text-lg text-gray-800 bg-gray-50 hover:bg-gray-100 flex justify-between items-center"
              type="button" data-bs-toggle="collapse" data-bs-target="#deptEMT">
              <span>Electro Medical Technology (EMT) <span
                  class="ml-3 bg-blue-100 text-blue-600 text-sm font-medium px-3 py-1 rounded-full">EMT</span></span>
              <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
          </div>
          <div id="deptEMT" class="accordion-collapse hidden" aria-labelledby="hdr-emt">
            <div class="p-4 bg-white">
              <div class="space-y-2" id="emtSemesters">
                <!-- EMT Sem 1 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#emtSem1">Semester 1</button>
                  </div>
                  <div id="emtSem1" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Human Anatomy</td>
                            <td class="p-2 text-center">EMT-101</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Basic Electronics for EMT</td>
                            <td class="p-2 text-center">EMT-102</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- EMT Sem 2 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#emtSem2">Semester 2</button>
                  </div>
                  <div id="emtSem2" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Physiology</td>
                            <td class="p-2 text-center">EMT-201</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Medical Instrumentation</td>
                            <td class="p-2 text-center">EMT-202</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- EMT Sem 3 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#emtSem3">Semester 3</button>
                  </div>
                  <div id="emtSem3" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Clinical Engineering Basics</td>
                            <td class="p-2 text-center">EMT-301</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Biostatistics</td>
                            <td class="p-2 text-center">EMT-302</td>
                            <td class="p-2 text-center">2.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- EMT Sem 4 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#emtSem4">Semester 4</button>
                  </div>
                  <div id="emtSem4" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Medical Imaging</td>
                            <td class="p-2 text-center">EMT-401</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Patient Monitoring</td>
                            <td class="p-2 text-center">EMT-402</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- EMT Sem 5 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#emtSem5">Semester 5</button>
                  </div>
                  <div id="emtSem5" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Biomedical Signal Processing</td>
                            <td class="p-2 text-center">EMT-501</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Clinical Practices</td>
                            <td class="p-2 text-center">EMT-502</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- EMT Sem 6 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#emtSem6">Semester 6</button>
                  </div>
                  <div id="emtSem6" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Medical Device Maintenance</td>
                            <td class="p-2 text-center">EMT-601</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Healthcare Technology Management</td>
                            <td class="p-2 text-center">EMT-602</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- EMT Sem 7 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#emtSem7">Semester 7</button>
                  </div>
                  <div id="emtSem7" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Advanced Medical Instrumentation</td>
                            <td class="p-2 text-center">EMT-701</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Regulatory & Safety Standards</td>
                            <td class="p-2 text-center">EMT-702</td>
                            <td class="p-2 text-center">2.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- EMT Sem 8 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#emtSem8">Semester 8</button>
                  </div>
                  <div id="emtSem8" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Final Project</td>
                            <td class="p-2 text-center">EMT-801</td>
                            <td class="p-2 text-center">6.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Biomedical Ethics</td>
                            <td class="p-2 text-center">EMT-802</td>
                            <td class="p-2 text-center">2.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div> <!-- end emtSemesters -->
            </div>
          </div>
        </div>

        <!-- TOURISM & HOSPITALITY TECHNOLOGY (THT) -->
        <div class="border-b">
          <div class="accordion-header" id="hdr-tht">
            <button
              class="accordion-button w-full text-left p-4 font-semibold text-lg text-gray-800 bg-gray-50 hover:bg-gray-100 flex justify-between items-center"
              type="button" data-bs-toggle="collapse" data-bs-target="#deptTHT">
              <span>Tourism & Hospitality Technology (THT) <span
                  class="ml-3 bg-blue-100 text-blue-600 text-sm font-medium px-3 py-1 rounded-full">THT</span></span>
              <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
          </div>
          <div id="deptTHT" class="accordion-collapse hidden" aria-labelledby="hdr-tht">
            <div class="p-4 bg-white">
              <div class="space-y-2" id="thtSemesters">
                <!-- THT Sem 1 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#thtSem1">Semester 1</button>
                  </div>
                  <div id="thtSem1" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Introduction to Tourism</td>
                            <td class="p-2 text-center">THT-101</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Basics of Hospitality</td>
                            <td class="p-2 text-center">THT-102</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- THT Sem 2 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#thtSem2">Semester 2</button>
                  </div>
                  <div id="thtSem2" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Front Office Operations</td>
                            <td class="p-2 text-center">THT-201</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Housekeeping Management</td>
                            <td class="p-2 text-center">THT-202</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- THT Sem 3 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#thtSem3">Semester 3</button>
                  </div>
                  <div id="thtSem3" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Food & Beverage Service</td>
                            <td class="p-2 text-center">THT-301</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Food Production</td>
                            <td class="p-2 text-center">THT-302</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- THT Sem 4 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#thtSem4">Semester 4</button>
                  </div>
                  <div id="thtSem4" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Event Management</td>
                            <td class="p-2 text-center">THT-401</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Tourism Marketing</td>
                            <td class="p-2 text-center">THT-402</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- THT Sem 5 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#thtSem5">Semester 5</button>
                  </div>
                  <div id="thtSem5" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Hospitality Accounting</td>
                            <td class="p-2 text-center">THT-501</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Tour Guide Management</td>
                            <td class="p-2 text-center">THT-502</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- THT Sem 6 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#thtSem6">Semester 6</button>
                  </div>
                  <div id="thtSem6" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Food Safety & Hygiene</td>
                            <td class="p-2 text-center">THT-601</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Tour Operations</td>
                            <td class="p-2 text-center">THT-602</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- THT Sem 7 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#thtSem7">Semester 7</button>
                  </div>
                  <div id="thtSem7" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Tourist Behavior</td>
                            <td class="p-2 text-center">THT-701</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Hospitality Law</td>
                            <td class="p-2 text-center">THT-702</td>
                            <td class="p-2 text-center">2.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- THT Sem 8 -->
                <div class="border rounded-lg">
                  <div class="accordion-header">
                    <button
                      class="accordion-button w-full text-left p-3 font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 flex justify-between items-center"
                      type="button" data-bs-toggle="collapse" data-bs-target="#thtSem8">Semester 8</button>
                  </div>
                  <div id="thtSem8" class="accordion-collapse hidden">
                    <div class="p-3">
                      <table class="w-full table-auto">
                        <thead class="bg-gradient-to-r from-accent1 to-blue-400 text-white">
                          <tr>
                            <th class="p-2 text-left">Book Name</th>
                            <th class="p-2">Subject Code</th>
                            <th class="p-2">Credit</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Final Project</td>
                            <td class="p-2 text-center">THT-801</td>
                            <td class="p-2 text-center">6.0</td>
                          </tr>
                          <tr class="hover:bg-blue-50">
                            <td class="p-2">Tourism Entrepreneurship</td>
                            <td class="p-2 text-center">THT-802</td>
                            <td class="p-2 text-center">3.0</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

              </div> <!-- end thtSemesters -->
            </div>
          </div>
        </div>
      </div> <!-- end departmentAccordion -->
    </div>
  </section>



  <!-- JavaScript for accordion functionality -->
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
    document.addEventListener('DOMContentLoaded', function() {
      // Get all accordion buttons
      const accordionButtons = document.querySelectorAll('.accordion-button');

      accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
          // Toggle the active class for styling
          this.classList.toggle('active');

          // Get the target element
          const targetId = this.getAttribute('data-bs-target');
          const target = document.querySelector(targetId);

          // Toggle the hidden class
          if (target) {
            target.classList.toggle('hidden');

            // Rotate the arrow icon
            const icon = this.querySelector('svg');
            if (icon) {
              icon.classList.toggle('rotate-180');
            }
          }
        });
      });
    });
  </script>

  <?php include_once '../includes/footer.php'; ?>

</body>

</html>