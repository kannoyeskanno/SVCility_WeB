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
        body {
            /* Adjusted overflow property */
            overflow: auto;
            padding: 10px;
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
        .sidebar {
            position: fixed;
            top: 40px;
            bottom: 0;
            left: 0;
            z-index: 1000;
            padding-top: 0px;
            width: 30%; /* Adjusted width */
            overflow-y: auto;
            background-color: #f8f9fa;
        }

        .sidebar .nav-link.active {
            background-color: lightgreen;
            transition: background-color 0.3s ease-in-out;
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
            color: white;
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
    <div class="container-fluid">
        <?php
        // Assuming that the header_.php file contains your common header elements
        include('../header_.php');
        ?>
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar" style="top: 60px;">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'admin_dashboard.php' ? 'active' : ''; ?>" href="admin_dashboard.php">
                                <i class="fas fa-th"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'calendar.php' ? 'active' : ''; ?>" href="calendar.php">
                                <i class="far fa-calendar"></i> Calendar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'inventory.php' ? 'active' : ''; ?>" href="inventory.php">
                                <i class="fas fa-database"></i> Inventory
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'equipment_reports.php' ? 'active' : ''; ?>" href="equipment_reports.php">
                                <i class="fas fa-tasks"></i> Equipment Reports
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
                    <div class="facilities_equipment_nav">
                        <div class="page_1">
                            <a class="active" href="javascript:void(0);" onclick="changeIframeSrc('iframe_content', 'frame_facilities_data.php', this)">Facilities / Events Place</a>
                        </div>
                        <div class="page_2">
                            <a href="javascript:void(0);" onclick="changeIframeSrc('iframe_content', 'frame_equipment_data.php', this)">Equipment</a>
                        </div>
                    </div>
                </div>

                <iframe id="iframe_content" src="frame_facilities_data.php" frameborder="0" width="100%" height="100%"></iframe>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function changeIframeSrc(target, src, clickedLink) {
            const links = document.querySelectorAll('.facilities_equipment_nav a');
            links.forEach(link => link.classList.remove('active'));
            
            clickedLink.classList.add('active');
            
            document.getElementById(target).src = src;
        }
    </script>
</body>

</html>
