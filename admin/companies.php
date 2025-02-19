<?php
session_start();
include("./config/DbContext.php");

if (isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $companyId = intval($_POST['company_id']);
    $newStatus = $_POST['status'] === 'active' ? 'active' : 'inactive';

    $stmt = $conn->prepare("UPDATE companies SET status = ? WHERE id = ?");
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
    $stmt = $conn->prepare("DELETE FROM companies WHERE id = ?");
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

$companies = [];
try {
    $result = $conn->query("SELECT * FROM companies ORDER BY created_at DESC");
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
    <title>Manage Companies</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 text-black">
    <?php include("./components/topnav.php"); ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Manage Event Management Companies</h1>
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Name</th>
                        <th class="py-3 px-6 text-left">Specialization</th>
                        <th class="py-3 px-6 text-left">Location</th>
                        <th class="py-3 px-6 text-left">Status</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm font-light">
                    <?php foreach ($companies as $company): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-6 text-left"> <?= htmlspecialchars($company['name']); ?> </td>
                            <td class="py-3 px-6 text-left"> <?= htmlspecialchars($company['specialization']); ?> </td>
                            <td class="py-3 px-6 text-left"> <?= htmlspecialchars($company['location']); ?> </td>
                            <td class="py-3 px-6 text-left">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $company['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?= ucfirst($company['status']); ?>
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center space-x-4">
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

    <script>
        // Update company status
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

        // Delete company
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