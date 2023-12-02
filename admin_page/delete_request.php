<?php
include '../dbConnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the request ID from the POST data
    $requestId = $_POST["id"];

    // Log the received request ID
    error_log("Received Request ID: " . $requestId);

    // Perform the database update (delete) based on the request ID
    $deleteSql = "DELETE FROM request WHERE id = ?";
    $stmtDelete = $conn->prepare($deleteSql);

    if ($stmtDelete) {
        $stmtDelete->bind_param("i", $requestId); // Assuming request_id is an integer

        if ($stmtDelete->execute()) {
            echo "Request deleted successfully";
        } else {
            // Error in execution
            echo "Error deleting request: " . $stmtDelete->error;
        }

        $stmtDelete->close();
    } else {
        // Error in preparation
        echo "Error preparing delete statement: " . $conn->error;
    }

    $conn->close();
}
?>
