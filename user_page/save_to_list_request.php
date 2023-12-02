<?php
session_start();
include '../dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['facilityID'])) {
    $facilityID = $_POST['facilityID'];
    
    if (isset($_SESSION['user_id'])) {
        $userID = $_SESSION['user_id'];
        
        $insertQuery = "INSERT INTO list_request (facility_id, user_id) VALUES ($facilityID, $userID)";
        
        if ($conn->query($insertQuery) === TRUE) {
            echo "Facility added to the list.";
        } else {
            echo "Error adding facility to the list: " . $conn->error;
        }
    } else {
        echo "User is not logged in or user ID is not set.";
    }

    $conn->close();
}
