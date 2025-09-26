<?php include_once '../includes/db.php'; ?>

<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers Details - Barishal Polytechnic Institute</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f4f8;
        }

        .header-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                url("Images/Hero.jpg") no-repeat center center;
            background-size: cover;
        }

        .teacher-card {
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .teacher-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            background: linear-gradient(45deg, #1e3a8a, #3b82f6);
            color: white;
            border-bottom: none;
        }

        .section-icon {
            color: #3b82f6;
            transition: transform 0.3s ease;
        }

        .section-icon:hover {
            transform: scale(1.1);
        }
    </style>
</head>

<body class="text-gray-800">
    <?php include_once '../includes/header.php'; ?>

    <header class="header-hero pt-24 pb-24 text-white">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-4">Meet Our Faculty</h1>
            <p class="text-lg md:text-xl text-gray-200 max-w-3xl mx-auto">
                Explore the dedicated and experienced instructors at Barishal Polytechnic Institute.
            </p>
        </div>
    </header>

    <div class="container mx-auto px-4 py-16">
        <!-- Welcome Section -->
        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 mb-16">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 text-center">
                <i class="fas fa-handshake mr-3 section-icon"></i>Welcome to Our Academic Community
            </h2>
            <div class="max-w-4xl mx-auto text-justify text-gray-700 leading-relaxed">
                <p class="mb-4">
                    At Barishal Polytechnic Institute, our faculty is the heart of our academic excellence. They are not just teachers, but mentors and innovators dedicated to shaping the next generation of engineers and technologists. Our instructors bring a wealth of real-world experience and a passion for education to the classroom, ensuring every student receives a high-quality, practical education.
                </p>
                <p>
                    We believe in a hands-on approach to learning, and our faculty members are committed to fostering an environment where curiosity thrives and creativity is celebrated. Get to know the brilliant minds who inspire our students every day.
                </p>
            </div>
        </div>

        <?php
        // Fetch all teachers ordered by designation and name
        $stmt = $pdo->query("
            SELECT * FROM teachers
            ORDER BY department ASC,
            CASE
                WHEN designation = 'Chief Instructor & Head of the Department' THEN 1
                WHEN designation = 'Instructor' THEN 2
                WHEN designation = 'Workshop Super' THEN 3
                WHEN designation = 'Junior Instructor' THEN 4
                ELSE 5
            END, name ASC
        ");
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group teachers by department
        $departments = [];
        foreach ($teachers as $t) {
            $departments[$t['department']][] = $t;
        }
        ?>

        <!-- Computer Departments -->
        <?php foreach ($departments as $deptName => $deptTeachers): ?>
            <section class="bg-white rounded-2xl shadow-xl p-6 md:p-8 mb-16">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8">
                    <i class="fas fa-chalkboard-teacher mr-3 text-blue-500"></i>
                    <?php echo htmlspecialchars($deptName); ?>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <?php foreach ($deptTeachers as $t):
                        $img = !empty($t['image']) ? '../uploads/teachers/' . htmlspecialchars($t['image']) : '/assets/images/default-teacher.png';
                    ?>
                        <div class="teacher-card text-center bg-gray-50 rounded-xl shadow-md overflow-hidden"
                            onclick='openTeacherModal(<?php echo htmlspecialchars(json_encode($t), ENT_QUOTES, 'UTF-8'); ?>)'>
                            <div class="bg-gradient-to-b from-blue-100 to-gray-50 pt-8 pb-4">
                                <img src="<?php echo $img; ?>" alt="Photo of <?php echo htmlspecialchars($t['name']); ?>"
                                    class="w-32 h-32 rounded-full mx-auto border-4 border-white shadow-lg object-cover">
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-xl text-gray-800 mb-1"><?php echo htmlspecialchars($t['name']); ?></h3>
                                <p class="text-blue-600 font-semibold text-sm"><?php echo htmlspecialchars($t['designation']); ?></p>
                                <p class="text-sm text-gray-500 mt-2"><?php echo htmlspecialchars($t['department']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>

        <!-- Civil Departments -->

        <?php foreach ($departments as $deptName => $deptTeachers): ?>
            <section class="bg-white rounded-2xl shadow-xl p-6 md:p-8 mb-16">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8">
                    <i class="fas fa-chalkboard-teacher mr-3 text-blue-500"></i>
                    Civil Technology
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <?php foreach ($deptTeachers as $t):
                        $img = !empty($t['image']) ? '../uploads/teachers/' . htmlspecialchars($t['image']) : '/assets/images/default-teacher.png';
                    ?>
                        <div class="teacher-card text-center bg-gray-50 rounded-xl shadow-md overflow-hidden"
                            onclick='openTeacherModal(<?php echo htmlspecialchars(json_encode($t), ENT_QUOTES, 'UTF-8'); ?>)'>
                            <div class="bg-gradient-to-b from-blue-100 to-gray-50 pt-8 pb-4">
                                <img src="<?php echo $img; ?>" alt="Photo of <?php echo htmlspecialchars($t['name']); ?>"
                                    class="w-32 h-32 rounded-full mx-auto border-4 border-white shadow-lg object-cover">
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-xl text-gray-800 mb-1"><?php echo htmlspecialchars($t['name']); ?></h3>
                                <p class="text-blue-600 font-semibold text-sm"><?php echo htmlspecialchars($t['designation']); ?></p>
                                <p class="text-sm text-gray-500 mt-2"><?php echo htmlspecialchars($t['department']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>

        <!-- Electrical Departments -->

        <?php foreach ($departments as $deptName => $deptTeachers): ?>
            <section class="bg-white rounded-2xl shadow-xl p-6 md:p-8 mb-16">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8">
                    <i class="fas fa-chalkboard-teacher mr-3 text-blue-500"></i>
                    Electrical Technology
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <?php foreach ($deptTeachers as $t):
                        $img = !empty($t['image']) ? '../uploads/teachers/' . htmlspecialchars($t['image']) : '/assets/images/default-teacher.png';
                    ?>
                        <div class="teacher-card text-center bg-gray-50 rounded-xl shadow-md overflow-hidden"
                            onclick='openTeacherModal(<?php echo htmlspecialchars(json_encode($t), ENT_QUOTES, 'UTF-8'); ?>)'>
                            <div class="bg-gradient-to-b from-blue-100 to-gray-50 pt-8 pb-4">
                                <img src="<?php echo $img; ?>" alt="Photo of <?php echo htmlspecialchars($t['name']); ?>"
                                    class="w-32 h-32 rounded-full mx-auto border-4 border-white shadow-lg object-cover">
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-xl text-gray-800 mb-1"><?php echo htmlspecialchars($t['name']); ?></h3>
                                <p class="text-blue-600 font-semibold text-sm"><?php echo htmlspecialchars($t['designation']); ?></p>
                                <p class="text-sm text-gray-500 mt-2"><?php echo htmlspecialchars($t['department']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>

        <!-- Electronics Departments -->

        <?php foreach ($departments as $deptName => $deptTeachers): ?>
            <section class="bg-white rounded-2xl shadow-xl p-6 md:p-8 mb-16">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8">
                    <i class="fas fa-chalkboard-teacher mr-3 text-blue-500"></i>
                    Electronics Technology
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <?php foreach ($deptTeachers as $t):
                        $img = !empty($t['image']) ? '../uploads/teachers/' . htmlspecialchars($t['image']) : '/assets/images/default-teacher.png';
                    ?>
                        <div class="teacher-card text-center bg-gray-50 rounded-xl shadow-md overflow-hidden"
                            onclick='openTeacherModal(<?php echo htmlspecialchars(json_encode($t), ENT_QUOTES, 'UTF-8'); ?>)'>
                            <div class="bg-gradient-to-b from-blue-100 to-gray-50 pt-8 pb-4">
                                <img src="<?php echo $img; ?>" alt="Photo of <?php echo htmlspecialchars($t['name']); ?>"
                                    class="w-32 h-32 rounded-full mx-auto border-4 border-white shadow-lg object-cover">
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-xl text-gray-800 mb-1"><?php echo htmlspecialchars($t['name']); ?></h3>
                                <p class="text-blue-600 font-semibold text-sm"><?php echo htmlspecialchars($t['designation']); ?></p>
                                <p class="text-sm text-gray-500 mt-2"><?php echo htmlspecialchars($t['department']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>

        <!-- Power Departments -->

        <?php foreach ($departments as $deptName => $deptTeachers): ?>
            <section class="bg-white rounded-2xl shadow-xl p-6 md:p-8 mb-16">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8">
                    <i class="fas fa-chalkboard-teacher mr-3 text-blue-500"></i>
                    Power Technology
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <?php foreach ($deptTeachers as $t):
                        $img = !empty($t['image']) ? '../uploads/teachers/' . htmlspecialchars($t['image']) : '/assets/images/default-teacher.png';
                    ?>
                        <div class="teacher-card text-center bg-gray-50 rounded-xl shadow-md overflow-hidden"
                            onclick='openTeacherModal(<?php echo htmlspecialchars(json_encode($t), ENT_QUOTES, 'UTF-8'); ?>)'>
                            <div class="bg-gradient-to-b from-blue-100 to-gray-50 pt-8 pb-4">
                                <img src="<?php echo $img; ?>" alt="Photo of <?php echo htmlspecialchars($t['name']); ?>"
                                    class="w-32 h-32 rounded-full mx-auto border-4 border-white shadow-lg object-cover">
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-xl text-gray-800 mb-1"><?php echo htmlspecialchars($t['name']); ?></h3>
                                <p class="text-blue-600 font-semibold text-sm"><?php echo htmlspecialchars($t['designation']); ?></p>
                                <p class="text-sm text-gray-500 mt-2"><?php echo htmlspecialchars($t['department']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>

        <!-- Key Departments -->
        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 mt-16">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8 text-center">
                <i class="fas fa-microchip mr-3 section-icon"></i>Our Key Departments
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="p-6 bg-gray-50 rounded-xl shadow-md text-center">
                    <div class="text-4xl text-blue-500 mb-4"><i class="fas fa-building"></i></div>
                    <h3 class="font-bold text-lg mb-2">Civil Technology</h3>
                    <p class="text-sm text-gray-600">
                        Building the world of tomorrow with practical skills in construction, infrastructure, and structural design.
                    </p>
                </div>
                <div class="p-6 bg-gray-50 rounded-xl shadow-md text-center">
                    <div class="text-4xl text-blue-500 mb-4"><i class="fas fa-bolt"></i></div>
                    <h3 class="font-bold text-lg mb-2">Electrical Technology</h3>
                    <p class="text-sm text-gray-600">
                        Igniting careers in power generation, electronics, and control systems with hands-on training.
                    </p>
                </div>
                <div class="p-6 bg-gray-50 rounded-xl shadow-md text-center">
                    <div class="text-4xl text-blue-500 mb-4"><i class="fas fa-cogs"></i></div>
                    <h3 class="font-bold text-lg mb-2">Mechanical Technology</h3>
                    <p class="text-sm text-gray-600">
                        Innovating for the future by mastering the principles of machinery, design, and manufacturing.
                    </p>
                </div>
                <div class="p-6 bg-gray-50 rounded-xl shadow-md text-center">
                    <div class="text-4xl text-blue-500 mb-4"><i class="fas fa-laptop-code"></i></div>
                    <h3 class="font-bold text-lg mb-2">Computer Science & Technology</h3>
                    <p class="text-sm text-gray-600">
                        Pioneering the future with cutting-edge programming, software development, and networking courses.
                    </p>
                </div>
            </div>
        </div>

        <!-- Vision & Mission -->
        <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 mt-16">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-8 text-center">
                <i class="fas fa-bullseye mr-3 section-icon"></i>Our Vision & Mission
            </h2>
            <div class="text-gray-700 max-w-4xl mx-auto">
                <h3 class="text-xl font-semibold text-blue-700 mb-2">Vision</h3>
                <p class="mb-6">
                    To be the leading polytechnic institute in Bangladesh, producing highly skilled, innovative, and ethically sound professionals who can meet the challenges of the modern industrial world and contribute to national development.
                </p>
                <h3 class="text-xl font-semibold text-blue-700 mb-2">Mission</h3>
                <ul class="list-disc list-inside space-y-2">
                    <li>To provide quality technical education and hands-on training that aligns with industry demands.</li>
                    <li>To foster a learning environment that encourages creativity, critical thinking, and problem-solving skills.</li>
                    <li>To ensure students are equipped with professional values, communication skills, and leadership qualities.</li>
                    <li>To promote research and innovation for the betterment of society and the industrial sector.</li>
                </ul>
            </div>
        </div>

    </div>

    <!-- Teacher Modal -->
    <div class="modal fade" id="teacherModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-xl shadow-2xl">
                <div class="modal-header rounded-t-xl">
                    <h5 class="modal-title text-xl font-bold">Teacher Profile</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-6">
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="flex flex-col items-center text-center md:col-span-1">
                            <img id="teacherModalImage" src="" alt="Teacher Photo"
                                class="w-40 h-40 rounded-full border-4 border-gray-200 shadow-lg object-cover">
                            <h3 id="teacherModalName" class="text-2xl font-bold mt-4"></h3>
                            <p id="teacherModalDesignation" class="text-blue-600 font-semibold"></p>
                        </div>
                        <div class="md:col-span-2">
                            <h4 class="text-lg font-semibold border-b pb-2 mb-4">Details</h4>
                            <div class="space-y-3 text-gray-700">
                                <p><strong>Department:</strong> <span id="teacherModalDepartment"></span></p>
                                <p><strong>Shift:</strong> <span id="teacherModalShift"></span></p>
                                <p><strong>Qualification:</strong> <span id="teacherModalQualification"></span></p>
                                <p><strong><i class="fas fa-phone-alt text-blue-500 mr-2"></i>Phone:</strong>
                                    <a id="teacherModalPhone" href="#" class="text-blue-600 hover:underline"></a>
                                </p>
                                <p><strong><i class="fas fa-envelope text-blue-500 mr-2"></i>Email:</strong>
                                    <a id="teacherModalEmail" href="#" class="text-blue-600 hover:underline"></a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openTeacherModal(teacher) {
            const defaultImg = '/assets/images/default-teacher.png';
            const teacherImg = teacher.image ? `../uploads/teachers/${teacher.image}` : defaultImg;

            document.getElementById('teacherModalImage').src = teacherImg;
            document.getElementById('teacherModalName').textContent = teacher.name;
            document.getElementById('teacherModalDesignation').textContent = teacher.designation;
            document.getElementById('teacherModalDepartment').textContent = teacher.department;
            document.getElementById('teacherModalShift').textContent = teacher.shift;
            document.getElementById('teacherModalQualification').textContent = teacher.qualification;

            const phoneLink = document.getElementById('teacherModalPhone');
            phoneLink.textContent = teacher.phone;
            phoneLink.href = `tel:${teacher.phone}`;

            const emailLink = document.getElementById('teacherModalEmail');
            emailLink.textContent = teacher.email;
            emailLink.href = `mailto:${teacher.email}`;

            new bootstrap.Modal(document.getElementById('teacherModal')).show();
        }
    </script>

</body>

</html>