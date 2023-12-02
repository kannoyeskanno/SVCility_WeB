<?php
session_start();

include 'dbConnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    $sql = "SELECT * FROM $role WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $user_id = $row["id"];

        session_start();

        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role;

        if ($role == 'client') {
            $stat = $row["account_stat"];
            if ($stat = 'accept') {
                header("Location: user_page/facilities_&_equipment.php");

               
            } else {
                $_SESSION['login_error'] = "This account has been.";
                header("Location: index.php"); // Redirect back to the login page
                exit();


            }

            exit();
        } elseif ($role == 'admin') {
            header("Location: admin_page/admin_dashboard.php");
            exit();
        }
    } else {
        
        $_SESSION['login_error'] = "Invalid email or password. Login failed.";
        header("Location: index.html"); // Redirect back to the login page
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
