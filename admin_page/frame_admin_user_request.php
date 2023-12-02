<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <!-- <h2 class="mb-4">Request List</h2> -->

<?php
include '../dbConnect.php';

session_start();

$sql = "SELECT * FROM request WHERE status = 'submitted'";
$result = $conn->query($sql);

if ($result) {
    $dateFacilityCounts = array(); // Track the count of each facility on each date

    while ($row = $result->fetch_assoc()) {
        // Fetch data from the database
        $subject = $row["subject"];
        $purpose = $row["purpose"];
        $date = $row["date"];
        $status = $row["status"];
        $uid = $row["user_id"];

        $client_sql = "SELECT * FROM client WHERE id = $uid";
        $client_result = $conn->query($client_sql);

        if ($client_result && $client_result->num_rows > 0) {
            $client_data = $client_result->fetch_assoc();
            $fname = $client_data["fname"];
            $lname = $client_data["lname"];
            $client_email = $client_data["email"];
            $office_org = $client_data["office_org"];
        }

        // Use a try-catch block for the explode function
        try {
            $facilityIds = explode(',', $row["facility_id"]);
        } catch (Exception $e) {
            // Handle the exception as needed
            $facilityIds = array(); // Set a default value
        }

        echo '<div class="card mb-3">';

        echo '<div class="card-header d-flex justify-content-between">';
        echo '<div class="d-flex flex-column">';
        echo '<strong>' . $subject . '</strong>';
        echo '</div>';
        echo '<div>';
        echo '<strong>' . $office_org . '</strong>';
        echo '</div>';
        echo '<div>';
        echo '<strong>' . $date . '</strong>';
        echo '</div>';

        echo '</div>';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">Request Details</h5>';


        '<strong>' . $office_org . '</strong>';

        
        echo '<div class="row">';
        echo '<div class="col-md-4">';
        echo '<h6 class="card-subtitle mb-2 text-muted">Client Details</h6>';
        echo '<p>Office/Organization: ' . $office_org . '</p>';
        echo '<p>Name: ' . $fname . ' ' . $lname . ' </p>';
        echo '<p>Email: ' . $client_email . '</p>';
        echo '</div>';

        echo '<div class="col-md-4">';
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
                }
            }
        }

        echo '</div>';

        // Facility details column
        echo '<div class="col-md-4">';
        echo '<h6 class="card-subtitle mb-2 text-muted">Facilities</h6>';

        foreach ($facilityIds as $facilityId) {
            $sqlFaci = "SELECT * FROM facility WHERE id = ?";
            $stmtFaci = $conn->prepare($sqlFaci);

            if ($stmtFaci) {
                $stmtFaci->bind_param("s", $facilityId);
                $stmtFaci->execute();
                $resultFaci = $stmtFaci->get_result();

                if ($resultFaci) {
                    while ($rowFaci = $resultFaci->fetch_assoc()) {
                        $facilityImageName = $rowFaci["image_name"];
                        $facilityImagePath = '../resources/facility_imgs/' . $facilityImageName . '.png';

                        echo '<div class="d-flex align-items-center mb-2">';
                        echo '<img src="' . $facilityImagePath . '" alt="' . $rowFaci["facility_name"] . '" class="mr-2" style="width: 30px; height: 30px;">';
                        echo '<p>' . $rowFaci["facility_name"] . '</p>';
                        echo '</div>';
                    }
                }
            }

            $stmtFaci->close();
        }
        echo '</div>';


        echo '</div>'; // End of card body

        // Card footer for action buttons
        echo '<div class="card-footer d-flex justify-content-end">';
        echo '<button type="button" onclick="updateStatus(' . $row["id"] . ', \'approved\')" class="btn btn-primary">Check</button>';
        echo '<button type="button" onclick="showDeleteModal(' . $row["id"] . ')" class="btn btn-danger btn-sm mx-2">Delete</button>';
        echo '<a href="view_request.php?request_id=' . $row["id"] . '" class="btn btn-info btn-sm">View Request</a>';
        echo '</div>'; // End of card footer
        echo '</div>'; // End of card
        
        // Modal for each request
        // echo '
        // <div class="modal fade" id="exampleModal' . $row["id"] . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        //     <div class="modal-dialog modal-dialog-centered">
        //         <form id="formModal" class="modal-content">
        //             <div class="modal-header">
        //                 <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
        //                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        //             </div>
        //             <div class="modal-body d-flex justify-content-center align-items-center">
        //                 <div class="row g-3">
        //                     <div class="col-auto">
        //                         <label for="staticEmail2" class="visually-hidden">Email</label>
        //                         <input type="text" readonly class="form-control-plaintext" id="staticEmail2" value="' . $expectedEmail . '">
        //                     </div>
        //                     <div class="col-auto">
        //                         <label for="inputPassword2" class="visually-hidden">Password</label>
        //                         <input type="password" class="form-control" id="inputPassword2" placeholder="Password">
        //                     </div>
        //                     <div class="col-auto">
        //                         <input type="hidden" id="requestId" value="' . $row["id"] . '">
        //                         <button type="submit" class="btn btn-primary">Confirm identity</button>
        //                     </div>
        //                 </div>
        //             </div>
        //         </form>
        //     </div>
        // </div>';
    }
} else {
    echo "<p>Error in SQL query: " . $conn->error . "</p>";
}
?>

</div>
</div>

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

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize datepicker
    $('#datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });
});

function showDeleteModal(requestId) {
    $('#deleteModal').modal('show');
    // Set the request ID in the modal
    document.getElementById("deleteModal").setAttribute("data-request-id", requestId);
}

function hideDeleteModal() {
    $('#deleteModal').modal('hide');
}

function deleteRequest() {
    var requestId = document.getElementById("deleteModal").getAttribute("data-request-id");
    console.log("Received Request ID:", requestId);

    var url = "delete_request.php";

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

    xhr.send("id=" + requestId);

    // Hide the modal after deleting
    hideDeleteModal();
    location.reload();
}

$('#datepicker').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    todayHighlight: true
});

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

</script>
<?php
$user_id = $_SESSION['user_id'];

$sqlAdmin = "SELECT email, password FROM admin WHERE id = ?";
$stmtAdmin = $conn->prepare($sqlAdmin);
$stmtAdmin->bind_param("i", $user_id);

$expectedEmail = $expectedPassword = "";

if ($stmtAdmin->execute()) {
    $resultAdmin = $stmtAdmin->get_result();

    while ($rowAdmin = $resultAdmin->fetch_assoc()) {
        $expectedEmail = $rowAdmin['email'];
        $expectedPassword = $rowAdmin['password'];
    }
} else {
    echo "Error fetching admin data: " . $stmtAdmin->error;
}

$stmtAdmin->close();
$conn->close();
?>

<script>
function updateStatus(event, requestId) {
    event.preventDefault();

    var email = document.getElementById("staticEmail2").value;
    var password = document.getElementById("inputPassword2").value;
    var requestId = document.getElementById("requestId").value;

    var url = "check_credentials.php";
    var data = "email=" + email + "&password=" + password;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var response = xhr.responseText;
                if (response === "success") {
                    // Call the updateStatus function if credentials are correct
                    updateStatusRequest(requestId, "approved");
                } else {
                    // Display error message in another modal
                    alert("Incorrect email or password");
                }
            } else {
                // Handle error cases
                alert("Error updating status: " + xhr.statusText);
            }
        }
    };

    xhr.send(data);
}

document.getElementById("formModal").addEventListener("submit", function(event) {
    updateStatus(event, <?php echo $row["id"]; ?>);
});
</script>

</body>
</html>
