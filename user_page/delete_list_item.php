<?php
session_start();
include('../dbConnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selectedItems'])) {
    $selectedItems = $_POST['selectedItems'];

    if (isset($_SESSION['user_id']) && filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT)) {
        $user_id = $_SESSION['user_id'];

        $sanitizedItemIds = array_map('intval', $selectedItems);
        $validatedItemIds = array_filter($sanitizedItemIds, 'filter_var', FILTER_VALIDATE_INT);

        $itemIdsString = implode(',', $validatedItemIds);

        if (!empty($itemIdsString)) {
            $deleteQuery = "DELETE FROM list_request WHERE id IN ($itemIdsString) AND user_id = $user_id";

            if ($conn->query($deleteQuery) === TRUE) {
                echo "Items deleted successfully.";
            } else {
                echo "Error deleting items: " . $conn->error;
            }
        } else {
            echo "No valid item IDs provided.";
        }
    } else {
        echo "User is not logged in or user ID is not valid.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
