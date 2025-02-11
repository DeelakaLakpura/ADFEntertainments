<?php
session_start();
if (file_exists('./config/DbContext.php')) {
    include './config/DbContext.php';
} else {
    die("<script>Swal.fire('Error', 'DbContext.php not found!', 'error');</script>");
}

// Function to hash passwords
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Function to verify passwords
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

$message = ""; // Store alert message

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $hashedPassword = hashPassword($password);

    $stmt = $conn->prepare("INSERT INTO tbl_admins (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        $message = "Swal.fire('Success', 'Registration successful!', 'success');";
    } else {
        $message = "Swal.fire('Error', 'Registration failed: " . addslashes($stmt->error) . "', 'error');";
    }

    $stmt->close();
}

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM tbl_admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (verifyPassword($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['admin_id'];
            $_SESSION['username'] = $user['username'];
            $message = "Swal.fire('Success', 'Login successful!', 'success').then(() => { window.location.href = 'index.php'; });";
        } else {
            $message = "Swal.fire('Error', 'Invalid password.', 'error');";
        }
    } else {
        $message = "Swal.fire('Error', 'No user found with this email.', 'error');";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.9.6/lottie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: rgba(0, 0, 255, 0.2);
        }
        .bg-glass {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 2.5rem;
        }
        #animation-container svg {
            height: 200px !important;
        }
    </style>
</head>
<body class="font-sans antialiased flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-glass">
        <div class="flex justify-between mb-6">
            <button id="login-tab" class="text-gray-700 font-semibold px-4 py-2 rounded-md transition-all duration-300 hover:bg-gray-300">Login</button>
            <button id="register-tab" class="text-gray-700 font-semibold px-4 py-2 rounded-md transition-all duration-300 hover:bg-gray-300">Register</button>
        </div>
        <div id="animation-container" class="flex justify-center mb-4"></div>
        
        <!-- Login Form -->
        <form id="login-form" class="grid grid-cols-1 gap-6" method="POST">
            <div class="flex items-center gap-2">
                <i class="fas fa-envelope text-gray-500"></i>
                <input type="email" name="email" class="w-full p-3 rounded-md bg-white border border-gray-300 text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-blue-400" placeholder="Email" required>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-lock text-gray-500"></i>
                <input type="password" name="password" class="w-full p-3 rounded-md bg-white border border-gray-300 text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-blue-400" placeholder="Password" required>
            </div>
            <button type="submit" name="login" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition-all">Login</button>
        </form>

        <!-- Register Form -->
        <form id="register-form" class="hidden grid grid-cols-1 gap-6" method="POST">
            <div class="flex items-center gap-2">
                <i class="fas fa-user text-gray-500"></i>
                <input type="text" name="name" class="w-full p-3 rounded-md bg-white border border-gray-300 text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-blue-400" placeholder="Name" required>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-envelope text-gray-500"></i>
                <input type="email" name="email" class="w-full p-3 rounded-md bg-white border border-gray-300 text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-blue-400" placeholder="Email" required>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-lock text-gray-500"></i>
                <input type="password" name="password" class="w-full p-3 rounded-md bg-white border border-gray-300 text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-blue-400" placeholder="Password" required>
            </div>
            <button type="submit" name="register" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition-all">Register</button>
        </form>
    </div>

    <script>
        document.getElementById('login-tab').addEventListener('click', function() {
            document.getElementById('login-form').classList.remove('hidden');
            document.getElementById('register-form').classList.add('hidden');
        });
        document.getElementById('register-tab').addEventListener('click', function() {
            document.getElementById('register-form').classList.remove('hidden');
            document.getElementById('login-form').classList.add('hidden');
        });

        var animation = lottie.loadAnimation({
            container: document.getElementById('animation-container'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: 'https://assets6.lottiefiles.com/packages/lf20_jcikwtux.json'
        });

        <?php if (!empty($message)) { echo $message; } ?>
    </script>
</body>
</html>
