<?php
include '../dbConnect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize user input
    $selectedDate = isset($_POST['datepicker']) ? htmlspecialchars($_POST['datepicker']) : '';
    $selectedFacility = isset($_POST['facilitySelect']) ? htmlspecialchars($_POST['facilitySelect']) : '';
    
    // Validate and sanitize sort order
    $sortOrder = ($_POST['sortRadio'] === 'asc') ? 'asc' : 'desc';

    // Prepare the SQL query
    $sql = "SELECT * FROM request WHERE status = 'submitted'";

    // Check if a date is selected
    if (!empty($selectedDate)) {
        $sql .= " AND date = ?";
    }

    // Check if a facility is selected
    if (!empty($selectedFacility)) {
        if (!empty($selectedDate)) {
            $facilityParam = "%,$selectedFacility,%";
            $sql .= " AND facility_id LIKE ?";
        } else {
            // If only facility is selected
            $facilityParam = "%,$selectedFacility,%";
            $sql .= " AND facility_id LIKE ?";
        }
    }

    $sql .= " ORDER BY date " . $sortOrder;

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Check if a date is selected
    if (!empty($selectedDate)) {
        $stmt->bind_param("s", $selectedDate);
    }

    // Check if a facility is selected
    if (!empty($selectedFacility)) {
        if (!empty($selectedDate)) {
            $stmt->bind_param("s", $facilityParam);
        } else {
            $stmt->bind_param("s", $facilityParam);
        }
    }

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Debugging: Output the parameters
    echo "Executed Query Parameters: Date: $selectedDate, Facility: $selectedFacility";

    // Debugging: Output the number of rows returned
    echo "Number of Rows: " . $result->num_rows;

    // Continue with the rest of your code...
}
?>
