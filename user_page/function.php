<?php

$idQtyPairs = [];

while ($equipmentRow = $equipmentResult->fetch_assoc()) {
    $equipmentID = $equipmentRow['id'];
    $remainingQuantity = $equipmentRow['qty'] - ($totalQuantity[$equipmentID] ?? 0);

    // Add the id:qty pair to the array
    $idQtyPairs[] = $equipmentID . ':' . $remainingQuantity;
}

// Check if there are any id:qty pairs
if (!empty($idQtyPairs)) {
    $resultString = implode(', ', $idQtyPairs);

    // Display the result string
    echo '<div class="table-container">';
    echo '<p>Equipment ID and Remaining Quantity:</p>';
    echo '<p>' . $resultString . '</p>';
    echo '</div>';

    // Start the session
    session_start();

    // Assign the $idQtyPairs array to $_SESSION
    $_SESSION['idQtyPairs'] = $idQtyPairs;

    // Function definition
    function getQuantityForEquipmentID($equipmentID) {
        // Access the external array from the session
        $idQtyPairs = $_SESSION['idQtyPairs'] ?? [];

        foreach ($idQtyPairs as $idQty) {
            list($id, $quantity) = explode(':', $idQty);
            if ($id == $equipmentID) {
                return $quantity;
            }
        }

        return 0; // Return 0 if the ID is not found
    }

    $exampleQuantity = getQuantityForEquipmentID($exampleEquipmentID);
    echo 'Example Quantity: ' . $exampleQuantity;
} else {
    // Handle the case where no equipment is found
    echo 'No equipment found.';
}
?>
