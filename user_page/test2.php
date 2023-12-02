<?php
session_start();

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../resources/logo/svcility_icon.png" type="image/x-icon">


</head>
<body>
<?php
    include '../header.php';
    ?>
   
   
   <div class="container mt-5">
   <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
    <div class="progress-bar w-50"></div>
    </div>
    <form action="test3.php" method="post">
        <h2>Available Facilities</h2>
        <p>Selected Date: <?php echo htmlspecialchars($selectedDate); ?></p>
        <?php
            $_SESSION['date'] = $selectedDate;
            $_SESSION['subject'] = $subject;
            $_SESSION['purpose'] = $purpose;

            $user_id = $_SESSION["user_id"];

            // Assuming $selectedFacilityIDs is an array containing the selected facility IDs
            $_SESSION['selectedFacilityIDs'] = isset($_POST['selectedFacilities']) ? $_POST['selectedFacilities'] : [];
        ?>
        <div class="card-group">
            <?php while ($rowFacility = $resultAllFacilities->fetch_assoc()): ?>
                <div class="card">
                    <img src="../resources/facility_imgs/<?php echo $rowFacility['image_name']; ?>.png" class="card-img-top" alt="Facility Image" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $rowFacility['facility_name']; ?></h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                    <div class="card-footer">
                        <div class="form-check">
                            <input style="color: black; background-color: gray;" type="radio" class="form-check-input" name="selectedFacilities[]" value="<?php echo $rowFacility['id']; ?>">

                            <!-- <input style="color: black; background-color: gray;" type="checkbox" class="form-check-input" name="selectedFacilities[]" value="<?php echo $rowFacility['id']; ?>"> -->
                            <label class="form-check-label">Add</label>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <button type="submit" class="btn btn-outline-primary mt-3">Next</button>
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
?>
