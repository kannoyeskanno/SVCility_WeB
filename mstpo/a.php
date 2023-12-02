<?php
// Include the database connection file
include('../dbConnect.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id']) && isset($_POST['equipment_id']) && isset($_POST['user_id'])) {
    // Get the request ID and equipment ID from the form
    $requestId = $_POST['request_id'];
    $equipmentId = $_POST['equipment_id'];
    $user_id = $_POST['user_id'];

    // Update the equipment_status to 'handed' for the given request ID
    $updateSql = "UPDATE request SET equipment_status = 'handed' WHERE id = $requestId";
    if ($conn->query($updateSql) === TRUE) {
        echo "Record updated successfully";

        // Insert a record into the equipment logs with the current date and time
        $insertLogSql = "INSERT INTO equipment_logs (user_id, request_id, equipment_id, handed) VALUES ($user_id, $requestId, $equipmentId, NOW())";
        if ($conn->query($insertLogSql) === TRUE) {
            echo "Log record inserted successfully";
        } else {
            echo "Error inserting log record: " . $conn->error;
        }
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Query to retrieve data where equipment_status is not equal to 'handed'
$sql = "SELECT * FROM request WHERE status = 'approved' AND equipment_status = ''";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='head-container'>";
    echo "<p class='head-text'>Equipment To be Handed</p>";
    echo "</div>";

    while ($row = $result->fetch_assoc()) {
        $equipment_qty_array = $row["equipment_qty"];
        $user_id = $row['user_id'];
        $request_id = $row['id'];

        try {
            $idArray = explode(',', $equipment_qty_array);

            foreach ($idArray as $element) {
                $explodedValues = explode(':', $element);

                if (count($explodedValues) == 2) {
                    list($id, $quantity) = $explodedValues;
                    echo "<form method='post' action=''>";
                    echo "<div class='request-container'>";
                    // Query to retrieve client information
                    $sqlClient = "SELECT * FROM client WHERE id = $user_id";
                    $resultClient = $conn->query($sqlClient);
                    $rowClient = $resultClient->fetch_assoc();

                    $sqlEquipment = "SELECT * FROM equipment WHERE id = $id";
                    $resultEquipment = $conn->query($sqlEquipment);
                    $rowEquipment = $resultEquipment->fetch_assoc();

                    
                    // echo "<p class='reqeust'>Request id: {$request_id}</p>";
                    // echo "<p class='reqeust'>Request id: {$user_id}</p>";

                    // Output client and equipment information
                    echo "<div class='image-container' style='background-image: url(\"../resources/equipment_imgs/{$rowEquipment['image_name']}.png\")'></div>";
                    echo "<div class='facility-details'>";
                    echo "<p class='user-name'>Name: {$rowClient['fname']} {$rowClient['lname']}</p>";
                    echo "<p class='facility-name'>Equipment: {$rowEquipment['equipment_name']}</p>";
                    echo "<p class='quantity'>Quantity: {$quantity}</p>";
                    echo "</div>";
                    echo "<div class='button'>";
                    echo "<input type='hidden' name='request_id' value='$request_id'>";
                    echo "<input type='hidden' name='equipment_id' value='$id'>";
                    echo "<button type='button' onclick='submitForm($user_id, $request_id, $id)'>Handed</button>";
                    echo "</div>";
                    echo "</div>";
                    echo "</form>";
                } else {
                    continue;
                }
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} else {
    echo "0 results";
}

$conn->close();
?>























<?php
include('../dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['returnForm'])) {
    // Handle form submission
    $requestId = $_POST['request_id'];
    $equipmentId = $_POST['equipment_id'];
    $userId = $_POST['user_id'];
    $missing = $_POST['missing'];
    $notGoodCondition = $_POST['notGoodCondition'];

    // Update request status
    $updateSql = "UPDATE request SET equipment_status = 'returned' WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("i", $requestId);
    $stmt->execute();
    $stmt->close();

    // Update equipment log
    $insertLogSql = "INSERT INTO equipment_logs (user_id, request_id, equipment_id, returned, number_missing, bad_condition) VALUES (?, ?, ?, NOW(), ?, ?)";
    $stmt = $conn->prepare($insertLogSql);
    $stmt->bind_param("iiiii", $userId, $requestId, $equipmentId, $missing, $notGoodCondition);
    $stmt->execute();
    $stmt->close();
}

$sql = "SELECT * FROM request WHERE equipment_status = 'handed'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='head-container'>";
    echo "<p class='head-text'>Equipment To Return</p>";
    echo "</div>";

    while ($row = $result->fetch_assoc()) {
        $equipment_qty_array = $row["equipment_qty"];
        $user_id = $row['user_id'];
        $request_id = $row['id'];

        try {
            $idArray = explode(',', $equipment_qty_array);

            foreach ($idArray as $element) {
                $explodedValues = explode(':', $element);

                if (count($explodedValues) == 2) {
                    list($id, $quantity) = $explodedValues;

                    $sqlClient = "SELECT * FROM client WHERE id = $user_id";
                    $resultClient = $conn->query($sqlClient);
                    $rowClient = $resultClient->fetch_assoc();

                    $sqlEquipment = "SELECT * FROM equipment WHERE id = $id";
                    $resultEquipment = $conn->query($sqlEquipment);
                    $rowEquipment = $resultEquipment->fetch_assoc();

                    echo "<div class='request-container'>";
                    echo "<div class='image-container' style='background-image: url(\"../resources/equipment_imgs/{$rowEquipment['image_name']}.png\")'></div>";
                    echo "<div class='facility-details'>";
                    echo "<p class='user-name'>User: {$rowClient['fname']} {$rowClient['lname']}</p>";
                    echo "<p class='facility-name'>Equipment: {$rowEquipment['equipment_name']}</p>";
                    echo "<p class='quantity'>Quantity: {$quantity}</p>";
                    echo "</div>";
                    echo "<div class='button'>";
                    echo "<button data-toggle='modal' data-target='#exampleModal'>Returned</button>";
                    echo "</div>";
                    echo "</div>";
                } else {
                    continue;
                }
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} else {
    echo "0 results";
}

$conn->close();
?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Equipment Return</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
                    <input type="hidden" name="equipment_id" value="<?php echo $id; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <div class="form-group">
                        <label for="missing">Quantity Returned:</label>
                        <input type="number" class="form-control" id="missing" name="missing" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="notGoodCondition">Number of Equipment Not in Good Condition:</label>
                        <input type="number" class="form-control" id="notGoodCondition" name="notGoodCondition" min="0" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="returnForm">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
