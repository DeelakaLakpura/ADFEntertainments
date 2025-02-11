<?php
session_start();
include './config/DbContext.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $message = trim($_POST["message"]);

    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'All fields are required!'];
        header("Location: contact.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Invalid email format!'];
        header("Location: contact.php");
        exit();
    }

    // Corrected SQL Query for mysqli
    $stmt = $conn->prepare("INSERT INTO tbl_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Message sent successfully!'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Something went wrong!'];
    }

    $stmt->close();
    $conn->close();

    header("Location: contact.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php include './components/topnav.php'; ?>

    <div class="flex items-center justify-center min-h-screen p-6">
        <div class="container max-w-7xl bg-white shadow-2xl rounded-3xl p-8 grid md:grid-cols-2 gap-8" style="font-family: Poppins;">
            
            <!-- Left Side: Contact Form -->
            <div class="p-8 bg-blue-50 rounded-2xl shadow-md">
                <h2 class="text-4xl font-bold text-blue-700 mb-6">Get in Touch</h2>
                <p class="text-gray-600 mb-6">We'd love to hear from you! Fill out the form and we’ll get back to you soon.</p>
                
                <form action="" method="POST" class="space-y-6">
                    <input type="text" name="name" placeholder="Your Name" class="w-full p-4 border border-blue-300 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500" required>
                    <input type="email" name="email" placeholder="Your Email" class="w-full p-4 border border-blue-300 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500" required>
                    <textarea name="message" rows="4" placeholder="Your Message" class="w-full p-4 border border-blue-300 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500" required></textarea>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-lg font-semibold text-lg shadow-lg transition-transform transform hover:scale-105">
                        Send Message
                    </button>
                </form>
            </div>

            <!-- Right Side: Lottie Animation & Contact Info -->
            <div class="flex flex-col items-center text-center">
                <lottie-player src="./assets/lottie/contact.json" background="transparent" speed="1" class="w-96 h-96" loop autoplay></lottie-player>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 w-full">
                    <div class="flex flex-col items-center p-6 shadow-lg border border-gray-200 rounded-xl bg-white hover:shadow-2xl transition-shadow">
                        <i class="fas fa-phone-alt text-blue-600 text-3xl mb-2"></i>
                        <p class="text-gray-700 font-medium text-sm">+94 11 34 567 890</p>
                    </div>
                    <div class="flex flex-col items-center p-6 shadow-lg border border-gray-200 rounded-xl bg-white hover:shadow-2xl transition-shadow">
                        <i class="fas fa-envelope text-blue-600 text-3xl mb-2"></i>
                        <p class="text-gray-700 font-medium text-sm">info@ADF.com</p>
                    </div>
                    <div class="flex flex-col items-center p-6 shadow-lg border border-gray-200 rounded-xl bg-white hover:shadow-2xl transition-shadow">
                        <i class="fas fa-map-marker-alt text-blue-600 text-3xl mb-2"></i>
                        <p class="text-gray-700 font-medium text-sm">123 Business St, City</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include './components/footer.php'; ?>

    <!-- SweetAlert Notifications -->
    <?php
    if (isset($_SESSION['alert'])) {
        echo "<script>
            Swal.fire({
                icon: '{$_SESSION['alert']['type']}',
                title: '". ucfirst($_SESSION['alert']['type']) ."',
                text: '{$_SESSION['alert']['message']}',
                confirmButtonColor: '#3085d6'
            });
        </script>";
        unset($_SESSION['alert']);
    }
    ?>
</body>
</html>
