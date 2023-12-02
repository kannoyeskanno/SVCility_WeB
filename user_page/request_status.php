<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>User</title>
    <style>
        body {
            font-family: 'Josephin Sans', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .facility-box {
            background-color: #f8f8f8;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.3s ease-in-out;
            cursor: pointer; /* Make the box clickable */
        }

        .image-container {
            width: 150px;
            height: 150px;
            overflow: hidden;
            border: 1px solid red;
            border-radius: 8px;
        }

        .image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .text-container {
            flex: 1;
            padding: 0 15px;
        }

        .details p {
            margin: 5px 0;
        }

        /* Add more styling for buttons, modal, and other elements as needed */
    </style>
</head>
<body>

<?php
include '../dbConnect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo '<script>
        if (confirm("You must log in or create an account to access this page. Click OK to log in.")) {
            window.location.href = "../index.php";
        } else {
            // Redirect or take any other action if the user clicks Cancel
        }
    </script>';
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM request WHERE user_id = $user_id";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $user_id = $row["user_id"];
        $subject = $row["date"];
        $purpose = $row["purpose"];
        $date = $row["date"];
        $facilityIds = $row["facility_id"];
        $equipmentIds = $row["equipment_qty"];
        $status = $row["status"];

        // echo '<a href="view_request.php?request_id=' . $row["id"] . '" class="request-container">';
        echo '<div class="facility-container" onclick="location.href=\'view_request.php?request_id=' . $row["id"] . '\'">';
        
        $facilityIdArray = explode(',', $facilityIds);
        foreach ($facilityIdArray as $facilityId) {
            $sqlFaci = "SELECT * FROM facility WHERE id = ?";
            $stmtFaci = $conn->prepare($sqlFaci);

            if ($stmtFaci) {
                $stmtFaci->bind_param("s", $facilityId);
                $stmtFaci->execute();
                $resultFaci = $stmtFaci->get_result();

                if ($resultFaci) {
                    while ($rowFaci = $resultFaci->fetch_assoc()) {
                        $image_name = $rowFaci["image_name"];
                        $imagePath = '../resources/facility_imgs/' . $image_name . '.png';

                        echo '<div class="facility-box">';
                        echo '<div class="image-container">';
                        echo '<img src="' . $imagePath . '" class="card-img-top image" alt="' . $rowFaci["facility_name"] . '">';
                        echo '</div>';
                        echo '<div class="text-container">';
                        echo '<h5 class="card-title">' . $rowFaci["facility_name"] . '</h5>';
                        echo "<h3>Status: $status</h3>";
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>Error in facility query: " . $stmtFaci->error . "</p>";
                }

                $stmtFaci->close();
            } else {
                echo "<p>Error in facility query preparation: " . $conn->error . "</p>";
            }
        }
        echo '</div>';
    }
} else {
    echo "<p>Error in SQL query: " . $conn->error . "</p>";
}

$conn->close();
?>

<div id="deleteModal" style="display: none;" data-request-id="" data-status="">
    <p id="deleteMessage">Are you sure you want to delete this request?</p>
    <button onclick="deleteRequest()" class="action-button btn btn-danger">Yes</button>
    <button onclick="hideDeleteModal()" class="action-button btn btn-secondary">No</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
