<?php
include '../dbConnect.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM request WHERE user_id = $user_id AND active = 0 AND status = 'approved' ORDER BY id DESC LIMIT 3";
$result = $conn->query($sql);

$requests_data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $requests_data[] = array(
        "id" => $row['id'],
        "status" => $row['status'],
        "subject" => $row['subject'],
        "purpose" => $row['purpose'] 
    );
}
?>

<?php
include '../dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['id'];

    // Update the 'active' status to 1
    $updateSql = "UPDATE request SET active = 1 WHERE id = $requestId";
    $conn->query($updateSql);

    echo "Request status updated successfully.";
}
?>


<div id="toast-container" class="toast-container">
    <?php foreach ($requests_data as $request) { ?>
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto"><?php echo $request['status']; ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <?php echo $request['subject'], '<br>'; ?>
                <?php echo $request['purpose']; ?>
            </div>
        </div>
    <?php } ?>
</div>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
     .toast-container {
    position: fixed;
    bottom: 0;
    right: 0;
    padding: 3rem;
}

#liveToastBtn {
    margin-left: 200px;
}

.search_result {
    border: solid;
    width: 30px;
    height:90px;
}

        img {
            width: 50px;
            height: auto;
        }

        @media (min-width: 600px) {
            .container {
                max-width: 600px;
            }

            img {
                width: 100px;
                height: auto;
                margin-right: 10px;
            }
        }

        @media (min-width: 800px) {
            .container {
                max-width: 800px;
            }
        }

        .profile-icon {
            color: gray;
            font-size: 2rem;
            margin-left: 40px;
            margin-right: 40px;

        }
    </style>

</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <!-- <img src="../resources/logo/svcility_logo.png" alt="Logo" class="d-inline-block align-text-top"> -->
        </a>
       

            <div id="dropdown_button" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                <i class="bi bi-person-circle profile-icon"></i>
            </div>
        </div>
    </div>
</nav>

<?php
include '../dbConnect.php';

if (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM admin WHERE id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastName = $row['lname'];
        $firstName = $row['fname'];
        $middleName = $row['mname'];
        $email = $row['email'];
        $officeName = "Office Of the Mayor";
    } else {
        echo "User data not found.";
    }
} else {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM client WHERE id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $officeName = $row['office_org'];
        $lastName = $row['lname'];
        $firstName = $row['fname'];
        $middleName = $row['mname'];
        $email = $row['email'];
        $acc_stat = $row['account_stat'];
    } else {
        echo "User data not found.";
    }
}
?>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" data-bs-backdrop="static">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row g-2">
            <div class="col">
                <label for="fname" class="form-label">First Name</label>
                <input id="fname" type="text" class="form-control" value="<?= $firstName ?>" aria-label="First name" readonly disabled>
            </div>
            <div class="col">
                <label for="lname" class="form-label">Last Name</label>
                <input id="lname" type="text" class="form-control" value="<?= $lastName ?>" aria-label="First name" readonly disabled>
            </div>
        </div>
        <div class="mb-3">
            <label for="formGroupExampleInput" class="form-label">Office/Org</label>
            <input type="text" class="form-control" id="formGroupExampleInput" value="<?= $officeName ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="formGroupExampleInput2" class="form-label">Email</label>
            <input type="text" class="form-control" id="formGroupExampleInput2" value="<?= $email ?>" readonly disabled>
        </div>
        <div class="list-group">
            <a href="profile.php" class="list-group-item list-group-item-action"><i class="bi bi-pen-fill" style="color: blue;"></i> Edit Profile</a>
            <a href="../logout.php" class="list-group-item list-group-item-danger"> <i class="bi bi-box-arrow-in-left" style="color: red;"></i> LogOut </a>
            <a href="" class="list-group-item list-group-item-action">A fourth link item</a>
            <a class="list-group-item list-group-item-action disabled" aria-disabled="true">A disabled link item</a>
        </div>
    </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
