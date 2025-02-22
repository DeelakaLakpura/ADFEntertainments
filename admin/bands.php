<?php
session_start();
include("./config/DbContext.php");

// Update band status
if (isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $bandId = intval($_POST['band_id']);
    $newStatus = $_POST['status'] === 'active' ? 'active' : 'inactive';

    $stmt = $conn->prepare("UPDATE tbl_bands SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $bandId);
    if ($stmt->execute()) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Band status updated successfully.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to update band status.'];
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Delete band
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $bandId = intval($_POST['band_id']);
    $stmt = $conn->prepare("DELETE FROM tbl_bands WHERE id = ?");
    $stmt->bind_param("i", $bandId);
    if ($stmt->execute()) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Band deleted successfully.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to delete band.'];
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Update band data (including image)
if (isset($_POST['action']) && $_POST['action'] === 'update_band') {
    $bandId = intval($_POST['band_id']);
    $name = $_POST['name'];
    $genre = $_POST['genre'];
    $location = $_POST['location'];
    $contactNumber = $_POST['contact_number'];
    $email = $_POST['email'];
    $description = $_POST['description'];

    $newImageUrl = null;
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
        $imageName = $_FILES['image_url']['name'];
        $imageTmpName = $_FILES['image_url']['tmp_name'];
        $imagePath = './uploads/' . basename($imageName);
        move_uploaded_file($imageTmpName, $imagePath);
        $newImageUrl = $imagePath;
    }

    $stmt = $conn->prepare("UPDATE tbl_bands SET name = ?, genre = ?, location = ?, contact_number = ?, email = ?, description = ?, image_url = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $name, $genre, $location, $contactNumber, $email, $description, $newImageUrl, $bandId);
    if ($stmt->execute()) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Band details updated successfully.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to update band details.'];
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch bands
$bands = [];
try {
    $result = $conn->query("SELECT * FROM tbl_bands ORDER BY created_at DESC");
    while ($row = $result->fetch_assoc()) {
        $bands[] = $row;
    }
} catch (Exception $e) {
    $_SESSION['alert'] = ['type' => 'error', 'message' => 'Error fetching bands.'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bands</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 text-black" style="font-family: Poppins;">
    <?php include("./components/topnav.php"); ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Manage Bands</h1>
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Image</th>
                        <th class="py-3 px-6 text-left">Name</th>
                        <th class="py-3 px-6 text-left">Genre</th>
                        <th class="py-3 px-6 text-left">Location</th>
                        <th class="py-3 px-6 text-left">Contact</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-left">Description</th>
                        <th class="py-3 px-6 text-left">Status</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm font-light">
                    <?php foreach ($bands as $band): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-6 text-left">
                                <?php if (!empty($band['image_url'])): ?>
                                    <img src=".<?= htmlspecialchars($band['image_url']); ?>" alt="Band Image" class="w-12 h-12 rounded-full object-cover">
                                <?php else: ?>
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-music text-gray-500"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-6"> <?= htmlspecialchars($band['name']); ?> </td>
                            <td class="py-3 px-6"> <?= htmlspecialchars($band['genre']); ?> </td>
                            <td class="py-3 px-6"> <?= htmlspecialchars($band['location']); ?> </td>
                            <td class="py-3 px-6"> <?= htmlspecialchars($band['contact_number']); ?> </td>
                            <td class="py-3 px-6"> <?= htmlspecialchars($band['email']); ?> </td>
                            <td class="py-3 px-6"> <?= htmlspecialchars($band['description']); ?> </td>
                            <td class="py-3 px-6">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $band['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?= ucfirst($band['status']); ?>
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center space-x-4">
                                    <button onclick="viewBand(<?= $band['id']; ?>)" class="w-8 h-8 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 flex items-center justify-center">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="updateStatus(<?= $band['id']; ?>, '<?= $band['status'] === 'active' ? 'inactive' : 'active'; ?>')" class="w-8 h-8 bg-blue-500 text-white rounded-full hover:bg-blue-600 flex items-center justify-center">
                                        <i class="fas <?= $band['status'] === 'active' ? 'fa-toggle-off' : 'fa-toggle-on'; ?>"></i>
                                    </button>
                                    <button onclick="deleteBand(<?= $band['id']; ?>)" class="w-8 h-8 bg-red-500 text-white rounded-full hover:bg-red-600 flex items-center justify-center">
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

    <!-- Modal for View and Edit Band -->
    <div id="bandModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg w-96">
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Edit Band</h2>
            <form id="bandForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_band">
                <input type="hidden" id="bandId" name="band_id">
                <div class="mb-4">
                    <label for="name" class="block text-gray-600">Name</label>
                    <input type="text" id="name" name="name" class="w-full border-gray-300 p-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label for="genre" class="block text-gray-600">Genre</label>
                    <input type="text" id="genre" name="genre" class="w-full border-gray-300 p-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label for="location" class="block text-gray-600">Location</label>
                    <input type="text" id="location" name="location" class="w-full border-gray-300 p-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label for="contact_number" class="block text-gray-600">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number" class="w-full border-gray-300 p-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-600">Email</label>
                    <input type="email" id="email" name="email" class="w-full border-gray-300 p-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-600">Description</label>
                    <textarea id="description" name="description" class="w-full border-gray-300 p-2 rounded" rows="4" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="image_url" class="block text-gray-600">Image</label>
                    <input type="file" id="image_url" name="image_url" class="w-full border-gray-300 p-2 rounded">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Save Changes</button>
                    <button type="button" onclick="closeModal()" class="bg-gray-500 text-white py-2 px-4 rounded ml-2">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function viewBand(bandId) {
            fetchBandData(bandId);
            document.getElementById('bandModal').classList.remove('hidden');
        }

        function fetchBandData(bandId) {
            fetch(`get_band_data.php?id=${bandId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('bandId').value = data.id;
                    document.getElementById('name').value = data.name;
                    document.getElementById('genre').value = data.genre;
                    document.getElementById('location').value = data.location;
                    document.getElementById('contact_number').value = data.contact_number;
                    document.getElementById('email').value = data.email;
                    document.getElementById('description').value = data.description;
                });
        }

        function closeModal() {
            document.getElementById('bandModal').classList.add('hidden');
        }

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
