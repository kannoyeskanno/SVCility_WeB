<?php
include '../dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the parameters from the POST request
    $requestId = $_POST['request_id'];
    $adminId = $_POST['admin_id'];
    $password = $_POST['password'];

    // Perform admin authentication (replace this with your authentication logic)
    if (authenticateAdmin($adminId, $password)) {
        // If authentication is successful, update the status
        updateStatus($requestId, 'submitted');
        echo 'Success: Status updated.';
    } else {
        echo 'Error: Invalid admin credentials.';
    }
} else {
    // Handle invalid request method
    echo 'Error: Invalid request method.';
}

// Function to authenticate admin (replace this with your authentication logic)
function authenticateAdmin($adminId, $password) {
    // Replace this with your actual authentication logic
    // For demonstration purposes, let's assume there is an admin with ID 'admin123' and password 'adminpass'
    return ($adminId === 'admin123' && $password === 'adminpass');
}

// Function to update the status in the database
function updateStatus($requestId, $status) {
    global $conn;

    $sql = "UPDATE request SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("si", $status, $requestId);
        $stmt->execute();
        $stmt->close();
    } else {
        // Handle the error if the update fails
        echo 'Error: Unable to update status.';
    }
}

$conn->close();
?>
