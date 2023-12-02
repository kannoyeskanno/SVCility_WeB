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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <title>Calendar</title>
    <style>
        body {
            height: 100vh;
            overflow: hidden;
        }

        .main-container {
            display: flex;
            justify-content: center; /* Center the content horizontally */
        }

        .sidebar {
            position: fixed;
            top: 90px;
            bottom: 0;
            left: 0;
            z-index: 1000;
            padding-top: 0px;
            width: 230px; /* Adjusted width */
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

        .content {
            flex: 1; /* Fill available space */
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
            margin-top: 20px;
        }

        th,
        td {
            text-align: center;
            width: 14.28%;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .current-day {
            background-color: lightblue;
        }

        .scroll-container {
            height: 100vh;
            width: 100%;
            overflow-y: auto;
            border: solid;
        }

    </style>
</head>

<body>
<div class="container-fluid">
    <?php include('../header_.php'); ?>

<div class="container-fluid">
    <div class="row">
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

        <main class="col-md-10 ms-sm-auto" style="margin-top: 100px; overflow-y: auto;">
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        include '../dbConnect.php';

        // Fetch facility schedules ordered by the nearest date
        $sql = "SELECT * FROM facility_schedules WHERE scheduled_date >= CURDATE() ORDER BY scheduled_date ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $request_id = $row['request_id'];
                $facility_id = $row['facility_id'];

                $sql_request = "SELECT * FROM request WHERE id = $request_id";
                $result_request = $conn->query($sql_request);

                if ($result_request->num_rows > 0) {
                    $request_data = $result_request->fetch_assoc();
                    $adminId = $request_data['admin_id'];

                    $sql_admin = "SELECT * FROM admin WHERE id = $adminId";
                    $result_admin = $conn->query($sql_admin);

                    if ($result_admin->num_rows > 0) {
                        $admin_data = $result_admin->fetch_assoc();

                        $sql_facility = "SELECT * FROM facility WHERE id = $facility_id";
                        $result_facility = $conn->query($sql_facility);

                        if (!$result_facility) {
                            continue;
                        }

                        if ($result_facility->num_rows > 0) {
                            $facility_data = $result_facility->fetch_assoc();

                            // Determine the background color based on the date
                            $date = strtotime($row['scheduled_date']);
                            $today = strtotime(date('Y-m-d'));
                            $diff = ($date - $today) / (60 * 60 * 24);

                            $bgClass = '';
                            if ($diff == 0) {
                                $bgClass = 'text-bg-success';
                            } elseif ($diff == 1 || $diff == 2) {
                                $bgClass = 'text-bg-warning';
                            } else {
                                $bgClass = 'text-bg-light';
                            }

                            echo '<div class="col">';
                            echo '<div class="card ' . $bgClass . '">';
                            echo '<img src="../resources/facility_imgs/' . $facility_data['image_name'] . '.png" class="card-img-top" alt="Facility Image" style="max-height: 100px; object-fit: cover;">';

                            // Accordion structure
                            echo '<div class="accordion" id="accordion_' . $row['scheduled_date'] . '">';
                            
                            // Accordion Item 1 - Subject and Date
                            echo '<div class="accordion-item">';
                            echo '<h2 class="accordion-header">';
                            echo '<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSubjectDate_' . $row['scheduled_date'] . '" aria-expanded="true" aria-controls="collapseSubjectDate_' . $row['scheduled_date'] . '">';
                            echo 'Subject and Date';
                            echo '</button>';
                            echo '</h2>';
                            echo '<div id="collapseSubjectDate_' . $row['scheduled_date'] . '" class="accordion-collapse collapse show" data-bs-parent="#accordion_' . $row['scheduled_date'] . '">';
                            echo '<div class="accordion-body">';
                            echo '<strong>Subject: ' . $request_data['subject'] . '</strong><br>';
                            echo 'Date: ' . $request_data['date'];
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            
                            // Accordion Item 2 - Admin
                            echo '<div class="accordion-item">';
                            echo '<h2 class="accordion-header">';
                            echo '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdmin_' . $row['scheduled_date'] . '" aria-expanded="false" aria-controls="collapseAdmin_' . $row['scheduled_date'] . '">';
                            echo 'Admin';
                            echo '</button>';
                            echo '</h2>';
                            echo '<div id="collapseAdmin_' . $row['scheduled_date'] . '" class="accordion-collapse collapse" data-bs-parent="#accordion_' . $row['scheduled_date'] . '">';
                            echo '<div class="accordion-body">';
                            echo '<strong>Admin Name: </strong>' . $admin_data['fname'] . ' ' . $admin_data['lname'] . '<br>';
                            echo '<strong>Admin Email: </strong>' . $admin_data['email'];
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            
                            // Accordion Item 3 - Others
                            echo '<div class="accordion-item">';
                            echo '<h2 class="accordion-header">';
                            echo '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOthers_' . $row['scheduled_date'] . '" aria-expanded="false" aria-controls="collapseOthers_' . $row['scheduled_date'] . '">';
                            echo 'Others';
                            echo '</button>';
                            echo '</h2>';
                            echo '<div id="collapseOthers_' . $row['scheduled_date'] . '" class="accordion-collapse collapse" data-bs-parent="#accordion_' . $row['scheduled_date'] . '">';
                            echo '<div class="accordion-body">';
                            echo '<strong>Scheduled Date: </strong>' . $row['scheduled_date'] . '<br>';
                            echo '<strong>Start Time: </strong>' . $row['start_time'] . '<br>';
                            echo '<strong>End Time: </strong>' . $row['end_time'] . '<br>';
                            echo '<strong>Request Purpose: </strong>' . $request_data['purpose'] . '<br>';
                            echo '<strong>Facility Name: </strong>' . $facility_data['facility_name'] . '<br>';
                            echo '<strong>Facility Location: </strong>' . $facility_data['location'];
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            
                            // Close Accordion
                            echo '</div>';
                            
                            echo '</div>';
                            echo '</div>';
                        }
                        else {
                            echo "No facilities found.";
                        }
                    }
                }
            }
        } else {
            echo 'No data available.';
        }

        $conn->close();
        ?>
    </div>
</main>

    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="js/search.js"></script>
</body>

</html>
