<?php
// Assume you have a database connection here
include('../dbConnect.php');

// Initialize $selectedDate and $resultAllFacilities to avoid undefined variable warnings
$selectedDate = $resultAllFacilities = null;

// Check if the date is set
if (isset($_POST['selectedDate'])) {
    $selectedDate = $_POST['selectedDate'];
    $subject = $_POST['subject'];
    $purpose = $_POST['purpose'];


    // Fetch available facility IDs for the selected date from the database
    $sql = "SELECT facility_id FROM facility_schedules WHERE scheduled_date = '$selectedDate'";
    $result = $conn->query($sql);

    if ($result) {
        $unavailableFacilities = [];
        while ($row = $result->fetch_assoc()) {
            $unavailableFacilities[] = $row['facility_id'];
        }

        // Fetch all facilities except the unavailable ones
        if (!empty($unavailableFacilities)) {
            $sqlAllFacilities = "SELECT * FROM facility WHERE id NOT IN (" . implode(',', $unavailableFacilities) . ")";
            $resultAllFacilities = $conn->query($sqlAllFacilities);
        } else {
            // No facilities are unavailable, so all facilities are available
            $sqlAllFacilities = "SELECT * FROM facility";
            $resultAllFacilities = $conn->query($sqlAllFacilities);
        }

        if ($resultAllFacilities !== false && $resultAllFacilities->num_rows > 0) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Form - Page 2</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <form action="test3.php" method="post">
            <h2>Page 2: Select Facilities</h2>
            <p>Selected Date: <?php echo htmlspecialchars($selectedDate); ?></p>
            <?php
                session_start();
                $_SESSION['user_id'] = 1;
                $_SESSION['date'] = $selectedDate;
                $_SESSION['subject'] = $subject;
                $_SESSION['purpose'] = $purpose;

                // Assuming $selectedFacilityIDs is an array containing the selected facility IDs
                $_SESSION['selectedFacilityIDs'] = isset($_POST['selectedFacilities']) ? $_POST['selectedFacilities'] : [];
            ?>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php while ($rowFacility = $resultAllFacilities->fetch_assoc()): ?>
                    <div class="col">
                        <div class="card">
                            <img src="../resources/facility_imgs/<?php echo $rowFacility['image_name']; ?>.png" class="card-img-top" alt="Facility Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $rowFacility['facility_name']; ?></h5>
                                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="selectedFacilities[]" value="<?php echo $rowFacility['id']; ?>">
                                    <label class="form-check-label">Add</label>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Next</button>
        </form>
    </div>
</body>
</html>
<?php
        } else {
            echo "No available facilities for the selected date.";
        }
    } else {
        // Output the error details
        echo "Error in query: " . $conn->error;
    }
} 
// else {
//     header("Location: page1.php");
//     exit();
// }
?>
