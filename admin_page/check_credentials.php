<?php

session_start();
$user_id = $_SESSION['user_id'];

$sqlAdmin = "SELECT email, password FROM admin WHERE id = $user_id";
$resultAdmin = $conn->query($sqlAdmin);

if ($resultAdmin) {
    while ($rowAdmin = $resultAdmin->fetch_assoc()) {
        $expectedEmail = $rowAdmin['email'];
        $expectedPassword = $rowAdmin['password'];
    }
}

// Check if the provided email and password match the database
if ($_POST['email'] == $expectedEmail && $_POST['password'] == $expectedPassword) {
    echo "success";
} else {
    echo "incorrect";
}
?>
