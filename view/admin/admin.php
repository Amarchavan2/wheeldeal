<!-- admin.php -->
<?php
include dirname(__DIR__, 2) . '/config.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
    <h1>Welcome, Admin!</h1>

    <div id="cars">
        <button onclick="window.location.href='upload_car.php'">Upload Car</button>
    </div>
    <div id="upload">
        <button onclick="window.location.href='view_cars.php'">View Cars</button>
    </div>
    <div id="message">
        <button onclick="window.location.href='view_bookings.php'">View bookings</button>
    </div>
    <div id="users">
        <button onclick="window.location.href='user_messages.php'">View User Messages</button>
    </div>
    <div id="logout">
    <button onclick="window.location.href='logout.php'">Logout</button>

</div>

    
</body>
</html>
