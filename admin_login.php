<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Login Page</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            border: solid 1px;
            border-radius: 15px 15px 0 0;
            width: 80%; /* Adjusted width for responsiveness */
            max-width: 400px; /* Added max-width for responsiveness */
            margin: auto;
            padding: 30px;
        }

        /* Additional styling for the error message */
        .error-message {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="form-container">
            <div class="login-header">
                <h2 class="text-center">Login</h2>
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
                    <input type='hidden' name='role' value='admin'>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
