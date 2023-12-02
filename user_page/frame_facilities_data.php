<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>Facilities Data</title>
    <style>

       
        body {
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .facility-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .facility-box {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            box-sizing: border-box;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
            width: 30.33%;
            display: flex;
            flex-direction: column;
        }

        p {
            font-size: 16px;
        }

        .image-container {
            width: 100%;
            height: 150px;
            overflow: hidden;
        }

        .image {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
        }

        .text-container {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 0 10px;
        }

        .facility-container {
            margin-top: 45px; 
        }
        
    </style>
</head>
<body>
<div class="facility-container">
    <?php
        include '../dbConnect.php';

        $sql = "SELECT * FROM facility";
        $result = $conn->query($sql);

        if ($result->num_rows > 1) {
            echo '<div class="card-group">'; // Add a card-group container
            while ($row = $result->fetch_assoc()) {
                $imagePath = '../resources/facility_imgs/' . $row['image_name'] . '.png';
            
                echo '<div class="card mb-3" style="width: 19rem;">';
                echo '<img src="' . $imagePath . '" class="card-img-top" alt="Facility Image">';
                echo '<div class="card-body" style="line-height: 20px;">'; // Adjust line-height here
                echo '<h5 class="card-title" style="height: 50px; overflow: hidden;">' . $row["facility_name"] . '</h5>';
                
                // Additional Information
                echo '<p class="card-text" style="margin-bottom: 0;"><strong>Type:</strong> ' . $row["type"] . '</p>';
                echo '<p class="card-text" style="margin-bottom: 0;"><strong>Capacity:</strong> ' . $row["capacity"] . '</p>';
                echo '<p class="card-text" style="margin-bottom: 0;"><strong>Location:</strong> ' . $row["location"] . '</p>';
                echo '<div class="card-footer">'; // Adjust line-height here
                echo '<a href="read_more.php?facilityID=' . $row["id"] . '" class="btn btn-primary">Read More</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>'; // Close the card-group container
        }
        
         else {
            while ($row = $result->fetch_assoc()) {
                $imagePath = '../resources/facility_imgs/' . $row['image_name'] . '.png';

                echo '<div class="card mb-3">';
                echo '<img src="' . $imagePath . '" class="card-img-top" alt="Facility Image">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $row["facility_name"] . '</h5>';
                echo '<p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>';
                echo '<a href="read_more.php?facilityID=' . $row["id"] . '" class="btn btn-primary">Read More</a>';
                echo '</div>';
                echo '</div>';
            }
        }

        $conn->close();
    ?>
</div>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


