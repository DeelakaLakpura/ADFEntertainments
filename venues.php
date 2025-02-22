<?php
session_start();
include("./config/DbContext.php");

// Handle venue submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $venueName = htmlspecialchars($_POST['venueName']);
    $venueType = htmlspecialchars($_POST['venueType']);
    $location = htmlspecialchars($_POST['location']);
    $capacity = htmlspecialchars($_POST['capacity']);
    $contactNumber = htmlspecialchars($_POST['contactNumber']);
    $email = htmlspecialchars($_POST['email']);
    $pricePerHour = htmlspecialchars($_POST['pricePerHour']);
    $description = htmlspecialchars($_POST['description']);
    $status = 'pending';
    $imagePath = '';

    // Image upload
    if (isset($_FILES['venueImage']) && $_FILES['venueImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = './admin/uploads/venues/';
        if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileTmpPath = $_FILES['venueImage']['tmp_name'];
        $fileName = basename($_FILES['venueImage']['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = uniqid('venue_', true) . "." . $fileExtension;
            $destPath = $uploadDir . $newFileName;
            if (move_uploaded_file($fileTmpPath, $destPath)) $imagePath = $destPath;
        }
    }

    $stmt = $conn->prepare("INSERT INTO venue_rentals (name, type, location, capacity, contact_number, email, price_per_hour, description, status, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssdsss", $venueName, $venueType, $location, $capacity, $contactNumber, $email, $pricePerHour, $description, $status, $imagePath);

    $_SESSION['alert'] = $stmt->execute()
        ? ['type' => 'success', 'message' => 'Venue added successfully.']
        : ['type' => 'error', 'message' => 'There was an error adding the venue.'];

    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch venues with search & filter
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$filterType = isset($_GET['venueTypeFilter']) ? $_GET['venueTypeFilter'] : '';
$venues = [];
$error = '';

try {
    $sql = "SELECT * FROM venue_rentals WHERE status = 'active'";
    $params = [];
    $types = '';

    if (!empty($searchQuery)) {
        $sql .= " AND (name LIKE ? OR location LIKE ? OR type LIKE ?)";
        $likeQuery = "%$searchQuery%";
        $params = [$likeQuery, $likeQuery, $likeQuery];
        $types .= "sss";
    }

    if (!empty($filterType)) {
        $sql .= " AND type = ?";
        $params[] = $filterType;
        $types .= "s";
    }

    $sql .= " ORDER BY created_at DESC;";
    $stmt = $conn->prepare($sql);

    if (!empty($params)) $stmt->bind_param($types, ...$params);

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) $venues[] = $row;
    $stmt->close();
} catch (Exception $e) {
    $error = "Error fetching venues: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venue Rental Directory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="text-black">
    <?php include("./components/topnav.php"); ?>

    <div style="font-family: Poppins;">
        <header class="py-12 px-6 bg-gradient-to-r from-purple-700 to-pink-600 text-center">
            <h1 class="text-4xl font-bold text-white">🏛️ Venue Rental Directory</h1>
            <p class="text-lg text-gray-200 mt-2">Find the perfect venue for your events and gatherings</p>
        </header>

        <div class="container mx-auto px-4 py-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
                <div class="relative">
                    <input type="text" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search by name, location, or type" class="w-full px-4 py-3 pl-12 rounded-lg border bg-gray-200 text-black focus:ring-2 focus:ring-purple-100 transition">
                    <button type="submit" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div>
                    <select name="venueTypeFilter" class="w-full px-4 py-3 rounded-lg border bg-gray-200 text-black focus:ring-2 focus:ring-purple-100 transition">
                        <option value="">All Venue Types</option>
                        <?php
                        $venueTypes = ["Conference Hall", "Banquet Hall", "Outdoor Space", "Meeting Room", "Auditorium"];
                        foreach ($venueTypes as $type) {
                            $selected = ($filterType === $type) ? 'selected' : '';
                            echo "<option value=\"$type\" $selected>$type</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="bg-purple-600 text-white rounded-lg py-3 hover:bg-purple-700 transition duration-300">Apply Filters</button>
            </form>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <?php if (!empty($error)): ?>
                    <p class='text-red-500 text-center col-span-4'><?= $error ?></p>
                <?php elseif (empty($venues)): ?>
                    <p class='text-gray-400 text-center col-span-4'>No venues found<?= !empty($searchQuery) ? " matching '" . htmlspecialchars($searchQuery) . "'" : '' ?><?= !empty($filterType) ? " for type '" . htmlspecialchars($filterType) . "'" : '' ?>.</p>
                <?php else: ?>
                    <?php foreach ($venues as $row): ?>
                        <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
                            <img src="<?= !empty($row["image_url"]) ? $row["image_url"] : 'https://images.unsplash.com/photo-1560185008-5d0b9201f327' ?>" alt="Venue Image" class="w-full h-48 object-cover">
                            <div class="p-6">
                                <h3 class="text-xl text-white font-semibold mb-2"><?= htmlspecialchars($row["name"]) ?></h3>
                                <p class="text-gray-400 text-sm mb-2"><strong>Type:</strong> <?= htmlspecialchars($row["type"]) ?></p>
                                <p class="text-gray-400 text-sm mb-2"><i class="fas fa-map-marker-alt mr-2"></i><?= htmlspecialchars($row["location"]) ?></p>
                                <p class="text-gray-400 text-sm mb-2"><i class="fas fa-users mr-2"></i>Capacity: <?= htmlspecialchars($row["capacity"]) ?></p>
                                <p class="text-gray-400 text-sm mb-2"><i class="fas fa-dollar-sign mr-2"></i>Price/Hour: $<?= htmlspecialchars($row["price_per_hour"]) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <button id="addVenueBtn" class="fixed bottom-10 right-10 bg-gradient-to-r from-purple-500 to-pink-600 text-white w-16 h-16 rounded-full shadow-lg hover:bg-gradient-to-r hover:from-purple-600 hover:to-pink-700 transition flex items-center justify-center">
            <i class="fas fa-plus text-3xl"></i>
        </button>

        <div id="addVenueModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-md flex items-center justify-center">
            <div class="bg-gray-800 rounded-lg p-8 w-full max-w-4xl mx-4">
                <h2 class="text-2xl font-semibold text-white mb-6 flex items-center">
                    <i class="fas fa-building mr-2 text-purple-500"></i>Add New Venue
                </h2>
                <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <input type="text" name="venueName" required placeholder="Venue Name" class="w-full px-4 py-3 rounded-lg border bg-gray-700 text-white">
                    <select name="venueType" required class="w-full px-4 py-3 rounded-lg border bg-gray-700 text-white">
                        <option value="Conference Hall">Conference Hall</option>
                        <option value="Banquet Hall">Banquet Hall</option>
                        <option value="Outdoor Space">Outdoor Space</option>
                        <option value="Meeting Room">Meeting Room</option>
                        <option value="Auditorium">Auditorium</option>
                    </select>
                    <input type="text" name="location" required placeholder="Location" class="w-full px-4 py-3 rounded-lg border bg-gray-700 text-white">
                    <input type="number" name="capacity" required placeholder="Capacity" class="w-full px-4 py-3 rounded-lg border bg-gray-700 text-white">
                    <input type="tel" name="contactNumber" required placeholder="Contact Number" class="w-full px-4 py-3 rounded-lg border bg-gray-700 text-white">
                    <input type="email" name="email" required placeholder="Email" class="w-full px-4 py-3 rounded-lg border bg-gray-700 text-white">
                    <input type="number" step="0.01" name="pricePerHour" required placeholder="Price per Hour ($)" class="w-full px-4 py-3 rounded-lg border bg-gray-700 text-white">
                    <textarea name="description" placeholder="Description" class="w-full px-4 py-3 rounded-lg border bg-gray-700 text-white h-28"></textarea>
                    <input type="file" name="venueImage" accept="image/*" class="w-full px-4 py-3 rounded-lg border bg-gray-700 text-white">
                    <div class="md:col-span-3 flex justify-end space-x-4 mt-8">
                        <button type="button" onclick="closeModal()" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700">Cancel</button>
                        <button type="submit" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Add Venue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("addVenueBtn").onclick = () => document.getElementById("addVenueModal").classList.toggle("hidden");
        function closeModal() { document.getElementById("addVenueModal").classList.add("hidden"); }
        document.addEventListener("DOMContentLoaded", () => {
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
