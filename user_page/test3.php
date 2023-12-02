<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="../resources/logo/svcility_icon.png" type="image/x-icon">

    <title>Reservation Form - Page 3</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<style>
.card {
        width: 100%;
    }

    .card-body {
        max-width: 100%;
    }
</style>
<body>
<?php
    session_start();
    include '../header.php';
?>

<div class="container custom-width" id="container1" style="margin-top: 60px;">
<div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
  <div class="progress-bar w-75"></div>
</div>
    <div class="row">
        <div class="col-md-6">
            <?php
            include('../dbConnect.php');
            
            $selectedDate = $_SESSION['date'];
            echo '<p>Selected Date: ' . htmlspecialchars($selectedDate) . '</p>';
            
            $selectedFacilityIDs = isset($_POST['selectedFacilities']) ? $_POST['selectedFacilities'] : [];

            if (isset($_SESSION['date'])) {
                $selectedDate = $_SESSION['date'];
                // $selectedFacilityIDs = $_POST['selectedFacilities'];
                
               
                // echo $selectedFacilityIDs;
                $sqlSelectedFacilities = "SELECT * FROM facility WHERE id IN (" . implode(',', $selectedFacilityIDs) . ")";
                $resultSelectedFacilities = $conn->query($sqlSelectedFacilities);

                if ($resultSelectedFacilities) {
            ?>

                    <div class="row row-cols-1 row-cols-md-1 g-1">
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

                    
            <?php
                } else {
                    echo '<div class="row row-cols-1 row-cols-md-1 g-1">';
                    echo '<div class="alert alert-secondary">'; 
                    echo '<p>No facility selected.</p>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="row row-cols-1 row-cols-md-1 g-1">';
                echo '<div class="alert alert-secondary">'; 
                echo '<p>No facility selected.</p>';
                echo '</div>';
                echo '</div>';
            }
            
            ?>
        </div>
        <div class="col-md-6">
    <?php
    include('../dbConnect.php');

    try {
        if (isset($_SESSION['date'])) {
            $selectedDate = $_SESSION['date'];

            $sql = "SELECT * FROM request WHERE status = 'approved' AND date = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $selectedDate);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result === false) {
                throw new Exception('Error in the query: ' . $stmt->error);
            } elseif ($result->num_rows > 0) {
                $totalQuantity = [];

                while ($row = $result->fetch_assoc()) {
                    $equipmentDetails = explode(',', $row['equipment_qty']);

                    foreach ($equipmentDetails as $equipment) {
                        if (strpos($equipment, ':') !== false) {
                            list($equipmentID, $quantity) = explode(':', $equipment);

                            if (!isset($totalQuantity[$equipmentID])) {
                                $totalQuantity[$equipmentID] = 0;
                            }

                            $totalQuantity[$equipmentID] += $quantity;
                        }
                    }
                }

                echo "<input type='hidden' id='selectedDate' value='$selectedDate'>";

                echo '<div class="container mt-5">';
                echo '<ul class="list-group">';

                $equipmentQuery = "SELECT * FROM equipment";
                $equipmentResult = $conn->query($equipmentQuery);

                if ($equipmentResult === false) {
                    throw new Exception('Error in the query: ' . $conn->error);
                }

                while ($equipmentRow = $equipmentResult->fetch_assoc()) {
                    $equipmentID = $equipmentRow['id'];
                    $remainingQuantity = $equipmentRow['qty'] - ($totalQuantity[$equipmentID] ?? 0);
                    $imagePath = '../resources/equipment_imgs/' . $equipmentRow['image_name'] . '.png';

                    echo '<li class="list-group-item">';
                    echo '<div class="d-flex align-items-center">';
                    echo '<div class="me-3">';
                    echo '<img src="' . $imagePath . '" alt="' . $equipmentRow['equipment_name'] . '" style="max-width: 50px; max-height: 50px;">';
                    echo '</div>';
                    echo '<div class="flex-grow-1">';
                    echo '<div data-remaining-quantity="' . $remainingQuantity . '">Remaining Quantity: ' . $remainingQuantity . '</div>';
                    echo '<div>';
                    echo '<div class="form-check">';
                    echo '<input type="checkbox" class="form-check-input" name="selectedEquipment[]" value="' . $equipmentRow['id'] . '">';
                    echo '<label class="form-check-label">Select</label>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div>';
                    echo '<div class="quantity">';
                    echo '<input type="number" class="quantity-input form-control" value="' . $remainingQuantity . '" min="1" max="' . $remainingQuantity . '" id="quantity_' . $equipmentRow["id"] . '">';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</li>';
                }

                echo '</ul>';
                echo '</div>';
            } else {
                echo "<input type='hidden' id='selectedDate' value='$selectedDate'>";

                echo '<div class="container mt-5">';
                echo '<ul class="list-group">';

                $equipmentQuery = "SELECT * FROM equipment";
                $equipmentResult = $conn->query($equipmentQuery);

                if ($equipmentResult === false) {
                    throw new Exception('Error in the query: ' . $conn->error);
                }

                while ($equipmentRow = $equipmentResult->fetch_assoc()) {
                    $equipmentID = $equipmentRow['id'];
                    $remainingQuantity = $equipmentRow['qty'] - ($totalQuantity[$equipmentID] ?? 0);
                    $imagePath = '../resources/equipment_imgs/' . $equipmentRow['image_name'] . '.png';

                    echo '<li class="list-group-item">';
                    echo '<div class="d-flex align-items-center">';
                    echo '<div class="me-3">';
                    echo '<img src="' . $imagePath . '" alt="' . $equipmentRow['equipment_name'] . '" style="max-width: 50px; max-height: 50px;">';
                    echo '</div>';
                    echo '<div class="flex-grow-1">';
                    echo '<div data-remaining-quantity="' . $remainingQuantity . '">Remaining Quantity: ' . $remainingQuantity . '</div>';
                    echo '<div>';
                    echo '<div class="form-check">';
                    echo '<input type="checkbox" class="form-check-input" name="selectedEquipment[]" value="' . $equipmentRow['id'] . '">';
                    echo '<label class="form-check-label">Select</label>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div>';
                    echo '<div class="quantity">';
                    // echo '<input type="number" class="quantity-input form-control" value="' . $remainingQuantity . '" min="1" max="' . $remainingQuantity . '" id="quantity_' . $equipmentRow["id"] . '">';
                    echo '<input type="number" class="quantity-input form-control" value="' . $remainingQuantity . '" min="1" max="' . $remainingQuantity . '" id="quantity_' . $equipmentRow["id"] . '" oninput="validateQuantity(' . $equipmentRow["id"] . ')">';
                    echo '<div id="error_' . $equipmentRow["id"] . '" class="invalid-quantity"></div>';

                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</li>';
                }

                echo '</ul>';
                echo '</div>';
            }

            $stmt->close();
        }
    } catch (Exception $e) {
        echo 'An error occurred: ' . $e->getMessage();
    } finally {
        $conn->close();
    }
    ?>
    <button type="button" id="proceedButton" class="btn btn-outline-primary mt-3" onclick="redirectToLastPage()">Proceed</button>

