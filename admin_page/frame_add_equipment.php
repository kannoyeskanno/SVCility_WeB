<?php
include '../dbConnect.php';

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

// function imageNameExists($imageName) {
//     global $conn;

//     $sql = "SELECT * FROM facility WHERE image_name = ?";
//     $stmt = $conn->prepare($sql);
//     if ($stmt) {
//         $stmt->bind_param("s", $imageName);
//         $stmt->execute();
//         $result = $stmt->get_result();
//         return $result->num_rows > 0;
//     }

//     return false;
// }

// function insertDataIntoDatabase($facilityName, $location, $type, $capacity, $supervisorName, $supervisorContact, $supervisorEmail, $facilityDetails, $imageName) {
//     global $conn;

//     $sql = "INSERT INTO facility (facility_name, location, type, capacity, supervisor_name, contact, email, details, image_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
//     $stmt = $conn->prepare($sql);

//     if ($stmt) {
//         $stmt->bind_param("sssssssss", $facilityName, $location, $type, $capacity, $supervisorName, $supervisorContact, $supervisorEmail, $facilityDetails, $imageName);
//         if ($stmt->execute()) {
//             return true;
//         }
//     }

//     return false;
// }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"> -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <title>Document</title>


    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.2/js/bootstrap.min.js"></script>


    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        

        /* #iframe_content {
            top: 20px;
            position: relative;

        }

        li {
            border: solid;
            height: 80px;
        }

        .sidebar .nav-link.active {
            background-color: lightgreen;
        }

        .nav-link {
            width: 100%;
            height: 100%;
        }
      */
        

    </style>
</head>
<body>
      
         


                
            <?php
                include '../dbConnect.php';

                $equipmentName = $qty = $supervisorName = $supervisorContact = $supervisorEmail = $equipmentDetails = $imageName = '';

                $errors = array();

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $equipmentName = $_POST['equipmentName'];
                    $qty = $_POST['qty'];
                    $supervisorName = $_POST['supervisorName'];
                    $supervisorContact = $_POST['supervisorContact'];
                    $supervisorEmail = $_POST['supervisorEmail'];
                    $equipmentDetails = $_POST['equipmentDetails'];
                    $imageName = $_POST['equipmentImageName'];

                    if (imageNameExists($imageName)) {
                        $errors[] = "An equipment with the same image name already exists. Please choose a different image name.";
                    }

                    if (isset($_FILES['equipmentImage']) && empty($errors)) {
                        $file = $_FILES['equipmentImage'];
                        $file_name = $file['name'];
                        $file_tmp = $file['tmp_name'];
                        $file_error = $file['error'];

                        $file_name_without_extension = pathinfo($file_name, PATHINFO_FILENAME);

                        $file_ext = 'png'; 
                        $allowed = array('jpg', 'jpeg', 'png', 'gif');
                        if (in_array($file_ext, $allowed) && $file_error === 0) {
                            $file_destination = '../resources/equipment_imgs/' . $imageName . '.' . $file_ext;
                            move_uploaded_file($file_tmp, $file_destination);

                            if (insertDataIntoDatabase($equipmentName, $qty, $supervisorName, $supervisorContact, $supervisorEmail, $equipmentDetails, $imageName)) {
                                // header("Location: inventory.php");
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

                    $sql = "SELECT * FROM equipment WHERE image_name = ?";
                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param("s", $imageName);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        return $result->num_rows > 0;
                    }

                    return false;
                }

                function insertDataIntoDatabase($equipmentName, $qty, $supervisorName, $supervisorContact, $supervisorEmail, $equipmentDetails, $imageName) {
                    global $conn;

                    $sql = "INSERT INTO equipment (equipment_name, qty, supervisor_name, supervisor_contact, supervisor_email, equipment_details, image_name) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);

                    if ($stmt) {
                        $stmt->bind_param("sssssss", $equipmentName, $qty, $supervisorName, $supervisorContact, $supervisorEmail, $equipmentDetails, $imageName);
                        if ($stmt->execute()) {
                            return true;
                        }
                    }

                    return false;
                }
                ?>


                <h1>Add Equipment</h1>
            
            <?php
                if (!empty($errors)) {
                    echo '<ul>';
                    foreach ($errors as $error) {
                        echo '<li>' . $error . '</li>';
                    }
                    echo '</ul>';
                }
                ?>

<div class="container mt-4">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="equipmentName" class="form-label">Equipment Name:</label>
            <input type="text" class="form-control" id="equipmentName" name="equipmentName" value="" required>
        </div>

        <div class="mb-3">
            <label for="qty" class="form-label">Quantity:</label>
            <input type="number" class="form-control" id="qty" name="qty" value="" required>
        </div>

        <div class="mb-3">
            <label for="supervisorName" class="form-label">Supervisor Name:</label>
            <input type="text" class="form-control" id="supervisorName" name="supervisorName" value="">
        </div>

        <div class="mb-3">
            <label for="supervisorContact" class="form-label">Supervisor Contact:</label>
            <input type="text" class="form-control" id="supervisorContact" name="supervisorContact" value="" >
        </div>

        <div class="mb-3">
            <label for="supervisorEmail" class="form-label">Supervisor Email:</label>
            <input type="email" class="form-control" id="supervisorEmail" name="supervisorEmail" value="">
        </div>

        <div class="mb-3">
            <label for="equipmentDetails" class="form-label">Equipment Details:</label>
            <textarea class="form-control" id="equipmentDetails" name="equipmentDetails" rows="4"></textarea>
        </div>

        <div class="mb-3">
            <label for="equipmentImageName" class="form-label">Image File Name:</label>
            <input type="text" class="form-control" id="equipmentImageName" name="equipmentImageName" value="" required>
        </div>

        <div class="mb-3">
            <label for="equipmentImage" class="form-label">Equipment Image:</label>
            <input type="file" class="form-control" id="equipmentImage" name="equipmentImage" required>
        </div>

        <div class="mb-3">
            <input type="submit" class="btn btn-primary" value="Add Equipment">
        </div>
    </form>
</div>



            </main>

        </div>
    
     
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function changeIframeSrc(target, src, clickedLink) {
            const links = document.querySelectorAll('.facilities_equipment_nav a');
            links.forEach(link => link.classList.remove('active'));
            
            clickedLink.classList.add('active');
            
            document.getElementById(target).src = src;
        }
    </script>
</body>
</html>








