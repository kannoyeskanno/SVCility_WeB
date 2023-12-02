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
         overflow: hidden;
      }

    </style>
</head>
<body>

<?php
include('../header.php');
?>

<div class="container-fluid" style="margin-top: 90px;">

    <!-- Content -->
    <main class="container-fluid">
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

        <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
            <iframe id="iframe_content" src="request_status.php" frameborder="0" width="100%" height="1000"></iframe>
        </div>
    </main>

</div>


    <!-- Bootstrap and other scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="js/search.js"></script>
</body>
</html>
