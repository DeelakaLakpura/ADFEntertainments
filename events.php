<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include './config/DbContext.php';

$query = "SELECT * FROM tbl_events WHERE status = 'active' ORDER BY event_datetime DESC";
$result = $conn->query($query);

$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Events | Eventify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #eef2f3; }
        .card { transition: all 0.3s ease; border-radius: 12px; overflow: hidden; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15); }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .event-image { height: 220px; object-fit: cover; border-top-left-radius: 12px; border-top-right-radius: 12px; }
        .btn { display: inline-block; padding: 10px 16px; border-radius: 8px; transition: 0.3s ease; }
        .btn-primary { background: #4F46E5; color: white; }
        .btn-primary:hover { background: #4338CA; }
        .whatsapp-btn {
            transition: width 0.3s ease-in-out, padding 0.3s ease-in-out;
        }
    </style>
</head>
<body>
    <?php include "./components/topnav.php"; ?>
    <div class="min-h-screen" style="font-family: 'Poppins', sans-serif;">
    <div class="gradient-bg text-white py-12 text-center">
        <h1 class="text-4xl font-bold mb-4">🎉 Explore Upcoming Events</h1>
        <p class="text-lg text-white/90">Discover amazing events happening near you</p>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-12" >
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (empty($events)): ?>
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-600 text-lg">No events found. Check back later!</p>
                </div>
            <?php else: ?>
                <?php foreach ($events as $event): ?>
                    <div class="card bg-white shadow-lg relative">
                        <img src="./admin/<?= $event['image_path'] ?>" alt="<?= $event['event_name'] ?>" class="event-image w-full">
                        <div class="p-6">
                            <span class="block text-gray-500 text-sm mb-2">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                <?= date('M d, Y @ h:i A', strtotime($event['event_datetime'])) ?>
                            </span>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2"><?= $event['event_name'] ?></h3>
                            <p class="text-gray-600 mb-4 truncate"> <?= $event['event_description'] ?> </p>
                            <div class="flex justify-between items-center">
                                <a href="event_details.php?id=<?= $event['event_id'] ?>" class="btn btn-primary">
                                    View Event <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    </div>
    <div class="fixed bottom-6 right-6 flex items-center">
        <a href="./events/add.php" 
           class="whatsapp-btn bg-green-500 text-white flex items-center justify-center rounded-full shadow-lg w-12 h-12 hover:w-40 hover:pl-4 overflow-hidden"
           id="addbutton">
            <i class="fab fa-plus text-2xl"></i>
            <span class="ml-3 text-lg font-medium whitespace-nowrap hidden">Add Event</span>
        </a>
    </div>

    <script>
        const button = document.getElementById('addbutton');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                button.classList.add('w-40', 'pl-4');
                button.querySelector('span').classList.remove('hidden');
            } else {
                button.classList.remove('w-40', 'pl-4');
                button.querySelector('span').classList.add('hidden');
            }
        });
    </script>
    <?php include "./components/footer.php"; ?>

</body>
</html>
