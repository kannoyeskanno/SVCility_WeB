<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            height: 100vh;
        }

        .facility-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            width: 100%;
            height: 100vh;

        }

        .facility-box {
            border: 1px solid #ddd;
            padding: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            text-align: center;
            width: 100%;

        }

        .image-container {
            width: 100%;
            height: 200px;
            overflow: hidden;
            border-radius: 8px 8px 0 0;
        }

        .image {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
        }

        .text-container {
            padding: 10px;
        }

        p {
            margin: 0;
        }
    </style>
</head>
<body>

<?php

// Include your database connection file
include '../dbConnect.php';

// Assuming the selected date is sent in the request
if (isset($_GET['selected_date'])) {
    $selectedDate = $_GET['selected_date'];

    // Fetch activities from the database based on the selected date
    $sql = "SELECT * FROM request WHERE date = ? AND status = 'approved'";
    $stmt = $conn->prepare($sql);



    if ($stmt) {
        $stmt->bind_param("s", $selectedDate);
        $stmt->execute();
        $result = $stmt->get_result();

        // Display facilities regardless of whether there are activities
        echo '<div class="facility-container">';

        // Check if there are activities
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $date = $row["date"];
                // $time = $row["start_time"];
                $facilityIds = $row["facility_id"];

                $facilityIdArray = explode(',', $facilityIds);
                foreach ($facilityIdArray as $facilityId) {
                    $sqlFaci = "SELECT * FROM facility WHERE id = ?";
                    $stmtFaci = $conn->prepare($sqlFaci);

                    

                    if ($stmtFaci) {
                        $stmtFaci->bind_param("i", $facilityId);
                        $stmtFaci->execute();
                        $resultFaci = $stmtFaci->get_result();

                        if ($resultFaci->num_rows > 0) {
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
                            // echo "<p>Error: Facility not found for facility_id: $facilityId</p>";
                        }

                        $stmtFaci->close();
                    } else {
                        echo "<p>Error in facility query preparation: " . $conn->error . "</p>";
                    }
                }
            }
        } else {
            echo "<p>No activities found for the selected date</p>";
        }

        echo '</div>';

        $stmt->close();
    } else {
        echo "<p>Error in query preparation: " . $conn->error . "</p>";
    }

    $conn->close();
} else {
    echo "<p>Invalid request</p>";
}
?>
    
</body>
</html>
