<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility Logs</title>
    <!-- Add Bootstrap CSS link -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
        }

        .container {
            border: solid;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .facility-image {
            width: 100%;
            height: 200px;
            background-size: cover;
            background-position: center;
            margin-bottom: 20px;
        }

        .log-entry {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #fff; /* White background for better readability */
            border-radius: 8px; /* Add rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add a subtle box shadow */
        }

        .log-entry table {
            width: 100%;
        }

        .log-entry th, .log-entry td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .log-entry th {
            background-color: #f2f2f2;
        }

        .log-entry td:first-child {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">

<?php
// Include your database connection code
include('../dbConnect.php');

// Initialize variables
$fname = $lname = $office_org = '';
$facility_name = '';

if (isset($_GET['facilityId'])) {
    $facilityId_ = $_GET['facilityId'];

    // Fetch facility name based on facilityId
    $sqlFaci = "SELECT * FROM facility WHERE id = ?";
    $stmtFaci = $conn->prepare($sqlFaci);

    if ($stmtFaci) {
        $stmtFaci->bind_param("s", $facilityId_);
        $stmtFaci->execute();
        $resultFaci = $stmtFaci->get_result();

        if ($resultFaci->num_rows > 0) {
            $rowFaci = $resultFaci->fetch_assoc();
            $facility_name = $rowFaci['facility_name'];
            $image_name = $rowFaci['image_name'];

            $imagePath = '../resources/facility_imgs/' . $image_name . '.png';

            echo '<div class="header">';
            echo '<h2>Facility Schedules for ' . $facility_name . '</h2>';
            echo '</div>';
            echo '<div class="facility-image" style="background-image: url(' . $imagePath . ');"></div>';

        } else {
            echo "<p>No matching records found in the facility table.</p>";
        }

        $stmtFaci->close();
    } else {
        echo "<p>Error in facility query preparation: " . $conn->error . "</p>";
    }
}

// Fetch all data from the facility_schedules table
$sql = "SELECT * FROM facility_schedules";
$result = $conn->query($sql);

if ($result === false) {
    echo "Error: " . $conn->error;
} else {

    echo '<div class="log-entry">';
    echo '<table class="table">';
    echo '<thead class="thead-light">'; 
    echo '<tr>';
    echo '<th>Office/Org</th>';
    echo '<th>Name</th>';
    echo '<th>Date</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $facilityIds = $row["facility_id"];
            $scheduleDate = $row["scheduled_date"];
            $reservedBy = $row["user_id"];

            $fname = $lname = $office_org = '';

            $sqlClient = "SELECT * FROM client WHERE id = ?";
            $stmtClient = $conn->prepare($sqlClient);

            if ($stmtClient) {
                $stmtClient->bind_param("i", $reservedBy);
                $stmtClient->execute();
                $resultClient = $stmtClient->get_result();

                if ($resultClient->num_rows > 0) {
                    while ($rowClient = $resultClient->fetch_assoc()) {
                        $fname = $rowClient['fname'];
                        $lname = $rowClient['lname'];
                        $office_org = $rowClient['office_org'];
                    }
                }
            }
            
            $facilityIdArray = explode(',', $facilityIds);
            foreach ($facilityIdArray as $facilityId) {
                if ($facilityId == $facilityId_) {
                    echo '<tr>';
                    echo '<td>' . $office_org . '</td>';
                    echo '<td>' . $fname . ' ' . $lname . '</td>';
                    echo '<td>' . $scheduleDate . '</td>';
                    echo '</tr>';
                }
            }
        }
    } else {
        echo "<tr><td colspan='3'>No records found in the facility_schedules table.</td></tr>";
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

// Close the database connection
$conn->close();
?>

</div>

<!-- Add Bootstrap JS and Popper.js (required for Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
