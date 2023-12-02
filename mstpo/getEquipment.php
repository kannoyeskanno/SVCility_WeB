<?php
include '../dbConnect.php';

// Fetch equipment data from the database
$query = "SELECT * FROM equipment";
$result = $conn->query($query);

$equipmentData = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $equipmentData[] = $row;
    }
}

// Return equipment data as JSON
header('Content-Type: application/json');
echo json_encode($equipmentData);

$conn->close();
?>
