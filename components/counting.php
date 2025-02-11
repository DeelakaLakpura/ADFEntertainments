<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Planner Cards</title>
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
       
        .card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background: linear-gradient(135deg, #6a82fb,rgb(49, 5, 195));
            color: white;
            padding: 1rem;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .count {
            font-size: 3rem;
            font-weight: bold;
        }
        .icon {
            font-size: 2rem;
        }
    </style>
</head>
<body class="bg-gray-50">

    <div class="container mx-auto p-8" style="  font-family: 'Poppins', sans-serif;">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Card 1 -->
            <div class="flex justify-center">
                <div class="max-w-xs card">
                    <div class="card-header flex items-center space-x-3">
                        <i class="fas fa-calendar-alt icon text-white"></i>
                        <h2 class="text-2xl font-semibold">Events Created</h2>
                    </div>
                    <div class="p-6">
                        <div class="mt-4 count text-blue-600" data-count="120">0</div>
                        <p class="mt-2 text-gray-600">Events created successfully by your team!</p>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="flex justify-center">
                <div class="max-w-xs card">
                    <div class="card-header flex items-center space-x-3">
                        <i class="fas fa-users icon text-white"></i>
                        <h2 class="text-2xl font-semibold">Attendees</h2>
                    </div>
                    <div class="p-6">
                        <div class="mt-4 count text-green-600" data-count="3500">0</div>
                        <p class="mt-2 text-gray-600">Total attendees for upcoming events.</p>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="flex justify-center">
                <div class="max-w-xs card">
                    <div class="card-header flex items-center space-x-3">
                        <i class="fas fa-dollar-sign icon text-white"></i>
                        <h2 class="text-2xl font-semibold">Revenue </h2>
                    </div>
                    <div class="p-6">
                        <div class="mt-4 count text-yellow-600" data-count="45000">0</div>
                        <p class="mt-2 text-gray-600">Revenue generated from all events this year.</p>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="flex justify-center">
                <div class="max-w-xs card">
                    <div class="card-header flex items-center space-x-3">
                        <i class="fas fa-cogs icon text-white"></i>
                        <h2 class="text-2xl font-semibold">Active Events</h2>
                    </div>
                    <div class="p-6">
                        <div class="mt-4 count text-purple-600" data-count="150">0</div>
                        <p class="mt-2 text-gray-600">Currently ongoing and active event projects.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.count');
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-count');
                    const count = +counter.innerText;
                    const increment = target / 200;

                    if (count < target) {
                        counter.innerText = Math.ceil(count + increment);
                        setTimeout(updateCount, 1);
                    } else {
                        counter.innerText = target;
                    }
                };

                updateCount();
            });
        });
    </script>

</body>
</html>
