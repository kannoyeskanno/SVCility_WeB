<?php
include 'dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['requestId'];

    // Update the 'active' status to 1
    $updateSql = "UPDATE request SET active = 1 WHERE id = $requestId";
    $conn->query($updateSql);

    echo "Request status updated successfully.";
}

$conn->close();
?>
