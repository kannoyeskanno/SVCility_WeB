<?php
// Include your database connection script
include("dbConnect.php");

// Start the session
// session_start();

// $userid = $_SESSION['user_id'];
// echo $userid;

$query = "SELECT id, purpose, CONCAT(date, ' ', time) AS timestamp FROM request WHERE status = 'approved' AND user_id = 2 ORDER BY id DESC LIMIT 5";
$result = $conn->query($query);

$notifications = [];

while ($row = $result->fetch_assoc()) {
    // Adjust the column names accordingly
    $notification = [
        'id' => $row['id'],
        'message' => $row['purpose'], // Change 'purpose' to the actual column name representing the message
        'timestamp' => $row['timestamp']
    ];

    $notifications[] = $notification;
}

echo json_encode($notifications);

// Close the database connection
$conn->close();
?>
