<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    echo '<script>
        if (confirm("You must log in or create an account to access this page. Click OK to log in.")) {
            window.location.href = "../index.php"; // Redirect to your login page
        } else {
            // Redirect or take any other action if the user clicks Cancel
        }
    </script>';
    exit;
}



if (isset($_GET['items'])) {
    $selectedItems = explode(',', $_GET['items']);

    // Display the selected item IDs
    echo "<h2>Selected Items:</h2>";
    echo "<ul>";
    foreach ($selectedItems as $itemId) {
        echo "<li>Item ID: $itemId</li>";
    }
    echo "</ul>";

    // Proceed with further processing or checkout logic here
} else {
    // echo "<p>No items selected for checkout.</p>";
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserved Facilities</title>
    

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="../css/user_page/facility_cart_list.css">
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="js/frame_cart_list.js"></script>
        <!-- Include jQuery -->
   
<script>
        // Initial fetch on page load
       

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

      

        function borrowEquipment(equipmentID, quantity) {
            $.ajax({
                type: 'GET',
                url: 'checkout_solo_equipment.php',
                data: {
                    equipmentID: equipmentID,
                    qty: quantity // Use 'qty' instead of 'remainingQuantity'
                },
                success: function (response) {
                    window.location.href = 'checkout_solo_equipment.php?equipmentID=' + equipmentID + '&qty=' + quantity;
                },
                error: function (error) {
                    console.error('Error adding equipment to the list:', error);
                }
            });
        }


    </script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>

<body>
<!-- <label for="datepicker">Select Date:</label>
<input type="text" id="datepicker">

<button id="confirmDate">Confirm Date</button>

<div id="selected-date"></div> -->

<script>
   
</script>





<?php


include('../dbConnect.php');
// && isset($_POST['selectedDate'])
if (isset($_SESSION['user_id']) && filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT)) {
    $user_id = $_SESSION['user_id'];
    // $selectedDate = $_POST['selectedDate'];


    
    // $sql = "SELECT * FROM request WHERE status = 'approved' AND date = ?";
    // $stmt = $conn->prepare($sql);
    // $stmt->bind_param("s", $selectedDate);
    // $stmt->execute();
    // $result = $stmt->get_result();

    // if ($result === false) {
    //     throw new Exception('Error in the query: ' . $stmt->error);
    // } elseif ($result->num_rows > 0) {
    //     $totalQuantity = [];

    //     while ($row = $result->fetch_assoc()) {
    //         $equipmentDetails = explode(',', $row['equipment_qty']);

    //         foreach ($equipmentDetails as $equipment) {
    //             if (strpos($equipment, ':') !== false) {
    //                 list($equipmentID, $quantity) = explode(':', $equipment);

    //                 // Initialize the total quantity for each equipment if not set
    //                 if (!isset($totalQuantity[$equipmentID])) {
    //                     $totalQuantity[$equipmentID] = 0;
    //                 }

    //                 $totalQuantity[$equipmentID] += $quantity;
    //             }
    //         }
    //     }

      

    //     $equipmentQuery = "SELECT * FROM equipment";
    //     $equipmentResult = $conn->query($equipmentQuery);

    //     $idQtyPairs = [];

    //     while ($equipmentRow = $equipmentResult->fetch_assoc()) {
    //         $equipmentID = $equipmentRow['id'];
    //         $remainingQuantity = $equipmentRow['qty'] - ($totalQuantity[$equipmentID] ?? 0);
    //         $imagePath = '../resources/equipment_imgs/' . $equipmentRow['image_name'] . '.png';

    //         // Add the id:qty pair to the array
    //         $idQtyPairs[] = $equipmentID . ':' . $remainingQuantity;
    //     }

    //     $resultString = implode(', ', $idQtyPairs);

    //     function getQuantityForEquipmentID($equipmentID, $resultString)
    //     {
    //         $idQtyPairs = explode(', ', $resultString);
    //         foreach ($idQtyPairs as $idQty) {
    //             list($id, $quantity) = explode(':', $idQty);
    //             if ($id == $equipmentID) {
    //                 return $quantity;
    //             }
    //         }
    //         return 0;
    //     }
    //     // Display the result
    //     if (!empty($idQtyPairs)) {
            
            
    //     } else {
    //         echo 'No equipment found.';
    //     }
    // } else {
    //     echo 'No approved requests found for the selected date.';
        
    // }


    $sql = "SELECT * FROM list_request WHERE user_id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            $facility_id = $row['facility_id'];
            $equipment_id = $row['equipment_id'];

            // Fetch facility details based on facility_id
            $facility_sql = "SELECT * FROM facility WHERE id = $facility_id";
            $facility_result = $conn->query($facility_sql);

            if ($facility_result->num_rows > 0) {
                $facility_row = $facility_result->fetch_assoc();
                $facility_name = $facility_row['facility_name'];
                $facility_image_name = $facility_row['image_name'];

                $facilityImagePath = '../resources/facility_imgs/' . $facility_image_name . '.png';

                echo '<li>';
                echo '<input id="facility_checkbox_' . $row['id'] . '" type="checkbox" name="facility[]" value="' . $row['id'] . '">';
                echo '<div class="image-container">';
                echo '<div class="image" style="background-image: url(' . $facilityImagePath . ');"></div>';
                echo '</div>';
                echo '<div class="name">' . $facility_name . '</div>';
                echo '<div class="controls">';
                echo '<div class="delete">';
                echo '<button class="btn btn-danger delete-btn" data-reservation-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>';
                echo '</div>';
                echo '</div>';
                echo '</li>';
            }
            $equipment_sql = "SELECT * FROM equipment WHERE id = ?";
            $equipment_statement = $conn->prepare($equipment_sql);
            $equipment_statement->bind_param("i", $equipment_id);
            $equipment_statement->execute();
            $equipment_result = $equipment_statement->get_result();

            if ($equipment_result === false) {
                echo "Error executing equipment query: " . $equipment_statement->error;
            } else {

                if ($equipment_result->num_rows > 0) {
                    while ($equipment_row = $equipment_result->fetch_assoc()) {
                        $equipment_name = $equipment_row['equipment_name'];
                        $equipment_image_name = $equipment_row['image_name'];
                
                        $equipmentImagePath = '../resources/equipment_imgs/' . $equipment_image_name . '.png';
                
                        echo '<li>';
                        echo '<input id="equipment_checkbox_' . $row['id'] . '" type="checkbox" name="equipment[]" value="' . $row['id'] . '">';
                        echo $equipment_row['id'];
                        echo '<div class="image-container">';
                        echo '<div class="image" style="background-image: url(' . $equipmentImagePath . ');"></div>';
                        echo '</div>';


                        echo '<div class="name">' . $equipment_name . '</div>';
                        // echo '<div class="name">' . getQuantityForEquipmentID($equipment_row['id'], $resultString) . '</div>';

                        echo '<div class="controls">';
                        echo '<div class="quantity">';
                        echo '<button class="btn btn-primary plus" data-reservation-id="' . $equipment_row['id'] . '"><i class="bi bi-plus"></i></button>';
                        // echo '<input type="number" class="quantity-input" max="' . getQuantityForEquipmentID($equipment_row['id'], $resultString) . '" value="' . $equipment_row['qty'] . '" min="1" data-reservation-id="' . $equipment_row['id'] . '">';

                        echo '<input type="number" class="quantity-input" value="' . $equipment_row['qty'] . '" min="1" data-reservation-id="' . $equipment_row['id'] . '">';
                        echo '<button class="btn btn-primary minus" data-reservation-id="' . $equipment_row['id'] . '"><i class="bi bi-dash"></i></button>';
                        echo '</div>';
                        echo '<div class="delete">';
                        echo '<button id="delete_solo" class="btn btn-danger delete-btn" data-reservation-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>';
                        echo '</div>';
                        echo '</div>';
                        echo '</li>';
                    }
                }
                

            }
            
        }
        echo "</ul>";

        echo '<div id="sticky-container" style="position: sticky; bottom: 0; background-color: #fff; padding: 10px; z-index: 0;">';
        echo '<input type="checkbox" id="select-all"> Select All';
        echo '<button class="btn btn-danger" id="delete_selected">Delete Selected</button>';
        echo '<button class="btn btn-primary" id="submit">Check Out</button>';
        echo '</div>';
    } else {
        echo "No facilities reserved for this user.";
    }
} else {
    // echo "Invalid user ID.";
}

$conn->close();
?>


    

    <div id="deleteModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this item?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirmDelete">Yes</button>
                    <button type="button" class="btn btn-secondary" id="cancelDelete" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-overlay"></div>

   

</body>

</html>