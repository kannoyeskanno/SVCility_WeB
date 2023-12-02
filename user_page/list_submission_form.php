<?php
include('../dbConnect.php'); // Include your database connection code

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

// Fetch user data based on the session user_id
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


if (isset($_GET['items'])) {
    $selectedItems = explode(',', $_GET['items']);

    // Display the selected item IDs
    echo "<h2>Selected Items:</h2>";
    echo "<ul>";
    foreach ($selectedItems as $itemId) {
        echo "<li>Item ID: $itemId</li>";
        // You can fetch additional details from the database based on $itemId
    }
    echo "</ul>";

    // Proceed with further processing or checkout logic here
} else {
    // No selected items, display a message or redirect
    echo "<p>No items selected for checkout.</p>";
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['pdf_file'])) {
    $file_name = $_FILES['pdf_file']['name'];
    $file_tmp = $_FILES['pdf_file']['tmp_name'];
    $file_destination = 'upload_folder/' . $file_name;

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

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
</head>
<style>
    /* Reset some default styles */
    body, html {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        height: 100%;
        overflow: hidden;
    }

    .container {
        width: 100%;
        height: 100%;
        display: flex;
    }

    .page {
        flex: 1;
        display: none;
        padding: 20px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 20px;
        overflow-y: auto;
        position: relative;
        top: 40px;
        right: 30px;
    }

    .page.active {
        display: block;
    }

    .dots {
        text-align: center;
        margin: 20px 0;
    }

    .dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        margin: 0 5px;
        border-radius: 50%;
        background: gray;
        cursor: pointer;
        position: relative;
        left: 20px;
    }

    .dot.active {
        background: blue;
    }

    .form-group {
        margin: 10px 0;
    }

    label {
        display: block;
        font-weight: bold;
    }

    input[type="text"],
    input[type="tel"],
    input[type="email"],
    input[type="date"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    button.btn {
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        font-weight: bold;
    }

    button.btn:hover {
        background-color: #0056b3;
    }

    .choice_list {
        margin: 5px 0;
        padding: 5px;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

   

    .page2 .file-upload {
        width: 100%;
        float: right;
        padding: 20px;
        border: solid;
        border-radius: 20px;
        background-color: #ffff;
      

        height: 100vh;

    }
    #form2 {
        border: solid;
    }
    .file-upload label {
        display: block;
        font-weight: bold;
    }

    .file-upload input[type="file"] {
        width: 100%;
    }

    .file-upload input[type="submit"] {
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        font-weight: bold;
    }

    .file-upload input[type="submit"]:hover {
        background-color: #0056b3;
    }


    #form2 {
        width: 30%;
    }

    .purpose {
        width: 100%;
        height: 100px;
        border: solid;
    }

    /* Add this CSS to your existing CSS code */
#page2 {
    display: flex; /* Use flexbox to arrange children horizontally */
    justify-content: space-between; /* Add space between children */
    align-items: flex-start; /* Align items to the top of the container */
}

.form-container {
    flex: 1; /* Allow the form to grow and take available space */
    padding-right: 20px; /* Add some space between the form and file upload */
}

.file-upload {
    flex: 1; /* Allow the file upload section to grow and take available space */
}



</style>
<body>
    <div class="container">
        <div class="dots">
            <span class="dot dot1 active"></span>
            <span class="dot dot2"></span>
        </div>

        <div class="page page1 active">
            <h1>Profile Info</h1>
            <form id="form1">
                <div class="form-group">
                    <label for="officeName">Office Name:</label>
                    <input type="text" id="officeName" name="officeName" readonly value="<?php echo $officeName; ?>">
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" readonly value="<?php echo $lastName; ?>">
                </div>
                <div class="form-group">
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName" readonly value="<?php echo $firstName; ?>">
                </div>
                <div class="form-group">
                    <label for="middleName">Middle Name:</label>
                    <input type="text" id="middleName" name="middleName" readonly value="<?php echo $middleName; ?>">
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
            <h1>Page 2</h1>
            <form id="form2">
                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                <div class="form-group">
                    <label for="purpose">Purpose:</label>
                </div>
                <div class="form-group">
                    <div class="purpose" id="purpose"></div>
                    <div class="choice_list"></div>
                    <div id="choices-container">
                        <button id="purpose_a" class="btn">Purpose A</button>
                        <button id="purpose_b" class="btn">Purpose B</button>
                        <button id="purpose_c" class="btn">Purpose C</button>
                        <button id="purpose_d" class="btn">Purpose D</button>
                        <button id="purpose_e" class="btn">Purpose E</button>
                        <button id="purpose_others" class="btn">Others</button>
                    </div>
                </div>
                <button id="prevButton2" class="btn">Previous</button>
                <button id="nextButton2" class="btn">Submit</button>
            </form>
        </div>
        <div class="file-upload">
            <h1>File Upload</h1>
            <?php if (isset($message)) : ?>
                <p style="color: green;"><?php echo $message; ?></p>
            <?php endif; ?>
            <?php if (isset($error)) : ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="list_submission_form.php" method="post" enctype="multipart/form-data">
                <label for="pdf_file">Select a PDF file:</label>
                <input type="file" name="pdf_file" accept=".pdf">
                <input type="submit" value="Upload">
            </form>
            <?php if (isset($files) && count($files) > 0) : ?>
                <h2>Uploaded Files</h2>
                <ul>
                    <?php foreach ($files as $file) : ?>
                        <li>
                            <a href="<?php echo $file['file_path']; ?>" target="_blank"><?php echo $file['file_name']; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
    </div>
    <script src="js/list_submission_form.js"></script>
</body>
</html>
