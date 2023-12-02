<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f2f2f2;
    }

    .request-container {
        background-color: #fff;
        border: 1px solid #ccc;
        margin: 10px;
        padding: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
    }

    .image-container {
        width: 100px;
        height: 100px;
        background-size: cover;
        background-position: center;
        margin-right: 20px;
        border: 1px solid #ddd;
    }

    .facility-details {
        flex-grow: 1;
    }

    .facility-name {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .user-name {
        font-size: 16px;
        color: #555;
    }

    .quantity {
        font-size: 14px;
        color: #777;
    }

    .button {
        margin-top: 10px;
    }

    button {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }

    p {
        margin: 0;
    }


    .icon {
        font-size: 50px;
        color: transparent;
        animation: fadeInScale 0.5s ease-in-out;
        border: solid red;
        width: 100%;
        height: 90px;
        background: url('../resources/logo/left.png') no-repeat center center;
        background-size: contain;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.5);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>
</head>

<body>
<!--  -->
<?php

include('../dbConnect.php');
if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_POST['request_id']) &&
    isset($_POST['equipment_id']) &&
    isset($_POST['user_id']) &&
    isset($_POST['missing']) &&
    isset($_POST['notGoodCondition'])
) {
    $requestId = $_POST['request_id'];
    $equipmentId = $_POST['equipment_id'];
    $userId = $_POST['user_id'];
    $missing = $_POST['missing'];
    $notGoodCondition = $_POST['notGoodCondition'];

    // Update equipment_logs table
    $insertLogSql = "UPDATE equipment_logs SET returned = NOW(), number_missing = ?, bad_condition = ? WHERE equipment_id = ? AND request_id = ? AND user_id = ?";
    if ($stmt = $conn->prepare($insertLogSql)) {
        $stmt->bind_param("iiiii", $missing, $notGoodCondition, $equipmentId, $requestId, $userId);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error updating log record: " . $conn->error;
        exit;
    }

    // Check if all items in equipment_logs for the given request_id are not null
    $checkSql = "SELECT * FROM equipment_logs WHERE request_id = ? AND returned IS NOT NULL";
    if ($stmt = $conn->prepare($checkSql)) {
        $stmt->bind_param("i", $requestId);
        $stmt->execute();
        $stmt->store_result();

        // Check the number of rows returned
        if ($stmt->num_rows > 0) {
            // All items are not null, update the status in the request table
            $updateSql = "UPDATE request SET equipment_status = 'returned' WHERE id = ?";
            if ($stmtUpdate = $conn->prepare($updateSql)) {
                $stmtUpdate->bind_param("i", $requestId);
                $stmtUpdate->execute();
                $stmtUpdate->close();
            } else {
                echo "Error updating record: " . $conn->error;
                exit;
            }
        }

        $stmt->close();
    } else {
        echo "Error checking log records: " . $conn->error;
        exit;
    }

    // Update equipment quantity
    $minus = $missing + $notGoodCondition;
    if ($minus > 0) {
        $minusEquipmentSql = "UPDATE equipment SET qty = qty - ? WHERE id = ?";
        if ($stmtMinus = $conn->prepare($minusEquipmentSql)) {
            $stmtMinus->bind_param("ii", $minus, $equipmentId);
            $stmtMinus->execute();
            $stmtMinus->close();
        } else {
            echo "Error updating equipment quantity: " . $conn->error;
            exit;
        }
    }

    echo "Equipment return successful";
} else {
    echo "Invalid request";
}




