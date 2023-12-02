

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">


    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.2/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.2/js/bootstrap.min.js"></script>

<!--     
    <style>
        body {
    font-family: 'Josefin Sans', sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
}

.container-fluid {
    margin-top: 5px;
    height: 90vh;
    overflow-y: auto;
    border: solid;
    padding: 30px;
}

h1 {
    margin-bottom: 20px;
    text-align: center;
}

form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

form div {
    border: 1px solid #ccc;
    padding: 20px;
}

label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
    color: #333;
}

input,
textarea,
select {
    width: 100%;
    box-sizing: border-box;
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input[type="file"] {
    margin-top: 5px;
}

input[type="submit"] {
    background-color: #28a745;
    color: white;
    cursor: pointer;
    padding: 12px;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    transition: background-color 0.3s ease-in-out;
}

input[type="submit"]:hover {
    background-color: #218838;
}

@media (max-width: 768px) {
    form {
        grid-template-columns: 1fr;
    }
} -->

    <!-- </style> -->
</head>
<body>
    <div class="container-fluid">
            <h1>Add Facility</h1>

            <?php
                include '../dbConnect.php';

                $facilityName = $location = $type = $capacity = $supervisorName = $supervisorContact = $supervisorEmail = $facilityDetails = $imageName = '';

                $errors = array();

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $facilityName = $_POST['facilityName'];
                    $location = $_POST['location'];
                    $type = $_POST['type'];
                    $capacity = $_POST['capacity'];
                    $supervisorName = $_POST['supervisorName'];
                    $supervisorContact = $_POST['supervisorContact'];
                    $supervisorEmail = $_POST['supervisorEmail'];
                    $facilityDetails = $_POST['facilityDetails'];
                    $imageName = $_POST['imageName'];

                    if (imageNameExists($imageName)) {
                        $errors[] = "A facility with the same image name already exists. Please choose a different image name.";
                    }

                    if (isset($_FILES['facilityImage']) && empty($errors)) {
                        $file = $_FILES['facilityImage'];
                        $file_name = $file['name'];
                        $file_tmp = $file['tmp_name'];
                        $file_error = $file['error'];

                        $file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);

                        $file_ext = 'png';
                        $allowed = array('jpg', 'jpeg', 'png', 'gif');
                        if (in_array($file_ext, $allowed) && $file_error === 0) {
                            $file_destination = '../resources/facility_imgs/' . $imageName . '.' . $file_ext;
                            move_uploaded_file($file_tmp, $file_destination);

                            if (insertDataIntoDatabase($facilityName, $location, $type, $capacity, $supervisorName, $supervisorContact, $supervisorEmail, $facilityDetails, $imageName)) {
                                header("Location: inventory.php");
                            } else {
                                $errors[] = "Database insertion failed.";
                            }
                        } else {
                            $errors[] = "File upload error. Please choose a valid image file.";
                        }
                    }
                }

                function imageNameExists($imageName) {
                    global $conn;

                    $sql = "SELECT * FROM facility WHERE image_name = ?";
                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param("s", $imageName);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        return $result->num_rows > 0;
                    }

                    return false;
                }

                function insertDataIntoDatabase($facilityName, $location, $type, $capacity, $supervisorName, $supervisorContact, $supervisorEmail, $facilityDetails, $imageName) {
                    global $conn;

                    $sql = "INSERT INTO facility (facility_name, location, type, capacity, supervisor_name, contact, email, details, image_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);

                    if ($stmt) {
                        $stmt->bind_param("sssssssss", $facilityName, $location, $type, $capacity, $supervisorName, $supervisorContact, $supervisorEmail, $facilityDetails, $imageName);
                        if ($stmt->execute()) {
                            return true;
                        }
                    }

                    return false;
                }
                
            if (!empty($errors)) {
                echo '<div class="alert alert-danger"><ul>';
                foreach ($errors as $error) {
                    echo '<li>' . $error . '</li>';
                }
                echo '</ul></div>';
            }
            ?>


    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="facilityName" class="form-label">Facility Name:</label>
                <input type="text" class="form-control" id="facilityName" name="facilityName" value="<?php echo $facilityName; ?>" required>
            </div>

            <div class="col-md-6">
                <label for="location" class="form-label">Location:</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo $location; ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="type" class="form-label">Type:</label>
                <select class="form-select" id="type" name="type" required>
                    <option value="Community Center" <?php if ($type === 'Community Center') echo 'selected'; ?>>Community Center</option>
                    <option value="Healthcare" <?php if ($type === 'Healthcare') echo 'selected'; ?>>Healthcare</option>
                    <option value="Recreational" <?php if ($type === 'Recreational') echo 'selected'; ?>>Recreational</option>
                    <option value="Cultural" <?php if ($type === 'Cultural') echo 'selected'; ?>>Cultural</option>
                    <option value="Other" <?php if ($type === 'Other') echo 'selected'; ?>>Other</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="capacity" class="form-label">Capacity:</label>
                <input type="number" class="form-control" id="capacity" name="capacity" value="<?php echo $capacity; ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="supervisorName" class="form-label">Supervisor Name:</label>
                <input type="text" class="form-control" id="supervisorName" name="supervisorName" value="<?php echo $supervisorName; ?>">
            </div>

            <div class="col-md-4">
                <label for="supervisorContact" class="form-label">Supervisor Contact:</label>
                <input type="text" class="form-control" id="supervisorContact" name="supervisorContact" value="<?php echo $supervisorContact; ?>">
            </div>

            <div class="col-md-4">
                <label for="supervisorEmail" class="form-label">Supervisor Email:</label>
                <input type="email" class="form-control" id="supervisorEmail" name="supervisorEmail" value="<?php echo $supervisorEmail; ?>">
            </div>
        </div>

        <div class="mb-3">
            <label for="facilityDetails" class="form-label">Facility Details:</label>
            <textarea class="form-control" id="facilityDetails" name="facilityDetails" rows="4" required><?php echo $facilityDetails; ?></textarea>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="imageName" class="form-label">Image File Name:</label>
                <input type="text" class="form-control" id="imageName" name="imageName" value="<?php echo $imageName; ?>" required>
            </div>

            <div class="col-md-6">
                <label for="facilityImage" class="form-label">Facility Image:</label>
                <input type="file" class="form-control" id="facilityImage" name="facilityImage" required>
            </div>
        </div>

        <div class="mb-3">
            <input type="submit" class="btn btn-primary" value="Add Facility">
        </div>

    </form>

</main>


    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>