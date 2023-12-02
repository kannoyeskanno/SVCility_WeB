<?php

include '../dbConnect.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM request WHERE user_id = $user_id AND active = 0 AND status = 'approved' ORDER BY id DESC LIMIT 3";
$result = $conn->query($sql);

$requests_data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $requests_data[] = array(
        "id" => $row['id'],
        "status" => $row['status'],
        "subject" => $row['subject'],
        "purpose" => $row['purpose'] 
    );
}


?>


<div id="toast-container" class="toast-container">
    <?php foreach ($requests_data as $request) { ?>
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto"><?php echo $request['status']; ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <?php echo $request['subject'], '<br>'; ?>
                <?php echo $request['purpose']; ?>

            </div>
            <div class="toast-footer" style="margin-left: 10px; margin-bottom: 10px;">
                <button type="button" class="btn btn-primary btn-sm">Ok</button>
            </div>


        </div>
    <?php } ?>
</div>

<script>
    function updateRequestStatus(requestId) {
        // Send AJAX request to update request status
        $.ajax({
            type: 'POST',
            url: 'aa.php', // Replace with the actual path to your PHP script
            data: { id: requestId },
            success: function (response) {
                // Optional: You can handle the response here (e.g., display a message)
                console.log('Request status updated successfully.');
            },
            error: function (error) {
                console.log('Error updating request status: ' + error);
            }
        });
    }
</script>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
     .toast-container {
        position: fixed;
        bottom: 0;
        right: 0;
        padding: 3rem;
    }

    #liveToastBtn {
        margin-left: 200px;
    }

    .search_result {
        border: solid;
        width: 30px;
        height:90px;
    }

        #logo {
            width: 100%;
            height: auto;
        }

        @media (min-width: 600px) {
            .container {
                max-width: 600px;
            }

            img {
                width: 100px;
                height: auto;
                margin-right: 10px;
            }
        }

        @media (min-width: 800px) {
            .container {
                max-width: 800px;
            }
        }

        .profile-icon {
            color: gray;
            font-size: 2rem;
            margin-left: 40px;
            margin-right: 40px;

        }
        
    </style>

</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <div class="image_container">
                <img src="../resources/logo/svLOGO.png" alt="Logo" id="logo" class="d-inline-block align-text-top" style="width: 130px; padding-left: 20px;">
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin-left: 60px;">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'home.php' ? 'active' : ''; ?>" href="home.php">
                        <i class="bi bi-send"></i> Send
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'facilities_&_equipment.php' ? 'active' : ''; ?>" href="facilities_&_equipment.php">
                        <i class="fas fa-th"></i> Facilities
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'calendar.php' ? 'active' : ''; ?>" href="calendar.php">
                        <i class="far fa-calendar"></i> Calendar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'request.php' ? 'active' : ''; ?>" href="request.php">
                        <i class="fas fa-tasks"></i> Requests
                    </a>
                </li>
            </ul>
            <button type="button" id="liveToastBtn" class="btn position-relative">
                <i style="font-size: 17px;" class="bi bi-bell-fill"></i>
                <?php if (!empty($requests_data)) { ?>
                    <span class="position-absolute bottom-10 start-200 translate-middle p-2 bg-danger rounded-circle">
                        <span class="visually-hidden">New alerts</span>
                      
                    </span>
                <?php } ?>

            </button>
           
            <!-- <form class="d-flex ms-auto" role="search" action="search.php" method="GET">
            <input class="search-box form-control" id="live_search" autocomplete="off" type="text" placeholder="Search">
            <div id="search_result"></div>

                <button class="btn btn-outline-success" type="submit">Search</button>
            </form> -->


            <div id="dropdown_button" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                <i class="bi bi-person-circle profile-icon"></i>
            </div>
        </div>
    </div>
</nav>

<?php
include '../dbConnect.php';

if (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
    // Admin user
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM admin WHERE id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastName = $row['lname'];
        $firstName = $row['fname'];
        $middleName = $row['mname'];
        $email = $row['email'];
        $officeName = "Office Of the Mayor";
    } else {
        echo "User data not found.";
    }
} else {
    // Regular user (not admin)
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
}
?>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" data-bs-backdrop="static">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row g-2">
            <div class="col">
                <label for="fname" class="form-label">First Name</label>
                <input id="fname" type="text" class="form-control" value="<?= $firstName ?>" aria-label="First name" readonly disabled>
            </div>
            <div class="col">
                <label for="lname" class="form-label">Last Name</label>
                <input id="lname" type="text" class="form-control" value="<?= $lastName ?>" aria-label="First name" readonly disabled>
            </div>
        </div>
        <div class="mb-3">
            <label for="formGroupExampleInput" class="form-label">Office/Org</label>
            <input type="text" class="form-control" id="formGroupExampleInput" value="<?= $officeName ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="formGroupExampleInput2" class="form-label">Email</label>
            <input type="text" class="form-control" id="formGroupExampleInput2" value="<?= $email ?>" readonly disabled>
        </div>
        <div class="list-group">
            <a href="profile.php" class="list-group-item list-group-item-action"><i class="bi bi-pen-fill" style="color: blue;"></i> Edit Profile</a>
            <a href="../logout.php" class="list-group-item list-group-item-danger"> <i class="bi bi-box-arrow-in-left" style="color: red;"></i> LogOut </a>
            <a href="" class="list-group-item list-group-item-action">A fourth link item</a>
            <a class="list-group-item list-group-item-action disabled" aria-disabled="true">A disabled link item</a>
        </div>
    </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        // Initialize toasts
        $('.toast').toast({ autohide: false });

        // Attach click event to toasts
        $('.toast').on('click', function () {
            var requestId = $(this).data('id');
            console.log('Request ID:', requestId); // Add this line for debugging
            updateRequestStatus(requestId);
        });
        // Show toasts when button is clicked
        $("#liveToastBtn").on('click', function (e) {
            e.preventDefault();
            $('.toast').toast('show');
        });
        // function updateRequestStatus(requestId) {
        //     // Make an AJAX request to update the request status
        //     $.ajax({
        //         url: 'aa.php', // Replace with the correct URL
        //         type: 'POST',
        //         data: { "id": requestId },
        //         success: function (data) {
        //             console.log(data);
        //             location.reload(); // Reload the page after updating
        //         }
        //     });
        // }
    });
</script>

<!-- Your existing HTML code -->

<script>
    // Function to handle live search using AJAX
    function liveSearch() {
        var query = document.getElementById('live_search').value;

        // Check if the search query is not empty
        if (query.trim() !== '') {
            // Create an XMLHttpRequest object
            var xhr = new XMLHttpRequest();

            // Configure it: GET-request for the search.php file
            xhr.open('GET', 'search.php?query=' + query, true);

            // Send the request
            xhr.send();

            // This will be called after the response is received
            xhr.onload = function() {
                if (xhr.status == 200) {
                    // Display the search results in the search_result div
                    document.getElementById('search_result').innerHTML = xhr.responseText;
                } else {
                    // Handle errors
                    console.error("Error: " + xhr.status);
                }
            };
        } else {
            // Clear the search results if the query is empty
            document.getElementById('search_result').innerHTML = '';
        }
    }

    // Add an event listener for the input field
    document.getElementById('live_search').addEventListener('input', liveSearch);
</script>