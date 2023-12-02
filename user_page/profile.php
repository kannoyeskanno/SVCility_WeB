



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        integrity="sha384-rbs5IzVb1Io4f1Xe2STlqziDhv1erLcCXPnXoeyTBRXcbC6L8voISq5C4bJ4l" crossorigin="anonymous">
    <link rel="icon" href="../resources/logo/svcility_icon.png" type="image/x-icon">

    <link rel="stylesheet" href="../css/style.css">
    <title>User Profile</title>
    <style>
        /* Your additional or custom styles */

        .profile-container {
            max-width: 600px;
            margin: 50px auto 80px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 80px;
            z-index: 1;
            position: relative;
        }

        .profile-header h1 {
            color: #333;
        }

        .profile-details p {
            font-size: 16px;
            margin-bottom: 10px;
            color: #555;
        }

        .profile-details strong {
            color: #333;
        }

        .edit-profile-link a {
            color: #333;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }

        .editable-form {
            display: none;
            margin-top: 20px;
        }
    </style>
</head>

<body>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'path/to/error.log');


        session_start();
        include('../dbConnect.php');
        include('../header.php'); 

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
        $sql = "SELECT * FROM client WHERE id = $user_id";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $officeName = $row['office_org'];
            $lastName = $row['lname'];
            $firstName = $row['fname'];
            $middleName = $row['mname'];
            $email = $row['email'];
            $acc_stat = $row['account_stat'];

        } else {
            echo "User data not found.";
        }        
    ?>

    <div class="container-sm">
        <div class="profile-container">
            <div class="profile-header text-center">
                <h1>User Profile</h1>
            </div>
            <div class="profile-details">
                <p><strong>Office/Organization:</strong> <?php echo $officeName; ?></p>
                <p><strong>Last Name:</strong> <?php echo $lastName; ?></p>
                <p><strong>First Name:</strong> <?php echo $firstName; ?></p>
                <p><strong>Middle Name:</strong> <?php echo $middleName; ?></p>
                <p><strong>Email:</strong> <?php echo $email; ?></p>
                <p><strong>Account Status:</strong> <?php echo $acc_stat; ?></p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Edit</button>

      
        </div>
    </div>

<!-- ... Your existing HTML ... -->

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Update Profile</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="profileEditForm">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Office/Organization:</label>
                        <input type="text" class="form-control" id="recipient-name" name="officeName">
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Last Name:</label>
                        <input type="text" class="form-control" id="message-text" name="lastName">
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">First Name:</label>
                        <input type="text" class="form-control" id="message-text" name="firstName">
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Middle Name:</label>
                        <input type="text" class="form-control" id="message-text" name="middleName">
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Email:</label>
                        <input type="email" class="form-control" id="message-text" name="email">
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateProfile()">Update</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Function to update selected date with AJAX
        function updateSelectedDate(selectedDate) {
            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: {
                    selectedDate: selectedDate
                },
                success: function (response) {
                    console.log('Server response:', response);
                    // Clear existing content including datepicker before updating
                    $('#selected-date').html(response).find('#datepicker').datepicker('destroy');
                    // Update the selected-date div with the server response
                    $('#selected-date').html(response);
                },
                error: function (error) {
                    console.error('Error:', error);
                }
            });
        }

        // Set the initial value of the datepicker to today's date
        var today = new Date();
        $('#datepicker').datepicker('setDate', today);

        // Initial AJAX call on page load
        updateSelectedDate(today);

        // Add click event for the Confirm Date button
        $('#confirmDate').click(function () {
            var selectedDate = $('#datepicker').val();
            updateSelectedDate(selectedDate);
        });

        // Add onSelect event for the datepicker
        $('#datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function (dateText, inst) {
                console.log('Date selected:', dateText);
                // Update the value of the datepicker with the selected date
                $(this).datepicker('setDate', dateText);
                // Update selected date with AJAX
                updateSelectedDate(dateText);
            }
        });

        // Populate modal fields with current values when "Edit" is clicked
        $('#exampleModal').on('show.bs.modal', function () {
            $('#profileEditForm [name="officeName"]').val('<?php echo $officeName; ?>');
            $('#profileEditForm [name="lastName"]').val('<?php echo $lastName; ?>');
            $('#profileEditForm [name="firstName"]').val('<?php echo $firstName; ?>');
            $('#profileEditForm [name="middleName"]').val('<?php echo $middleName; ?>');
            $('#profileEditForm [name="email"]').val('<?php echo $email; ?>');
        });
    });

    function updateProfile() {
        Perform AJAX request to update the profile
        You can use the values from the form fields
        Example:
        var formData = $('#profileEditForm').serialize();
        $.ajax({
            type: 'POST',
            url: 'update_profile.php',
            data: formData,
            success: function (response) {
                console.log('Profile updated:', response);
                // You might want to update the UI or take other actions upon success
            },
            error: function (error) {
                console.error('Error updating profile:', error);
            }
        });
    }
</script>


    <!-- Bootstrap JS (Popper.js and Bootstrap JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-btbD2MgDs/81I9U3eXwmWSN88xVi9eBvc/IqF+uB1Z/BqUjsrEWrE2z3NoEN2fTb" crossorigin="anonymous"></script>
</body>

</html>
