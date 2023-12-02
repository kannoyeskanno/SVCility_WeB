<?php
include 'dbConnect.php';

if (isset($_GET['id'])) {
    $item_id = mysqli_real_escape_string($conn, $_GET['id']);

    $sql = "SELECT * FROM facility WHERE id = $item_id";
    $result = $conn->query($sql);

    $item_data = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Details</title>
    <!-- Add your CSS and Bootstrap links here -->
</head>
<body>
    <div class="container">
        <h1>Item Details</h1>
        
        <?php if (isset($item_data) && !empty($item_data)) { ?>
            <p>
                Facility Name: <?php echo $item_data['facility_name']; ?><br>
                Location: <?php echo $item_data['location']; ?><br>
                Type: <?php echo $item_data['type']; ?><br>
                Capacity: <?php echo $item_data['capacity']; ?><br>
                <!-- Add other fields as needed -->
            </p>
        <?php } else { ?>
            <p>Item not found.</p>
        <?php } ?>
    </div>
</body>
</html>
