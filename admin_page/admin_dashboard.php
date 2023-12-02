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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            height: 100vh; /* Set the body height to full viewport height */
            overflow: hidden; /* Hide the vertical scrollbar */
        }

        main {
            margin-top: 90px;
            height: calc(100vh - 90px);
            overflow: auto; /* Add overflow property to enable scrolling inside the main content */
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .sidebar {
            position: fixed;
            top: 90px;
            bottom: 0;
            left: 0;
            z-index: 1000;
            padding-top: 0px;
            width: 30%; /* Adjusted width */
            overflow-y: auto;
            background-color: #f8f9fa;
        }

        .nav-link {
            width: 100%;
            height: 100%;
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
    </style>
</head>

<body>

    <?php
    include('../header_.php');
    ?>
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
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
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
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

        <iframe id="iframe_content" src="frame_admin_user_request.php"></iframe>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
