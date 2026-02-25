<?php
// Include the database connection file
include 'db.php';

// Start the session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables for error/success messages
$error = ""; // Initialize the $error variable

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($email) || empty($password)) {
        $error = "Email and Password are required.";
    } else {
        // Query to find the user
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: admin_dashboard.php');
                    exit;
                } else {
                    header('Location: user_dashboard.php');
                    exit;
                }
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(90deg, #4CAF50, #2E7D32);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
        }
        .container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
            color: #4CAF50;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            border: none;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-group button:hover {
            background: #2E7D32;
        }
        .message {
            margin-bottom: 15px;
            text-align: center;
            font-size: 14px;
            color: #d9534f;
        }
        .forgot-password {
            margin-top: 10px;
            text-align: center;
        }
        .forgot-password a {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
            font-size: 14px;
            transition: color 0.3s, transform 0.3s;
        }
        .forgot-password a:hover {
            color: #2E7D32;
            transform: scale(1.05);
        }
        .register-link {
            margin-top: 10px;
            text-align: center;
        }
        .register-link a {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
            font-size: 14px;
            transition: color 0.3s, transform 0.3s;
        }
        .register-link a:hover {
            color: #2E7D32;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php if ($error): ?>
            <div class="message"> <?= $error ?> </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>
        <div class="forgot-password">
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
        <div class="register-link">
            <a href="register.php">Don't have an account? Register here</a>
        </div>
    </div>
</body>
</html>
