<?php
session_start(); 

include '../dbConnect.php';

if (isset($_GET['facilityID'])) {
    $facilityID = $_GET['facilityID'];

    if (isset($_SESSION['user_id'])) {
        $userID = $_SESSION['user_id'];

        $checkSql = "SELECT * FROM list_request WHERE facilityId = '$facilityID' AND user_id = '$userID'";
        $checkResult = $conn->query($checkSql);

        if ($checkResult->num_rows > 0) {
            $updateSql = "UPDATE list_request SET reservation_date = NOW() WHERE facilityId = '$facilityID' AND user_id = '$userID'";

            if ($conn->query($updateSql) === TRUE) {
                echo "Reservation updated in the list.";
            } else {
                echo "Error updating reservation in the list: " . $conn->error;
            }
        } else {
            $insertSql = "INSERT INTO list_request (facilityId, user_id, reservation_date) VALUES ('$facilityID', '$userID', NOW())";

            if ($conn->query($insertSql) === TRUE) {
                echo "Reservation added to the list.";
            } else {
                echo "Error adding reservation to the list: " . $conn->error;
            }
        }
    } else {
        echo "User is not logged in. Please log in to add reservations to the list.";
    }
} else {
    echo "Facility ID not provided.";
}

$conn->close();
?>
