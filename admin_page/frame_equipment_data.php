<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>Facilities Data</title>
    
    <style>
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

        .add-button:hover {
            background-color: #f0f0f0; /* Background color change on hover */
        }

        .add-button i {
            font-size: 24px;
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
                        <div class="card mb-3" style="max-width: 100%;">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="<?php echo $imagePath; ?>" class="img-fluid rounded-start" alt="Equipment Image">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $row["equipment_name"]; ?></h5>
                                        <p class="card-text"><?php echo $row["qty"] . ' pc/s'; ?></p>
                                        <div class="buttons">
                                            <button class="btn btn-primary" onclick="borrowEquipment(<?php echo $row["id"]; ?>)">Borrow</button>
                                            <button class="btn btn-secondary" onclick="addToListEquipment(<?php echo $row["id"]; ?>)">Add to list</button>
                                        </div>
                                    </div>
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

            <div class="add-button">
                <div class="button-item">
                    <a class="add_facility" href="frame_add_equipment.php">
                        <i class="bi bi-cloud-plus-fill"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
