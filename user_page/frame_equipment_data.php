<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>Facilities Data</title>
    
    <style>
        .equipment-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 20px;
        }

        .card {
            width: 250px; /* Adjust the width as needed */
            margin: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card img {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            max-height: 150px;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
        }

        .card-text {
            color: #555;
        }

        .buttons {
            margin-top: 10px;
        }

        .btn-primary {
            background-color: #007BFF;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="equipment-container">
                <div class="equipment-box-head table-header">
                    <h1>MSTPO Equipments</h1>
                </div>

                <?php
                include '../dbConnect.php';

                $sql = "SELECT * FROM equipment";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $imagePath = '../resources/equipment_imgs/' . $row['image_name'] . '.png';

                        list($width, $height) = getimagesize($imagePath);
                        $maxImageWidth = 70;
                        $maxImageHeight = 50;
                        if ($width > $maxImageWidth || $height > $maxImageHeight) {
                            $ratio = min($maxImageWidth / $width, $maxImageHeight / $height);
                            $newWidth = $width * $ratio;
                            $newHeight = $height * $ratio;
                        } else {
                            $newWidth = $width;
                            $newHeight = $height;
                        }
                ?>
                        <div class="card mb-3">
                            <img src="<?php echo $imagePath; ?>" class="img-fluid rounded-start" alt="Equipment Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row["equipment_name"]; ?></h5>
                                <p class="card-text"><?php echo $row["qty"] . ' pc/s'; ?></p>
                                <div class="buttons">
                                    <button class="btn btn-primary" onclick="borrowEquipment(<?php echo $row["id"]; ?>)">Borrow</button>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p class='text-center'>No facilities found.</p>";
                }

                $conn->close();
                ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
