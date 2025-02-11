<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include '../config/DbContext.php';
include'../components/topnav.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_event_id'])) {
    // Get the event ID to delete
    $eventId = $_POST['delete_event_id'];

    // Prepare the SQL statement to delete the event
    $stmt = $conn->prepare("DELETE FROM tbl_events WHERE id = ?");
    $stmt->bind_param("i", $eventId);

    if ($stmt->execute()) {
        $successMessage = "Event deleted successfully!";
    } else {
        $errorMessage = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

// Fetch events from the database
$query = "SELECT * FROM tbl_events";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events | Eventify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f9f9f9; }
        .card { box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body class="min-h-screen p-6">

<div class="flex justify-center items-center min-h-screen">
<div class="max-w-5xl w-full bg-white rounded-3xl p-8 m-10">
        <div class="bg-blue-600 p-8 text-center rounded-xl mb-6">
            <h1 class="text-4xl font-extrabold text-white mb-2">Manage Your Events</h1>
            <p class="text-blue-200 text-lg">Review and delete your events</p>
        </div>

        <!-- Display events in a table -->
        <table class="min-w-full table-auto text-left border-collapse">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="p-3">Event Name</th>
                    <th class="p-3">Date & Time</th>
                    <th class="p-3">Location</th>
                    <th class="p-3">Max Capacity</th>
                    <th class="p-3">Organizer</th>
                    <th class="p-3">Image</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($event = $result->fetch_assoc()) { ?>
                    <tr>
                        <td class="p-3"><?php echo htmlspecialchars($event['event_name']); ?></td>
                        <td class="p-3"><?php echo htmlspecialchars($event['event_datetime']); ?></td>
                        <td class="p-3"><?php echo htmlspecialchars($event['location']); ?></td>
                        <td class="p-3"><?php echo htmlspecialchars($event['max_capacity']); ?></td>
                        <td class="p-3"><?php echo htmlspecialchars($event['organizer_name']); ?></td>
                        <td class="p-3">
                            <?php if (!empty($event['image_path'])): ?>
                                <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="Event Image" class="w-24 h-24 object-cover rounded">
                            <?php else: ?>
                                No image
                            <?php endif; ?>
                        </td>
                        <td class="p-3">
                            <form method="POST" onsubmit="return confirmDelete()">
                                <input type="hidden" name="delete_event_id" value="<?php echo $event['id']; ?>">
                                <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700">
                                    Delete <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    </div>
    

    <script>
        function confirmDelete() {
            return Swal.fire({
                title: 'Are you sure?',
                text: 'This event will be deleted permanently!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                return result.isConfirmed; // If confirmed, the form will submit
            });
        }

        <?php if (isset($successMessage)): ?>
            Swal.fire({
                title: 'Success!',
                text: <?php echo json_encode($successMessage); ?>,
                icon: 'success',
                confirmButtonText: 'OK'
            });
        <?php elseif (isset($errorMessage)): ?>
            Swal.fire({
                title: 'Error!',
                text: <?php echo json_encode($errorMessage); ?>,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>
<?php include'../components/footer.php';
 ?>
</body>
</html>
