<?php
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Enable verbose debug output
    $mail->SMTPDebug = 3; // Set to 0 for no debug output, 2 for normal debug, 3 or 4 for verbose
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'joyswapnilrajparadeshi@gmail.com'; // Your Gmail address
    $mail->Password = 'puge ilbh nlhb ejhh';             // Your app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;    // Use PHPMailer::ENCRYPTION_SMTPS for SSL
    $mail->Port = 465;                                 // Port for SSL connections

    // Temporarily disable SSL peer verification
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ],
    ];

    // Sender and recipient settings
    $mail->setFrom('joyswapnilrajparadeshi@gmail.com', 'Community Complaint System'); // Sender details
    $mail->addAddress('joyswapnilrajparadeshi@gmail.com');                            // Recipient details

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body = '<h1>This is a test email sent using PHPMailer</h1>';
    $mail->AltBody = 'This is a plain-text version of the email content';

    $mail->send();
    echo 'Email sent successfully!';
} catch (Exception $e) {
    echo "Email could not be sent. Error: {$mail->ErrorInfo}";
}
