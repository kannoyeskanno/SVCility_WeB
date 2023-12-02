<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Request</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <style>
        body, html {
            height: 100%;
            margin: 0;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            overflow-y: auto; /* Allow vertical scrolling */
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
            display: flex; /* Make it a flex container */
            justify-content: space-between; /* Space between the two sides */
        }

        .left-side {
            flex: 1; /* Take up 50% of the width */
            text-align: left; /* Align text to the left */
        }

        .right-side {
            flex: 1; /* Take up 50% of the width */
            text-align: left; /* Align text to the left */
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
            flex-direction: column; /* Stack items vertically */
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .header {
            background-color: #007BFF;
            color: #fff;
            padding: 10px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .body {
            padding: 20px;
        }

        .footer {
            background-color: #007BFF;
            color: #fff;
            padding: 10px;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .image-container {
            width: 100%;
            height: 100px;
            background-size: cover;
            background-position: center;
            margin-bottom: 10px;
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

                // Display associated facilities
                $facilityIds = explode(',', $row['facility_id']);

                if ($facilityIds != null) {
                    echo '<h2>Facility</h2>';
                    foreach ($facilityIds as $facilityId) {
                        $facilitySql = "SELECT * FROM facility WHERE id = ?";
                        $facilityStmt = $conn->prepare($facilitySql);

                        if ($facilityStmt) {
                            $facilityStmt->bind_param("s", $facilityId);
                            $facilityStmt->execute();
                            $facilityResult = $facilityStmt->get_result();

                            if ($facilityResult->num_rows > 0) {
                                $facilityRow = $facilityResult->fetch_assoc();

                                echo '<div class="facility-box">';
                                echo '<div class="header">' . $facilityRow['facility_name'] . '</div>';
                                echo '<div class="body">';
                                
                                $image_name = $facilityRow["image_name"];
                                $imagePath = '../resources/facility_imgs/' . $image_name . '.png';

                                echo '<div class="image-container" style="background-image: url(' . $imagePath . ');"></div>';
                                echo '<p><strong>Facility Name:</strong> ' . $facilityRow["facility_name"] . '</p>';
                                echo '<p><strong>Subject:</strong> ' . $row['subject'] . '</p>';
                                echo '<p><strong>Purpose:</strong> ' . $row['purpose'] . '</p>';
                                echo '</div>';
                                echo '<div class="footer">'; // Client Information
                                echo '<h5>Client Information</h5>';
                                
                                // Fetch client details
                                $client = "SELECT * FROM client WHERE id = ?";
                                $clientStmt = $conn->prepare($client);

                                if ($clientStmt) {
                                    $clientStmt->bind_param("i", $row['user_id']);
                                    $clientStmt->execute();
                                    $resultClient = $clientStmt->get_result();

                                    if ($resultClient->num_rows > 0) {
                                        $rowClient = $resultClient->fetch_assoc();
                                        echo '<ul class="list-group list-group-flush">';
                                        echo '<li class="list-group-item"><strong>Name:</strong> ' . htmlspecialchars($rowClient['fname'] . ' ' . $rowClient['lname']) . '</li>';
                                        echo '<li class="list-group-item"><strong>Email:</strong> ' . htmlspecialchars($rowClient['email']) . '</li>';
                                        echo '<li class="list-group-item"><strong>Phone:</strong> ' . htmlspecialchars($rowClient['contact_number']) . '</li>';
                                        echo '</ul>';
                                    } else {
                                        echo "<p>No client found with ID: " . $row['user_id'] . "</p>";
                                    }
                                } else {
                                    echo "<p>Error in client query preparation: " . $conn->error . "</p>";
                                }

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
                }
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

</body>
</html>
