<?php
include("../dbConnect.php");

if (isset($_GET['query'])) {
    $input = $_GET['query'];

    $query = "SELECT id, facility_name, image_name FROM facility WHERE facility_name LIKE ? 
              UNION
              SELECT id, equipment_name, image_name FROM equipment WHERE equipment_name LIKE ?
              LIMIT 3";

    if ($stmt = $conn->prepare($query)) {
        $param = "%$input%";
        $stmt->bind_param("ss", $param, $param);

        // Execute the statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<div class='facility-container'>";
                while ($row = $result->fetch_assoc()) {
                    echo "<a href='readmoreSearch.php?facilityID={$row['id']}' class='facility-item'>";
                    
                    $tablePrefix = isset($row['facility_name']) ? 'facility' : 'equipment';
                    echo "<div class='image-container' style='background-image: url(\"../resources/{$tablePrefix}_imgs/{$row['image_name']}.png\")'></div>";

                    echo "<div class='facility-details'>";
                    echo "<p class='facility-name'>{$row['facility_name']}</p>";
                  
                    echo "</div>";
                    
                    echo "</a>";
                }
                echo "</div>";
            } else {
                echo "No results found";
            }
        } else {
            echo "Error executing query: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

$conn->close();
?>
