<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Add DataTables CSS link -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.css">
    <!-- Add jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Add DataTables JS and DataTables Bootstrap JS -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.js"></script>
    <style>
        .future-date {
            color: yellow;
        }
    </style>
</head>
<body>

<?php
// Include your database connection code
include('../dbConnect.php');

if (isset($_GET['facilityId'])) {
    $facilityId = $_GET['facilityId'];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM facility_schedules WHERE facility_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $facilityId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            echo "Error: " . $conn->error;
        } else {
            echo '<div class="container text-center">';
            echo '<table class="table table-bordered" id="schedulesTable">';
            echo '<thead class="thead-light">';
            echo '<tr>';
            echo '<th class="table-primary">#</th>';
            echo '<th class="table-success">Scheduled Date</th>';
            echo '<th class="table-info">Name</th>';
            echo '<th class="table-warning">Office</th>';
            // Add more columns as needed
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            $rowNumber = 1;

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $scheduledDate = $row["scheduled_date"];
                    $userId = $row["user_id"];

                    // Use prepared statement to prevent SQL injection
                    $sqlUser = "SELECT * FROM client WHERE id = ?";
                    $stmtUser = $conn->prepare($sqlUser);

                    if ($stmtUser) {
                        $stmtUser->bind_param("i", $userId);
                        $stmtUser->execute();
                        $resultUser = $stmtUser->get_result();

                        if ($resultUser === false) {
                            echo "Error: " . $conn->error;
                        } else {
                            while ($userRow = $resultUser->fetch_assoc()) {
                                // Check if the scheduled date is in the future
                                $isFutureDate = strtotime($scheduledDate) > time();

                                echo '<tr>';
                                echo '<th scope="row" class="table-primary">' . $rowNumber . '</th>';
                                echo '<td class="table-success ' . ($isFutureDate ? 'future-date' : '') . '">' . $scheduledDate . '</td>';
                                echo '<td class="table-info">' . $userRow['fname'] . ' ' . $userRow['lname'] . '</td>';
                                echo '<td class="table-warning">' . $userRow['office_org'] . '</td>';
                                // Add more columns as needed
                                echo '</tr>';

                                $rowNumber++;
                            }
                        }
                        $stmtUser->close();
                    }
                }
            } else {
                echo "<tr><td colspan='4'>No records found in the facility_schedules table for the specified facility.</td></tr>";
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        }
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>

<!-- Initialize DataTables -->
<script>
    $(document).ready(function () {
        $('#schedulesTable').DataTable();
    });
</script>

</body>
</html>
