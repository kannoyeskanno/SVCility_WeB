<?php
session_start();
// Include your database connection file
include 'dbConnect.php';

// Assuming you have a session started
$user_id = $_SESSION['user_id'];

// Fetch the data from the database
$sql = "SELECT * FROM request WHERE user_id = $user_id AND active = 0 ORDER BY id DESC LIMIT 3";
$result = $conn->query($sql);

$requests_data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $requests_data[] = array(
        "subject" => $row['subject'],
        "purpose" => $row['purpose']
    );
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sample Toasts</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .toast-container {
            position: fixed;
            bottom: 0;
            end: 0;
            padding: 3rem;
        }
    </style>
</head>
<body>

<!-- Button to trigger the toasts -->
<button type="button" class="btn btn-primary" id="liveToastBtn">Show live toast</button>

<!-- Toast Container -->
<div id="toast-container" class="toast-container">
    <?php foreach ($requests_data as $request) { ?>
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto"><?php echo $request['subject']; ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <?php echo $request['purpose']; ?>
            </div>
        </div>
    <?php } ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        // Initialize toasts
        $('.toast').toast({ autohide: false });

        // Attach click event to toasts
        $('.toast').on('click', function () {
            // Handle click event if needed
            console.log('Toast clicked!');
        });

        // Show toasts when button is clicked
        $("#liveToastBtn").on('click', function (e) {
            e.preventDefault();
            $('.toast').toast('show');
        });
    });
</script>

</body>
</html>
