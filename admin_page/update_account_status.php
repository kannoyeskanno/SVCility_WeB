<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../dbConnect.php';

    $clientId = $_POST['id'];
    $currentStatus = $_POST['currentStatus'];

    $newStatus = ($currentStatus === 'accept') ? 'denied' : 'accept';

    $updateSql = "UPDATE client SET account_stat = '$newStatus' WHERE id = $clientId";

    if ($conn->query($updateSql) === TRUE) {
        echo $newStatus;
    } else {
        echo 'Error updating account status: ' . $conn->error;
    }

    $conn->close();
} else {
    http_response_code(400);
    echo 'Bad Request';
}
?>
