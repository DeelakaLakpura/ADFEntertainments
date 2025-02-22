<?php
session_start();
include("./config/DbContext.php");

if (isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $companyId = intval($_POST['company_id']);
    $newStatus = $_POST['status'] === 'active' ? 'active' : 'inactive';

    $stmt = $conn->prepare("UPDATE security_companies SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $companyId);
    if ($stmt->execute()) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Company status updated successfully.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to update company status.'];
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $companyId = intval($_POST['company_id']);
    $stmt = $conn->prepare("DELETE FROM security_companies WHERE id = ?");
    $stmt->bind_param("i", $companyId);
    if ($stmt->execute()) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Company deleted successfully.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to delete company.'];
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST['action']) && $_POST['action'] === 'update_company') {
    $companyId = intval($_POST['company_id']);
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];
    $location = $_POST['location'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE security_companies SET name = ?, specialization = ?, location = ?, contact_number = ?, email = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $name, $specialization, $location, $contact_number, $email, $description, $companyId);
    if ($stmt->execute()) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Company details updated successfully.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to update company details.'];
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$companies = [];
try {
    $result = $conn->query("SELECT * FROM security_companies ORDER BY created_at DESC");
    while ($row = $result->fetch_assoc()) {
        $companies[] = $row;
    }
} catch (Exception $e) {
    $_SESSION['alert'] = ['type' => 'error', 'message' => 'Error fetching companies.'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Security Companies</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 text-black" style="font-family: Poppins;">
    <?php include("./components/topnav.php"); ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Manage Security Companies</h1>
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Image</th>
                        <th class="py-3 px-6 text-left">Name</th>
                        <th class="py-3 px-6 text-left">Specialization</th>
                        <th class="py-3 px-6 text-left">Location</th>
                        <th class="py-3 px-6 text-left">Contact</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-left">Status</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm font-light">
                    <?php foreach ($companies as $company): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-6 text-left">
                                <?php if (!empty($company['image_url'])): ?>
                                    <img src=".<?= htmlspecialchars($company['image_url']); ?>" alt="Company Logo" class="w-12 h-12 rounded-full object-cover">
                                <?php else: ?>
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-shield-alt text-gray-500"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-6"> <?= htmlspecialchars($company['name']); ?> </td>
                            <td class="py-3 px-6"> <?= htmlspecialchars($company['specialization']); ?> </td>
                            <td class="py-3 px-6"> <?= htmlspecialchars($company['location']); ?> </td>
                            <td class="py-3 px-6"> <?= htmlspecialchars($company['contact_number']); ?> </td>
                            <td class="py-3 px-6"> <?= htmlspecialchars($company['email']); ?> </td>
                            <td class="py-3 px-6">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $company['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?= ucfirst($company['status']); ?>
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center space-x-4">
                                    <button onclick="viewCompany(<?= $company['id']; ?>)" class="w-8 h-8 bg-green-500 text-white rounded-full hover:bg-green-600 flex items-center justify-center">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="updateStatus(<?= $company['id']; ?>, '<?= $company['status'] === 'active' ? 'inactive' : 'active'; ?>')" class="w-8 h-8 bg-blue-500 text-white rounded-full hover:bg-blue-600 flex items-center justify-center">
                                        <i class="fas <?= $company['status'] === 'active' ? 'fa-toggle-off' : 'fa-toggle-on'; ?>"></i>
                                    </button>
                                    <button onclick="deleteCompany(<?= $company['id']; ?>)" class="w-8 h-8 bg-red-500 text-white rounded-full hover:bg-red-600 flex items-center justify-center">
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

    <!-- Modal for viewing and updating company details -->
    <div id="viewModal" class="fixed inset-0 z-50 hidden justify-center items-center bg-gray-800 bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg p-8 w-96">
            <h2 class="text-2xl font-bold mb-4">Update Company Details</h2>
            <form id="updateCompanyForm" method="POST">
                <input type="hidden" name="action" value="update_company">
                <input type="hidden" name="company_id" id="modalCompanyId">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="modalName" name="name" class="mt-1 block w-full border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization</label>
                    <input type="text" id="modalSpecialization" name="specialization" class="mt-1 block w-full border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                    <input type="text" id="modalLocation" name="location" class="mt-1 block w-full border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                    <input type="text" id="modalContactNumber" name="contact_number" class="mt-1 block w-full border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="modalEmail" name="email" class="mt-1 block w-full border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="modalDescription" name="description" class="mt-1 block w-full border-gray-300 rounded-md" rows="3"></textarea>
                </div>
                <div class="flex justify-between">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function viewCompany(companyId) {
            // Fetch company data using AJAX or other methods if needed
            const companyData = <?php echo json_encode($companies); ?>.find(company => company.id === companyId);
            
            // Fill the modal with company data
            document.getElementById('modalCompanyId').value = companyData.id;
            document.getElementById('modalName').value = companyData.name;
            document.getElementById('modalSpecialization').value = companyData.specialization;
            document.getElementById('modalLocation').value = companyData.location;
            document.getElementById('modalContactNumber').value = companyData.contact_number;
            document.getElementById('modalEmail').value = companyData.email;
            document.getElementById('modalDescription').value = companyData.description || '';

            // Show the modal
            document.getElementById('viewModal').classList.remove('hidden');
        }

        function closeModal() {
            // Hide the modal
            document.getElementById('viewModal').classList.add('hidden');
        }

        document.getElementById('updateCompanyForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                Swal.fire({
                    title: 'Success',
                    text: 'Company details updated successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    closeModal();
                    location.reload();
                });
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to update company details.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });

        function updateStatus(companyId, newStatus) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to set this company to ${newStatus}?`,
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
                                      <input type="hidden" name="company_id" value="${companyId}">
                                      <input type="hidden" name="status" value="${newStatus}">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function deleteCompany(companyId) {
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
                                      <input type="hidden" name="company_id" value="${companyId}">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
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
