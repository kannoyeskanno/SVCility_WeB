<?php
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <title>Document</title>

    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap');
        body {
            overflow: hidden;
        }
        .facilities_equipment_nav {
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: space-around;
            border-radius: 30px;
        }

        .main_view_nav {
            display: flex;
            justify-content: center; 
            align-items: center;
            top: 5px;
            position:relative;

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
            min-width: 200px; 
            display: flex;
            justify-content: center;
            align-items: center;
            width: 300px;
            height: 10px;
            
        }



        .facilities_equipment_nav a.active {
            background-color: lightgreen;
            color: white;
        }

        #iframe_content {
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
     

    </style>
</head>
<body>
    <div class="container-fluid">
        <?php
            include('../header.php'); 
            // include('../search.php'); 

        ?>
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block bg-light sidebar" style="top: 60px;">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'facilities_&_equipment.php' ? 'active' : ''; ?>" href="facilities_&_equipment.php">
                                <i class="fas fa-th"></i> Facilities & Equipment
                            </a>


                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'calendar.php' ? 'active' : ''; ?>" href="calendar.php">
                                <i class="far fa-calendar"></i> Calendar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'propose_reservation_list.php' ? 'active' : ''; ?>" href="propose_reservation_list.php">
                                <i class="fas fa-list-alt"></i> Propose Reservation List
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'request.php' ? 'active' : ''; ?>" href="request.php">
                                <i class="fas fa-tasks"></i> Requests
                            </a>
                        </li>
                        
                    </ul>
                </div>
            </nav>

            <!-- Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="top: 60px; border:solid; height: 89vh;">
                <?php
                if (!isset($_SESSION['user_id'])) {
                    echo '<script>
                        if (confirm("You must log in or create an account to access this page. Click OK to log in.")) {
                            window.location.href = "../index.php";
                        } else {
                            // Redirect or take any other action if the user clicks Cancel
                        }
                    </script>';
                    exit; 
                }
                ?>

                <?php
                if (isset($_SESSION['user_id'])) {
                    // echo '<p>User ID: ' . $_SESSION['user_id'] . '</p>';
                }
                ?>

                <div class="main_view_nav">
                <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility Details</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #495057;
        }

        .container {
            display: flex; /* Use flexbox for layout */
            justify-content: space-between; /* Put space between .content and .right_bar */
            max-width: 1200px; /* Optional: Set a max-width for the container */
            margin: 0 auto; /* Optional: Center the container */
        }

        .content {
            width: 60%;
            max-width: 800px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid red;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .right_bar {
            width: 40%;
            border: solid;
        }

        #iframe_content {
            border: solid red;
            height: 98vh;
            width: 98%;

        }


        

        img {
            max-width: 100%;
            max-height: 400px;
            width: 100%;
            height: auto;
            border-radius: 10px 10px 0 0;
        }

        .facility-title {
            font-size: 24px;
            margin: 10px 0;
            color: #007bff;
        }

        .details-container {
            padding: 20px;
        }

        .facility-info {
            margin-top: 20px;
        }

        .facility-info p {
            font-size: 16px;
            margin: 8px 0;
        }

        .reserve-button,
        .add-to-list-button {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .reserve-button:hover,
        .add-to-list-button:hover {
            background-color: #0056b3;
        }

        .calendar-container {
            margin-top: 30px;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .calendar-header button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #007bff;
        }

        .calendar-header h2 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 24px;
            margin: 0;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: center;
            width: 14.28%; /* 100% divided by 7 */
            padding: 10px;
            border: 1px solid #ced4da;
        }

        th {
            background-color: #007BFF;
            color: #fff;
        }

        .current-day {
            background-color: red;
            color: #fff;
        }

        #activity-container {
            border: 1px solid #ced4da;
            padding: 20px;
            margin-top: 20px;
            height: 100%;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        td {
            text-align: center;
            width: 14.28%;
            padding: 10px;
            border-radius: 3px;
            cursor: pointer;
        }

        td:hover {
            border-radius: 30px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <?php
    include '../dbConnect.php';

    if (isset($_GET['facilityID'])) {
        $facilityID = $_GET['facilityID'];

        $sql = "SELECT * FROM facility WHERE id = $facilityID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $facilityName = $row["facility_name"];
            $facilityLocation = $row["location"];
            $facilityType = $row["type"];
            $facilityDetails = $row["details"];
            $facilityCapacity = $row["capacity"];
            $facilityImgName = $row["image_name"];

            $sqlMax = "SELECT MAX(id) AS max_id FROM facility";
            $sqlMin = "SELECT MIN(id) AS min_id FROM facility";

            $resultMax = $conn->query($sqlMax);
            $resultMin = $conn->query($sqlMin);

            $rowMax = $resultMax->fetch_assoc();
            $rowMin = $resultMin->fetch_assoc();

            $maxID = $rowMax["max_id"];
            $minID = $rowMin["min_id"];

            $nextID = ($facilityID >= $maxID) ? $minID : $facilityID + 1;
            $previousID = ($facilityID <= $minID) ? $maxID : $facilityID - 1;

            $imagePath = '../resources/facility_imgs/' . $facilityImgName . '.png';

            ?>
            <div class="container">
                <div class="content">
                    <img src="<?php echo $imagePath; ?>" alt="Facility Image">
                    <h2 class="facility-title"><?php echo $facilityName; ?></h2>
                    <div class="details-container">
                        <div class="facility-info">
                            <p><strong>Location:</strong> <?php echo $facilityLocation; ?></p>
                            <p><strong>Type:</strong> <?php echo $facilityType; ?></p>
                            <p><strong>Details:</strong> <?php echo $facilityDetails; ?></p>
                            <p><strong>Capacity:</strong> <?php echo $facilityCapacity; ?></p>
                            <button id="reserveButton" class="reserve-button">Reserve</button>
                            <button id="addToListButton" class="add-to-list-button">Add to List</button>
                        </div>
                    </div>
                    <a href="read_more.php?facilityID=<?php echo $nextID; ?>">Next Facility</a>
                    <a href="read_more.php?facilityID=<?php echo $previousID; ?>">Previous Facility</a>
                </div>

                <script>
                document.getElementById('reserveButton').addEventListener('click', function() {
                    var confirmed = confirm('Do you want to Reserve Only This?');
                    if (confirmed) {
                        window.location.href = 'list_submission_form.php?facilityID=<?php echo $facilityID; ?>';
                    }
                });

                document.getElementById('addToListButton').addEventListener('click', function() {
                    var facilityID = <?php echo $facilityID; ?>;
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'save_to_list_request.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            alert(xhr.responseText);
                        }
                    };
                    xhr.send('facilityID=' + facilityID);
                });

            </script>

                <div class="right_bar">
                <iframe id="iframe_content" src="facility_schedule.php?facilityID=<?php echo $facilityID; ?>" frameborder="0"></iframe>
                </div>
            </div>
            <?php
        } else {
            echo "Facility not found.";
        }
    } else {
        echo "Facility ID not provided.";
    }

    $conn->close();
    ?>
</body>
</html>

                </div>


            </main>
        </div>
    </div>

    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
    <!-- Uncomment the jQuery inclusion line -->

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function changeIframeSrc(target, src, clickedLink) {
        const links = document.querySelectorAll('.facilities_equipment_nav a');
        links.forEach(link => link.classList.remove('active'));

        clickedLink.classList.add('active');

        document.getElementById(target).src = src;
    }

    $(document).ready(function() {
    // Use event delegation to handle dynamically added elements
    $(document).on("keyup", "#live_search", function() {
        var input = $(this).val();
        
        if (input != "") {
            $.ajax({
                url: '../search.php',
                method: "POST",
                data: { input: input },
                success: function(data) {
                    // Assuming that your search.php returns HTML content
                    $("#search_result").html(data);
                    $("#search_result").css("display", "block"); // Show the results
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    $("#search_result").css("display", "block");
                }
            });
        } else {
            $("#search_result").css("display", "none"); // Hide the results
        }
    });

    // Toggle the search result visibility
    $(document).on("click", function(event) {
        if (!$(event.target).closest("#search_result, #live_search").length) {
            // If the click is outside the search result or input, hide the search result
            $("#search_result").css("display", "none");
        }
    });
});

</script>

</body>
</html>
