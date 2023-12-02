<?php
session_start();
include('../dbConnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['user_id']) && filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT)) {
        $user_id = $_SESSION['user_id'];
        $reservationId = $_POST['reservationId'];
        $newQuantity = $_POST['newQuantity'];

        // Validate inputs if needed

        // Update the quantity in the database
        $updateSql = "UPDATE list_request SET qty = $newQuantity WHERE user_id = $user_id AND id = $reservationId";
        $updateResult = $conn->query($updateSql);

        if ($updateResult) {
            // Send a success response if needed
            echo json_encode(['success' => true]);
        } else {
            // Send an error response if needed
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
    }
    exit;
}

// Redirect to the home page or display an error message if accessed directly
header('Location: ../index.php');
exit;
?>
