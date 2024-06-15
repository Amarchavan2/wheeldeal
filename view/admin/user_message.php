<?php
include dirname(__DIR__, 2) . '/config.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Handle user deletion
$resultDeleteUserMessage = ''; // Initialize the message variable
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user']) && is_numeric($_POST['delete_user'])) {
    $user_id = intval($_POST['delete_user']); // Use intval to sanitize input
    $sqlDeleteUser = "DELETE FROM users WHERE id = $user_id";
    if ($conn->query($sqlDeleteUser) === true) {
        $resultDeleteUserMessage = "User deleted successfully!";
    } else {
        $resultDeleteUserMessage = "Error deleting user: " . $conn->error;
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
    <link rel="stylesheet" href="../../css/user_messagesstyle.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, Admin!</h1>

        <h2>User List</h2>
        <?php if (!empty($resultDeleteUserMessage)): ?>
            <p id="delete-message"><?php echo htmlspecialchars($resultDeleteUserMessage); ?></p>
        <?php endif; ?>
        
        <ul>
            <?php if ($resultUsers->num_rows > 0): ?>
                <?php while ($user = $resultUsers->fetch_assoc()): ?>
                    <li>User ID: <?php echo htmlspecialchars($user['id']); ?> - Username: <?php echo htmlspecialchars($user['username']); ?> - Email: <?php echo htmlspecialchars($user['email']); ?> 
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="delete_user" value="<?php echo htmlspecialchars($user['id']); ?>">
                        <input type="submit" value="Delete User" onclick="return confirm('Are you sure you want to delete this user?');">
                    </form>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No users available.</p>
            <?php endif; ?>
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
