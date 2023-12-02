<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facilities Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Josephin Sans', sans-serif;
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
            width: 270px;
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

        .add-button {
        position: fixed;
        background-color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        top: 350px;
        right: 20px;
        z-index: 1;
        transition: background-color 0.3s;
        border: 5px solid #ccc; /* Add border for circular button */
    }

   

    .add-button i {
        font-size: 24px;
    }

    .ban-btn i {
    color: yellow;
}

.pen-btn i {
    color: green;
}

.trash-btn i {
    color: red;
}

.btn {
    border: none;
}

.list-btn {
    color: blue;
}


    </style>
</head>
<body>
    <div class="facility-container">
    <?php
            include '../dbConnect.php';

            $sql = "SELECT * FROM facility";
            $result = $conn->query($sql);

           
        if ($result->num_rows > 3) {
            while ($row = $result->fetch_assoc()) {
                $imagePath = '../resources/facility_imgs/' . $row['image_name'] . '.png';

                echo '<div class="card mb-3" style="width: 18rem;">';
                echo '<img src="' . $imagePath . '" class="card-img-top" alt="Facility Image">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $row["facility_name"] . '</h5>';
                echo '<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>';
                echo '<a href="logs_faci.php?facilityId=' . $row["id"] . '" class="btn btn-primary">Logs</a>';
                echo '<div class="d-grid gap-2 d-md-block">';
                echo '<button class="btn trash-btn mb-2" type="button"><i class="bi bi-trash3"></i></button>';
                echo '<button class="btn pen-btn mb-2" type="button"><i class="bi bi-pen-fill"></i></button>';
                echo '<button class="btn trash-btn mb-2" type="button"><i class="bi bi-trash3"></i></button>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                
            }
        } else {
            while ($row = $result->fetch_assoc()) {
                $imagePath = '../resources/facility_imgs/' . $row['image_name'] . '.png';
                echo '<div class="card mb-3">';
                echo '<img src="' . $imagePath . '" class="card-img-top" alt="Facility Image">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $row["facility_name"] . '</h5>';
                echo '<p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>';
                echo '<div class="d-grid gap-2 d-md-block">';
                echo '<a href="logs_faci.php?facilityId=' . $row["id"] . '" class="btn btn-primary">Logs</a>';
                echo '<button class="btn trash-btn mb-2" type="button"><i class="bi bi-trash3"></i></button>';
                echo '<button class="btn pen-btn mb-2" type="button"><i class="bi bi-pen-fill"></i></button>';
                echo '<button class="btn trash-btn mb-2" type="button"><i class="bi bi-trash3"></i></button>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                
                
                
            
            }
        }

            $conn->close();
        ?>    </div>
        <div class="add-button">
            <div class="button-item">
                <a class="add_facility" href="frame_add_facility.php">
                    <i class="bi bi-cloud-plus-fill"></i>
                </a>
            </div>
        </div>



    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
   
</body>
</html>

