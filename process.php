<?php
include('dbConnect.php'); // Include your database connection script

if (isset($_POST['returnEquipment'])) {
    $equipmentId = $_POST['equipment_id'];
    // Add any necessary validation

    // You can set a session variable here if needed
    // $_SESSION['equipment_id_return'] = $equipmentId;

    // Optional: You can use JavaScript to trigger the modal here
    echo '<script type="text/javascript">';
    echo '  $(document).ready(function(){';
    echo '      $("#exampleModal").modal("show");';
    echo '  });';
    echo '</script>';
}

if (isset($_POST['confirmReturn'])) {
    $equipmentIdReturn = $_POST['equipment_id_return'];
    // Update the database or perform any other necessary actions

    echo "Equipment with ID $equipmentIdReturn returned successfully!";
}

$conn->close();
?>
