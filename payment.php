<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['event_id'] = $_POST['event_id'];
    $_SESSION['event_name'] = $_POST['event_name'];
    $_SESSION['tickets'] = $_POST['tickets'];
    $_SESSION['ticket_price'] = $_POST['ticket_price'];
    $_SESSION['ticket_type'] = $_POST['ticket_type'];

    $total_amount = 0;
    foreach ($_SESSION['tickets'] as $id => $quantity) {
        $price = $_SESSION['ticket_price'][$id];
        $total_amount += $price * $quantity;
    }
    $_SESSION['total_amount'] = $total_amount; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment | <?= htmlspecialchars($_SESSION['event_name']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="bg-gray-100">
<?php include'./components/topnav.php'  ?>
<div class="container mx-auto py-10">
    <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Buyer Details</h2>
    <div class="container mx-auto py-10">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form id="payment-form">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="buyer_name" placeholder="Full Name" required class="border p-3 rounded-lg">
                    <input type="email" name="buyer_email" placeholder="Email" required class="border p-3 rounded-lg">
                    <input type="text" name="buyer_phone" placeholder="Phone Number" required class="border p-3 rounded-lg">
                </div>
                <div class="mt-6">
                    <h3 class="text-xl font-semibold">Total Amount: Rs.<?= number_format($_SESSION['total_amount'], 2, '.', '') ?></h3>
                </div>
                <button id="stripe-button" class="mt-8 w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    Pay with Stripe
                </button>
            </form>
        </div>
    </div> 
</div>
<?php include'./components/footer.php'  ?>



    <script>
        const stripe = Stripe('pk_test_51QrL9cE6kHXgz0dxvspQN5AqBfeAMbTeg1riSrtPghQyEAynT0xmm68qsXwfY91XNnpXCA71grQLGCxapT2RPIn000KsJjM65y'); // Replace with your public key
        
        document.getElementById('stripe-button').addEventListener('click', async (e) => {
            e.preventDefault();
            
            const form = document.getElementById('payment-form');
            const formData = new FormData(form);
            const buyerData = {
                buyer_name: formData.get('buyer_name'),
                buyer_email: formData.get('buyer_email'),
                buyer_phone: formData.get('buyer_phone')
            };

            try {
                // Save buyer details
                await fetch('save_buyer_details.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(buyerData)
                });

                // Create Stripe session
                const response = await fetch('create_session.php');
                const session = await response.json();

                // Redirect to Stripe
                const result = await stripe.redirectToCheckout({ sessionId: session.id });
                if (result.error) {
                    alert(result.error.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });
    </script>
</body>
</html>