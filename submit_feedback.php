<?php
session_start();
include 'db.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Display success or error messages
if (isset($_SESSION['success'])) {
    echo '<div style="
        color: white; 
        background-color: #4CAF50; 
        font-weight: bold; 
        font-size: 1.2em; 
        text-align: center; 
        padding: 10px; 
        border-radius: 5px; 
        margin-bottom: 20px;">
        ' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo '<div style="
        color: white; 
        background-color: #F44336; 
        font-weight: bold; 
        font-size: 1.2em; 
        text-align: center; 
        padding: 10px; 
        border-radius: 5px; 
        margin-bottom: 20px;">
        ' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}


// Fetch resolved complaints for the logged-in user
$query = "SELECT id, title FROM complaints WHERE user_id = ? AND status = 'Resolved'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback</title>
    <style>
        /* Same styles as in your code */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(90deg, #4CAF50, #2E7D32);
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 700px;
            margin: 50px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            color: #2E7D32;
            margin-bottom: 20px;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #2E7D32;
        }
        select, textarea, input[type="submit"] {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
            font-size: 16px;
        }
        select:focus, textarea:focus, input[type="submit"]:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }
        textarea {
            height: 120px;
        }
        input[type="submit"] {
            background: linear-gradient(90deg, #4CAF50, #2E7D32);
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }
        input[type="submit"]:hover {
            background: #45a049;
            transform: translateY(-3px);
        }
        footer {
            text-align: center;
            padding: 1em 0;
            margin-top: 40px;
            color: white;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Submit Feedback</h1>
    <form action="submit_feedback_handler.php" method="POST">
        <label for="complaint_id">Select Complaint</label>
        <select name="complaint_id" id="complaint_id" required>
            <option value="">-- Select Resolved Complaint --</option>
            <?php while ($row = $result->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['title']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="rating">Rating (1-5)</label>
        <select name="rating" id="rating" required>
            <option value="">-- Select Rating --</option>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?></option>
            <?php endfor; ?>
        </select>

        <label for="comments">Comments</label>
        <textarea name="comments" id="comments" placeholder="Provide your feedback" required></textarea>

        <input type="submit" value="Submit Feedback">
    </form>
</div>

<footer>
    &copy; 2025 Community Complaint System. All rights reserved.
</footer>

</body>
</html>
