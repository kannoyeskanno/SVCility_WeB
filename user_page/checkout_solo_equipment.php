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

$selectedDate = isset($_REQUEST['selectedDate']) ? $_REQUEST['selectedDate'] : '';

$soloDate = $_SESSION['soloDate'];

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

if (isset($_GET['equipmentID'])) {
    $equipmentId = $_GET['equipmentID'];
    $qty = isset($_GET['qty']) ? $_GET['qty'] : 0;

    // Now you can use $equipmentId and $qty as needed
} else {
    // Handle the case when 'equipmentID' is not set in the URL
    echo "Error: Equipment ID is not set in the URL.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
    <link rel="stylesheet" href="../css/user_page/checkout.css">
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
                    // Fetch facility details based on facility_id
                    $equipment_sql = "SELECT * FROM equipment WHERE id = $equipmentId";
                    $equipment_result = $conn->query($equipment_sql);

                    if ($equipment_result->num_rows > 0) {
                        $equipment_row = $equipment_result->fetch_assoc();
                        $equipment_name = $equipment_row['equipment_name'];
                        $equipment_image_name = $equipment_row['image_name'];

                        $equipmentImagePath = '../resources/equipment_imgs/' . $equipment_image_name . '.png';

                        echo '<li>';
                        echo '<div class="image-container">';
                        echo '<div class="image" style="background-image: url(\'' . $equipmentImagePath . '\');"></div>';
                        echo '</div>';
                        echo '<div class="name">' . $equipment_name . '</div>';
                        echo '<div class="input">';
                        echo '<input type="number" id="equipment_quantity" name="equipment_quantity" min="1" value="' . $qty . '" readonly>';
                        echo '</div>';
                        echo '</li>';
                    }
                    ?>
                </ul>

                <!-- Display equipment quantity -->
                <p>Quantity: <span id="displayQuantity">1</span></p>
            </div>

            <h1>Profile Info</h1>
            <form id="form1" action="#" method="post">
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
                <button id="nextButton1">Next</button>
            </form>
            <div class="file-upload"></div>
        </div>

        <div class="page page2" id="page2">
            <div class="form-and-upload-container">
                <div class="form-container">
                    <h1>Page 2</h1>
                    <form id="form2" action="show_result_solo_equipment.php" method="post" enctype="multipart/form-data">
                        <!-- Additional hidden input for selected items -->
                        <input type="hidden" name="equipmentId" value="<?php echo $equipmentId; ?>">
                        <!-- Hidden input for equipment quantity -->
                        <input type="hidden" name="equipment_quantity" id="hidden_quantity" value="1">

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

                        
                        <label for="pdf_file" class="file-label">Select a PDF file:</label>
                        <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" class="file-input" required>


                        <div class="form-group">
                            <label for="datepicker">Select Date:</label>
                            <input type="text" id="datepicker" name="datepicker" value="<?php echo $soloDate; ?>" readonly>
                        </div>

                        <label for="pdf_file" class="file-label">Select a PDF file:</label>
                        <input type="file" name="pdf_file" id="pdf_file" accept=".pdf, .jpeg, .jpg, .png" class="form-control">

                        <button id="prevButton2" class="btn">Previous</button>
                        <button type="submit" class="btn" name="submitForm">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const form1 = document.getElementById("form1");
        const nextButton1 = document.getElementById("nextButton1");
        const form2 = document.getElementById("form2");
        const prevButton2 = document.getElementById("prevButton2");
        const nextButton2 = document.getElementById("nextButton2");
        const dot1 = document.querySelector(".dot1");
        const dot2 = document.querySelector(".dot2");

        nextButton1.addEventListener("click", (e) => {
            e.preventDefault();

            // Update the hidden input value with the equipment quantity
            const quantityInput = document.getElementById("equipment_quantity");
            document.getElementById("hidden_quantity").value = quantityInput.value;

            // Display the quantity on page 1
            document.getElementById("displayQuantity").innerText = quantityInput.value;

            document.querySelector(".page1").classList.remove("active");
            document.querySelector(".page2").classList.add("active");
            dot2.classList.add("active");
        });

        prevButton2.addEventListener("click", (e) => {
            e.preventDefault();
            document.querySelector(".page1").classList.add("active");
            document.querySelector(".page2").classList.remove("active");
            dot1.classList.add("active");
            dot2.classList.remove("active");
        });

        nextButton2.addEventListener("click", (e) => {
            e.preventDefault();
            if (document.getElementById("subject").value === "") {
                // Subject is blank, show a message or take necessary action
                alert("Subject is required!");
            } else {
                // Proceed with form submission
                form2.submit();
            }
        });
    </script>
</body>
</html>
