<?php
session_start();
require './config/DbContext.php'; 
require 'vendor/autoload.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

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

// Check if insertion was successful
if ($stmt->affected_rows === 0) {
    die("Error saving order.");
}

$orderId = $stmt->insert_id;

// Generate QR code data
$qrData = "Event: $eventName\nName: $buyerName\nEmail: $buyerEmail\nPhone: $buyerPhone\nTotal: Rs. $totalAmount\nTickets: $ticketTypes";

// Set QR Code options
$options = new QROptions([
    'outputType' => QRCode::OUTPUT_IMAGE_PNG,
    'eccLevel' => QRCode::ECC_H,
    'scale' => 10,
]);

// Generate QR code
$qrCode = new QRCode($options);
$qrImage = $qrCode->render($qrData);

// Ensure directory exists - use absolute path
$qrDir = $_SERVER['DOCUMENT_ROOT'] . '/qrcodes/'; // Adjust if your document root differs
if (!file_exists($qrDir)) {
    if (!mkdir($qrDir, 0755, true)) {
        die("Failed to create QR directory.");
    }
}

// Save QR code image
$qrFilename = 'order_' . $orderId . '.png';
$qrPath = $qrDir . $qrFilename;
if (!file_put_contents($qrPath, $qrImage)) {
    die("Failed to save QR code.");
}

// Web-accessible path for the image
$webQrPath = '/qrcodes/' . $qrFilename; // Adjust if your directory is under a subfolder
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
        <img src="<?= $webQrPath ?>" alt="QR Code" class="mx-auto mb-6">
        <a href="index.php" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Go to Home</a>
    </div>
</body>
</html>