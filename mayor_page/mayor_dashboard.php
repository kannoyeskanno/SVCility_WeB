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
        ?>
        <div class="row">
         

        <nav class="col-md-2 d-none d-md-block bg-light sidebar" style="top: 60px;">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'mayor_dashboard.php' ? 'active' : ''; ?>" href="mayor_dashboard.php">
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
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'returned.php' ? 'active' : ''; ?>" href="request.php">
                            <i class="fas fa-tasks"></i> Returned
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

                <iframe id="iframe_content" src="frame_mayor_user_request.php" frameborder="0" width="100%" height="450"></iframe>
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








