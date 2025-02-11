<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

</head>

<body >
        <?php include'./components/topnav.php' ?>
    <div class="flex items-center justify-center min-h-screen p-6">
    <div class="container max-w-7xl bg-white shadow-2xl rounded-3xl p-8 grid md:grid-cols-2 gap-8" style="font-family: poppins;">
        <!-- Left Side: Contact Form -->
        <div class="p-8 bg-blue-50 rounded-2xl shadow-md">
            <h2 class="text-4xl font-bold text-blue-700 mb-6">Get in Touch</h2>
            <p class="text-gray-600 mb-6">We'd love to hear from you! Fill out the form and we’ll get back to you soon.</p>
            <form class="space-y-6">
                <input type="text" name="name" placeholder="Your Name" class="w-full p-4 border border-blue-300 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500">
                <input type="email" name="email" placeholder="Your Email" class="w-full p-4 border border-blue-300 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500">
                <textarea name="message" rows="4" placeholder="Your Message" class="w-full p-4 border border-blue-300 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-500"></textarea>
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-lg font-semibold text-lg shadow-lg transition-transform transform hover:scale-105">Send Message</button>
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
    <?php include'./components/footer.php' ?>

</body>
</html>
