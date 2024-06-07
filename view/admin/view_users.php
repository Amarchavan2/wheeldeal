<?php
include 'config.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Handle user deletion
$resultDeleteUserMessage = ''; // Initialize the message variable
if (isset($_GET['delete_user']) && is_numeric($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];
    $sqlDeleteUser = "DELETE FROM users WHERE id = $user_id";
    $resultDeleteUser = $conn->query($sqlDeleteUser);

    if ($resultDeleteUser === false) {
        // Check for query execution error
        echo "Error deleting user: " . $conn->error;
    } else {
        $resultDeleteUserMessage = "User deleted successfully!";
    }
}

// Fetch all users
$sqlUsers = "SELECT * FROM users";
$resultUsers = $conn->query($sqlUsers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="view_user_style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, Admin!</h1>

        <h2>User List</h2>
        <?php
        // Check if the user deletion message is set
        if (!empty($resultDeleteUserMessage)) {
            echo "<p id='delete-message'>{$resultDeleteUserMessage}</p>";
        }
        ?>
        <ul>
            <?php
            // Check if there are any users
            if ($resultUsers->num_rows > 0) {
                while ($user = $resultUsers->fetch_assoc()) {
                    echo "<li>User ID: {$user['id']} - Username: {$user['username']} - Email: {$user['email']} <a href='view_user.php?delete_user={$user['id']}'>Delete User</a></li>";
                }
            } else {
                echo "<p>No users available.</p>";
            }
            ?>
        </ul>

        <br>
        <form action="admin.php" method="post">
            <input type="submit" value="Back to Admin Dashboard">
        </form>
    </div>

    <script>
        // Function to hide the message after a delay
        function hideMessage() {
            var messageElement = document.getElementById('delete-message');
            if (messageElement) {
                setTimeout(function () {
                    messageElement.style.display = 'none';
                }, 2000); // 2000 milliseconds = 2 seconds
            }
        }

        // Call the hideMessage function when the page loads
        window.onload = hideMessage;
    </script>
</body>
</html>