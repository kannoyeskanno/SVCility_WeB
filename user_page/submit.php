<?php
include('../dbConnect.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    // Handle the case where the user is not logged in
    echo '<script>
        if (confirm("You must log in or create an account to access this page. Click OK to log in.")) {
            window.location.href = "../index.php"; // Redirect to your login page
        } else {
            // Redirect or take any other action if the user clicks Cancel
        }
    </script>';
    exit;
}

if (isset($_POST['submit'])) {
    // Retrieve the form data
    $subject = $_POST['subject'];
    $date = $_POST['date'];

    $user_id = $_SESSION['user_id'];
    $sql = "INSERT INTO request (subject, date, user_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $subject, $date, $user_id);

    if ($stmt->execute()) {
        // The data is successfully inserted into the database
        // You can redirect the user to a success page or perform any other action
        echo "Form data submitted successfully.";
    } else {
        // Handle the case where the insertion failed
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $stmt->close();
}
