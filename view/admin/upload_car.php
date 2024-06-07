<?php
// Include the database connection file
include dirname(__DIR__, 2) . '/config.php';

// Start the session
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Define the upload directory relative to the directory of this PHP script
$uploadDirectory = '../view/uploads/';

// Create the upload directory if it doesn't exist
$uploadDirectoryFullPath = $_SERVER['DOCUMENT_ROOT'] . '/wheeldeal/' . $uploadDirectory;
if (!file_exists($uploadDirectoryFullPath)) {
    mkdir($uploadDirectoryFullPath, 0777, true);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Validate and sanitize form inputs
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $brand = filter_var($_POST['brand'], FILTER_SANITIZE_STRING);
    $showroom_price = filter_var($_POST['showroom_price'], FILTER_VALIDATE_FLOAT);
    $onroadprice = filter_var($_POST['onroadprice'], FILTER_VALIDATE_FLOAT);

    // Get other form values
    $max_power = filter_var($_POST['max_power'], FILTER_VALIDATE_FLOAT);
    $displacement = filter_var($_POST['displacement'], FILTER_VALIDATE_FLOAT);
    $fuel_tank = filter_var($_POST['fuel_tank'], FILTER_VALIDATE_FLOAT);
    $engine = filter_var($_POST['engine'], FILTER_SANITIZE_STRING);
    $fuel_type = filter_var($_POST['fuel_type'], FILTER_SANITIZE_STRING);
    $emission_norms = filter_var($_POST['emission_norms'], FILTER_SANITIZE_STRING);
    $max_torque = filter_var($_POST['max_torque'], FILTER_VALIDATE_FLOAT);
    $mileage = filter_var($_POST['mileage'], FILTER_VALIDATE_FLOAT);
    $gradeability = filter_var($_POST['gradeability'], FILTER_VALIDATE_FLOAT);
    $max_speed = filter_var($_POST['max_speed'], FILTER_VALIDATE_FLOAT);
    $engine_cylinders = filter_var($_POST['engine_cylinders'], FILTER_VALIDATE_INT);
    $battery_capacity = filter_var($_POST['battery_capacity'], FILTER_VALIDATE_FLOAT);

    // Check if any field is empty
    $errorMessages = [];

    if (empty($name)) {
        $errorMessages[] = "Name is required.";
    }

    if (empty($brand)) {
        $errorMessages[] = "Brand is required.";
    }

    if ($showroom_price === false) {
        $errorMessages[] = "Showroom Price must be a valid number.";
    }

    if ($onroadprice === false) {
        $errorMessages[] = "On Road Price must be a valid number.";
    }

    if ($max_power === false) {
        $errorMessages[] = "Max Power must be a valid number.";
    }

    if ($displacement === false) {
        $errorMessages[] = "Displacement must be a valid number.";
    }

    if ($fuel_tank === false) {
        $errorMessages[] = "Fuel Tank must be a valid number.";
    }

    if ($max_torque === false) {
        $errorMessages[] = "Max Torque must be a valid number.";
    }

    if ($mileage === false) {
        $errorMessages[] = "Mileage must be a valid number.";
    }

    if ($gradeability === false) {
        $errorMessages[] = "Gradeability must be a valid number.";
    }

    if ($max_speed === false) {
        $errorMessages[] = "Max Speed must be a valid number.";
    }

    if ($engine_cylinders === false) {
        $errorMessages[] = "Engine Cylinders must be a valid number.";
    }

    if ($battery_capacity === false) {
        $errorMessages[] = "Battery Capacity must be a valid number.";
    }

    if (!empty($errorMessages)) {
        echo "<p class='error-message'>Please correct the following errors:</p>";
        echo "<ul class='error-list'>";
        foreach ($errorMessages as $errorMessage) {
            echo "<li>$errorMessage</li>";
        }
        echo "</ul>";
        exit;
    }

    // Handle file uploads
    $uploadedImages = [];
    $imageOrder = isset($_POST['image_order']) ? explode(',', $_POST['image_order']) : [];
    if (isset($_FILES['images']['error']) && is_array($_FILES['images']['error'])) {
        // Define allowed MIME types
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        foreach ($imageOrder as $index) {
            $index = intval($index);
            if (isset($_FILES['images']['error'][$index]) && $_FILES['images']['error'][$index] === UPLOAD_ERR_OK) {
                $fileMimeType = $_FILES['images']['type'][$index];
                $targetFileName = basename($_FILES['images']['name'][$index]);
                $fileExtension = pathinfo($targetFileName, PATHINFO_EXTENSION);
                $newFileName = uniqid() . '.' . $fileExtension; // Generate a unique filename
                $targetFilePath = $uploadDirectoryFullPath . $newFileName;

                if (in_array($fileMimeType, $allowedMimeTypes)) {
                    // Move uploaded file and handle errors
                    $tempFilePath = $_FILES['images']['tmp_name'][$index];
                    if (move_uploaded_file($tempFilePath, $targetFilePath)) {
                        $uploadedImages[] = $uploadDirectory . $newFileName; // Store relative path
                    } else {
                        echo "<p class='error-message'>Failed to move uploaded file: $targetFileName</p>";
                        error_log("Failed to move uploaded file: $targetFileName");
                    }
                } else {
                    echo "<p class='error-message'>Invalid file type for file: $targetFileName. Only JPG, PNG, GIF, and WEBP files are allowed.</p>";
                }
            } elseif (isset($_FILES['images']['error'][$index]) && $_FILES['images']['error'][$index] !== UPLOAD_ERR_NO_FILE) {
                // Handle other upload errors
                echo "<p class='error-message'>File upload error: {$_FILES['images']['error'][$index]}</p>";
                error_log("File upload error for file index $index: {$_FILES['images']['error'][$index]}");
            }
        }
    } else {
        echo "<p class='error-message'>No files were uploaded or the upload exceeded the maximum file size.</p>";
    }

    // Check if at least one image was uploaded
    if (empty($uploadedImages)) {
        echo "<p class='error-message'>Please upload at least one valid image.</p>";
        exit;
    }

    // Limit the number of images to 10
    $limitedImages = array_slice($uploadedImages, 0, 10);
    $imagePaths = implode(',', $limitedImages); // Concatenate file paths with commas

    // Insert data into the database with $uploadDirectory using prepared statements
    $sql = "INSERT INTO cars (name, brand, showroom_price, onroadprice, max_power, displacement, fuel_tank, engine, fuel_type, emission_norms, max_torque, mileage, gradeability, max_speed, engine_cylinders, battery_capacity, images) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdddddssssddidds", $name, $brand, $showroom_price, $onroadprice, $max_power, $displacement, $fuel_tank, $engine, $fuel_type, $emission_norms, $max_torque, $mileage, $gradeability, $max_speed, $engine_cylinders, $battery_capacity, $imagePaths);

    // Check if the query was successful
    if ($stmt->execute()) {
        echo "<p class='success-message'>Car uploaded successfully!</p>";
    } else {
        echo "<p class='error-message'>Error uploading car: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Upload</title>
    <link rel="stylesheet" href="../../css/upload_car_style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Car Upload</h1>

        <!-- Feedback messages -->
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!empty($errorMessages)) {
                echo "<ul class='error-list'>";
                foreach ($errorMessages as $message) {
                    echo "<li>$message</li>";
                }
                echo "</ul>";
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" name="name" required>

            <label for="brand">Brand:</label>
            <input type="text" name="brand" required>

            <label for="showroom_price">Showroom Price:</label>
            <input type="text" name="showroom_price" required>

            <label for="onroadprice">On Road Price:</label>
            <input type="text" name="onroadprice" required>

            <label for="max_power">Max Power (hp):</label>
            <input type="number" name="max_power" required>

            <label for="displacement">Displacement (cc):</label>
            <input type="number" name="displacement" required>

            <label for="fuel_tank">Fuel Tank Capacity (Litres):</label>
            <input type="number" name="fuel_tank" required>

            <label for="engine">Engine:</label>
            <input type="text" name="engine" required>

            <label for="fuel_type">Fuel Type:</label>
            <input type="text" name="fuel_type" required>

            <label for="emission_norms">Emission Norms:</label>
            <input type="text" name="emission_norms" required>

            <label for="max_torque">Max Torque (Nm):</label>
            <input type="number" name="max_torque" required>

            <label for="mileage">Mileage (kmpl):</label>
            <input type="text" name="mileage" required>

            <label for="gradeability">Gradeability (%):</label>
            <input type="number" name="gradeability" required>

            <label for="max_speed">Max Speed (km/h):</label>
            <input type="number" name="max_speed" required>

            <label for="engine_cylinders">Engine Cylinders:</label>
            <input type="number" name="engine_cylinders" required>

            <label for="battery_capacity">Battery Capacity (Ah):</label>
            <input type="number" name="battery_capacity" required>

            <label for="images">Images (up to 10):</label>
            <input type="file" id="image-input" name="images[]" multiple accept="image/*" onchange="handleFileSelect(event)">
            <ul id="image-list" class="sortable-list"></ul>
            <input type="hidden" id="image-order" name="image_order">

            <input type="submit" name="submit" value="Upload Car">
        </form>

        <br>
        <a href="admin.php" class="button">Back to Admin Dashboard</a>
    </div>

    <script>
        function handleFileSelect(event) {
            const files = event.target.files;
            const imageList = document.getElementById('image-list');
            imageList.innerHTML = '';

            Array.from(files).forEach((file, index) => {
                const li = document.createElement('li');
                li.textContent = file.name;
                li.dataset.index = index;
                li.id = 'image_' + index; // Add id to each li for Sortable to use
                imageList.appendChild(li);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const imageList = document.getElementById('image-list');
            const sortable = Sortable.create(imageList, {
                onSort: function (evt) {
                    const order = sortable.toArray().map(id => id.replace('image_', ''));
                    document.getElementById('image-order').value = order.join(',');
                }
            });
        });
    </script>
</body>
</html>
