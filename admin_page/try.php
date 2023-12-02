please reconstruct this page 

make it a good table using boostrap 


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <style>
        body {
            font-family: 'Josephin Sans', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .main {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            box-sizing: border-box;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            text-decoration: none; /* Remove default link styling */
            color: #333; /* Better text readability */
        }

        .facility-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }

        .facility-box {
            background-color: #fff;
            border: 1px solid red; /* Adjusted border styling */
            border-radius: 8px;
            padding: 10px;
            box-sizing: border-box;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            text-align: center;
            width: 80%;
            display: flex;
            flex-direction: column;
        }

        p {
            font-size: 14px;
            margin: 5px 0;
        }

        .image-container {
            width: 100%;
            height: 80px;
            overflow: hidden;
            border: 1px solid #ccc; /* Adjusted border styling */
        }

        .image {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
        }

        .text-container {
            flex-grow: 1;
            display: flex;
            justify-content: space-between;
            padding: 0 10px;
            border: solid;
            height: 100%;

        }

        .details {
            gap: 10px;
            border: solid;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 10px;
            border: solid;
            height: 100%;
        }

        .action-button {
            display: inline-block;
            margin-top: 5px;
            padding: 5px 10px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
        }

        .action-button:hover {
            background-color: #0056b3;
        }

        .delete button {
            margin: 0 5px;
            padding: 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .quantity button {
            background-color: #007BFF;
            color: #fff;
        }

        .delete button {
            background-color: #dc3545;
            color: #fff;
            width: 30px;
            height: 30px;
        }

        .name {
            margin-right: 40px;
        }

        #deleteModal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        /* Style for the overlay/background behind the modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .combi {
            border: solid red;
            display: flex;
         }
    </style>
</head>
<body>

<?php
include '../dbConnect.php';
$sql = "SELECT * FROM request";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $user_id = $row["user_id"];
        $subject = $row["subject"];
        $purpose = $row["purpose"];
        $date = $row["date"];
        $time = $row["time"];
        $facilityIds = $row["facility_id"];
        $equipmentIds = $row["equipment_qty"];
        $status = $row["status"];
        echo '<div class="main">';

        // Request container
        echo '<a href="view_request.php?request_id=' . $row["id"] . '" class="request-container">';

        // Facilities
        echo '<div class="facility-container">';


        $pairs = explode(",", rtrim($equipmentIds, ","));

        foreach ($pairs as $pair) {
            $parts = explode(":", $pair);
        
            if (count($parts) === 2) {
                $id = $parts[0];
                $quantity = $parts[1];            
                $equipment_sql = "SELECT * FROM equipment WHERE id = $id";
                $equipment_result = $conn->query($equipment_sql);
        
                if ($equipment_result !== false) {
                    if ($equipment_result->num_rows > 0) {
                        $equipment_row = $equipment_result->fetch_assoc();
                        $equipment_name = $equipment_row['equipment_name'];
                        $equipment_image_name = $equipment_row['image_name'];
        
                        $equipmentImagePath = '../resources/equipment_imgs/' . $equipment_image_name . '.png';
        
                        echo '<div class="facility-box">';
                        echo '<div class="image-container">';
                        echo '<div class="image" style="background-image: url(' . $equipmentImagePath . ');"></div>';
                        echo '</div>';
                        echo '<div class="text-container">';
                        echo '<p>' . $equipment_name . '</p>';
                        echo '</div>';
                        echo '</div>';                    
                    
                    } else {
                        echo "No equipment found for ID: $id<br>";
                    }
                } else {
                    echo "Error executing equipment query: " . $conn->error . "<br>";
                }
            } else {
            }
        }
        
        

    


        $facilityIdArray = explode(',', $facilityIds);
        foreach ($facilityIdArray as $facilityId) {
            $sqlFaci = "SELECT * FROM facility WHERE id = ?";
            $stmtFaci = $conn->prepare($sqlFaci);

            if ($stmtFaci) {
                $stmtFaci->bind_param("s", $facilityId);
                $stmtFaci->execute();
                $resultFaci = $stmtFaci->get_result();

                if ($resultFaci) {
                    while ($rowFaci = $resultFaci->fetch_assoc()) {
                        $image_name = $rowFaci["image_name"];
                        $imagePath = '../resources/facility_imgs/' . $image_name . '.png';

                        echo '<div class="facility-box">';
                        echo '<div class="image-container">';
                        echo '<div class="image" style="background-image: url(' . $imagePath . ');"></div>';
                        echo '</div>';
                        echo '<div class="text-container">';
                        echo '<p>' . $rowFaci["facility_name"] . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>Error in facility query: " . $stmtFaci->error . "</p>";
                }

                $stmtFaci->close();
            } else {
                echo "<p>Error in facility query preparation: " . $conn->error . "</p>";
            }
        }

        echo '<div class="text-container">';
        echo '<div class="combi">';

        // Details
            echo '<div class="details">';
                echo "<p>Subject: $subject</p>";
                echo "<p>Purpose: $purpose</p>";
                echo "<p>Date: $date</p>";

                // Status
                echo "<p>Status: $status</p>";

            echo '</div>';

            // Check and Delete buttons
            echo '<div class="actions">';
                echo '<a href="#" onclick="updateStatus(' . $row["id"] . ', \'submitted\')" class="action-button">Check</a>';
                // Show modal for delete action
                echo '<a href="#" onclick="showDeleteModal(' . $row["id"] . ')" class="action-button">Delete</a>';
            echo '</div>';
            echo '</div>';

        echo '</div>'; // Close text container
        echo '</div>'; // Close request container
        echo '</a>'; // Close anchor tag for request-container
        echo '</div>'; // Close request container

    }
} else {
    echo "<p>Error in SQL query: " . $conn->error . "</p>";
}

