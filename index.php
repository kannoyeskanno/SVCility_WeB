<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            width: 400px;
            margin: auto;
        }

        .login-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }

        .btn-login {
            width: 100%;
            margin-top: 10px;
        }

        .signup-link {
            display: block;
            text-align: center;
            margin-top: 10px;
        }

        .image-container {
            flex-grow: 1;
            background: url('your-image-url.jpg') center/cover;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="form-container">
            <div class="login-header">
                <h2>Login</h2>
            </div>
            <div class="login-form">
                <div class="error-message">
                    <?php
                        session_start();
                        if (isset($_SESSION['login_error'])) {
                            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['login_error'] . '</div>';
                            unset($_SESSION['login_error']); 
                        }
                    ?>
                </div>
                <form method="post" action="login_process.php">
                    <div class="mb-3">
                        <input type='hidden' name='role' value='client'>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-login">Login</button>
                    <p class="mt-2">or</p>
                    <a href="user_signup.php" class="signup-link">Sign Up</a>
                </form>
            </div>
        </div>
        <div class="image-container"></div>
    </div>

    <!-- Bootstrap Alert -->
    <div id="liveAlertPlaceholder"></div>
    <button type="button" class="btn btn-primary" id="liveAlertBtn">Show live alert</button>

    <script>
        $(document).ready(function () {
            // Show live alert on button click
            $("#liveAlertBtn").click(function () {
                var alertHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">This account has been temporarily denied access due to a conflict related to returning equipment. Please take a moment to reach out to our support team or an authorized representative to discuss and resolve this matter. Your cooperation is highly appreciated, and we are here to assist you in resolving any concerns regarding the equipment. Thank you for your understanding.</div>';
                $("#liveAlertPlaceholder").html(alertHtml);
            });
        });
    </script>
</body>
</html>
