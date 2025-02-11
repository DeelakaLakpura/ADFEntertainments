<?php

include('./config/DbContext.php'); 

$target_dir = "./admin/uploads/bands";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $band_name = mysqli_real_escape_string($conn, $_POST['BandName']);
    $email = mysqli_real_escape_string($conn, $_POST['Email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['PhoneNumber']);
    $price = mysqli_real_escape_string($conn, $_POST['Price']);
    $description = mysqli_real_escape_string($conn, $_POST['productDescription']);

    if (!empty($_FILES['productImage']['name'])) {
        $image_name = basename($_FILES['productImage']['name']);
        $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($image_extension, $allowed_extensions)) {
            $new_image_name = uniqid('band_', true) . "." . $image_extension;
            $target_file = $target_dir . $new_image_name;

            // Move the file to the uploads directory
            if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $target_file)) {
                // Store the file path in the database
                $image_path = $target_file;
            } else {
                echo "<script>Swal.fire('Error', 'Failed to upload image!', 'error');</script>";
                exit;
            }
        } else {
            echo "<script>Swal.fire('Error', 'Invalid image format. Allowed: JPG, PNG, GIF', 'error');</script>";
            exit;
        }
    } else {
        $image_path = ""; // If no image uploaded, keep empty
    }

    // Insert data into the database
    $sql = "INSERT INTO tb_bands (band_name, phonenumber, email, price, image_path, description, status) 
            VALUES ('$band_name', '$phone_number', '$email', '$price', '$image_path', '$description', 'inactive')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>Swal.fire('Success', 'Band added successfully!', 'success');</script>";
    } else {
        echo "<script>Swal.fire('Error', 'Something went wrong!', 'error');</script>";
    }
}

// Fetch existing bands from the database
$query = "SELECT * FROM tb_bands WHERE status = 'active'";
$result = mysqli_query($conn, $query);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Showcase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
        }

        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .button-hover:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .popup-enter {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: scale(0.95);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body class="bg-gray-50" >
    <?php include("./components/topnav.php"); ?>

    <div class="relative min-h-screen bg-gray-50 p-4" style="font-family: Poppins;">
        <!-- Add Product Button -->
        <button class="fixed bottom-4 right-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-110" onclick="togglePopup()">
            Add Your Band &nbsp;<i class="fas fa-plus"></i>
        </button>

        <div class="ml-2 mt-2">
            <h4 class="mb-4 font-extrabold text-gray-900 dark:text-black text-xl md:text-3xl">
                <span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">All</span> Bands.
            </h4>
        </div>

        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-4">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="relative bg-white shadow-lg rounded-xl p-6 overflow-hidden transition-all duration-500 transform hover:scale-105 hover:shadow-2xl group">
            <!-- Floating animation effect -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-blue-700 opacity-0 group-hover:opacity-20 transition-all duration-500 rounded-xl"></div>

            <!-- Image with animation -->
            <div class="relative">
                <img src="./<?php echo $row['image_path']; ?>" alt="Product"
                    class="w-full h-48 object-cover rounded-xl mb-4 transition-transform duration-500 transform group-hover:scale-110">
            </div>

            <!-- Product Name -->
            <h3 class="text-xl font-semibold text-gray-800 transition-all duration-300 group-hover:text-blue-500">
                <?php echo $row['band_name']; ?>
            </h3>

            <!-- Price Section -->
            <p class="text-gray-600 mt-2 flex items-center space-x-2">
              
                <span>Rs. &nbsp;<?php echo $row['price']; ?></span>
            </p>

         
            <button class="fixed bottom-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition-all duration-300 transform hover:bg-blue-600 hover:scale-105 shadow-lg" style="z-index: 9999;">
    <i class="fas fa-eye"></i>
    <span>View Band</span>
</button>

        </div>
    <?php } ?>
</div>

        <!-- Popup Box -->
        <div id="popup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden popup-enter">
            <div class="bg-white p-6 sm:p-8 rounded-lg shadow-lg w-full max-w-md sm:max-w-2xl">
                <h2 class="text-2xl font-semibold mb-4 text-center">Add Your Band</h2>
                <form method="POST" enctype="multipart/form-data">
                    <!-- Grid Layout for Form Items -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="BandName" class="block text-gray-700"><i class="fas fa-tag mr-2"></i>Band Name</label>
                            <input type="text" name="BandName" id="BandName" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter band name" required>
                        </div>

                        <div class="mb-4">
                            <label for="Email" class="block text-gray-700"><i class="fas fa-envelope mr-2"></i>Email</label>
                            <input type="email" name="Email" id="Email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter email" required>
                        </div>

                        <div class="mb-4">
                            <label for="PhoneNumber" class="block text-gray-700"><i class="fas fa-phone mr-2"></i>Phone Number</label>
                            <input type="text" name="PhoneNumber" id="PhoneNumber" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter phone number" required>
                        </div>

                        <div class="mb-4">
                            <label for="Price" class="block text-gray-700"><i class="fas fa-dollar-sign mr-2"></i>Price</label>
                            <input type="number" name="Price" id="Price" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter price" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="productImage" class="block text-gray-700"><i class="fas fa-image mr-2"></i>Image Upload</label>
                        <input type="file" name="productImage" id="productImage" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div class="mb-4">
                        <label for="productDescription" class="block text-gray-700"><i class="fas fa-align-left mr-2"></i>Description</label>
                        <textarea name="productDescription" id="productDescription" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter description" required></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-md hover:bg-blue-600 transition duration-300">
                        <i class="fas fa-check mr-2"></i> Add
                    </button>
                    <button type="button" onclick="togglePopup()" class="mt-2 w-full text-gray-500 p-3 rounded-md hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Popup Box Script -->
    <script>
        function togglePopup() {
            const popup = document.getElementById('popup');
            popup.classList.toggle('hidden');
        }
    </script>
    <?php include('./components/footer.php'); ?>
</body>

</html>
