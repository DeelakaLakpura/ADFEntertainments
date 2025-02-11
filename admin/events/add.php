<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include '../config/DbContext.php';
include'../components/topnav.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDir = "../uploads/events";
    $imagePath = "";

    if (isset($_FILES["eventImage"]) && $_FILES["eventImage"]["error"] == 0) {
        $fileName = time() . "_" . basename($_FILES["eventImage"]["name"]);
        $targetFilePath = $uploadDir . "/" . $fileName;

        if (move_uploaded_file($_FILES["eventImage"]["tmp_name"], $targetFilePath)) {
            $imagePath = $targetFilePath;
        }
    }

    $stmt = $conn->prepare("INSERT INTO tbl_events (event_name, event_datetime, location, max_capacity, event_type, organizer_name, image_path, event_description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssssssss",
        $_POST["event_name"],
        $_POST["event_datetime"],
        $_POST["location"],
        $_POST["max_capacity"],
        $_POST["event_type"],
        $_POST["organizer_name"],
        $imagePath,
        $_POST["event_description"]
    );

    if ($stmt->execute()) {
        $successMessage = "Event created successfully!";
    } else {
        $errorMessage = "Error: " . $stmt->error;
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
    <title>Create Amazing Event | Eventify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f9f9f9; }
        .card { box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); }
        .icon-input { position: relative; }
        .icon-input i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #4A5568; }
        .icon-input input, .icon-input textarea { padding-left: 40px; }
        #imagePreview { width: 100%; height: auto; display: none; margin-top: 10px; }
    </style>
</head>
<body class="min-h-screen flex justify-center items-center min-h-screen " >
<div class="flex justify-center items-center min-h-screen">
  <div class="max-w-5xl w-full bg-white rounded-3xl p-8 m-10">
        <div class="bg-blue-600 p-8 text-center rounded-xl mb-6">
            <h1 class="text-4xl font-extrabold text-white mb-2">Create Your Dream Event</h1>
            <p class="text-blue-200 text-lg">Share your event with the world 🌍</p>
        </div>
        <form class="grid md:grid-cols-3 gap-6" method="POST" enctype="multipart/form-data">
            <div class="icon-input">
                <i class="fas fa-ticket-alt"></i>
                <input type="text" name="event_name" placeholder="Event Name" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div class="icon-input">
                <i class="fas fa-calendar-alt"></i>
                <input type="datetime-local" name="event_datetime" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div class="icon-input">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" name="location" placeholder="Location" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div class="icon-input">
                <i class="fas fa-users"></i>
                <input type="number" name="max_capacity" placeholder="Max Capacity" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div class="icon-input">
                <i class="fas fa-tag"></i>
                <input type="text" name="event_type" placeholder="Event Type" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div class="icon-input">
                <i class="fas fa-user"></i>
                <input type="text" name="organizer_name" placeholder="Organizer Name" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div class="col-span-3 border-2 border-dashed border-gray-300 rounded-xl p-8 text-center">
                <label for="eventImage" class="cursor-pointer flex flex-col items-center">
                    <i class="fas fa-cloud-upload-alt text-5xl text-blue-500 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-700">Upload Event Image</h3>
                </label>
                <input type="file" name="eventImage" id="eventImage" class="hidden" required onchange="previewImage()">
                <img id="imagePreview" alt="Event Image Preview">
            </div>
            <div class="col-span-3 icon-input">
                <i class="fas fa-align-left"></i>
                <textarea name="event_description" placeholder="Event Description" rows="4" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required></textarea>
            </div>
            <div class="col-span-3">
                <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-lg font-bold text-lg hover:bg-blue-700 transition-all transform hover:scale-105">
                    Create Event <i class="fas fa-rocket ml-2"></i>
                </button>
            </div>
        </form>
        </div>
        </div>
    </div>

    <script>
        function previewImage() {
            const file = document.getElementById('eventImage').files[0];
            const reader = new FileReader();
            reader.onloadend = function () {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.src = reader.result;
                imagePreview.style.display = 'block';
            };
            if (file) {
                reader.readAsDataURL(file);
            } else {
                document.getElementById('imagePreview').style.display = 'none';
            }
        }

        <?php if (isset($successMessage)): ?>
            Swal.fire({
                title: 'Success!',
                text: '<?php echo $successMessage; ?>',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        <?php elseif (isset($errorMessage)): ?>
            Swal.fire({
                title: 'Error!',
                text: '<?php echo $errorMessage; ?>',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>

    <?php include"../components/footer.php"; ?>
</body>
</html>
