<?php
include '../dbConnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requestId = $_POST["id"];

    error_log("Received Request ID: " . $requestId);

    $deleteSql = "DELETE FROM request WHERE id = ?";
    $stmtDelete = $conn->prepare($deleteSql);

    if ($stmtDelete) {
        $stmtDelete->bind_param("i", $requestId); 

        if ($stmtDelete->execute()) {
            echo "Request deleted successfully";
        } else {
            echo "Error deleting request: " . $stmtDelete->error;
        }

        $stmtDelete->close();
    } else {
        echo "Error preparing delete statement: " . $conn->error;
    }

    $conn->close();
}
?>