$sql = "SELECT * FROM request WHERE equipment_status = 'handed'";
$sql = "SELECT * FROM equipment_logs WHERE returned IS NULL";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='head-container'>";
    echo "<p class='head-text'>Equipment To Return</p>";
    echo "</div>";

    while ($row = $result->fetch_assoc()) {
        // $equipment_qty_array = $row["equipment_qty"];
        // $user_id = $row['user_id'];
        // $request_id = $row['id'];

        $equipment_id = $row["equipment_id"];
        $user_id = $row['user_id'];
        $request_id = $row['request_id'];
        $quantity = $row['qty'];

        // try {
        //     $idArray = explode(',', $equipment_qty_array);
        
        //     foreach ($idArray as $element) {
        //         $explodedValues = explode(':', $element);
        
        //         if (count($explodedValues) == 2) {
        //             list($id, $quantity) = $explodedValues;
        
        $sqlClient = "SELECT * FROM client WHERE id = $user_id";
        $resultClient = $conn->query($sqlClient);
        $rowClient = $resultClient->fetch_assoc();

        $sqlEquipment = "SELECT * FROM equipment WHERE id = $equipment_id";
        $resultEquipment = $conn->query($sqlEquipment);
        $rowEquipment = $resultEquipment->fetch_assoc();

        $imageName = $rowEquipment['image_name'];
        $imagePath = "../resources/equipment_imgs/{$imageName}.png";

        echo "<div class='request-container'>";
        echo "<div class='image-container' style='background-image: url(\"$imagePath\")'></div>";
        echo "<div class='facility-details'>";
        echo "<p class='user-name'>User: {$rowClient['fname']} {$rowClient['lname']}</p>";
        echo "<p class='facility-name'>Equipment: {$rowEquipment['equipment_name']}</p>";
        echo "<p class='quantity'>Quantity: {$quantity}</p>";
        echo "<p class='quantity'>Eid: {$equipment_id}</p>";

        echo "</div>";
        echo "<div class='button'>";
        echo '<a href="#" onclick="showModal(\'' . $rowEquipment["equipment_name"] . '\', \'' . $imagePath . '\')" class="action-button">Return</a>';
        echo "<input type='hidden' class='request-id' value='$request_id'>";
        echo "<input type='hidden' class='equipment-id' value='$equipment_id'>";
        echo "<input type='hidden' class='user-id' value='$user_id'>";
        echo "</div>";
        echo "</div>";
               
    }
} else {
    echo "0 results";
}

$conn->close();
?>



<div id="return" class="modal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Equipment Accession</h5>
                <button type="button" class="close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="icon">
                    <!-- You can replace this with an appropriate icon for returning equipment -->
                    &#x1F4E6;
                </div>
                <p class="mt-3">Please confirm the return of the following equipment:</p>

                <!-- Display equipment details -->
                <div id="equipmentDetails"></div>

                <!-- Input for quantity returned and not in good condition -->
                <div class="form-group">
                    <label for="missing">Quantity Missing:</label>
                    <input type="number" class="form-control" id="missing" min="0" placeholder='0' required>
                </div>
                <div class="form-group">
                    <label for="notGoodCondition">Number of Equipment Not in Good Condition:</label>
                    <input type="number" placeholder='0' class="form-control" id="notGoodCondition" min="0" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="confirmReturn()">Yes</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">No</button>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
      function showModal(equipmentName, equipmentImage) {
        // Update equipment details in the modal
        var equipmentDetails = document.getElementById('equipmentDetails');
        equipmentDetails.innerHTML = `
            <p><strong>Equipment Name:</strong> ${equipmentName}</p>
            <img src="${equipmentImage}" alt="${equipmentName}" style="width: 50px; height: 50px;">
        `;

        // Show the modal
        $('#return').modal('show');
    }

    function closeModal() {
        $('#return').modal('hide');
    }
    function updateStatus(requestId, status) {

        var url = "update_status.php";

        var xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Set up the callback function to handle the response
        xhr.onreadystatechange = function () {
            console.log("ReadyState:", xhr.readyState);
            console.log("Status:", xhr.status);
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Handle the response here (e.g., show a success message)
                console.log("Response:", xhr.responseText);
            }
        };

        // Send the request with the request ID and status
        xhr.send("request_id=" + requestId + "&status=" + status);
    }

    function confirmReturn() {
    // Get the values needed for the update from the modal input fields
    var requestId = $('.request-id').val();
    var equipmentId = $('.equipment-id').val();
    var userId = $('.user-id').val();
    var missing = $('#missing').val();
    var notGoodCondition = $('#notGoodCondition').val();

    // AJAX request to update the database
    $.ajax({
        type: 'POST',
        url: 'frame_equipment_return.php', // Replace with the actual path to your PHP script
        data: {
            request_id: requestId,
            equipment_id: equipmentId,
            user_id: userId,
            missing: missing,
            notGoodCondition: notGoodCondition
        },
        success: function (response) {
            // Handle the response here, e.g., show a success message
            console.log(response);

            // Optionally, you can update the UI or perform other actions as needed
            // For example, hide the modal after a successful update
            closeModal();
        },
        error: function (error) {
            // Handle errors here, e.g., show an error message
            console.error(error);
        }
    });
}


</script>

</body>

</html>
