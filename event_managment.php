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
        $uploadDir = './admin/uploads/emcompanies/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileTmpPath = $_FILES['companyImage']['tmp_name'];
        $fileName = basename($_FILES['companyImage']['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = uniqid('company_', true) . "." . $fileExtension;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $imagePath = $destPath;
            }
        }
    }

  
    $stmt = $conn->prepare("INSERT INTO companies (name, specialization, location, contact_number, email, description, status, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $companyName, $specialization, $location, $contactNumber, $email, $description, $status, $imagePath);

    if ($stmt->execute()) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Company added successfully.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'There was an error adding the company.'];
    }
    $stmt->close();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$companies = [];
$error = '';

if (!empty($searchQuery)) {
    try {
        $sql = "SELECT * FROM companies
WHERE status = 'active'
  AND (name LIKE ? 
       OR location LIKE ? 
       OR specialization LIKE ?)
ORDER BY created_at DESC;
";
        
        $stmt = $conn->prepare($sql);
        $likeQuery = "%$searchQuery%";
        $stmt->bind_param("sss", $likeQuery, $likeQuery, $likeQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $companies[] = $row;
            }
        }
        $stmt->close();
    } catch (Exception $e) {
        $error = "Error searching companies: " . $e->getMessage();
    }
} else {
    
    try {
        $result = $conn->query("SELECT * FROM companies WHERE status = 'active' ORDER BY created_at DESC");
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $companies[] = $row;
            }
        }
    } catch (Exception $e) {
        $error = "Error fetching companies: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management Directory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="text-black">
    <?php include("./components/topnav.php"); ?>

    <header class="py-12 px-6 bg-gradient-to-r from-purple-700 to-blue-600">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-bold text-white">🎉Event Management Companies</h1>
            <p class="text-lg text-gray-200 mt-2">Discover top event management companies for your next occasion</p>
        </div>
    </header>

    <div style="font-family: poppins;" class="container mx-auto px-4 py-8">
        <!-- Search Bar -->
        <form method="GET" class="relative mb-10">
            <input type="text" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search companies by name, location, or specialty" class="w-full px-4 py-3 pl-12 rounded-lg border border-gray-100 bg-gray-200 text-white focus:ring-2 focus:ring-blue-100 focus:outline-none transition duration-200">
            <button type="submit" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <!-- Display Companies -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php if (!empty($error)): ?>
                <p class='text-red-500 text-center col-span-4'><?= $error ?></p>
            <?php elseif (empty($companies)): ?>
                <p class='text-gray-400 text-center col-span-4'>No companies found<?= !empty($searchQuery) ? " matching '".htmlspecialchars($searchQuery)."'" : '' ?>.</p>
            <?php else: ?>
                <?php foreach ($companies as $row): ?>
                    <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
                        <img src="<?= !empty($row["image_url"]) ? $row["image_url"] : 'https://images.unsplash.com/photo-1505236858219-8359eb29e329' ?>" 
                             alt="Company Logo" 
                             class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl text-white font-semibold mb-2"><?= htmlspecialchars($row["name"]) ?></h3>
                            <p class="text-gray-400 text-sm mb-2"><?= htmlspecialchars($row["specialization"]) ?></p>
                            <p class="text-gray-400 text-sm mb-2">
                                <i class="fas fa-map-marker-alt mr-2"></i><?= htmlspecialchars($row["location"]) ?>
                            </p>
                            <p class="text-gray-400 text-sm mb-2">
                                <i class="fas fa-phone mr-2"></i><?= htmlspecialchars($row["contact_number"]) ?>
                            </p>
                           
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add Company Button -->
    <button id="addCompanyBtn" class="fixed bottom-10 right-10 bg-gradient-to-r from-blue-500 to-indigo-600 text-white w-16 h-16 rounded-full shadow-lg hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-700 transition duration-300 flex items-center justify-center">
        <i class="fas fa-plus text-3xl"></i>
    </button>

    <!-- Add Company Modal -->
    <div id="addCompanyModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-md flex items-center justify-center transition-all duration-300 ease-in-out">
        <div class="bg-gray-800 rounded-lg p-8 w-full max-w-4xl mx-4">
            <h2 class="text-2xl font-semibold text-white mb-6 flex items-center">
                <i class="fas fa-building mr-2 text-blue-500"></i>Add New Company
            </h2>
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Company Name</label>
                        <input type="text" name="companyName" required class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Specialization</label>
                        <select name="specialization" required class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-blue-500">
                            <option value="Concert">Concert</option>
                            <option value="Conference">Conference</option>
                            <option value="Workshop">Workshop</option>
                            <option value="Festival">Festival</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Location</label>
                        <input type="text" name="location" required class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Contact Number</label>
                        <input type="tel" name="contactNumber" required class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Description</label>
                        <textarea name="description" class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-blue-500 h-28"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-400 mb-2">Company Image</label>
                        <input type="file" name="companyImage" accept="image/*" class="w-full px-4 py-3 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-8">
                    <button type="button" onclick="closeModal()" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">Add Company</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle modal visibility
        document.getElementById("addCompanyBtn").onclick = function() {
            document.getElementById("addCompanyModal").classList.toggle("hidden");
        };
        function closeModal() {
            document.getElementById("addCompanyModal").classList.add("hidden");
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

<?php
$conn->close();
?>