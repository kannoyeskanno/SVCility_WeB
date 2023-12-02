<?php
include '../dbConnect.php';

echo 'PHP file executed';

try {
    if (isset($_POST['selectedDate'])) {
        $selectedDate = $_POST['selectedDate'];

        echo $selectedDate;

        // Fetch all approved requests for the selected date
        $sql = "SELECT * FROM request WHERE status = 'approved' AND date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $selectedDate);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            throw new Exception('Error in the query: ' . $stmt->error);
        } elseif ($result->num_rows > 0) {
            // Create an array to store the total quantity requested for each equipment
            $totalQuantity = [];

            while ($row = $result->fetch_assoc()) {
                $equipmentDetails = explode(',', $row['equipment_qty']);

                foreach ($equipmentDetails as $equipment) {
                    if (strpos($equipment, ':') !== false) {
                        list($equipmentID, $quantity) = explode(':', $equipment);

                        // Initialize the total quantity for each equipment if not set
                        if (!isset($totalQuantity[$equipmentID])) {
                            $totalQuantity[$equipmentID] = 0;
                        }

                        $totalQuantity[$equipmentID] += $quantity;
                    }
                }
            }

            // Fetch all equipment
            $equipmentQuery = "SELECT * FROM equipment";
            $equipmentResult = $conn->query($equipmentQuery);

            if ($equipmentResult === false) {
                throw new Exception('Error in the query: ' . $conn->error);
            }

            // Create an array to store the id:qty pairs
            $idQtyPairs = [];

            while ($equipmentRow = $equipmentResult->fetch_assoc()) {
                $equipmentID = $equipmentRow['id'];
                $remainingQuantity = $equipmentRow['qty'] - ($totalQuantity[$equipmentID] ?? 0);
                $imagePath = '../resources/equipment_imgs/' . $equipmentRow['image_name'] . '.png';

                // Add the id:qty pair to the array
                $idQtyPairs[] = $equipmentID . ':' . $remainingQuantity;
            }

            

            // Display the result
            if (!empty($idQtyPairs)) {
                $resultString = implode(', ', $idQtyPairs);

                echo '<div class="table-container">';
                echo '<p>Equipment ID and Remaining Quantity:</p>';
                echo '<p>' . $resultString . '</p>';
                $_SESSION['resultString'] = $resultString;
                echo '</div>';
            } else {
                echo 'No equipment found.';
            }
        } else {
            echo 'No approved requests found for the selected date.';
        }

        $stmt->close();
    } else {
        throw new Exception('Error: Selected date not set.');
    }
} catch (Exception $e) {
    echo 'An error occurred: ' . $e->getMessage();
} finally {
    $conn->close();
}
?>
