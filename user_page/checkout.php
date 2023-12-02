<?php
include('../dbConnect.php'); // Include your database connection code

session_start();

// if (!isset($_SESSION['user_id'])) {
//     echo '<script>
//         if (confirm("You must log in or create an account to access this page. Click OK to log in.")) {
//             window.location.href = "../index.php"; // Redirect to your login page
//         } else {
//             // Redirect or take any other action if the user clicks Cancel
//         }
//     </script>';
//     exit;
// }


$selectedDate = isset($_GET['selectedDate']) ? $_GET['selectedDate'] : '';
echo $selectedDate;
$user_id = $_SESSION['user_id'];
$sql = "SELECT office_org, lname, fname, mname FROM client WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $officeName = $row['office_org'];
    $lastName = $row['lname'];
    $firstName = $row['fname'];
    $middleName = $row['mname'];
} else {
    echo "User data not found.";
}

$selectedItems = isset($_GET['items']) ? $_GET['items'] : '';
$selectedItemsArray = explode(',', $selectedItems);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['pdf_file'])) {
    
    $file_name = $_FILES['pdf_file']['name'];
    $file_tmp = $_FILES['pdf_file']['tmp_name'];
    $file_destination = '../resources/file' . $file_name;

    if (move_uploaded_file($file_tmp, $file_destination)) {
        // File uploaded successfully, now store the file information in the database
        $sql = "INSERT INTO files (file_name, file_path) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $file_name, $file_destination);
        
        if ($stmt->execute()) {
            $message = "File uploaded and stored in the database successfully.";
        } else {
            $error = "Error storing file information in the database.";
        }
        $stmt->close();
    } else {
        $error = "Error moving the uploaded file.";

    }

}

$sql = "SELECT * FROM files";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $files = $result->fetch_all(MYSQLI_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
    <!-- <link rel="stylesheet" href="../css/user_page/checkout.css"> -->
    <style>
        body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    color: #333;
}

.container {
    margin: 20px auto;
    max-width: 95%;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #ddd; 
}

.dots {
    display: flex;
    margin-bottom: 20px;
    border: solid;
    width: 40px;
}

.dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    margin: 0 5px;
    background-color: #bbb;
    border-radius: 50%;
}

.dot1.active,
.dot2.active {
    background-color: #4CAF50;
}

.page {
    display: none;
}

.active {
    display: block;
}

h1 {
    color: #333;
    text-align: center;
}

ul {
    list-style: none;
    padding: 0;
}

li {
    margin-bottom: 10px;
}

.image-container {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    margin-right: 50px;
}

.image {
    width: 70px;
    height: 60px;
    background-size: cover;
    background-position: center;
    margin-right: 10px;
}

.name {
    font-weight: bold;
    font-size: 12px;
}

.form-group {
    margin-bottom: 20px; 
}

input {
    width: 100%;
    padding: 12px;
    box-sizing: border-box;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    transition: border-color 0.3s;
}

input:focus {
    border-color: #4CAF50;
}

.btn {
    background-color: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-size: 14px;
    padding: 10px 20px;
    transition: background-color 0.3s;
    margin-left: 20px;
    margin-top: 5px;
}

.btn:hover {
    background-color: #45a049; 
}

.file-upload {
    margin-top: 20px;
}

.success-message {
    color: #4CAF50;
}

.error-message {
    color: #f44336;
}

.file-list {
    list-style: none;
    padding: 0;
}

.file-list li {
    margin-bottom: 10px;
}

.file-list a {
    color: #4CAF50;
    text-decoration: none;
    font-weight: bold;
}

.file-list a:hover {
    text-decoration: underline;
}

.form-and-upload-container {
    display: flex;
    justify-content: space-between;
}

.form-container {
    width: 48%;
}

.file-upload {
    width: 48%;
}

.choice_list {
    background-color: #4CAF50;
    color: #fff;
    padding: 12px 16px;
    margin-right: 8px;
    margin-bottom: 8px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}

.choice_list:hover {
    background-color: #45a049;
}

.purpose {
    border: 1px solid #ddd; 
    padding: 10px;
}

ul {
    list-style: none;
    padding: 0;
}

li {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    padding: 15px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.quantity {
    margin-left: 50px;
}





#surenames {
    display: flex;

}

#surenames input {
}

.fname {
    margin-right: 20px;
}


#page2 .form-container{
    display: flex;
    border: solid;
    width: 100%;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="dots">
            <span class="dot dot1 active"></span>
            <span class="dot dot2"></span>
        </div>

        <div class="page page1 active">
            <div>
                <h1>Welcome to Checkout, <?php echo $firstName . ' ' . $lastName; ?>!</h1>
                <p>Your selected items:</p>
                <ul>
                <?php
