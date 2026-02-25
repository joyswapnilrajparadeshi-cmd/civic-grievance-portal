<?php
session_start();
require 'db.php'; // Database connection

$error = "";
$success = "";

// Retrieve email from query parameter
if (!isset($_GET['email']) || !filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_GET['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp']);

    if (!is_numeric($otp) || strlen($otp) !== 6) {
        $error = "Invalid OTP format.";
    } else {
        // Check OTP in the database
        $query = $conn->prepare("SELECT id, token_expiry FROM users WHERE email = ? AND reset_token = ?");
        $query->bind_param("ss", $email, $otp);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $expiry = $row['token_expiry'];

            // Check if the OTP is expired
            if (strtotime($expiry) < time()) {
                $error = "OTP has expired. Please request a new one.";
            } else {
                // OTP is valid
                $_SESSION['reset_email'] = $email; // Store email for resetting password
                header("Location: reset_password.php");
                exit();
            }
        } else {
            $error = "Invalid OTP.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
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
        input[type="text"], button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        input[type="text"] {
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
        .success {
            color: #69f0ae;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Verify OTP</h1>
        <?php if ($error): ?>
            <div class="message error"> <?php echo $error; ?> </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="message success"> <?php echo $success; ?> </div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <button type="submit">Verify OTP</button>
        </form>
    </div>
</body>
</html>
