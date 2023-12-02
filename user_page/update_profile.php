<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'path/to/error.log');
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect or handle the case where the user is not logged in
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

include('../dbConnect.php');

$user_id = $_SESSION['user_id'];

$officeName = $_POST['officeName'];
$lastName = $_POST['lastName'];
$firstName = $_POST['firstName'];
$middleName = $_POST['middleName'];
$email = $_POST['email'];
$acc_stat = $_POST['acc_stat'];

$sql = "UPDATE client SET office_org = '$officeName', lname = '$lastName', fname = '$firstName', mname = '$middleName', email = '$email' WHERE id = $user_id";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => 'Profile updated successfully']);
} else {
    echo json_encode(['error' => 'Error updating profile']);
}

$conn->close();
?>
