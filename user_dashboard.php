<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user's complaints
$query = "SELECT * FROM complaints WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Dashboard - Community Complaint System</title>
    <style>
        /* Existing styles remain unchanged */
        header h1 {
    color: white;
    font-weight: bold;
}


        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background: #f4f4f9;
        }

        header {
            background: linear-gradient(90deg, #4CAF50, #2E7D32);
            color: white;
            padding: 1em 0;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        nav {
            display: flex;
            justify-content: center;
            background: #333;
            padding: 0.5em 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            margin-bottom: 30px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 1em;
            padding: 0.5em 1em;
            border-radius: 5px;
            transition: background 0.3s, transform 0.2s;
        }

        nav a:hover {
            background: #4CAF50;
            transform: scale(1.1);
        }

        .container {
            max-width: 900px;
            margin: 0 auto 60px;
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h1, h2 {
            color: #2E7D32;
            margin-bottom: 20px;
        }

        .logout {
            text-align: right;
            margin-bottom: 30px;
        }

        .logout a {
            color: #4CAF50;
            font-weight: bold;
            text-decoration: none;
            padding: 8px 16px;
            border: 2px solid #4CAF50;
            border-radius: 30px;
            transition: background 0.3s, color 0.3s;
        }

        .logout a:hover {
            background: #4CAF50;
            color: white;
        }

        .submit-btn {
            display: inline-block;
            background: #4CAF50;
            color: white;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: bold;
            text-decoration: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: background 0.3s, transform 0.2s;
            margin-bottom: 30px;
        }

        .submit-btn:hover {
            background: #45a049;
            transform: translateY(-3px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 16px;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        a.view-btn {
            background-color: #4CAF50;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 30px;
            font-size: 14px;
            transition: background 0.3s;
        }

        a.view-btn:hover {
            background-color: #388E3C;
        }

        .no-complaints {
            text-align: center;
            color: #666;
            padding: 30px 0;
            font-size: 16px;
        }

        /* Feedback Section Styles */
        .feedback-section {
            margin-top: 40px;
            padding: 20px;
            background: #f4f4f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .feedback-section h2 {
            color: #2E7D32;
            margin-bottom: 20px;
        }

        .feedback-btn {
            display: inline-block;
            background: linear-gradient(90deg, #4CAF50, #2E7D32);
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 18px;
            text-decoration: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background 0.3s, transform 0.2s;
        }

        .feedback-btn:hover {
            background: #45a049;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

<header>
    <h1>Municipal Community Complaint System</h1>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="user_dashboard.php">Dashboard</a>
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>

    <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
    <h2>Your Complaints</h2>

    <a href="new_complaint.php" class="submit-btn">Submit New Complaint</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Category</th>
                <th>Reported At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows === 0): ?>
                <tr>
                    <td colspan="7" class="no-complaints">You have not submitted any complaints yet.</td>
                </tr>
            <?php else: ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$row['id'] ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['priority']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= htmlspecialchars($row['reported_at']) ?></td>
                        <td><a class="view-btn" href="view_complaint.php?id=<?= (int)$row['id'] ?>">View</a></td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Feedback Section -->
    <div class="feedback-section">
        <h2>We Value Your Feedback!</h2>
        <a href="submit_feedback.php" class="feedback-btn">Submit Feedback</a>
    </div>
</div>

<footer style="text-align:center; padding: 2em; color:#666;">
    &copy; 2025 Community Complaint System. All rights reserved.
</footer>

</body>
</html>
