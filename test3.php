

<?php
session_start();

include('dbConnect.php');

if (isset($_SESSION['date']) && isset($_POST['selectedFacilities'])) {
    $selectedDate = $_SESSION['date'];
    $selectedFacilityIDs = $_POST['selectedFacilities'];

    // Fetch details of the selected facilities
    $sqlSelectedFacilities = "SELECT * FROM facility WHERE id IN (" . implode(',', $selectedFacilityIDs) . ")";
    $resultSelectedFacilities = $conn->query($sqlSelectedFacilities);

    if ($resultSelectedFacilities) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Form - Page 3</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
    <div class="container mt-5">
        <h2>Page 3: Selected Facilities</h2>
        <p>Selected Date: <?php echo htmlspecialchars($selectedDate); ?></p>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php while ($rowFacility = $resultSelectedFacilities->fetch_assoc()): ?>
                <div class="col">
                    <div class="card">
                        <img src="../resources/facility_imgs/<?php echo $rowFacility['image_name']; ?>.png" class="card-img-top" alt="Facility Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $rowFacility['facility_name']; ?></h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
<?php
    } else {
        echo "Error fetching selected facilities: " . $conn->error;
    }
} 
?>








<?php
// Include your database connection here
include('../dbConnect.php');

try {
    // Check if the session date is set
    if (isset($_SESSION['date'])) {
        $selectedDate = $_SESSION['date'];

        // Fetch all approved requests for the selected date
        $sql = "SELECT * FROM request WHERE status = 'approved' AND date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $selectedDate);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            throw new Exception('Error in the query: ' . $stmt->error);
        } elseif ($result->num_rows > 0) {
            // Create an array to store the total quantity requested for each equipment
            $totalQuantity = [];

            while ($row = $result->fetch_assoc()) {
                $equipmentDetails = explode(',', $row['equipment_qty']);

                foreach ($equipmentDetails as $equipment) {
                    if (strpos($equipment, ':') !== false) {
                        list($equipmentID, $quantity) = explode(':', $equipment);

                        // Initialize the total quantity for each equipment if not set
                        if (!isset($totalQuantity[$equipmentID])) {
                            $totalQuantity[$equipmentID] = 0;
                        }

                        $totalQuantity[$equipmentID] += $quantity;
                    }
                }
            }

            echo "<input type='hidden' id='selectedDate' value='$selectedDate'>";

            // Display a table with the fetched data
            echo '<div class="container mt-5">';
            echo '<table class="table table-bordered">';
            echo '<thead class="table-dark">';
            echo '<tr><th scope="col">Remaining Quantity</th><th scope="col">Equipment Image</th><th scope="col">Request Qty</th><th scope="col">Action</th></tr>';
            echo '</thead>';
            echo '<tbody>';

            // Fetch all equipment
            $equipmentQuery = "SELECT * FROM equipment";
            $equipmentResult = $conn->query($equipmentQuery);

            if ($equipmentResult === false) {
                throw new Exception('Error in the query: ' . $conn->error);
            }

            while ($equipmentRow = $equipmentResult->fetch_assoc()) {
                $equipmentID = $equipmentRow['id'];
                $remainingQuantity = $equipmentRow['qty'] - ($totalQuantity[$equipmentID] ?? 0);
                $imagePath = '../resources/equipment_imgs/' . $equipmentRow['image_name'] . '.png';

                // Display data in the table
                echo '<tr>';
                echo '<td data-remaining-quantity="' . $remainingQuantity . '">' . $remainingQuantity . '</td>';
                echo '<td><img src="' . $imagePath . '" alt="' . $equipmentRow['equipment_name'] . '" style="max-width: 50px; max-height: 50px;"></td>';
                echo '<td>';
                echo '<div class="quantity">';
                echo '<input type="checkbox" class="form-check-input" name="selectedEquipment[]" value="' . $equipmentRow['id'] . '">';
                echo '<label class="form-check-label">Select</label>';
                echo '</div>';
                echo '</td>';
                echo '<td>';
                echo '<div class="quantity">';
                echo '<button class="btn btn-primary plus" data-reservation-id="' . $equipmentRow['id'] . '"><i class="bi bi-plus"></i></button>';
                echo '<input type="number" class="quantity-input form-control" value="' . $remainingQuantity . '" min="1" max="' . $remainingQuantity . '" id="quantity_' . $equipmentRow["id"] . '">';
                echo '<button class="btn btn-primary minus" data-reservation-id="' . $equipmentRow['id'] . '"><i class="bi bi-dash"></i></button>';
                echo '</div>';
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';

           
        } else {
            echo "<input type='hidden' id='selectedDate' value='$selectedDate'>";

            echo '<div class="container mt-5">';
            echo '<table class="table table-bordered">';
            echo '<thead class="table-dark">';
            echo '<tr><th scope="col">Remaining Quantity</th><th scope="col">Equipment Image</th><th scope="col">Request Qty</th><th scope="col">Action</th></tr>';
            echo '</thead>';
            echo '<tbody>';
        
            $equipmentQuery = "SELECT * FROM equipment";
            $equipmentResult = $conn->query($equipmentQuery);
        
            if ($equipmentResult === false) {
                throw new Exception('Error in the query: ' . $conn->error);
            }
        
            while ($equipmentRow = $equipmentResult->fetch_assoc()) {
                $equipmentID = $equipmentRow['id'];
                $imagePath = '../resources/equipment_imgs/' . $equipmentRow['image_name'] . '.png';
        
                // Display data in the table
                echo '<tr>';
                echo '<td data-remaining-quantity="' . $equipmentRow['qty'] . '">' . $equipmentRow['qty'] . '</td>';
                echo '<td><img src="' . $imagePath . '" alt="' . $equipmentRow['equipment_name'] . '" style="max-width: 50px; max-height: 50px;"></td>';
                echo '<td>';
                echo '<div class="quantity">';
                echo '<input type="checkbox" class="form-check-input" name="selectedEquipment[]" value="' . $equipmentRow['id'] . '">';
                echo '<label class="form-check-label">Select</label>';
                echo '</div>';
                echo '</td>';
                echo '<td>';
                echo '<div class="quantity">';
                echo '<button class="btn btn-primary plus" data-reservation-id="' . $equipmentRow['id'] . '"><i class="bi bi-plus"></i></button>';
                echo '<input type="number" class="quantity-input form-control" value="' . $equipmentRow['qty'] . '" min="1" max="' . $equipmentRow['qty'] . '" id="quantity_' . $equipmentRow["id"] . '">';
                echo '<button class="btn btn-primary minus" data-reservation-id="' . $equipmentRow['id'] . '"><i class="bi bi-dash"></i></button>';
                echo '</div>';
                echo '</td>';
                echo '</tr>';
            }
        
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        }
        

        $stmt->close();
    } 
    // else {
    //     header("Location: page1.php");
    //     exit();
    // }
} catch (Exception $e) {
    echo 'An error occurred: ' . $e->getMessage();
} finally {
    $conn->close();
}
?>


 <button type="button" class="btn btn-primary mt-3" onclick="redirectToLastPage()">Submit Reservation</button>

<script>
   function redirectToLastPage() {
    var selectedDate = document.getElementById('selectedDate').value;
    var selectedFacilities = <?php echo json_encode($selectedFacilityIDs); ?>;
    var selectedEquipment = [];

    var checkboxes = document.getElementsByName('selectedEquipment[]');
    checkboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
            var equipmentID = checkbox.value;
            var quantityInput = document.getElementById('quantity_' + equipmentID);
            var quantity = quantityInput.value;
            selectedEquipment.push({ id: equipmentID, quantity: quantity });
        }
    });

    var url = 'test5.php?date=' + selectedDate +
              '&selectedFacilities=' + selectedFacilities.join(',') +
              '&selectedEquipment=' + JSON.stringify(selectedEquipment);
              '&selectedEquipment=' + JSON.stringify(selectedEquipment);


    window.location.href = url;
}

</script>

</script>

</body>
</html>





