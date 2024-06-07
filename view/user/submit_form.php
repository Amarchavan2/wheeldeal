<?php
// Include the config file
include '../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carName = $_POST['carName'];
    $userName = $_POST['userName'];
    $userPhone = $_POST['userPhone'];
    $purchasePeriod = implode(', ', $_POST['purchasePeriod']);

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO bookings (car_name, user_name, user_phone, purchase_period) VALUES (?, ?, ?, ?)");

    // Check if the statement preparation was successful
    if ($stmt) {
        $stmt->bind_param("ssss", $carName, $userName, $userPhone, $purchasePeriod);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            $message = "we will be contacting you soon!";
        } else {
            $message = "Error executing statement: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Error preparing statement: " . $conn->error;
    }

    $conn->close();

    // Redirect back to the car details page with a success message
    header("Location: car-details.php?id=" . urlencode($_GET['id']) . "&message=" . urlencode($message));
    exit;
} else {
    // Redirect to the homepage if accessed directly
    header("Location: ../../index.html");
    exit;
}
?>
