
<?php
include_once '../includes/db.php';
session_start();
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $roll = trim($_POST['roll']);
    $registration = trim($_POST['registration']);

    if(!empty($roll) && !empty($registration)){
        $stmt = $pdo->prepare("SELECT * FROM students WHERE roll=? AND registration=? LIMIT 1");
        $stmt->execute([$roll, $registration]);
        $student = $stmt->fetch();

        if($student){
            // Student exists, set session
            $_SESSION['student_id'] = $student['id'];
            header("Location: student-dashboard.php");
            exit();
        } else {
            $error = "ভুল Roll বা Registration!";
        }
    } else {
        $error = "সকল ফিল্ড পূরণ করুন!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-indigo-500 to-purple-800 flex items-center justify-center min-h-screen p-4">

    <div
        class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-md transform transition-all hover:scale-105 duration-300">
        <div class="flex flex-col items-center mb-8">
            <div class="bg-blue-600 rounded-full p-4 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" viewBox="0 0 24 24"
                    fill="currentColor">
                    <path
                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Student Login</h1>
        </div>

        <?php if($error) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="student-login.php" class="space-y-6">
            <div>
                <label for="roll" class="block text-sm font-medium text-gray-700">Roll Number</label>
                <input type="text" id="roll" name="roll" placeholder="Enter your roll number" required class="mt-1 block w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="registration" class="block text-sm font-medium text-gray-700">Registration Number</label>
                <input type="text" id="registration" name="registration" placeholder="Enter your registration number" required class="mt-1 block w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
            </div>

            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700">
            Login
        </button>
        </form>
        <div id="error-popup" class="hidden bg-red-100 text-red-700 p-3 rounded-lg text-center mb-4">
            Roll number or registration number is incorrect.
        </div>

        <script>
            // Check if error parameter is in URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('error')) {
                document.getElementById('error-popup').classList.remove('hidden');
            }
        </script>


        <div class="mt-6 text-center">
            <a href="student_register.php" class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline">Create Account</a>
            <span class="text-gray-400 mx-2">•</span>
            <a href="index.php" class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline">Back to
                Homepage</a>
        </div>
    </div>

</body>

</html>