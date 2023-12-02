<?php
session_start();
include '../dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['equipmentID']) && isset($_GET['remainingQuantity'])) {
    $equipmentID = $_GET['equipmentID'];
    $remainingQuantity = $_GET['remainingQuantity'];

    if (isset($_SESSION['user_id'])) {
        $userID = $_SESSION['user_id'];

        $insertQuery = "INSERT INTO list_request (equipment_id, user_id, qty) VALUES ($equipmentID, $userID, 1)";

        if ($conn->query($insertQuery) === TRUE) {
            // Return the remaining quantity in the response
            $response = "Equipment added to the list. Remaining quantity: " . $remainingQuantity;
            echo json_encode(array('status' => 'success', 'message' => $response, 'remainingQuantity' => $remainingQuantity));
        } else {
            $response = "Error adding equipment to the list: " . $conn->error;
            echo json_encode(array('status' => 'error', 'message' => $response));
        }
    } else {
        $response = "User is not logged in or user ID is not set.";
        echo json_encode(array('status' => 'error', 'message' => $response));
    }

    $conn->close();
}
?>
