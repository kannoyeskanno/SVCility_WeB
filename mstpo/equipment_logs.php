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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <title>Calendar</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap');

        .main_view_nav {
            display: flex;
            align-items: center;
            top: 5px;
            position: relative;
            width: 100%;
            border: solid;
            height: 88vh;
        }

        li {
            border: solid;
            height: 80px;
        }

        .sidebar .nav-link.active {
            background-color: lightgreen;
        }

        #calendar-container {
            border: solid;
            width: 100%; /* Full-width container */
            height: 100%; /* Full-height container */
            padding: 20px;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .calendar-header button {
            background: none;
            border: none;
            cursor: pointer;
        }

        .calendar-header h2 {
            font-family: 'Josefin Sans', sans-serif;
            font-size: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: center;
            width: 14.28%; /* 100% divided by 7 */
            padding: 10px;
            border: 1px solid #ccc;
        }

        .current-day {
            background-color: lightblue;
        }

        #activity-container {
            border: 1px solid ;
            padding: 20px;
            margin-top: 20px;
            height: 100%;
        }

        td {
            text-align: center;
            width: 14.28%;
            padding: 10px;
            border-radius: 3px;
        }

        td:hover {
            background-color: lightgray;
            cursor: pointer;
            border-radius: 50%;
        }

        .facility-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
        }

        .facility-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
            text-align: center;
        }

        .image-container {
            width: 100px;
            height: 100px;
            overflow: hidden;
            margin: 0 auto 10px;
        }

        .image {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
        }

        .text-container {
            font-size: 14px;
        }

        iframe {
            width: 90%;
            height: 100%;
            border: none;
        }

        .nav-link {
            width: 100%;
            height: 100%;
        }


        .sidebar .nav-link.active {
            background-color: lightgreen;
            transition: background-color 0.3s ease-in-out; /* Add transition for smooth animation */

        }

        .sidebar .nav-link.active::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: linear-gradient(to left, lightgreen, transparent);
            z-index: -1;
        }

        .sidebar .nav-link.active {
            color: white; /* Change text color for better visibility */
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <?php
        include('../header.php');
        ?>
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar" style="top: 60px;">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                    <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                                <i class="fas fa-th"></i> Dashboard
                            </a>


                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'calendar.php' ? 'active' : ''; ?>" href="calendar.php">
                                <i class="far fa-calendar"></i> Calendar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'equipment_inventory.php' ? 'active' : ''; ?>" href="equipment_inventory.php">
                                <i class="fas fa-list-alt"></i> Equipment Inventory
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'returned.php' ? 'active' : ''; ?>" href="returned.php">
                                <i class="fas fa-tasks"></i> Returned
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'equipment_logs.php' ? 'active' : ''; ?>" href="equipment_logs.php">
                                <i class="fas fa-tasks"></i> Equipment Logs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'contacts.php' ? 'active' : ''; ?>" href="contacts.php">
                                <i class="far fa-address-book"></i> Contacts
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="top: 60px; border:solid; height: 89vh;">
                 <iframe id="iframe_content" src="logs.php" frameborder="0" width="100%" height="450"></iframe>

            </main>
        </div>
    </div>
</body>
</html>
