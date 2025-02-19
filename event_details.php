<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include './config/DbContext.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid event ID.");
}

$event_id = intval($_GET['id']);
$query = "SELECT * FROM tbl_events WHERE event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    die("Event not found.");
}

$query_tickets = "SELECT * FROM tbl_event_tickets WHERE event_id = ?";
$stmt_tickets = $conn->prepare($query_tickets);
$stmt_tickets->bind_param("i", $event_id);
$stmt_tickets->execute();
$tickets_result = $stmt_tickets->get_result();
$tickets = $tickets_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$stmt_tickets->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($event['event_name']) ?> | Event Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>
    <style>
        body {  background: #f9fafb; }
        .btn { display: inline-block; padding: 12px 20px; border-radius: 8px; transition: 0.3s ease; }
        .btn-primary { background: #6366F1; color: white; }
        .btn-primary:hover { background: #4F46E5; }
        #map { height: 400px; width: 100%; border-radius: 8px; }
    </style>
    <script>
        function initMap() {
            const location = { lat: <?= $event['latitude'] ?>, lng: <?= $event['longitude'] ?> };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: location,
            });
            new google.maps.Marker({
                position: location,
                map: map,
            });
        }
    </script>
</head>
<body >
    <?php include'./components/topnav.php'; ?>
    <div class="min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <header class="bg-white shadow-md py-4" >
        <div class="container mx-auto px-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Event Details</h1>
            <a href="events.php" class="btn btn-primary">Back to Events</a>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-4 py-12">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <img src="./admin/<?= htmlspecialchars($event['image_path']) ?>" class="w-full h-96 object-cover" alt="Event Image">
            <div class="p-6">
                <h1 class="text-4xl font-bold text-gray-800 mb-4"> <?= htmlspecialchars($event['event_name']) ?> </h1>
                <p class="text-gray-600 mb-6"> <?= htmlspecialchars($event['event_description']) ?> </p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="text-gray-700 flex items-center"><i class="fas fa-calendar-alt mr-2"></i> <?= date('M d, Y @ h:i A', strtotime($event['event_datetime'])) ?></div>
                    <div class="text-gray-700 flex items-center"><i class="fas fa-map-marker-alt mr-2"></i> <?= htmlspecialchars($event['location']) ?></div>
                    <div class="text-gray-700 flex items-center"><i class="fas fa-users mr-2"></i> Max Capacity: <?= $event['max_capacity'] ?></div>
                    <div class="text-gray-700 flex items-center"><i class="fas fa-tag mr-2"></i> Type: <?= htmlspecialchars($event['event_type']) ?></div>
                    <div class="text-gray-700 flex items-center"><i class="fas fa-user mr-2"></i> Organizer: <?= htmlspecialchars($event['organizer_name']) ?></div>
                    <div id="map" class="md:col-span-2 lg:col-span-3"></div>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">🎟 Ticket Options</h2>
            <?php if (empty($tickets)): ?>
                <p class="text-gray-600">No tickets available.</p>
            <?php else: ?>
                <form action="payment.php" method="POST">
    <input type="hidden" name="event_id" value="<?= $event_id ?>">
    <input type="hidden" name="event_name" value="<?= htmlspecialchars($event['event_name']) ?>">
    <div class="space-y-4">
        <?php foreach ($tickets as $ticket): ?>
            <div class="flex justify-between items-center border p-4 rounded-lg shadow-sm">
                <div>
                    <h3 class="text-2xl font-bold"><?= htmlspecialchars($ticket['ticket_type']) ?></h3>
                    <p class="text-gray-600">Rs. <?= number_format($ticket['price'], 2) ?></p>
                </div>
                <input type="hidden" name="ticket_price[<?= $ticket['ticket_id'] ?>]" value="<?= $ticket['price'] ?>">
                <input type="hidden" name="ticket_type[<?= $ticket['ticket_id'] ?>]" value="<?= htmlspecialchars($ticket['ticket_type']) ?>">
                <input type="number" name="tickets[<?= $ticket['ticket_id'] ?>]" min="0" value="0" class="w-16 p-2 border rounded-lg">
            </div>
        <?php endforeach; ?>
    </div>
    <button type="submit" class="btn btn-primary mt-6 w-full">Proceed to Payment</button>
</form>

            <?php endif; ?>
        </div>
    </main>

    <?php include'./components/footer.php'; ?>

    </div>
</body>
</html>