foreach ($selectedItemsArray as $item) {
    $sql = "SELECT * FROM list_request WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $facility_id = $row['facility_id'];
            $equipment_id = $row['equipment_id'];

            // Fetch facility details based on facility_id
            $facility_sql = "SELECT facility_name, image_name FROM facility WHERE id = ?";
            $facility_stmt = $conn->prepare($facility_sql);
            $facility_stmt->bind_param("i", $facility_id);
            $facility_stmt->execute();
            $facility_result = $facility_stmt->get_result();

            if ($facility_result->num_rows > 0) {
                $facility_row = $facility_result->fetch_assoc();
                $facility_name = $facility_row['facility_name'];
                $facility_image_name = $facility_row['image_name'];

                $facilityImagePath = '../resources/facility_imgs/' . $facility_image_name . '.png';

                echo '<li>';
                echo '<div class="image-container">';
                echo '<div class="image" style="background-image: url(\'' . $facilityImagePath . '\');"></div>';
                echo '</div>';
                echo '<div class="name">' . $facility_name . '</div>';
                echo '</li>';
            }

            // Fetch equipment details based on equipment_id
            $equipment_sql = "SELECT * FROM equipment WHERE id = ?";
            $equipment_stmt = $conn->prepare($equipment_sql);
            $equipment_stmt->bind_param("i", $equipment_id);
            $equipment_stmt->execute();
            $equipment_result = $equipment_stmt->get_result();

            if ($equipment_result->num_rows > 0) {
                while ($equipment_row = $equipment_result->fetch_assoc()) {
                    $equipment_name = $equipment_row['equipment_name'];
                    $equipment_image_name = $equipment_row['image_name'];

                    $equipmentImagePath = '../resources/equipment_imgs/' . $equipment_image_name . '.png';

                    echo '<li>';
                    echo '<div class="image-container">';
                    echo '<div class="image" style="background-image: url(\'' . $equipmentImagePath . '\');"></div>';
                    echo '</div>';
                    echo '<div class="name">' . $equipment_name . '</div>';
                    echo '<div class="quantity">';
                    echo '<div class="quantity">' . $row['qty'] . '</div>';
                    echo '</div>';
                    echo '</li>';
                }
            }
            $equipment_stmt->close();
            $facility_stmt->close();
        }
    } else {
        echo "No facilities reserved for this user.";
    }
}
?>

                </ul>
            </div>

            <h1>Profile Info</h1>
            <form id="form1" action="show_result.php" method="post">
                <div class="form-group">
                    <label for="officeName">Office Name:</label>
                    <input type="text" id="officeName" name="officeName" readonly value="<?php echo $officeName; ?>">
                </div>

                <div class="form-group">
                        <label for="lastName">Last Name:</label>
                        <input type="text" id="lastName" name="lastName" readonly value="<?php echo $lastName; ?>">
                </div>

                <div class="form-group" id='surenames'>
                    <div class='fname'>
                        <label for="firstName">First Name:</label>
                        <input type="text" id="firstName" name="firstName" readonly value="<?php echo $firstName; ?>">
                    </div>

                    <div>
                        <label for="middleName">Middle Name:</label>
                        <input type="text" id="middleName" name="middleName" readonly value="<?php echo $middleName; ?>">
                    </div>
              
                    
                </div>
               
                <div class="form-group">
                    <label for="phoneNumber">Phone Number:</label>
                    <input type="tel" id="phoneNumber" name="phoneNumber" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button id="nextButton1" class="btn">Next</button>
            </form>
            <div class="file-upload"></div>
        </div>

        <div class="page page2" id="page2">
            <div class="form-and-upload-container">
                <div class="form-container">
    <div class="right">
    <h1>Page 2</h1>
                    <form id="form2" action="show_result.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" required>
    </div>

    <div class="form-group">
        <label for="purpose">Purpose:</label>
        <select id="purpose" name="purpose" class="btn">
            <option value="Purpose A">Purpose A</option>
            <option value="Purpose B">Purpose B</option>
            <option value="Purpose C">Purpose C</option>
            <option value="Purpose D">Purpose D</option>
            <option value="Purpose E">Purpose E</option>
            <option value="Others">Others</option>
        </select>
    </div>

    <div class="form-group">
        <label for="datepicker">Selected Date:</label>
        <input type="text" id="datepicker" name="datepicker" value="<?php echo htmlspecialchars($selectedDate); ?>" readonly>
    </div>
    
    <!-- Add this hidden input to pass the selected items to the server -->
    <input type="hidden" name="selectedItems" value="<?php echo htmlspecialchars(implode(',', $selectedItemsArray)); ?>">

    </div>
    <div class="left">
        <label for="pdf_file" class="file-label">Select a PDF file:</label>
        <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" class="file-input" required>

        <button id="prevButton2" class="btn">Previous</button>
        <button type="submit" class="btn" name="submitForm">Submit</button>
    </div>
</form>


                </div>
                

            </div>
        </div>
    </div>
    <script src="js/list_submission_form.js"></script>
</body>
</html>
