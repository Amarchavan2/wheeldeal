<?php
include dirname(__DIR__, 2) . '/config.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Initialize the message variable
$message = "";

// Handle message deletion
if (isset($_GET['delete_message']) && is_numeric($_GET['delete_message'])) {
    $message_id = $_GET['delete_message'];

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM contact_form WHERE id = ?");
    $stmt->bind_param("i", $message_id);
    $resultDeleteMessage = $stmt->execute();

    if ($resultDeleteMessage === false) {
        // Check for query execution error
        $message = "Error deleting message: " . $conn->error;
    } else {
        $message = "Message deleted successfully!";
    }
    $stmt->close();
}

// Fetch all messages
$sqlMessages = "SELECT * FROM contact_form";
$resultMessages = $conn->query($sqlMessages);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Messages</title>
    <link rel="stylesheet" href="../../css/user_messagesstyle.css">
</head>
<body>
    <div class="container">
        <div class="box">
            <h1>User Messages</h1>

            <?php
            // Display the message prompt only if a message is deleted
            if (!empty($message)) {
                echo "<p class='message'>$message</p>";
            }
            ?>

            <ul>
                <?php
                if ($resultMessages === false) {
                    // Check for query execution error
                    echo "Error in SQL query: " . $conn->error;
                } else {
                    // Check if there are any messages
                    if ($resultMessages->num_rows > 0) {
                        while ($row = $resultMessages->fetch_assoc()) {
                            $name = isset($row['name']) ? $row['name'] : 'N/A';
                            $email = isset($row['email']) ? $row['email'] : 'N/A';
                            $message_content = isset($row['message']) ? $row['message'] : 'N/A';
                            $created_at = isset($row['created_at']) ? $row['created_at'] : 'N/A';
                            
                            echo "<li>User: $name ($email) - $message_content <a href='user_messages.php?delete_message={$row['id']}' class='delete-link' onclick='return confirm(\"Are you sure you want to delete this message?\")'>Delete Message</a></li>";
                        }
                    } else {
                        echo "<p>No messages available.</p>";
                    }
                }
                ?>
            </ul>

            <br>
            <a href="admin.php" class="button">Back to Admin Dashboard</a>
        </div>
    </div>

    <script>
        // Function to hide the message after a delay
        function hideMessage() {
            var messageElement = document.querySelector('.message');
            if (messageElement) {
                setTimeout(function () {
                    messageElement.style.display = 'none';
                }, 3000); // 3 seconds delay
            }
        }

        // Call the hideMessage function when the page loads
        window.onload = hideMessage;
    </script>

</body>
</html>
