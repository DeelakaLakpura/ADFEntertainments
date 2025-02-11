<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include '../config/DbContext.php';

$successMessage = $errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDir = "../admin/uploads/events";
    $imagePath = "";

    // Handle file upload
    if (isset($_FILES["eventImage"]) && $_FILES["eventImage"]["error"] == 0) {
        $fileName = time() . "_" . basename($_FILES["eventImage"]["name"]);
        $targetFilePath = $uploadDir . "/" . $fileName;

        if (move_uploaded_file($_FILES["eventImage"]["tmp_name"], $targetFilePath)) {
            $imagePath = $targetFilePath;
        }
    }

   
    $stmt = $conn->prepare("INSERT INTO tbl_events (
        event_name, 
        event_datetime, 
        location, 
        max_capacity, 
        event_type, 
        organizer_name,
        image_path, 
        event_description,
        status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $default_status = "pending"; 
    
    $stmt->bind_param(
        "sssisssss", 
        $_POST["event_name"],
        $_POST["event_datetime"],
        $_POST["location"],
        $_POST["max_capacity"],
        $_POST["event_type"],
        $_POST["organizer_name"],
        $imagePath,
        $_POST["event_description"],
        $default_status
    );
    
   

    if ($stmt->execute()) {
        $event_id = $conn->insert_id;

        // Handle ticket types
        if (!empty($_POST["ticket_types"]) && !empty($_POST["ticket_prices"])) {
            $stmtTickets = $conn->prepare("INSERT INTO tbl_event_tickets (event_id, ticket_type, price) VALUES (?, ?, ?)");

            foreach ($_POST["ticket_types"] as $index => $type) {
                $price = $_POST["ticket_prices"][$index];
                $stmtTickets->bind_param("iss", $event_id, $type, $price);
                $stmtTickets->execute();
            }
            $stmtTickets->close();
        }

        $successMessage = "Event created successfully!";
    } else {
        $errorMessage = "Error creating event: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event | Eventify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        .card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
        .gradient-bg { background: linear-gradient(135deg,rgb(21, 38, 114) 0%,rgb(41, 9, 218) 100%); }
        .input-group { transition: all 0.3s ease; }
        .input-group:focus-within { transform: translateY(-2px); }
    </style>
</head>
<body >
    <?php include'../components/topnav.php' ?>
    <div class="min-h-screen py-10" style="font-family: 'Poppins', sans-serif;">
    <div class="max-w-4xl mx-auto px-4">
        <div class="card rounded-2xl p-8 shadow-2xl">
            <div class="gradient-bg text-white rounded-2xl p-8 mb-8 text-center">
                <h1 class="text-4xl font-bold mb-4">🎉 Create Your Event</h1>
                <p class="text-white/90">Fill in the details below to create an unforgettable experience</p>
            </div>

            <form method="POST" enctype="multipart/form-data" class="space-y-8">
                <!-- Event Basics Section -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="input-group">
                        <label class="block text-gray-700 mb-2 font-medium">Event Name</label>
                        <div class="flex items-center space-x-3 bg-white p-3 rounded-lg border border-gray-200">
                            <i class="fas fa-star text-purple-500"></i>
                            <input type="text" name="event_name" required 
                                   class="w-full outline-none bg-transparent"
                                   placeholder="Enter event name">
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="block text-gray-700 mb-2 font-medium">Event Type</label>
                        <div class="flex items-center space-x-3 bg-white p-3 rounded-lg border border-gray-200">
                            <i class="fas fa-tag text-blue-500"></i>
                            <select name="event_type" required class="w-full outline-none bg-transparent">
                                <option value="">Select Type</option>
                                <option value="Concert">Concert</option>
                                <option value="Conference">Conference</option>
                                <option value="Workshop">Workshop</option>
                                <option value="Festival">Festival</option>
                            </select>
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="block text-gray-700 mb-2 font-medium">Date & Time</label>
                        <div class="flex items-center space-x-3 bg-white p-3 rounded-lg border border-gray-200">
                            <i class="fas fa-calendar-alt text-green-500"></i>
                            <input type="datetime-local" name="event_datetime" required 
                                   class="w-full outline-none bg-transparent">
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="block text-gray-700 mb-2 font-medium">Max Capacity</label>
                        <div class="flex items-center space-x-3 bg-white p-3 rounded-lg border border-gray-200">
                            <i class="fas fa-users text-red-500"></i>
                            <input type="number" name="max_capacity" required 
                                   class="w-full outline-none bg-transparent"
                                   placeholder="Enter maximum attendees">
                        </div>
                    </div>
                </div>

                <!-- Location & Organizer Section -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="input-group">
                        <label class="block text-gray-700 mb-2 font-medium">Location</label>
                        <div class="flex items-center space-x-3 bg-white p-3 rounded-lg border border-gray-200">
                            <i class="fas fa-map-marker-alt text-yellow-500"></i>
                            <input type="text" name="location" required 
                                   class="w-full outline-none bg-transparent"
                                   placeholder="Enter event location">
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="block text-gray-700 mb-2 font-medium">Organizer Name</label>
                        <div class="flex items-center space-x-3 bg-white p-3 rounded-lg border border-gray-200">
                            <i class="fas fa-user-tie text-pink-500"></i>
                            <input type="text" name="organizer_name" required 
                                   class="w-full outline-none bg-transparent"
                                   placeholder="Enter organizer name">
                        </div>
                    </div>
                </div>

                <!-- Image Upload Section -->
                <div class="input-group">
                    <label class="block text-gray-700 mb-2 font-medium">Event Image</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center relative">
                        <input type="file" name="eventImage" id="eventImage" 
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                               onchange="previewImage(event)" required>
                        <div class="space-y-4">
                            <i class="fas fa-cloud-upload-alt text-4xl text-blue-500"></i>
                            <p class="text-gray-600">Drag & drop or click to upload</p>
                            <p class="text-sm text-gray-500">Recommended size: 1200x800 pixels</p>
                        </div>
                        <img id="imagePreview" class="mt-4 rounded-lg shadow-md hidden max-h-48 mx-auto">
                    </div>
                </div>

                <!-- Description Section -->
                <div class="input-group">
                    <label class="block text-gray-700 mb-2 font-medium">Event Description</label>
                    <textarea name="event_description" required rows="4"
                        class="w-full p-4 rounded-lg border border-gray-200 focus:ring-2 focus:ring-purple-500 outline-none"
                        placeholder="Describe your event..."></textarea>
                </div>

                <!-- Ticket Types Section -->
                <div class="input-group">
                    <label class="block text-gray-700 mb-4 font-medium">Ticket Types</label>
                    <div id="ticketContainer" class="space-y-4">
                        <div class="ticket-type flex gap-4 items-center">
                            <input type="text" name="ticket_types[]" 
                                   class="flex-1 p-3 border rounded-lg" 
                                   placeholder="Ticket Type" required>
                            <input type="number" name="ticket_prices[]" 
                                   class="flex-1 p-3 border rounded-lg"
                                   placeholder="Price (Rs.)" required>
                            <button type="button" onclick="removeTicketType(this)" 
                                    class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" onclick="addTicketType()"
                            class="mt-4 text-blue-500 hover:text-blue-700 font-medium">
                        <i class="fas fa-plus-circle mr-2"></i>Add Ticket Type
                    </button>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full gradient-bg text-white py-4 rounded-xl font-bold text-lg hover:opacity-90 transition-all">
                    🚀 Create Event
                </button>
            </form>
        </div>
    </div>
    </div>

    <script>
        function addTicketType() {
            const container = document.getElementById('ticketContainer');
            const newField = document.createElement('div');
            newField.className = 'ticket-type flex gap-4 items-center';
            newField.innerHTML = `
                <input type="text" name="ticket_types[]" 
                       class="flex-1 p-3 border rounded-lg" 
                       placeholder="Ticket Type" required>
                <input type="number" name="ticket_prices[]" 
                       class="flex-1 p-3 border rounded-lg"
                       placeholder="Price (Rs. )" required>
                <button type="button" onclick="removeTicketType(this)" 
                        class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(newField);
        }

        function removeTicketType(btn) {
            btn.closest('.ticket-type').remove();
        }

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('imagePreview');
                preview.src = reader.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        <?php if($successMessage): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= $successMessage ?>',
                confirmButtonColor: '#667eea'
            });
        <?php endif; ?>

        <?php if($errorMessage): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= $errorMessage ?>',
                confirmButtonColor: '#667eea'
            });
        <?php endif; ?>
    </script>

<?php include '../components/footer.php' ?>

</body>
</html>