<?php
include '../../config.php';

// Check if form is submitted for status update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if it's a status update
    if (isset($_POST['booking_id'], $_POST['status'])) {
        $bookingId = $_POST['booking_id'];
        $status = $_POST['status'];

        // Update the status in the database (using prepared statement for security)
        $sql = "UPDATE bookings SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $bookingId);

        if ($stmt->execute()) {
            echo "<script>alert('Status updated successfully.');</script>";
        } else {
            echo "<script>alert('Error updating status.');</script>";
        }

        $stmt->close();
    }
    // Check if it's a delete request
    elseif (isset($_POST['delete_id'])) {
        $deleteId = $_POST['delete_id'];

        // Delete the booking from the database
        $sql_delete = "DELETE FROM bookings WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $deleteId);

        if ($stmt_delete->execute()) {
            echo "<script>alert('Booking deleted successfully.');</script>";
        } else {
            echo "<script>alert('Error deleting booking.');</script>";
        }

        $stmt_delete->close();
    }
}

// Fetch all booking details from the database
$sql = "SELECT * FROM bookings";
$result = $conn->query($sql);

$bookingDetails = ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Booking Details</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <style>
        /* Add custom CSS styles here */
        .status-select {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>All Booking Details</h1>
        <?php if (!empty($bookingDetails)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Car Name</th>
                        <th>User Name</th>
                        <th>Phone Number</th>
                        <th>Purchase Period</th>
                        <th>Status</th>
                        <th>Booking Date</th>
                        <th>Action</th> <!-- New column for delete action -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookingDetails as $booking): ?>
                        <tr>
                            <td><?php echo $booking['id']; ?></td>
                            <td><?php echo $booking['car_name']; ?></td>
                            <td><?php echo $booking['user_name']; ?></td>
                            <td><?php echo $booking['user_phone']; ?></td>
                            <td><?php echo $booking['purchase_period']; ?></td>
                            <td>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                    <select name="status" class="status-select" onchange="this.form.submit()">
                                        <option value="Pending" <?php echo ($booking['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Done" <?php echo ($booking['status'] == 'Done') ? 'selected' : ''; ?>>Done</option>
                                    </select>
                                </form>
                            </td>
                            <td><?php echo $booking['created_at']; ?></td>
                            <td>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                    <input type="hidden" name="delete_id" value="<?php echo $booking['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No booking details found.</p>
        <?php endif; ?>
        <a href="admin.php" class="btn btn-primary btn-back">Back to Admin Dashboard</a>
    </div>
    

    <script src="../../js/bootstrap.min.js"></script>
</body>
</html>
