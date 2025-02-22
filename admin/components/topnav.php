<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .navbar-link {
            transition: all 0.3s ease-in-out;
        }

        .navbar-link:hover {
            transform: translateY(-3px);
            color: #4F46E5;
        }

        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .group:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-100 to-blue-50 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white shadow-lg p-4 rounded-b-3xl sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <!-- Logo Section -->
            <div class="flex items-center space-x-4">
                <img src="../assets/images/logo.png" alt="Logo" class="w-12 h-12 rounded-full shadow-lg">
                <div class="text-indigo-600 text-3xl font-extrabold tracking-wide">ADF</div>
            </div>

            <!-- Menu Section -->
            <div class="flex space-x-8 items-center">
                <!-- Welcome Message -->
                <h6 class="text-gray-700 text-xl font-semibold hidden md:block">Welcome, <?php echo $_SESSION['username']; ?>!</h6>

                <!-- Events Dropdown -->
                <div class="relative group">
                    <button class="text-gray-700 text-lg flex items-center navbar-link">
                        <i class="fas fa-calendar mr-2"></i> Events
                        <i class="fas fa-chevron-down ml-2"></i>
                    </button>
                    <div class="dropdown-menu absolute left-0 bg-white text-black shadow-xl rounded-lg py-2 w-48">
                        <a href="./events/add.php" class="block px-4 py-2 hover:bg-gray-100">Add Event</a>
                        <a href="./events/edit.php" class="block px-4 py-2 hover:bg-gray-100">Edit Event</a>
                        <a href="./events/delete.php" class="block px-4 py-2 hover:bg-gray-100">Delete Event</a>
                    </div>
                </div>

                <!-- EM Companies -->
                <a href="./companies.php" class="text-gray-700 text-lg flex items-center navbar-link">
                    <i class="fas fa-building mr-2"></i> EM Companies
                </a>

                <!-- Security Companies -->
                <a href="./security_companies.php" class="text-gray-700 text-lg flex items-center navbar-link">
                    <i class="fas fa-shield-alt mr-2"></i> Security Companies
                </a>

                <!-- Venues -->
                <a href="./venues.php" class="text-gray-700 text-lg flex items-center navbar-link">
                    <i class="fas fa-map-marker-alt mr-2"></i> Venues
                </a>

                <!-- Bands -->
                <a href="./bands.php" class="text-gray-700 text-lg flex items-center navbar-link">
                    <i class="fas fa-music mr-2"></i> Bands
                </a>

                <!-- Logout Button -->
                <a href="#" id="logout-btn" class="text-red-500 text-lg flex items-center navbar-link">
                    <i class="fas fa-sign-out-alt mr-2"></i> 
                </a>
            </div>
        </div>
    </nav>

  
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById("logout-btn").addEventListener("click", function(event) {
    event.preventDefault();
    Swal.fire({
        title: "Are you sure?",
        text: "You will be logged out!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, Logout"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "logout.php";
        }
    });
});
</script>

</body>

</html>