</div>


        </div>
    </div>
</div>

<script>
        var isValidQuantity = true; // Variable to track overall validation status

        function validateQuantity(equipmentID) {
            var quantityInput = document.getElementById('quantity_' + equipmentID);
            var remainingQuantity = parseInt(quantityInput.getAttribute('max'), 10);
            var enteredQuantity = parseInt(quantityInput.value, 10);

            var errorElement = document.getElementById('error_' + equipmentID);

            if (isNaN(enteredQuantity) || enteredQuantity <= 0 || enteredQuantity > remainingQuantity) {
                errorElement.innerHTML = 'Invalid quantity';
                quantityInput.classList.add('is-invalid');
                isValidQuantity = false;
            } else {
                errorElement.innerHTML = '';
                quantityInput.classList.remove('is-invalid');
                isValidQuantity = true;
            }

            updateProceedButton();
        }

        function updateProceedButton() {
            var proceedButton = document.getElementById('proceedButton');
            proceedButton.disabled = !isValidQuantity;
        }
    </script>
<script>
    function redirectToLastPage() {
        var selectedDate = document.getElementById('selectedDate').value;
        var selectedFacilities = <?php echo json_encode($selectedFacilityIDs); ?>;
        var selectedEquipment = [];

        var checkboxes = document.getElementsByName('selectedEquipment[]');
        checkboxes.forEach(function (checkbox) {
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

        window.location.href = url;
    }
</script>

</body>
</html>


