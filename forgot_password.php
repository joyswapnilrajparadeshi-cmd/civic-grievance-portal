<?php
session_start();
require 'db.php'; // Database connection
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if the email exists
        $query = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows === 1) {
            // Generate OTP
            $otp = rand(100000, 999999);
            $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

            // Update the database with the OTP and expiry
            $updateQuery = $conn->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
            $updateQuery->bind_param("sss", $otp, $expiry, $email);

            if ($updateQuery->execute()) {
                // Send OTP via email
                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Set SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'joyswapnilrajparadeshi@gmail.com'; // Your email
                    $mail->Password = 'puge ilbh nlhb ejhh'; // Your email password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    $mail->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ],
];
                    // Recipients
                    $mail->setFrom('joyswapnilrajparadeshi@gmail.com', 'Community Complaint System');
                    $mail->addAddress($email);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset OTP';
                    $mail->Body = "<p>Your OTP for password reset is: <strong>$otp</strong></p><p>This OTP is valid for 10 minutes.</p>";

                    $mail->send();

                    // Redirect to verify_otp.php
                    header("Location: verify_otp.php?email=" . urlencode($email));
                    exit(); // Ensure no further code is executed
                } catch (Exception $e) {
                    $error = "Failed to send email. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                $error = "Failed to generate OTP. Please try again.";
            }
        } else {
            $error = "Email not found in our records.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #00c853, #b2ff59);
            color: #fff;
            text-align: center;
            padding: 50px;
        }
        .container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        input[type="email"], button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        input[type="email"] {
            background: #fff;
            color: #000;
        }
        button {
            background: #00c853;
            color: #fff;
            cursor: pointer;
        }
        button:hover {
            background: #b2ff59;
        }
        .message {
            margin: 10px 0;
            font-weight: bold;
        }
        .error {
            color: #ff1744;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Forgot Password</h1>
        <?php if ($error): ?>
            <div class="message error"> <?php echo $error; ?> </div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Send OTP</button>
        </form>
    </div>
</body> 
</html>
