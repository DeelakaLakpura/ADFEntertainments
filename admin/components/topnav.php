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
        .navbar-link:hover {
            transform: scale(1.1);
            transition: transform 0.3s ease-in-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-100 to-blue-50">

<!-- Navbar -->
<nav class="bg-white shadow-md p-4">
    <div class="container mx-auto flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center space-x-4">
  <img src="./assets/images/logo.png" alt="Logo" class="w-12 h-12 rounded-full">
  <div class="text-indigo-600 text-3xl font-bold">ADF</div>
</div>

        
        <!-- Search Bar -->
        <div class="relative w-1/3">
            <input type="text" placeholder="Search..." class="w-full p-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>

        <!-- Menu -->
        <div class="flex space-x-6 items-center">
            <div class="relative group">
                <button class="text-gray-700 text-lg flex items-center navbar-link">
                    <i class="fas fa-calendar mr-2"></i> Events
                    <i class="fas fa-chevron-down ml-2"></i>
                </button>
                <div class="absolute left-0 hidden bg-white text-black shadow-lg rounded-lg group-hover:block transition-all duration-300 ease-in-out">
                    <a href="./events/add.php" class="block px-4 py-2 hover:bg-gray-200">Add</a>
                    <a href="./events/edit.php" class="block px-4 py-2 hover:bg-gray-200">Edit</a>
                    <a href="./events/delete.php" class="block px-4 py-2 hover:bg-gray-200">Delete</a>
                </div>
            </div>

            <div class="relative group">
                <button class="text-gray-700 text-lg flex items-center navbar-link">
                    <i class="fas fa-users mr-2"></i> Users
                    <i class="fas fa-chevron-down ml-2"></i>
                </button>
                <div class="absolute left-0 hidden bg-white text-black shadow-lg rounded-lg group-hover:block transition-all duration-300 ease-in-out">
                    <a href="#" class="block px-4 py-2 hover:bg-gray-200">User Management</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-200">Roles</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-200">Permissions</a>
                </div>
            </div>

            <a href="#" class="text-gray-700 text-lg flex items-center navbar-link">
                <i class="fas fa-bell mr-2"></i> Notifications
            </a>
            
            <a href="#" class="text-red-500 text-lg flex items-center navbar-link">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
        </div>
    </div>
</nav>
</body>
</html>
