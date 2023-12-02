<?php
// Include your database connection file
include '../dbConnect.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo '<script>
        if (confirm("You must log in or create an account to access this page. Click OK to log in.")) {
            window.location.href = "../index.html"; // Redirect to your login page
        } else {
            // Redirect or take any other action if the user clicks Cancel
        }
    </script>';
    exit;
}

$userId = $_SESSION['user_id'];

// Use prepared statements to prevent SQL injection
$sql = "SELECT account_stat FROM client WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);

$result = $stmt->execute();

if ($result) {
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($accountStatus);
        $stmt->fetch();

        if ($accountStatus === 'denied') {
            echo '<script>
                const enableOverlayAndAlert = () => {
                    const overlay = document.getElementById("overlay");
                    overlay.style.display = "block";
                    overlay.style.pointerEvents = "none";



                    // Trigger the modal
                    const myModal = new bootstrap.Modal(document.getElementById("exampleModal"));
                    myModal.show();
                };

                // Use window.onload to make sure the DOM is fully loaded before executing the script
                window.onload = enableOverlayAndAlert;
            </script>';
        } elseif ($accountStatus === 'accept') {
            // Handle the case when the account status is 'accept'
        } else {
            // Handle other cases if needed
        }
    } else {
        // Handle the case when no rows are returned
        echo "No rows found for user with ID: $userId";
    }

    $stmt->close();
} else {
    // Handle database query error
    echo "Error: " . $conn->error;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">


    <title>Document</title>


    <style>
        body {
        }
        main {
            width: 100%;
            margin-top: 70px;
        }

        body {
            background-color: #f8f9fa;
        }

        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            color: #007bff;
        }

        label {
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        button {
            background-color: #007bff;
            color: #ffffff;
            transition: background-color 0.3s ease-in-out;
        }

        button:hover {
            background-color: #0056b3;
        }

        .facilities_equipment_nav {
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: space-around;
            border-radius: 30px;
            /* Adjusted height property */
            height: 50px;
        }
        .main_view_nav {
            display: flex;
            justify-content: center;
            align-items: center;
            /* Removed top property and added margin-top */
            margin-top: 5px;
            position: relative;
        }

        .nav-link {
            width: 100%;
            height: 100%;
        }

        .facilities_equipment_nav .page_1 a {
            border-radius: 30px 0 0 30px;
        }

        .facilities_equipment_nav .page_2 a {
            border-radius: 0 30px 30px 0;
        }

        .facilities_equipment_nav a {
            text-decoration: none;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 30px;
            color: #333;
            font-size: 12px;
            /* Adjusted width property */
            min-width: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
            /* Adjusted height property */
            height: 30px;
        }

        .facilities_equipment_nav a.active {
            background-color: lightgreen;
            color: white;
        }

        #iframe_content {
            /* Removed top property and added height property */
            height: calc(100vh - 60px);
            width: 98%;
            position: absolute;
            right: 16px;
        }
    

    </style>
</head>
<body>
<?php
    include('../header.php'); 

        ?>
    <div class="container-fluid">
    <div id="overlay"></div>

    <div id="liveAlertPlaceholder"></div>
    
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Account Denied</h1>
        </div>
        <div class="modal-body">
            <p>
            This account has been temporarily denied access due to a conflict related to returning equipment. Please take a moment to reach out to our support team or an authorized representative to discuss and resolve this matter. Your cooperation is highly appreciated, and we are here to assist you in resolving any concerns regarding the equipment. Thank you for your understanding.

            </p>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="understoodButton" data-bs-dismiss="modal">Understood</button>
        </div>
        </div>
    </div>
    </div>

       
        


<!-- Content -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-1 mx-auto">
    <!-- <div class="main_view_nav">
        <div class="facilities_equipment_nav">
            <div class="page_1">
                <a class="active" href="javascript:void(0);" onclick="changeContent('frame_facilities_data.php', this)">Facilities / Events Place</a>
            </div>
            <div class="page_2">
                <a href="javascript:void(0);" onclick="changeContent('frame_equipment_data.php', this)">Equipment</a>
            </div>
        </div>
    </div> -->

    <div id="content_container" style="height: 89vh;">
        <?php include 'frame_facilities_data.php'; ?>
    </div>
</main>

<script>
    document.getElementById('exampleModal').addEventListener('shown.bs.modal', function () {
        // Add a click event listener to the "Understood" button
        document.getElementById('understoodButton').addEventListener('click', function () {
            // Redirect to the login page
            window.location.href = '../index.html';
        });
    });
function changeContent(src, clickedLink) {
    const links = document.querySelectorAll('.facilities_equipment_nav a');
    links.forEach(link => link.classList.remove('active'));

    clickedLink.classList.add('active');

    // Fetch the content from the specified URL
    fetch(src)
        .then(response => response.text())
        .then(html => {
            // Replace the content of the container with the fetched HTML
            document.getElementById('content_container').innerHTML = html;
        })
        .catch(error => console.error('Error fetching content:', error));
}

</script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<script src="js/search.js"></script>
</body>
</html>
