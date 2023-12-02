<?php
session_start();

include('dbConnect.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

if (isset($_POST['send_email'])) {
    // Validate and sanitize inputs here

    $recipientEmail = $_POST['recipient_email']; // Fetch the recipient email from the form

    // Send email function
    if (sendCommissionEmail($recipientEmail, $cusEmail = 'jomarasisgriffin@gmail.com', $budget = '0', $description = '', $size = '', $finishDate = '')) {
        echo "<script>alert('Email sent successfully.');document.location.href = 'test.php';</script>";
    } else {
        echo "<script>alert('Failed to send email.');document.location.href = 'test.php';</script>";
    }
}

function sendCommissionEmail($recipientEmail, $cusEmail, $budget, $description, $size, $finishDate) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = "smtp.gmail.com";
        $mail->SMTPAuth   = true;
        $mail->Username   = "wwan63326@gmail.com";
        $mail->Password   = "alnxogaogwuxzlrz";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->isHTML(true);

        $subject = "Commission Request";
        $imagePathLogo = '../resources/logo/san_vicente_logo.png';

        // Set the email body with HTML formatting
        $message = "<html>
                        <head>
                            <title>SVCility</title>
                        </head>
                        <body>

                            <div class='container'>
                            <div class='logo'>
                            <div class='image-container'>
                            <div class='image' style='background-image: url(' . $imagePath . ');'></div>
                            </div>
                                
                            </div>
                            <h2>Form Submission</h2>
                            <p><strong>Budget:</strong> $budget</p>
                            <p><strong>Description:</strong> $description</p>
                            <p><strong>Size:</strong> $size</p>
                            <p><strong>Finish Date:</strong> $finishDate</p>
                            <p><strong>Customer Email:</strong> <a href='mailto:$cusEmail'>$cusEmail</a></p>
                            </div>
                            
                        </body>
                    </html>";

        $mail->setFrom('wwan63326@gmail.com', 'SVCility');
        $mail->addAddress($recipientEmail);
        $mail->Subject = $subject;
        $mail->Body = $message;

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Sender</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 100%;
            height: auto;
        }

        h2 {
            color: #0088cc;
        }

        strong {
            color: #333;
        }

        p {
            margin-bottom: 10px;
        }

        a {
            color: #0088cc;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        button {
            background-color: #0088cc;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #006699;
        }
    </style>
</head>
<body>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="recipient_email">Recipient Email:</label>
    <input type="email" id="recipient_email" name="recipient_email" required>
    <button type="submit" name="send_email">Send Email</button>
</form>

</body>
</html>
