<?php
session_start();
$event_id = $_SESSION['event_id'];
$buyer_name = $_POST['buyer_name'] ?? '';
$buyer_email = $_POST['buyer_email'] ?? '';
$buyer_phone = $_POST['buyer_phone'] ?? '';


session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Success</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-10 rounded-lg shadow-lg text-center">
        <h1 class="text-3xl font-bold text-green-600 mb-4">✅ Payment Successful!</h1>
        <p class="text-gray-600 mb-4">Thank you, <?= htmlspecialchars($buyer_name) ?>, for purchasing tickets.</p>
        <a href="events.php" class="btn btn-primary">Back to Events</a>
    </div>
</body>
</html>
