<?php
session_start();
$admin_id = $_SESSION['user_id'];
include '../dbConnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requestId = $_POST["request_id"];
    $status = $_POST["status"];
    $userId = $_POST["user_id"]; 

    // Log the received request ID
    error_log("Received Request ID for Status Update: " . $requestId);

    // Perform the database update (status update) based on the request ID
    $updateSql = "UPDATE request SET admin_id = ?, status = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($updateSql);

    if ($stmtUpdate) {
        $stmtUpdate->bind_param("isi", $admin_id, $status, $requestId); // Assuming status is a string and request_id is an integer

        if ($stmtUpdate->execute()) {
            // The status was successfully updated

            // Get the facility_id, date, and user_id from the request
            $requestDataSql = "SELECT * FROM request WHERE id = ?";
            $stmtRequestData = $conn->prepare($requestDataSql);

            if ($stmtRequestData) {
                $stmtRequestData->bind_param("i", $requestId); // Assuming request_id is an integer
                $stmtRequestData->execute();
                $resultRequestData = $stmtRequestData->get_result();

                if ($resultRequestData->num_rows > 0) {
                    $rowRequestData = $resultRequestData->fetch_assoc();
                    $facilityId = $rowRequestData['facility_id'];
                    $date = $rowRequestData['date'];
                    $user_id = $rowRequestData['user_id'];
                    $request_id = $rowRequestData['id'];

                    $insertQuery = "INSERT INTO facility_schedules (facility_id, scheduled_date, user_id, request_id) 
                    VALUES ('$facilityId', '$date', '$user_id', '$request_id')";

                    if ($conn->query($insertQuery) === TRUE) {
                        
                        header("Location: mayor_page/mayor_dashboard.php");
                    } else {
                        echo '<script>alert("Error creating account: ' . $conn->error . '");</script>';
                    }
                    
                    
                } else {
                    echo "Error: Data not found for the given request_id";
                }

                $stmtRequestData->close();
            } else {
                // Error in preparation
                echo "Error preparing data retrieval statement: " . $conn->error;
                error_log("Error preparing data retrieval statement: " . $conn->error);
            }
        } else {
            // Error in execution
            echo "Error updating status: " . $stmtUpdate->error;
            error_log("Error updating status: " . $stmtUpdate->error);
        }

        $stmtUpdate->close();
    } else {
        // Error in preparation
        echo "Error preparing status update statement: " . $conn->error;
    }

    $conn->close();
}
?>
