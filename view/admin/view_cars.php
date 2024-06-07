<?php
// Include the database connection file
include dirname(__DIR__, 2) . '/config.php';

// Start the session
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Delete car logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    // Sanitize the input to prevent SQL injection
    $carId = mysqli_real_escape_string($conn, $_POST['delete']);

    // Fetch the car's images to delete them from the server
    $sqlFetchImages = "SELECT images FROM cars WHERE id = '$carId'";
    $resultImages = $conn->query($sqlFetchImages);
    if ($resultImages->num_rows > 0) {
        $rowImages = $resultImages->fetch_assoc();
        $imageUrls = explode(",", $rowImages['images']);
        foreach ($imageUrls as $imageUrl) {
            $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/wheeldeal/view/uploads/' . trim($imageUrl);
            if (file_exists($imagePath) && is_file($imagePath)) {
                unlink($imagePath); // Delete the image file
            }
        }
    }

    // Delete the car from the database
    $sqlDelete = "DELETE FROM cars WHERE id = '$carId'";
    if ($conn->query($sqlDelete) === TRUE) {
        echo "<script>alert('Car deleted successfully.');</script>";
        header("Location: view_cars.php"); // Redirect to refresh the page
        exit();
    } else {
        echo "<script>alert('Error deleting car: " . $conn->error . "');</script>";
    }
}

// Fetch all cars
$sqlCars = "SELECT * FROM cars";
$resultCars = $conn->query($sqlCars);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cars</title>
    <link rel="stylesheet" href="../../css/view_cars_style.css">
</head>
<body>
    <h1>View Cars</h1>
    <br>
    <br>
    <?php
    if ($resultCars->num_rows > 0) {
        $count = 0;
        while ($row = $resultCars->fetch_assoc()) {
            echo "<div class='car-box'>";
            echo "<h3>{$row['name']} ({$row['brand']})</h3>";
            echo "<div class='car-images'>";

// Check if images exist and are not null
if (!empty($row['images'])) {
    $imageUrls = explode(",", $row['images']);
    foreach ($imageUrls as $imageUrl) {
        $imageUrl = trim($imageUrl); // Trim any extra spaces
        if (!empty($imageUrl)) {
            // Construct the correct relative path to the image
            $imagePath = '..' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . basename($imageUrl);

            // Display the image
            if (file_exists($imagePath)) {
                echo "<img src='{$imagePath}' alt='Car Image'>";
            } else {
                echo "<p>Image not found: {$imageUrl}</p>";
                echo "<p>Expected path: {$imagePath}</p>";
            }
        }
    }
} else {
    echo "<p>No images available</p>";
}





            echo "</div>";
            echo "<p>Showroom Price: ₹{$row['showroom_price']}</p>";
            echo "<p>On Road Price: ₹{$row['onroadprice']}</p>";

            // Additional car details can be displayed here if needed

            // Delete form
            echo "<form method='post' action='view_cars.php' style='display:inline;'>";
            echo "<input type='hidden' name='delete' value='{$row['id']}'>";
            echo "<input type='submit' value='Delete Car'>";
            echo "</form>";

            // Edit link (you can replace this with a form for editing)
            echo "<a href='edit_car.php?id={$row['id']}'>Edit Car</a>";

            echo "</div>";

            $count++;

            // Start a new row after every 4th car
            if ($count % 4 == 0) {
                echo "<div class='clearfix'></div>";
            }
        }
    } else {
        echo "<p>No cars available.</p>";
    }
    ?>
    <div class="clearfix"></div> <!-- Ensure the last row is cleared -->

    <br>
    <div class="center-button">
        <form method="get" action="admin.php" style="display: inline;">
            <button type="submit" class="dashboard-button">Back to Admin Dashboard</button>
        </form>
    </div>
</body>
</html>
