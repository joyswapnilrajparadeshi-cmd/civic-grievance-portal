<?php
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';
include 'db.php'; // Ensure db.php connects correctly

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $complaintId = $_POST['complaint_id'] ?: null;
    $message = $_POST['message'];

    // Fetch user email
    $query = "SELECT email FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $recipientEmail = $user['email'];

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'joyswapnilrajparadeshi@gmail.com';
            $mail->Password = 'puge ilbh nlhb ejhh';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('joyswapnilrajparadeshi@gmail.com', 'Community Complaint System');
            $mail->addAddress($recipientEmail);

            $mail->isHTML(true);
            $mail->Subject = 'Notification from Admin';
            $mail->Body = "<p>{$message}</p>";
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];

            $mail->send();

            // Insert notification into database
            $insertQuery = "INSERT INTO notifications (user_id, complaint_id, message) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("iis", $userId, $complaintId, $message);

            if ($insertStmt->execute()) {
                // Redirect to admin dashboard with success message
                header("Location: admin_dashboard.php?status=success");
                exit();
            } else {
                // Redirect to admin dashboard with database error message
                header("Location: admin_dashboard.php?status=error&message=" . urlencode($insertStmt->error));
                exit();
            }
        } catch (Exception $e) {
            // Redirect to admin dashboard with email error message
            header("Location: admin_dashboard.php?status=error&message=" . urlencode($mail->ErrorInfo));
            exit();
        }
    } else {
        // Redirect to admin dashboard with user not found message
        header("Location: admin_dashboard.php?status=error&message=User not found");
        exit();
    }
}
?>
