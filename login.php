<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Register Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .custom-bg {
            background-image: url('https://via.placeholder.com/1500x1000');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="font-sans antialiased custom-bg">

<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <div class="flex justify-between mb-6">
            <button id="login-tab" class="text-gray-700 font-semibold px-4 py-2 rounded-t-lg focus:outline-none focus:bg-gray-200">Login</button>
            <button id="register-tab" class="text-gray-700 font-semibold px-4 py-2 rounded-t-lg focus:outline-none focus:bg-gray-200">Register</button>
        </div>
        <div id="login-form" class="grid grid-cols-1 gap-6">
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-user-circle text-5xl text-gray-300"></i>
            </div>
            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Enter your email">
            </div>
            <div>
                <label class="block text-gray-700">Password</label>
                <input type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Enter your password">
            </div>
            <div>
                <button class="w-full bg-indigo-500 text-white py-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600">Login</button>
            </div>
        </div>
        <div id="register-form" class="hidden grid grid-cols-1 gap-6">
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-user-plus text-5xl text-gray-300"></i>
            </div>
            <div>
                <label class="block text-gray-700">Name</label>
                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Enter your name">
            </div>
            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Enter your email">
            </div>
            <div>
                <label class="block text-gray-700">Password</label>
                <input type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Enter your password">
            </div>
            <div>
                <button class="w-full bg-indigo-500 text-white py-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600">Register</button>
            </div>
        </div>
    </div>
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
</script>

</body>
</html>