$conn->close();
?>

<!-- Modal for Delete -->
<div id="deleteModal" style="display: none;" data-request-id="" data-status="">
    <p id="deleteMessage">Are you sure you want to delete this request?</p>
    <button onclick="deleteRequest()" class="action-button">Yes</button>
    <button onclick="hideDeleteModal()" class="action-button">No</button>
</div>

<!-- <div id="doneModal">
    <p id="doneMessage">Request Submitted for Approval</p>
    <button onclick="hideDeleteModal()" class="action-button">Ok</button>
</div> -->

<script>
    function showDeleteModal(requestId, status) {
        // Set the request ID and status in the modal
        document.getElementById("deleteModal").setAttribute("data-request-id", requestId);
        document.getElementById("deleteModal").setAttribute("data-status", status);

        // Set the delete message based on the current status
        document.getElementById("deleteMessage").innerText = status === "Submitted" ? "Are you sure you want to delete this request?" : "This request is already checked. Are you sure you want to delete it?";

        // Display the modal
        document.getElementById("deleteModal").style.display = "block";
    }

    function hideDeleteModal() {
        // Hide the modal
        document.getElementById("deleteModal").style.display = "none";
    }

    function deleteRequest() {
        var requestId = document.getElementById("deleteModal").getAttribute("data-request-id");

        console.log("Received Request ID:", requestId);

       
        var url = "delete_request.php";

        // Using AJAX to send a request to the server
        var xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Set up the callback function to handle the response
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Handle the response here (e.g., show a success message)
                console.log(xhr.responseText);
            }
        };

        // Send the request with the request ID
        xhr.send("id=" + requestId);

        // Hide the modal after deleting
        hideDeleteModal();
        location.reload();
    }

    function updateStatus(requestId, status) {
        // Implement your logic to update the status to "Submitted"
        // For demonstration, let's use AJAX to update the database

        // Assuming you have a separate PHP file to handle the status update (e.g., update_status.php)
        var url = "update_status.php";

        // Using AJAX to send a request to the server
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

        xhr.send("request_id=" + requestId + "&status=" + status);
    }
</script>

</body>
</html>