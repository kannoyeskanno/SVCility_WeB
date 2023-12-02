<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Request</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
        }

        header {
            background-color: #007BFF;
            color: #fff;
            padding: 20px;
        }

        section {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #007BFF;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        .facility-box {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .image-container {
            flex: 1;
        }

        .image {
            width: 100%;
            height: 100px; /* Adjust height as needed */
            background-size: cover;
            background-position: center;
        }

        .text-container {
            flex: 2;
            padding: 10px;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .button {
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        footer {
            background-color: #007BFF;
            color: #fff;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .file-link {
            color: #007BFF;
            text-decoration: none;
            margin-top: 10px;
            display: inline-block;
        }
    </style>
</head>
<body>

<header>
    <h1>Request Details</h1>
</header>



<section>
    <?php
    include '../dbConnect.php';

    // Assuming you receive the request_id through GET
    if (isset($_GET['request_id'])) {
        $requestId = $_GET['request_id'];

        // Fetch details of the selected request
        $sql = "SELECT * FROM request WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $requestId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Display request details
                echo '<h2>Request Information</h2>';
                $subject = $row['subject'];
                $purpose = $row['purpose'];
                $date = $row['date'];
                $user_id = $row['user_id'];

                // $time = $row['time'];
                $file = $row['file'];
                $equipment = $row['equipment_qty'];


                $client = "SELECT * FROM client WHERE id = ?";
                $clientStmt = $conn->prepare($client);
                
                if ($clientStmt) {
                    $clientStmt->bind_param("i", $user_id);
                    $clientStmt->execute();
                    $resultClient = $clientStmt->get_result();
                
                    if ($resultClient->num_rows > 0) {
                        $rowClient = $resultClient->fetch_assoc();
                ?>
                       
                            <div class="container mt-5">
                                <h1>Client Details</h1>
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Client Information</h5>
                                        <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Name:</strong> <?php echo isset($rowClient['fname']) ? $rowClient['fname'] : 'N/A'; ?></li>
                                        <li class="list-group-item"><strong>Email:</strong> <?php echo isset($rowClient['email']) ? $rowClient['email'] : 'N/A'; ?></li>
                                        <li class="list-group-item"><strong>Phone:</strong> <?php echo isset($rowClient['contact_number']) ? $rowClient['contact_number'] : 'N/A'; ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                
                    
                <?php
                    } else {
                        echo "<p>No client found with ID: $user_id</p>";
                    }
                } else {
                    echo "<p>Error in client query preparation: " . $conn->error . "</p>";
                }
                

                $facilityIds = explode(',', $row['facility_id']);

                if ($facilityIds != null) {
                    echo '<h2>Facility</h2>';
                    echo '<ul>';
                    echo '</ul>';
                }
              
                foreach ($facilityIds as $facilityId) {
                    $facilitySql = "SELECT * FROM facility WHERE id = ?";
                    $facilityStmt = $conn->prepare($facilitySql);

                    if ($facilityStmt) {
                        $facilityStmt->bind_param("s", $facilityId);
                        $facilityStmt->execute();
                        $facilityResult = $facilityStmt->get_result();

                        if ($facilityResult->num_rows > 0) {
                            $facilityRow = $facilityResult->fetch_assoc();

                            echo '<li>' . $facilityRow['facility_name'] . '</li>';
                            
                            $image_name = $facilityRow["image_name"];
                            $imagePath = '../resources/facility_imgs/' . $image_name . '.png';

                            echo '<div class="facility-box">';
                            echo '<div class="image-container">';
                            echo '<div class="image" style="background-image: url(' . $imagePath . ');"></div>';
                            echo '</div>';
                            echo '<div class="text-container">';
                            echo '<p><strong>Facility Name:</strong> ' . $facilityRow["facility_name"] . '</p>';
                            echo '<p><strong>Subject:</strong> ' . $subject . '</p>';
                            echo '<p><strong>Purpose:</strong> ' . $purpose . '</p>';
                            echo '</div>';
                            echo '</div>';



                           
                        } else {
                            continue;
                        }

                        $facilityStmt->close();
                    } else {
                        echo '<li>Error in facility query preparation</li>';
                    }
                }
          
                $fileSql = "SELECT * FROM files WHERE id = ?";
                $fileStmt = $conn->prepare($fileSql);

                if ($fileStmt) {
                    $fileStmt->bind_param("i", $file);
                    $fileStmt->execute();
                    $result = $fileStmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();

                        $filePath = $row['file_path'];

                        echo '<p><a class="file-link" href="' . $filePath . '" target="_blank">View File</a></p>';
                    }
                }
                echo $subject;
                echo $purpose;
                echo $date;

                echo '<div class="button-container">';
                echo '<button class="button">Check</button>';
                echo '<button class="button">Delete</button>';
                echo '</div>';

                // Display associated equipment
       



if ($equipment != null) {
    echo '<h2>Equipment</h2>';
    echo '<ul>';


    echo '</ul>';
}

    $equipmentArray = explode(',', rtrim($equipment, ','));

    foreach ($equipmentArray as $index => $item) {
        $equipmentValues = explode(':', $item);

        if (count($equipmentValues) >= 2) {
            list($equipment_id, $qty) = $equipmentValues;
            echo "<li>$qty x ";
        } else {
            continue; // Skip the current iteration if the format is invalid
        }

        $equipmentSql = "SELECT * FROM equipment WHERE id = ?";
        $equipmentStmt = $conn->prepare($equipmentSql);

        try {
            if ($equipmentStmt) {
                $equipmentStmt->bind_param("s", $equipment_id);
                $equipmentStmt->execute();
                $equipmentResult = $equipmentStmt->get_result();

                if ($equipmentResult->num_rows > 0) {
                    $equipmentRow = $equipmentResult->fetch_assoc();
                    echo $equipmentRow['equipment_name'] . '</li>';
                } else {
                    throw new Exception('Equipment not found');
                }

                $equipmentStmt->close();
            } else {
                throw new Exception('Error in equipment query preparation');
            }
        } catch (Exception $e) {
            echo '<li>Error: ' . $e->getMessage() . '</li>';
        }
    }




echo '</ul>';

            } else {
                echo '<p>Request not found</p>';
            }

            $stmt->close();
        } else {
            echo '<p>Error in query preparation</p>';
        }
    } else {
        echo '<p>Invalid request</p>';
    }

    $conn->close();
    ?>
</section>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
