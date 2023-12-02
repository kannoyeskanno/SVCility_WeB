<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .request-container {
            border: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            overflow: hidden;
        }

        .image-container {
            width: 50px;
            height: 50px;
            background-size: cover;
            background-position: center;
            float: left;
            margin-right: 20px;
        }

        .facility-details {
            float: left;
            width: calc(60% - 20px); /* Adjusted width with margin */
            border: solid;
            display: flex;
            flex-direction: column;
        }

        .user-name, .facility-name, .quantity {
            margin: 0;
            font-size: 14px;
        }

        .button {
            float: right;
            margin-top: 10px; /* Add margin for separation */
        }

        button {
            background-color: #333;
            color: #fff;
            padding: 8px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        /* Media query for small screens */
        @media screen and (max-width: 600px) {
            .facility-details {
                width: 100%; /* Full width on small screens */
                margin-top: 10px; /* Add margin for separation */
            }

            .button {
                float: none; /* Center button on small screens */
                margin-top: 10px; /* Add margin for separation */
                text-align: center; /* Center button text */
            }
        }
    </style>

    <script>
        function submitForm(userId, requestId, equipmentId) {
            // Create a new FormData object
            var formData = new FormData();
            formData.append('user_id', userId);
            formData.append('request_id', requestId);
            formData.append('equipment_id', equipmentId);

            var xhr = new XMLHttpRequest();

            xhr.open('POST', window.location.href, true);

            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 300) {
                    // If the request was successful, reload the page
                    location.reload(true);
                } else {
                    // If there was an error, log it to the console
                    console.error('Form submission failed with status ' + xhr.status);
                }
            };

            xhr.onerror = function () {
                console.error('Form submission failed');
            };

            // Send the FormData object with the request
            xhr.send(formData);
        }
    </script>

</head>
<body>


<div class="container mt-4">
    <h2 class="mb-4">To be handed</h2>

    <?php
    include '../dbConnect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id']) && isset($_POST['equipment_id']) && isset($_POST['user_id'])) {
        // Get the request ID and equipment ID from the form
        $requestId = $_POST['request_id'];
        $equipmentId = $_POST['equipment_id'];
        $user_id = $_POST['user_id'];

        $updateSql = "UPDATE request SET equipment_status = 'handed' WHERE id = $requestId";
        if ($conn->query($updateSql) === TRUE) {
            echo "Record updated successfully";

            $equipmentIds = $_POST["equipment_id"];  // Use the submitted equipment_id
            $pairs = explode(",", rtrim($equipmentIds, ","));

            foreach ($pairs as $pair) {
                $parts = explode(":", $pair);

                if (count($parts) === 2) {
                    $id = $parts[0];
                    $quantity = $parts[1];

                    $insertLogSql = "INSERT INTO equipment_logs (user_id, request_id, equipment_id, qty, handed) VALUES ($user_id, $requestId, $id, $quantity, NOW())";
                    if ($conn->query($insertLogSql) === TRUE) {
                        echo "Log record inserted successfully";
                    } else {
                        echo "Error inserting log record: " . $conn->error;
                    }
                }
            }
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }

    // Query to retrieve data where equipment_status is not equal to 'handed'
    $sql = "SELECT * FROM request WHERE status = 'approved' AND equipment_status = ''";
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Fetch data from the database
            $requestId = $row["id"];
            $subject = $row["subject"];
            $purpose = $row["purpose"];
            $date = $row["date"];
            $status = $row["status"];
            $user_id = $row["user_id"];

            // Display request details in a Bootstrap card
            echo '<div class="card mb-3">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">Request Details</h5>';

            // Equipment details
            echo '<div class="row">';
            echo '<div class="col-md-6">';
            echo '<h6 class="card-subtitle mb-2 text-muted">Equipment</h6>';

            $equipmentIds = $row["equipment_qty"];
            $pairs = explode(",", rtrim($equipmentIds, ","));

            foreach ($pairs as $pair) {
                $parts = explode(":", $pair);

                if (count($parts) === 2) {
                    $id = $parts[0];
                    $quantity = $parts[1];
                    $equipment_sql = "SELECT * FROM equipment WHERE id = $id";
                    $equipment_result = $conn->query($equipment_sql);

                    if ($equipment_result !== false && $equipment_result->num_rows > 0) {
                        $equipment_row = $equipment_result->fetch_assoc();
                        $equipment_name = $equipment_row['equipment_name'];

                        echo '<p>' . $equipment_name . ' - Quantity: ' . $quantity . '</p>';

                        $equipmentImageName = $equipment_row["image_name"];
                        $equipmentImagePath = '../resources/equipment_imgs/' . $equipmentImageName . '.png';

                        echo '<div class="d-flex align-items-center mb-2">';
                        echo '<img src="' . $equipmentImagePath . '" alt="' . $equipment_name . '" class="mr-2" style="width: 30px; height: 30px;">';
                        echo '<p>' . $equipment_name . '</p>';
                        echo '</div>';


                        echo '<div class="col-md-6 text-right">';
                        echo '<h6 class="card-subtitle mb-2 text-muted">Actions</h6>';
                        echo "<form method='post' action=''>";
                        echo "<input type='hidden' name='request_id' value='$requestId'>";

                        $equipmentIdsString = implode(",", $pairs);
                        echo "<input type='hidden' name='equipment_id' value='$equipmentIdsString'>";

                        echo "<input type='hidden' name='user_id' value='$user_id'>";
                        echo "<button type='button' onclick='submitForm($user_id, $requestId, \"$equipmentIdsString\")'>Handed</button>";
                        echo "</form>";
                    }
                }
            }

            echo '</div>';

            

            echo '</div>';
            echo '</div>';

        }
    } else {
        echo "<p>Error in SQL query: " . $conn->error . "</p>";
    }

    $conn->close();
    ?>
</div>



<!-- Modal for Delete -->
<div id="deleteModal" class="modal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Request</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="deleteMessage">Are you sure you want to delete this request?</p>
            </div>
            <div class="modal-footer">
                <button onclick="deleteRequest()" class="btn btn-danger">Yes</button>
                <button onclick="hideDeleteModal()" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>



