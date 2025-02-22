<?php
session_start();
require './config/DbContext.php'; // Include DB connection
require 'vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

// Store order details in DB
$eventId = $_SESSION['event_id'];
$eventName = $_SESSION['event_name'];
$buyerName = $_SESSION['buyer_name'];
$buyerEmail = $_SESSION['buyer_email'];
$buyerPhone = $_SESSION['buyer_phone'];
$totalAmount = $_SESSION['total_amount'];
$tickets = json_encode($_SESSION['tickets']);
$ticketTypes = json_encode($_SESSION['ticket_type']);
$orderDate = date('Y-m-d H:i:s');

// Insert order
$stmt = $conn->prepare("INSERT INTO Tbl_orders (event_id, event_name, buyer_name, buyer_email, buyer_phone, total_amount, tickets, ticket_types, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssdsss", $eventId, $eventName, $buyerName, $buyerEmail, $buyerPhone, $totalAmount, $tickets, $ticketTypes, $orderDate);
$stmt->execute();

// Generate QR code data
$qrData = "Event: $eventName\nName: $buyerName\nEmail: $buyerEmail\nPhone: $buyerPhone\nTotal: Rs. $totalAmount\nTickets: $ticketTypes";

// Generate QR Code
$result = Builder::create()
    ->writer(new PngWriter())
    ->data($qrData)
    ->size(300)
    ->margin(10)
    ->build();

$qrPath = 'qrcodes/order_' . $stmt->insert_id . '.png';
$result->saveToFile($qrPath);

// Display success and QR
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-10 text-center">
        <h2 class="text-3xl font-bold text-green-600 mb-4">Payment Successful!</h2>
        <p class="text-lg text-gray-700 mb-6">Thank you, <strong><?= htmlspecialchars($buyerName) ?></strong>, for purchasing tickets for <strong><?= htmlspecialchars($eventName) ?></strong>.</p>
        <h3 class="text-xl font-semibold mb-4">Your QR Code:</h3>
        <img src="<?= $qrPath ?>" alt="QR Code" class="mx-auto mb-6">
        <a href="index.php" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Go to Home</a>
    </div>
</body>
</html>
