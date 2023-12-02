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
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    
    <title>Facility Details</title>
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #495057;
        }

        .container {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
        }

        .content {
            width: 60%;
            max-width: 800px;
            padding: 10px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .right_bar {
            width: 40%;
            border: solid;
        }

        #iframe_content {
            height: 98vh;
            width: 98%;
        }

        .locator {
            margin-left: 450px;
        }

        .content a {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            margin-left: 10px;
        }

        img {
            max-width: 100%;
            max-height: 400px;
            width: 100%;
            height: auto;
            border-radius: 10px 10px 0 0;
        }

        .facility-title {
            font-size: 24px;
            margin: 10px 0;
            color: #007bff;
        }

        .details-container {
            padding: 20px;
        }

        .facility-info {
            margin-top: 20px;
        }

        .facility-info p {
            font-size: 16px;
            margin: 8px 0;
        }

        .reserve-button,
        .add-to-list-button {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .reserve-button:hover,
        .add-to-list-button:hover {
            background-color: #0056b3;
        }

        .calendar-container {
            margin-top: 30px;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .calendar-header button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #007bff;
        }

        .calendar-header h2 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 24px;
            margin: 0;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: center;
            width: 14.28%;
            padding: 10px;
            border: 1px solid #ced4da;
        }

        th {
            background-color: #007BFF;
            color: #fff;
        }

        .current-day {
            background-color: red;
            color: #fff;
        }

        #activity-container {
            border: 1px solid #ced4da;
            padding: 20px;
            margin-top: 20px;
            height: 100%;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        td {
            text-align: center;
            width: 14.28%;
            padding: 10px;
            border-radius: 3px;
            cursor: pointer;
        }

        td:hover {
            border-radius: 30px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <?php
    include '../dbConnect.php';
    include '../header.php';



    if (isset($_GET['facilityID'])) {
        $facilityID = $_GET['facilityID'];

        $sql = "SELECT * FROM facility WHERE id = $facilityID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $facilityName = $row["facility_name"];
            $facilityLocation = $row["location"];
            $facilityType = $row["type"];
            $facilityDetails = $row["details"];
            $facilityCapacity = $row["capacity"];
            $facilityImgName = $row["image_name"];

            $sqlMax = "SELECT MAX(id) AS max_id FROM facility";
            $sqlMin = "SELECT MIN(id) AS min_id FROM facility";

            $resultMax = $conn->query($sqlMax);
            $resultMin = $conn->query($sqlMin);

            $rowMax = $resultMax->fetch_assoc();
            $rowMin = $resultMin->fetch_assoc();

            $maxID = $rowMax["max_id"];
            $minID = $rowMin["min_id"];

            $nextID = ($facilityID >= $maxID) ? $minID : $facilityID + 1;
            $previousID = ($facilityID <= $minID) ? $maxID : $facilityID - 1;

            $imagePath = '../resources/facility_imgs/' . $facilityImgName . '.png';

            ?>
            <div class="container">
                <div class="content">
                    <img src="<?php echo $imagePath; ?>" alt="Facility Image">
                    <h2 class="facility-title"><?php echo $facilityName; ?></h2>
                    <div class="details-container">
                        <div class="facility-info">
                            <p><strong>Location:</strong> <?php echo $facilityLocation; ?></p>
                            <p><strong>Type:</strong> <?php echo $facilityType; ?></p>
                            <p><strong>Details:</strong> <?php echo $facilityDetails; ?></p>
                            <p><strong>Capacity:</strong> <?php echo $facilityCapacity; ?></p>
                            <!-- <button id="reserveButton" class="reserve-button">Reserve</button> -->
                            <!-- <button id="addToListButton" class="add-to-list-button">Add to List</button> -->
                        </div>
                    </div>
                    <div class="locator">
                        <a href="read_more.php?facilityID=<?php echo $nextID; ?>">
                            <i class="bi bi-arrow-left-square-fill"></i>                       
                         </a>
                        <a href="read_more.php?facilityID=<?php echo $previousID; ?>">
                            <i class="bi bi-arrow-right-square-fill"></i>                        
                        </a>
                    </div>
                </div>

                <script>
                    document.getElementById('reserveButton').addEventListener('click', function() {
                        var confirmed = confirm('Do you want to Reserve Only This?');
                        if (confirmed) {
                            window.location.href = 'checkout_solo.php?facilityId=<?php echo $facilityID; ?>';
                        }
                    });

                
                </script>

                <div class="right_bar">
                    <iframe id="iframe_content" src="facility_schedule.php?facilityID=<?php echo $facilityID; ?>" frameborder="0"></iframe>
                </div>
            </div>
            <?php
        } else {
            echo "Facility not found.";
        }
    } else {
        echo "Facility ID not provided.";
    }

    $conn->close();
    ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
