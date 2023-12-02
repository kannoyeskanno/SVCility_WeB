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

    <title>Propose Reservation List</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap');
        body {
            overflow: hidden;
            padding: 10px;

        }
        .main_view_nav {
            display: flex;
            justify-content: center; 
            align-items: center;
            top: 5px;
            position:relative;

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

        li {
            border: solid;
            height: 80px;
        }

        .sidebar .nav-link.active {
            background-color: lightgreen;
        }


        /* Add this CSS to your stylesheet */
        .main_view_nav {
    background-color: #f5f5f5;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 20px;
  }

  .main_view_nav h1 {
    font-size: 24px;
    margin: 0 0 20px;
  }

  .main_view_nav ul {
    list-style: none;
    padding: 0;
  }

  .main_view_nav li {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
  }

  .main_view_nav li img {
    width: 60px;
    height: 60px;
    margin-left: 10px;
  }

  .main_view_nav li .info {
    flex: 1;
  }
     

    </style>
</head>
<body>
    <div class="container-fluid">
        <?php
            include('../header.php'); 
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
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'request.php' ? 'active' : ''; ?>" href="request.php">
                                <i class="fas fa-tasks"></i> Requests
                            </a>
                        </li>
                    
                    </ul>
                </div>
            </nav>

            <!-- Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="top: 60px; border:solid; height: 89vh;">
               
                <iframe id="iframe_content" src="frame_cart_list.php" frameborder="0" width="100%" height="450"></iframe>

            </main>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="js/search.js"></script>
</body>
</html>
