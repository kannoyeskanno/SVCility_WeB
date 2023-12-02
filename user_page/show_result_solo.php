
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Add your CSS styling for the modal -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <style>
        /* Additional styling to center the modal and customize appearance */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            margin: 0;
        }

        .modal-content {
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            border: none;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-body {
            text-align: center;
            padding: 20px;
        }

        .check-icon {
            font-size: 50px;
            color: #28a745;
            animation: fadeInScale 0.5s ease-in-out;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.5);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../dbConnect.php');

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

$user_id = $_SESSION['user_id'];
$sql = "SELECT office_org, lname, fname, mname, contact_number, email FROM client WHERE id = $user_id";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $officeName = $row['office_org'];
        $lastName = $row['lname'];
        $firstName = $row['fname'];
        $middleName = $row['mname'];
        $phoneNumber = $row['contact_number'];
        $email = $row['email'];
    } else {
        echo "User data not found.";
        exit;
    }
} else {
    echo "Error fetching user data: " . $conn->error;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['pdf_file'])) {
    $file_name = $_FILES['pdf_file']['name'];
    $file_tmp = $_FILES['pdf_file']['tmp_name'];
    $file_destination = '../resources/file/' . $file_name;

    if (move_uploaded_file($file_tmp, $file_destination)) {
        $sql = "INSERT INTO files (file_name, file_path, user_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("sss", $file_name, $file_destination, $user_id);

        if ($stmt->execute()) {
            $message = "File uploaded and stored in the database successfully.";
        } else {
            $error = "Error storing file information in the database: " . $stmt->error;

            error_log("SQL Error: " . $error);
        }
        $stmt->close();
    } else {
        $error = "Error moving the uploaded file.";

        error_log("File Upload Error: " . $error);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $purpose = isset($_POST['purpose']) ? $_POST['purpose'] : '';
    $datepicker = isset($_POST['datepicker']) ? $_POST['datepicker'] : '';
    $facilityItem = isset($_POST['facilityId']) ? $_POST['facilityId'] : '';

    if (isset($_POST['facilityId'])) {
    }
    $selectedItemsString = isset($_POST['selectedItems']) ? $_POST['selectedItems'] : '';


    $sql_file = "SELECT id FROM files WHERE user_id = $user_id AND file_name = '$file_name'";
    $result_file = $conn->query($sql_file);

    if ($result_file && $result_file->num_rows > 0) {
        $row_file = $result_file->fetch_assoc();
        $file_id = $row_file['id'];
        $value = 'submitted';
        $sql_request = "INSERT INTO request (facility_id, subject, date, purpose, user_id, file, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_request);
        
        $stmt->bind_param("sssssis", $facilityItem, $subject, $datepicker, $purpose, $user_id, $file_id, $value);
        

        if ($stmt->execute()) {
            $message = "Request information stored in the database successfully.";
        } else {
            $error = "Error storing request information in the database: " . $stmt->error;

            error_log("SQL Error: " . $error);
        }
        $stmt->close();
    } else {
        echo "File data not found.";
    }

    $sql = "SELECT id FROM request ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastRequestId = $row['id'];

    $li = 'http://localhost/SVCIlity/admin_page/view_request.php?request_id=' . $lastRequestId;

    echo '<div id="myForm">';
    echo '<input type="hidden" name="subject" value="' . htmlspecialchars($subject) . '">';
    echo '<input type="hidden" name="purpose" value="' . htmlspecialchars($purpose) . '">';
    echo '<input type="hidden" name="datepicker" value="' . htmlspecialchars($datepicker) . '">';
    echo '<input type="hidden" name="link" value="' . htmlspecialchars($li) . '">';
    echo '<input type="hidden" name="email" value="' . htmlspecialchars($email) . '">';

    echo '</div>';
    } 




} else {
    echo "File data not found.";

    
}
?>

    <?php
    echo '<script>
        // Function to show the modal
        function showModal() {
            var modalOverlay = document.querySelector(".modal-overlay");
            var modal = document.querySelector(".modal");

            modalOverlay.style.display = "flex";
            modal.classList.add("fadeIn");
        }

        // Call showModal() when the record is deleted successfully
        showModal();
    </script>';
    ?>


<div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body">
                <div class="check-icon">&#10003;</div>
                <p class="mt-3">Your form has been submitted successfully!</p>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="redirectToPage()">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function () {
            $('#exampleModal').modal('show');
        });

    function redirectToPage() {
        window.location.href = 'request_status.php';
    }
    function sendFormData() {
        var formData = new URLSearchParams();
        formData.append("subject", document.getElementById("myForm").querySelector('[name="subject"]').value);
        formData.append("purpose", document.getElementById("myForm").querySelector('[name="purpose"]').value);
        formData.append("datepicker", document.getElementById("myForm").querySelector('[name="datepicker"]').value);
        formData.append("link", document.getElementById("myForm").querySelector('[name="link"]').value);
        formData.append("email", document.getElementById("myForm").querySelector('[name="email"]').value);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "test.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            console.log("ReadyState:", xhr.readyState);
            console.log("Status:", xhr.status);
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log("Response:", xhr.responseText);
            }
        };

        xhr.send(formData.toString());
    }

    sendFormData();
</script>
</body>
</html>


