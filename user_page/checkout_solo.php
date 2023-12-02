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

if (isset($_GET['facilityId']) ? $_GET['facilityId'] : '') {
    $facilityItem = (isset($_GET['facilityId']) ? $_GET['facilityId'] : '');

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
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
                <h1 class="mt-3">Welcome to Checkout, <?php echo $firstName . ' ' . $lastName; ?>!</h1>
                <p>Your selected items:</p>
                <ul class="list-unstyled">
                    <?php
                    // Fetch facility details based on facility_id
                    $facility_sql = "SELECT * FROM facility WHERE id = $facilityItem";
                    $facility_result = $conn->query($facility_sql);

                    if ($facility_result->num_rows > 0) {
                        $facility_row = $facility_result->fetch_assoc();
                        $facility_name = $facility_row['facility_name'];
                        $facility_image_name = $facility_row['image_name'];

                        $facilityImagePath = '../resources/facility_imgs/' . $facility_image_name . '.png';

                        echo '<li class="mb-3">';
                        echo '<div class="d-flex align-items-center">';
                        echo '<div class="image-container me-3">';
                        echo '<div class="image" style="background-image: url(\'' . $facilityImagePath . '\');"></div>';
                        echo '</div>';
                        echo '<div class="name">' . $facility_name . '</div>';
                        echo '</div>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>

            <h1 class="mt-4">Profile Info</h1>
            <form id="form1" action="#" method="post">
                <div class="mb-3">
                    <label for="officeName" class="form-label">Office Name:</label>
                    <input type="text" id="officeName" name="officeName" readonly class="form-control" value="<?php echo $officeName; ?>">
                </div>
                <div class="mb-3">
                    <label for="lastName" class="form-label">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" readonly class="form-control" value="<?php echo $lastName; ?>">
                </div>
                <div class="mb-3">
                    <label for="firstName" class="form-label">First Name:</label>
                    <input type="text" id="firstName" name="firstName" readonly class="form-control" value="<?php echo $firstName; ?>">
                </div>
                <div class="mb-3">
                    <label for="middleName" class="form-label">Middle Name:</label>
                    <input type="text" id="middleName" name="middleName" readonly class="form-control" value="<?php echo $middleName; ?>">
                </div>
                <div class="mb-3">
                    <label for="phoneNumber" class="form-label">Phone Number:</label>
                    <input type="tel" id="phoneNumber" name="phoneNumber" required class="form-control">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" required class="form-control">
                </div>
                <button id="nextButton1" class="btn btn-primary">Next</button>
            </form>
            <div class="file-upload"></div>
        </div>

        <div class="page page2" id="page2">
            <div class="form-and-upload-container">
                <div class="form-container">
                    <h1>Page 2</h1>
                    <form id="form2" action="show_result_solo.php" method="post" enctype="multipart/form-data">

                        <!-- Additional hidden input for selected items -->
                        <input type="hidden" name="facilityId" value="<?php echo $facilityItem; ?>">

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject:</label>
                            <input type="text" id="subject" name="subject" required class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose:</label>
                            <select id="purpose" name="purpose" class="form-select">
                                <option value="Purpose A">Purpose A</option>
                                <option value="Purpose B">Purpose B</option>
                                <option value="Purpose C">Purpose C</option>
                                <option value="Purpose D">Purpose D</option>
                                <option value="Purpose E">Purpose E</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="datepicker" class="form-label">Select Date:</label>
                            <input type="date" id="datepicker" name="datepicker" required class="form-control">
                        </div>

                        <label for="pdf_file" class="file-label">Select a PDF file:</label>
                        <input type="file" name="pdf_file" id="pdf_file" accept=".pdf, .jpeg, .jpg, .png" class="form-control">

                        <button id="prevButton2" class="btn btn-secondary">Previous</button>
                        <button type="submit" class="btn btn-primary" name="submitForm">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js (required for Bootstrap's JavaScript plugins) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
