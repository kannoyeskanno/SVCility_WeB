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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="icon" href="../resources/logo/svcility_icon.png" type="image/x-icon">

    <title>Document</title>


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap');

       

        #iframe_content1, #iframe_content2 {
            border: solid;
            width: 90%;
            height: 100vhpx;
            margin-top: 60px;
            margin: 20px 20px 20px 20px;
        }

        #iframe_content2 {
            display: none; /* Set display to none by default */
        }


.main_view_nav {
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
    width: 100%;
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

.nav_frames {
    border: solid;
    width: 40%;
    height: 100px;
}

    .input-group {
        margin-left: 20px;
    }

    .main_view_nav {
    display: flex;
    flex-direction: column;
    height: 100%;
    padding: 20px; /* Add padding for better spacing */
}

.iframe_container {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: space-around;
    margin-top: 20px; /* Add margin for better spacing */
}

    

    .btn-toolbar {
        border: solid;
        position: relative;
        left: 200px;
    }

  

    iframe {
    width: 100%;
    height: 90vh; 
    border: 1px solid #ddd;
    border-radius: 5px;
}

    </style>
</head>

<body>
    <div class="container-fluid">
        <?php
            include('../header_.php'); 
        ?>

        <div class="row">
            <!-- Sidebar -->
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

                     
                    </ul>
                </div>
            </nav>

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

                <div class="main_view_nav">
                  
                    <div class="btn-toolbar mb-3" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group me-2" role="group" aria-label="First group">
                            <button type="button" class="btn btn-outline-secondary" onclick="showFrame('iframe_content1')">To User</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="showFrame('iframe_content2')">Returned</button>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search Client" aria-label="Input group example" aria-describedby="btnGroupAddon">
                        </div>
                    </div>


            
                    <div class="iframe_container">
                        <iframe id="iframe_content1" src="frame_equipment_to_user.php" frameborder="0"></iframe>
                        <iframe id="iframe_content2" src="frame_equipment_return.php" frameborder="0"></iframe>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="js/search.js"></script>
    <script>
        function showFrame(target) {
            document.getElementById('iframe_content1').style.display = 'none';
            document.getElementById('iframe_content2').style.display = 'none';
            document.getElementById(target).style.display = 'block';
        }
    </script>
</body>
</html>
