<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Document</title>
    <style>
        /* Add your custom styles here */
        .card-header {
            background-color: #007bff; /* Bootstrap primary color */
            color: #fff;
        }

        .card-body {
            background-color: #f8f9fa; /* Bootstrap light grey background color */
        }

        #accountStatus {
            font-weight: bold;
        }

        .form-switch {
            margin-top: 10px;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        include '../dbConnect.php';

        $sql = "SELECT * FROM equipment_logs WHERE number_missing > 0 OR bad_condition > 0";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $cid = $row['user_id'];
                $rid = $row['request_id'];
                $equipmentId = $row['equipment_id'];

                $sqlReq = "SELECT * FROM request WHERE id = $rid";
                $resultReq = $conn->query($sqlReq);

                $sqlClient = "SELECT * FROM client WHERE id = $cid";
                $resultClient = $conn->query($sqlClient);

                $sqlEquipment = "SELECT * FROM equipment WHERE id = $equipmentId";
                $resultEquipment = $conn->query($sqlEquipment);

                // Start the combined card
                echo '<div class="card mb-3">';
                echo '<div class="card-header">';
                echo '<h5 class="mb-0">Issue ticket</h5>';
                echo '</div>';
                echo '<div class="card-body row">';

                // Client details on the left
                echo '<div class="col-md-6">';
                if ($resultClient && $resultClient->num_rows > 0) {
                    while ($rowClient = $resultClient->fetch_assoc()) {
                        // Client details
                        echo '<h5 class="card-title">Client Name: ' . htmlspecialchars($rowClient["fname"] . " " . $rowClient["lname"]) . '</h5>';
                        echo '<p class="card-text">Client Email: ' . htmlspecialchars($rowClient["email"]) . '</p>';
                        echo '<p class="card-text">Client Contact: ' . htmlspecialchars($rowClient["contact_number"]) . '</p>';
                        echo '<p class="card-text">Account Status: <span id="accountStatus">' . htmlspecialchars($rowClient["account_stat"]) . '</span></p>';
                       
                        // Display switch
                        echo '<div class="form-check form-switch">';
                        echo '<input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault"';

                        // Check the current account status and set the switch accordingly
                        if ($rowClient["account_stat"] == 'accept') {
                            echo ' checked';
                        }

                        echo '>';
                        echo '<label class="form-check-label" for="flexSwitchCheckDefault">Toggle Account Status</label>';
                        echo '</div>';
                    }
                } else {
                    echo "Error: " . $sqlClient . "<br>" . $conn->error . "<br>" . mysqli_error($conn);
                }
                echo '</div>'; // End left column

                // Admin details and specified information on the right
                echo '<div class="col-md-6">';
                // Display admin information if available
                if ($resultReq && $resultReq->num_rows > 0) {
                    while ($rowReq = $resultReq->fetch_assoc()) {
                        $adminId = $rowReq['admin_id'];

                        // Admin details
                        echo '<p class="card-text">approved by: ' . getAdminName($conn, $adminId) . '</p>';
                    }
                } else {
                    echo "Error: " . $sqlReq . "<br>" . $conn->error . "<br>" . mysqli_error($conn);
                }

                // Display equipment information if available
                if ($resultEquipment && $resultEquipment->num_rows > 0) {
                    while ($rowEquipment = $resultEquipment->fetch_assoc()) {
                        echo '<p class="card-text">Equipment Name: ' . htmlspecialchars($rowEquipment["equipment_name"]) . '</p>';
                       
                    }
                } else {
                    echo "Error: " . $sqlEquipment . "<br>" . $conn->error . "<br>" . mysqli_error($conn);
                }

                // Display specified information
                echo '<p class="card-text">Number Missing: ' . $row["number_missing"] . '</p>';
                echo '<p class="card-text">Returned Bad Condition: ' . $row["bad_condition"] . '</p>';
                echo '</div>'; // End right column

                // End the combined card
                echo '</div></div>';
            }
        } else {
            echo "No records found.";
        }


        $conn->close();

        // Function to get admin name
        function getAdminName($conn, $adminId) {
            $sqlAdmin = "SELECT * FROM admin WHERE id = $adminId";
            $resultAdmin = $conn->query($sqlAdmin);

            if ($resultAdmin && $resultAdmin->num_rows > 0) {
                while ($rowAdmin = $resultAdmin->fetch_assoc()) {
                    return htmlspecialchars($rowAdmin["fname"] . " " . $rowAdmin["lname"]);
                }
            } else {
                return "Unknown Admin";
            }
        }
        ?>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(document).ready(function() {
        // Handle the switch change event for the main content
        $('#flexSwitchCheckDefault').change(function() {
            // Get the current account status
            var currentStatus = $('#accountStatus').text();
            // Send an AJAX request to update the account status
            $.ajax({
                type: 'POST',
                url: 'update_account_status.php', // Replace with the actual path to your update script
                data: {id: <?php echo $cid; ?>, currentStatus: currentStatus},
                success: function(response) {
                    // Update the displayed status
                    $('#accountStatus').text(response);
                    // Update the footer status
                    $('#accountStatusFooter').text(response);
                },
                error: function(error) {
                    console.log('Error updating account status: ' + error);
                }
            });
        });
    });
    </script>
</body>
</html>
