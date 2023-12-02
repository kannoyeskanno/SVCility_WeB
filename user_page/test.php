<?php
// Include necessary files and initialize session if not already done
session_start();
include('../dbConnect.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../vendor/autoload.php';

// Check if the form was submitted using POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the values from the hidden fields
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $purpose = isset($_POST['purpose']) ? $_POST['purpose'] : '';
    $datepicker = isset($_POST['datepicker']) ? $_POST['datepicker'] : '';
    $link = isset($_POST['link']) ? $_POST['link'] : '';


    $recipientEmail = 'jomarasisgriffin@gmail.com';

    // Send email function
    if (sendCommissionEmail($recipientEmail, $subject, $purpose, $datepicker, $link)) {
        echo "<script>alert('Email sent successfully.');document.location.href = 'test.php';</script>";
    } else {
        echo "<script>alert('Failed to send email.');document.location.href = 'test.php';</script>";
    }
}

function sendCommissionEmail($recipientEmail, $subject, $purpose, $datepicker, $link) {
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

        $imagePathLogo = '../resources/logo/san_vicente_logo.png';

      $message = "<html>
                <head>
                    <title>SVCility</title>
                </head>
                <body style='font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 25px;'>
                    <div class='container' style=' border: solid 1px #B0A695; border-radius: 40px 40px 0px 0px; height: 80vh; max-width: 55%; margin: 20px auto; padding: 0; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>
                        <div class='logo' style='text-align: center; margin-top: 5px; padding: 10px; background: url(\"https://products.ls.graphics/paaatterns/images/009.png\"); background-size: cover; border-radius: 40px 40px 0px 10px;'>
                            <div class='image-container' style='display: inline-block; margin-top: 5px; overflow: hidden; border-radius: 50%;'>
                                <div class='image' style='width: 80px; height: 80px; background-size: cover; background-position: center; background-image: url(resources/logo/san_vicente_logo.png); background-color: black;'></div>
                            </div>
                        </div>
                        <div class='message' style='background-color: #f8f8ff;'>
                            <div style='margin: 20px auto; background-color: #f8f8ff; padding-left: 40px; padding-right: 40px;'>
                                <div style='margin: 20px auto; text-align: center;'>
                                    <h2 style='color: #666666; text-decoration: underline;'><strong>" . htmlspecialchars($subject) . "</strong></h2>
                                </div>
                                <p style='color: #666666;'><strong>Purpose:</strong> " . htmlspecialchars($purpose) . "</p>
                                <p style='color: #666666;'><strong>Date:</strong> " . htmlspecialchars($datepicker) . "</p>
                            </div>
                            <div style='padding-left: 50px; padding-right: 50px; text-align: center; margin-top: 70px;'>
                                <a href='" . htmlspecialchars($link) . "' class='button' style='display: inline-block; padding: 12px 20px; font-size: 16px; font-weight: 500; letter-spacing: .25px; margin-bottom: 8px; text-decoration: none; text-transform: none; border-radius: 8px; background-color: #007bff; color: #fff; border: 1px solid #007bff; text-align: center;'>View Request</a>
                            </div>
                        </div>
                    </div>
                </body>
            </html>";


        $mail->setFrom('sanvicente@gmail.com', 'SVCility');
        $mail->addAddress($recipientEmail);
        $mail->Subject = $subject;
        $mail->Body = $message;

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}
?>
