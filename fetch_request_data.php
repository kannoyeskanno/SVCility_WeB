<?php
include 'dbConnect.php';

try {
    // Check if the selectedDate is set in POST
    if (isset($_POST['selectedDate'])) {
        $selectedDate = $_POST['selectedDate'];

        // Fetch data from the request table using a prepared statement
        $sql = "SELECT * FROM request WHERE status = 'approved' AND date = ?";
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bind_param("s", $selectedDate);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        if ($result === false) {
            // Display an error message if the query fails
            throw new Exception('Error in the query: ' . $stmt->error);
        } elseif ($result->num_rows > 0) {
            // Display a table with the fetched data
            echo '<table>';
            echo '<tr><th>Equipment ID</th><th>Requested Quantity</th><th>Remaining Quantity</th></tr>';
            while ($row = $result->fetch_assoc()) {
                // Get equipment details from the request
                $equipmentDetails = explode(',', $row['equipment_qty']);
        
                if (!empty($equipmentDetails[0])) {
                    foreach ($equipmentDetails as $equipment) {
                        try {
                            // Check if the equipment string is in the expected format
                            if (strpos($equipment, ':') !== false) {
                                list($equipmentID, $quantity) = explode(':', $equipment);
        
                                // Fetch equipment details from the equipment table using a prepared statement
                                $equipmentQuery = "SELECT * FROM equipment WHERE id = ?";
                                $equipmentStmt = $conn->prepare($equipmentQuery);
        
                                // Bind the parameter
                                $equipmentStmt->bind_param("i", $equipmentID);
        
                                // Execute the statement
                                $equipmentStmt->execute();
        
                                // Get the result
                                $equipmentResult = $equipmentStmt->get_result();
        
                                if ($equipmentResult === false) {
                                    // Display an error message if the query fails
                                    throw new Exception('Error in the query: ' . $equipmentStmt->error);
                                } elseif ($equipmentResult->num_rows > 0) {
                                    $equipmentRow = $equipmentResult->fetch_assoc();
                                    $remainingQuantity = $equipmentRow['qty'] - $quantity;
        
                                    // Display data in the table
                                    echo '<tr>';
                                    echo '<td>' . $equipmentID . '</td>';
                                    echo '<td>' . $quantity . '</td>';
                                    echo '<td>' . $remainingQuantity . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                // Handle the case where the equipment string is not in the expected format
                                throw new Exception('Invalid format for equipment string: ' . $equipment);
                            }
                        } catch (Exception $equipmentException) {
                            // Handle exceptions specific to the equipment fetching process
                            continue;
                        }
                    }
                }
            }
        
            echo '</table>';
        } else {
            // Display a message if no data is found
            echo '<p>No approved requests found for the selected date.</p>';
        }

        // Close the statement
        $stmt->close();
    } else {
        // Display an error message if selectedDate is not set
        throw new Exception('Error: Selected date not set.');
    }
} catch (Exception $e) {
    // Display a user-friendly error message
    echo 'An error occurred: ' . $e->getMessage();
} finally {
    // Close the database connection
    $conn->close();
}
?>
