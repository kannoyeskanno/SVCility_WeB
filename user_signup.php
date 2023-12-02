<?php
session_start();

include('dbConnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
    $middleName = isset($_POST['middleName']) ? $_POST['middleName'] : '';
    $orgDept = isset($_POST['orgDept']) ? $_POST['orgDept'] : '';
    $contactNumber = isset($_POST['contactNumber']) ? $_POST['contactNumber'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $emailCheckQuery = "SELECT COUNT(*) as count FROM client WHERE email = '$email'";
    $emailCheckResult = $conn->query($emailCheckQuery);

    if ($emailCheckResult && $emailCheckResult->fetch_assoc()['count'] > 0) {
        echo '<script>alert("Email is already taken. Please choose a different email.");</script>';
    } else {
        $insertQuery = "INSERT INTO client (fname, lname, mname, contact_number, email, password, office_org) 
                        VALUES ('$firstName', '$lastName', '$middleName', '$contactNumber', '$email', '$password', '$orgDept')";

        if ($conn->query($insertQuery) === TRUE) {
            echo '<script>alert("Account created successfully.");</script>';
            header("Location: user_page/facilities_&_equipment.php");
        } else {
            echo '<script>alert("Error creating account: ' . $conn->error . '");</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Bootstrap CSS for a more refined styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: url('resources/logo/san_vicente_logo.png') no-repeat #F4EAE0;
            background-size: center;

        }

        .container {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            max-width: 600px; 
            height: 600px;
            width: 100%;
            display: flex;
            flex-direction: column;
            margin-right: 10px;
            margin-top: 70px;
        }

        .image-container {
            overflow: hidden;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            flex: 1;
            padding: 80px;
        }

        .image-container img {
            width: 100%;
            height: auto;
            object-fit: cover;
            transition: transform 0.5s ease-in-out;
        }

        #form {
            padding: 2em;
            flex: 1;
        }

        #form h2 {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }

        .form-row {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-size: 12px;
        }

        input {
            width: 100%;
            height: 30px;
            padding: 7px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input::placeholder {
            color: #999;
            font-size: 11px;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
            text-align: center;
         }

        p {
            margin-top: 15px;
            font-size: 14px;
            color: #555;
        }

        a {
            color: #007BFF;
            text-decoration: none;
            transition: color 0.3s ease-in-out;
        }

        a:hover {
            color: #0056b3;
        }

        .contacts {
            display: flex;
            flex-wrap: wrap;
        }

        .form-row {
            flex: 1;
            margin-right: 10px;
        }
        

        #svcility_logo {
            height: 90px;
            width: 100%;
            border: solid;
        }

        #header {
            height: 60px;
            border: solid 1px;
            background-color: #9ADE7B;
            border-radius: 5px 0 5px 0;
            margin-bottom: 5px;
        }

        .img_con {

            margin-left: 100px;
            background: url('resources/logo/svcility_logo.png') no-repeat;
            background-size: cover;
            width: 140px;
            height: 140px; 
            top: -60px;
            position: relative;
            display: inline-block;
        }



       

    </style>
</head>
<body>
    <div class="image-container">
        <img src="resources/styles/s1.svg" alt="SVG Image">
        <img src="resources/styles/s2.svg" alt="SVG Image">
    </div>
    <div class="container">
       
        <div id="form">
            <div id="header" class="d-flex p-3">
                <h2>Create Your Account</h2>
                <div class="img_con">

                </div>
            </div>
            <form id="signInForm" method="post" onsubmit="return validateForm()">
                <div class="form-row">
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName" required placeholder="Enter your first name">
                </div>
                <div class="form-row">
                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName" required placeholder="Enter your last name">
                </div>
                <div class="form-row">
                    <label for="middleName">Middle Name:</label>
                    <input type="text" id="middleName" name="middleName" required placeholder="Enter your middle name">
                </div>
                <div class="form-row">
                    <label for="orgDept">Organization or Department:</label>
                    <input type="text" id="orgDept" name="orgDept" required placeholder="Enter your Org name or Department name">
                </div>

                <div class="contacts">
                    <div class="form-row">
                        <label for="contactNumber">Contact Number:</label>
                        <input type="tel" id="contactNumber" name="contactNumber" pattern="[\+]?[0-9]+" required placeholder="Enter your contact number">
                    </div>
                    <div class="form-row">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required placeholder="Enter your email">
                    </div>
                </div>
                
                <div class="form-row">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>                
                <input style="font-size: 14;  padding: 0;" type="submit" value="Sign Up">

                <p>Already have an account? <a href="index.html">Login</a></p>
            </form>
        </div>
    </div>
    <script>
        function validateForm() {
            return true;
        }

        const images = document.querySelectorAll('.image-container img');
            
            let currentIndex = 0;

            // Function to show the next image
            const showNextImage = () => {
                // Hide the current image
                images[currentIndex].style.display = 'none';

                // Increment index or reset to 0 if at the end
                currentIndex = (currentIndex + 1) % images.length;

                // Show the next image
                images[currentIndex].style.display = 'block';

                setTimeout(showNextImage, 4000);
            };

            showNextImage();
    </script>
</body>
</html>
