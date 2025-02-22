<?php
session_start();
include("./config/DbContext.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $companyName = htmlspecialchars($_POST['companyName']);
    $specialization = htmlspecialchars($_POST['specialization']);
    $location = htmlspecialchars($_POST['location']);
    $contactNumber = htmlspecialchars($_POST['contactNumber']);
    $email = htmlspecialchars($_POST['email']);
    $description = htmlspecialchars($_POST['description']);
    $status = 'pending';
    $imagePath = '';

    if (isset($_FILES['companyImage']) && $_FILES['companyImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = './admin/uploads/securitycompanies/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileTmpPath = $_FILES['companyImage']['tmp_name'];
        $fileName = basename($_FILES['companyImage']['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = uniqid('security_company_', true) . "." . $fileExtension;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $imagePath = $destPath;
            }
        }
    }

    $stmt = $conn->prepare("INSERT INTO security_companies (name, specialization, location, contact_number, email, description, status, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $companyName, $specialization, $location, $contactNumber, $email, $description, $status, $imagePath);

    if ($stmt->execute()) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Security company added successfully.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'There was an error adding the security company.'];
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$companies = [];
$error = '';

try {
    $sql = "SELECT * FROM security_companies WHERE status = 'active' AND (name LIKE ? OR location LIKE ? OR specialization LIKE ?) ORDER BY created_at DESC;";
    $stmt = $conn->prepare($sql);
    $likeQuery = "%$searchQuery%";
    $stmt->bind_param("sss", $likeQuery, $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $companies[] = $row;
    }
    $stmt->close();
} catch (Exception $e) {
    $error = "Error fetching security companies: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Companies Directory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="text-black">
    <?php include("./components/topnav.php"); ?>

    <div  style="font-family: Poppins;">

  
    <header class="py-12 px-6 bg-gradient-to-r from-green-700 to-blue-600">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-bold text-white">🔒 Security Companies Directory</h1>
            <p class="text-lg text-gray-200 mt-2">Find top security service providers for your protection needs</p>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <form method="GET" class="relative mb-10">
            <input type="text" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search security companies by name, location, or specialty" class="w-full px-4 py-3 pl-12 rounded-lg border border-gray-100 bg-gray-200 text-black focus:ring-2 focus:ring-green-100 focus:outline-none transition duration-200">
            <button type="submit" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php if (!empty($error)): ?>
                <p class='text-red-500 text-center col-span-4'><?= $error ?></p>
            <?php elseif (empty($companies)): ?>
                <p class='text-gray-400 text-center col-span-4'>No security companies found<?= !empty($searchQuery) ? " matching '" . htmlspecialchars($searchQuery) . "'" : '' ?>.</p>
            <?php else: ?>
                <?php foreach ($companies as $row): ?>
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <img src="<?= !empty($row["image_url"]) ? $row["image_url"] : 'https://images.unsplash.com/photo-1556740749-887f6717d7e4' ?>" alt="Company Logo" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl text-white font-semibold mb-2"><?= htmlspecialchars($row["name"]) ?></h3>
                            <p class="text-gray-400 text-sm mb-2"><?= htmlspecialchars($row["specialization"]) ?></p>
                            <p class="text-gray-400 text-sm mb-2"><i class="fas fa-map-marker-alt mr-2"></i><?= htmlspecialchars($row["location"]) ?></p>
                            <p class="text-gray-400 text-sm mb-2"><i class="fas fa-phone mr-2"></i><?= htmlspecialchars($row["contact_number"]) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <button id="addCompanyBtn" class="fixed bottom-10 right-10 bg-gradient-to-r from-green-500 to-blue-600 text-white w-16 h-16 rounded-full shadow-lg hover:bg-gradient-to-r hover:from-green-600 hover:to-blue-700 transition duration-300 flex items-center justify-center">
        <i class="fas fa-plus text-3xl"></i>
    </button>

    <div id="addCompanyModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-md flex items-center justify-center transition-all duration-300 ease-in-out">
        <div class="bg-gray-800 rounded-lg p-8 w-full max-w-4xl mx-4">
            <h2 class="text-2xl font-semibold text-white mb-6 flex items-center">
                <i class="fas fa-shield-alt mr-2 text-green-500"></i>Add New Security Company
            </h2>
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Company Name</label>
                        <input type="text" name="companyName" required class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Specialization</label>
                        <select name="specialization" required class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-green-500">
                            <option value="Corporate Security">Corporate Security</option>
                            <option value="Event Security">Event Security</option>
                            <option value="Residential Security">Residential Security</option>
                            <option value="Cybersecurity">Cybersecurity</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Location</label>
                        <input type="text" name="location" required class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Contact Number</label>
                        <input type="tel" name="contactNumber" required class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Description</label>
                        <textarea name="description" class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-green-500 h-28"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-400 mb-2">Company Image</label>
                        <input type="file" name="companyImage" accept="image/*" class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-8">
                    <button type="button" onclick="closeModal()" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-300">Add Company</button>
                </div>
            </form>
        </div>
    </div>
    </div>
    <script>
        document.getElementById("addCompanyBtn").onclick = function() {
            document.getElementById("addCompanyModal").classList.toggle("hidden");
        };
        function closeModal() {
            document.getElementById("addCompanyModal").classList.add("hidden");
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

<?php
$conn->close();
?> 