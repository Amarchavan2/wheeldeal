<?php
include 'config.php';

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
    $sqlDeleteMessage = "DELETE FROM messages WHERE id = $message_id";
    $resultDeleteMessage = $conn->query($sqlDeleteMessage);

    if ($resultDeleteMessage === false) {
        // Check for query execution error
        $message = "Error deleting message: " . $conn->error;
    } else {
        $message = "Message deleted successfully!";
    }
}

// Fetch all messages
$sqlMessages = "SELECT * FROM messages";
$resultMessages = $conn->query($sqlMessages);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Messages</title>
    <link rel="stylesheet" href="user_messagesstyle.css">
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
                        while ($message = $resultMessages->fetch_assoc()) {
                            echo "<li>User: {$message['user_name']} ({$message['user_email']}, {$message['user_phone']}) - {$message['message_content']} <a href='user_messages.php?delete_message={$message['id']}' class='delete-link'>Delete Message</a></li>";
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

    <!-- ... (other HTML code) -->

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