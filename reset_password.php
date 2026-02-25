<?php
session_start();
require 'db.php'; // Database connection

// Check if email is set from OTP verification
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $email = $_SESSION['reset_email'];

        // Update the password in the database
        $updateQuery = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE email = ?");
        $updateQuery->bind_param("ss", $hashedPassword, $email);

        if ($updateQuery->execute()) {
            unset($_SESSION['reset_email']); // Clear the email session
            header("Location: password_reset_success.php");
            exit(); // Ensure no further code is executed
        } else {
            $error = "Failed to reset password. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
        input[type="password"], button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        input[type="password"] {
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
        .error {
            color: #ff1744;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reset Password</h1>
        <?php if ($error): ?>
            <div class="error"> <?php echo $error; ?> </div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="password" name="password" placeholder="Enter new password" required>
            <input type="password" name="confirm_password" placeholder="Confirm new password" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
