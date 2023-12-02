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
      body {
            font-family: 'Josefin Sans', sans-serif;
            background-color: #f8f9fa;
        }

        .container-fluid {
            padding-right: 0;
            padding-left: 0;
        }

        .sidebar {
            position: fixed;
            top: 60px;
            bottom: 0;
            left: 0;
            z-index: 1000;
            padding-top: 20px;
            width: 230px;
            background-color: #ffffff;
            border-right: 1px solid #dee2e6;
            height: 100vh;
        }

        .sidebar .nav-link {
            color: #6c757d;
        }

        .sidebar .nav-link.active {
            color: #ffffff;
            background-color: #007bff;
        }

        .main {
            margin-top: 60px;
            margin-left: 230px;
            padding: 20px;
            border: 1px solid #dee2e6;
            height: calc(100vh - 60px);
            overflow-y: auto;
        }

        .main_view_nav {
            border: 1px solid #dee2e6;
            height: 100%;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <?php
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

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="top: 60px; border:solid; height: 89vh;">
               
                <div class="main_view_nav">
                    
                    <iframe id="iframe_content" src="frame_equipment_issue.php" frameborder="0"></iframe>
                </div>               
                
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

            <script src="js/search.js"></script>
            </main>
        </div>
    </div>
</body>
</html>
