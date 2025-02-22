<?php
session_start();
include("./config/DbContext.php");

if (isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $venueId = intval($_POST['venue_id']);
    $newStatus = $_POST['status'] === 'active' ? 'active' : 'inactive';

    $stmt = $conn->prepare("UPDATE venue_rentals SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $venueId);
    if ($stmt->execute()) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Venue status updated successfully.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to update venue status.'];
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $venueId = intval($_POST['venue_id']);
    $stmt = $conn->prepare("DELETE FROM venue_rentals WHERE id = ?");
    $stmt->bind_param("i", $venueId);
    if ($stmt->execute()) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Venue deleted successfully.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to delete venue.'];
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$venues = [];
try {
    $result = $conn->query("SELECT * FROM venue_rentals ORDER BY created_at DESC");
    while ($row = $result->fetch_assoc()) {
        $venues[] = $row;
    }
} catch (Exception $e) {
    $_SESSION['alert'] = ['type' => 'error', 'message' => 'Error fetching venues.'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Venues</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 text-black">
    <?php include("./components/topnav.php"); ?>

    <div class="container mx-auto px-4 py-8" style="font-family: Poppins;">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Manage Venues</h1>
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Name</th>
                        <th class="py-3 px-6 text-left">Type</th>
                        <th class="py-3 px-6 text-left">Location</th>
                        <th class="py-3 px-6 text-left">Capacity</th>
                        <th class="py-3 px-6 text-left">Price/Hour</th>
                        <th class="py-3 px-6 text-left">Status</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm font-light">
                    <?php foreach ($venues as $venue): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-6 text-left"> <?= htmlspecialchars($venue['name']); ?> </td>
                            <td class="py-3 px-6 text-left"> <?= htmlspecialchars($venue['type']); ?> </td>
                            <td class="py-3 px-6 text-left"> <?= htmlspecialchars($venue['location']); ?> </td>
                            <td class="py-3 px-6 text-left"> <?= htmlspecialchars($venue['capacity']); ?> </td>
                            <td class="py-3 px-6 text-left"> $<?= number_format($venue['price_per_hour'], 2); ?> </td>
                            <td class="py-3 px-6 text-left">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $venue['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?= ucfirst($venue['status']); ?>
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center space-x-4">
                                    <button onclick="updateStatus(<?= $venue['id']; ?>, '<?= $venue['status'] === 'active' ? 'inactive' : 'active'; ?>')" class="w-8 h-8 bg-blue-500 text-white rounded-full hover:bg-blue-600 flex items-center justify-center">
                                        <i class="fas <?= $venue['status'] === 'active' ? 'fa-toggle-off' : 'fa-toggle-on'; ?>"></i>
                                    </button>
                                    <button onclick="deleteVenue(<?= $venue['id']; ?>)" class="w-8 h-8 bg-red-500 text-white rounded-full hover:bg-red-600 flex items-center justify-center">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Update venue status
        function updateStatus(venueId, newStatus) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to set this venue to ${newStatus}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.innerHTML = `<input type="hidden" name="action" value="update_status">
                                      <input type="hidden" name="venue_id" value="${venueId}">
                                      <input type="hidden" name="status" value="${newStatus}">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Delete venue
        function deleteVenue(venueId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.innerHTML = `<input type="hidden" name="action" value="delete">
                                      <input type="hidden" name="venue_id" value="${venueId}">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Show SweetAlert notifications
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (isset($_SESSION['alert'])): ?>
                Swal.fire({
                    title: '<?= $_SESSION['alert']['type'] === 'success' ? 'Success!' : 'Error!' ?>',
                    text: '<?= $_SESSION['alert']['message'] ?>',
                    icon: '<?= $_SESSION['alert']['type'] ?>',
                    confirmButtonText: 'OK'
                });
                <?php unset($_SESSION['alert']); ?>
            <?php endif; ?>
        });
    </script>
    <?php include('./components/footer.php'); ?>
</body>
</html>

<?php $conn->close(); ?>
