<?php
session_start();

include '../dbConnect.php';


try {
    if (isset($_POST['selectedDate'])) {
        $selectedDate = $_POST['selectedDate'];

        $_SESSION['soloDate'] = $selectedDate;


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
            echo '<div class="table-container">';
            echo '<table>';
            echo '<tr><th>Remaining Quantity</th><th>Equipment Image</th><th>Request Qyt</th><th>Action</th></tr>';

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
                // Display data in the table
                echo '<tr>';
                echo '<td data-remaining-quantity="' . $remainingQuantity . '">' . $remainingQuantity . '</td>';
                echo '<td><img src="' . $imagePath . '" alt="' . $equipmentRow['equipment_name'] . '" style="max-width: 50px; max-height: 50px;"></td>';
                echo '<td>';
                    echo '<div class="quantity">';
                echo '<button class="btn btn-primary plus" data-reservation-id="' . $equipmentRow['id'] . '"><i class="bi bi-plus"></i></button>';
                echo '<input type="number" class="quantity-input" value="' . $remainingQuantity . '" min="1" max="' . $remainingQuantity . '" id="quantity_' . $equipmentRow["id"] . '">';
                echo '<button class="btn btn-primary minus" data-reservation-id="' . $equipmentRow['id'] . '"><i class="bi bi-dash"></i></button>';
                echo '</div>';
                echo '</td>';
                echo '<td>';
                echo '<button onclick="borrowEquipment(' . $equipmentRow["id"] . ')">Borrow</button>';
                echo '<button onclick="addToListEquipment(' . $equipmentRow["id"] . ',' . $remainingQuantity . ')">Add to list</button>';
                echo '</td>';
                echo '</tr>';

            }

            echo '</table>';
            echo '</div>';
        } else {

            echo '<div class="table-container">';
            echo '<table>';
            echo '<tr><th>Remaining Quantity</th><th>Equipment Image</th><th>Request Qyt</th><th>Action</th></tr>';

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

                echo '<tr>';
                echo '<td data-remaining-quantity="' . $remainingQuantity . '">' . $remainingQuantity . '</td>';
                echo '<td><img src="' . $imagePath . '" alt="' . $equipmentRow['equipment_name'] . '" style="max-width: 50px; max-height: 50px;"></td>';
                echo '<td>';
                echo '<div class="quantity">';
                echo '<button class="btn btn-primary plus" data-reservation-id="' . $equipmentRow['id'] . '"><i class="bi bi-plus"></i></button>';
                echo '<input type="number" class="quantity-input" value="' . $remainingQuantity . '" min="1" max="' . $remainingQuantity . '" id="quantity_' . $equipmentRow["id"] . '">';
                echo '<button class="btn btn-primary minus" data-reservation-id="' . $equipmentRow['id'] . '"><i class="bi bi-dash"></i></button>';
                echo '</div>';
                echo '</td>';
                echo '<td>';
                echo '<button onclick="borrowEquipment(' . $equipmentRow["id"] . ')">Borrow</button>';
                echo '<button onclick="addToListEquipment(' . $equipmentRow["id"] . ',' . $remainingQuantity . ')">Add to list</button>';
                echo '</td>';
                echo '</tr>';

        }
    }

        $stmt->close();
    } else {
        throw new Exception('Error: Selected date not set.');
    }
} catch (Exception $e) {
    echo 'An error occurred: ' . $e->getMessage();
} finally {
    $conn->close();
}
?>


<script>
function borrowEquipment(equipmentID) {
    var quantityInput = document.getElementById('quantity_' + equipmentID);

    var quantity = quantityInput.value;
    $.ajax({
        type: 'GET',
        url: 'checkout_solo_equipment.php',
        data: {
            equipmentID: equipmentID,
            quantity: quantity,
            // selectedDate: selectedDate
        },
        success: function (response) {
            // Handle the response here (e.g., update UI)
            console.log('Success:', response);
            // Redirect to the next page if needed
            window.location.href = 'checkout_solo_equipment.php?equipmentID=' + equipmentID + '&qty=' + quantity;
        },
        error: function (error) {
            console.error('Error adding equipment to the list:', error);
        }
    });
}


function addToListEquipment(equipmentID, remainingQuantity) {
            $.ajax({
                type: 'GET',
                url: 'reserveEquipment.php',
                data: {
                    equipmentID: equipmentID,
                    remainingQuantity: remainingQuantity
                },
                success: function (response) {
                    alert(response);
                },
                error: function (error) {
                    console.error('Error adding equipment to the list:', error);
                }
            });
        }
</script>


