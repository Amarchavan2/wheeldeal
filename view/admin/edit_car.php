<?php
include dirname(__DIR__, 2) . '/config.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Fetch car details for editing
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $carId = $_GET['id'];
    $sqlFetchCar = "SELECT * FROM cars WHERE id = '$carId'";
    $resultCar = $conn->query($sqlFetchCar);

    if ($resultCar->num_rows > 0) {
        $row = $resultCar->fetch_assoc();

        // Assign fetched values to variables for easier access
        $name = $row['name'];
        $brand = $row['brand'];
        $showroom_price = $row['showroom_price'];
        $onroadprice = $row['onroadprice'];
        $max_power = $row['max_power'];
        $displacement = $row['displacement'];
        $fuel_tank = $row['fuel_tank'];
        $engine = $row['engine'];
        $fuel_type = $row['fuel_type'];
        $emission_norms = $row['emission_norms'];
        $max_torque = $row['max_torque'];
        $mileage = $row['mileage'];
        $gradeability = $row['gradeability'];
        $max_speed = $row['max_speed'];
        $engine_cylinders = $row['engine_cylinders'];
        $battery_capacity = $row['battery_capacity'];
        $images = $row['images'];
    } else {
        echo "Car not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car</title>
    <link rel="stylesheet" href="../../css/upload_car_style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Car</h1>

        <!-- Feedback messages -->
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
            if ($conn->query($sqlUpdate) === TRUE) {
                echo "<p class='success-message'>Car details updated successfully!</p>";
            } else {
                echo "<p class='error-message'>Error updating car details: " . $conn->error . "</p>";
            }
        }
        ?>
        <form method="post" action="" enctype="multipart/form-data">
    <input type="hidden" name="car_id" value="<?php echo $carId; ?>">

    <label for="name">Name:</label>
    <input type="text" name="name" value="<?php echo $name; ?>" required>

    <label for="brand">Brand:</label>
    <input type="text" name="brand" value="<?php echo $brand; ?>" required>

    <label for="showroom_price">Showroom Price:</label>
    <input type="text" name="showroom_price" value="<?php echo $showroom_price; ?>" required>

    <label for="onroadprice">On Road Price:</label>
    <input type="text" name="onroadprice" value="<?php echo $onroadprice; ?>" required>

    <label for="max_power">Max Power (hp):</label>
    <input type="number" name="max_power" value="<?php echo $max_power; ?>" required>

    <label for="displacement">Displacement (cc):</label>
    <input type="number" name="displacement" value="<?php echo $displacement; ?>" required>

    <label for="fuel_tank">Fuel Tank Capacity (Litres):</label>
    <input type="number" name="fuel_tank" value="<?php echo $fuel_tank; ?>" required>

    <label for="engine">Engine:</label>
    <input type="text" name="engine" value="<?php echo $engine; ?>" required>

    <label for="fuel_type">Fuel Type:</label>
    <input type="text" name="fuel_type" value="<?php echo $fuel_type; ?>" required>

    <label for="emission_norms">Emission Norms:</label>
    <input type="text" name="emission_norms" value="<?php echo $emission_norms; ?>" required>

    <label for="max_torque">Max Torque (Nm):</label>
    <input type="number" name="max_torque" value="<?php echo $max_torque; ?>" required>

    <label for="mileage">Mileage (kmpl):</label>
    <input type="text" name="mileage" value="<?php echo $mileage; ?>" required>

    <label for="gradeability">Gradeability (%):</label>
    <input type="number" name="gradeability" value="<?php echo $gradeability; ?>" required>

    <label for="max_speed">Max Speed (km/h):</label>
    <input type="number" name="max_speed" value="<?php echo $max_speed; ?>" required>

    <label for="engine_cylinders">Engine Cylinders:</label>
    <input type="number" name="engine_cylinders" value="<?php echo $engine_cylinders; ?>" required>

    <label for="battery_capacity">Battery Capacity (Ah):</label>
    <input type="number" name="battery_capacity" value="<?php echo $battery_capacity; ?>" required>

    <label for="images">Images (up to 10):</label>
    <input type="file" name="images[]" multiple accept="image/*">

    <input type="submit" name="submit" value="Update Car">
</form>


        <br>
        <a href="admin.php" class="button">Back to Admin Dashboard</a>
    </div>
</body>
</html>


