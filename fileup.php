<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('dbConnect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['pdf_file'])) {
    $file_name = $_FILES['pdf_file']['name'];
    $file_tmp = $_FILES['pdf_file']['tmp_name'];
    $file_destination = 'resources/file/' . $file_name;

    if (move_uploaded_file($file_tmp, $file_destination)) {
        // File uploaded successfully, now store the file information in the database
        $sql = "INSERT INTO files (file_name, file_path, user_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind three parameters: 'sss' indicates three strings
        $stmt->bind_param("sss", $file_name, $file_destination, $user_id);

        if ($stmt->execute()) {
            $message = "File uploaded and stored in the database successfully.";
        } else {
            $error = "Error storing file information in the database: " . $stmt->error;

            // Add this line for debugging purposes
            error_log("SQL Error: " . $error);
        }
        $stmt->close();
    } else {
        $error = "Error moving the uploaded file.";

        error_log("File Upload Error: " . $error);
    }
}
?>