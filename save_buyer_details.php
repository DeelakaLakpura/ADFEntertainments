<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $_SESSION['buyer_name'] = $data['buyer_name'];
    $_SESSION['buyer_email'] = $data['buyer_email'];
    $_SESSION['buyer_phone'] = $data['buyer_phone'];
    echo json_encode(['success' => true]);
}
?>