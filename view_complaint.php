<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$complaintId = (int)$_GET['id'];
$userId = $_SESSION['user_id'];

// Fetch complaint details
$query = "
    SELECT title, description, status, priority, category, location, reported_at, updated_at
    FROM complaints 
    WHERE id = ? AND user_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $complaintId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Complaint not found or access denied.";
    exit();
}

$complaint = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Complaint</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background: linear-gradient(to bottom, #4CAF50, #2E7D32);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 800px;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h1 {
            color: #2E7D32;
            font-size: 28px;
            margin-bottom: 20px;
        }

        dl {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 15px 30px;
            margin: 20px 0;
        }

        dt {
            font-weight: bold;
            color: #4CAF50;
        }

        dd {
            margin: 0;
            color: #555;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: linear-gradient(to right, #4CAF50, #2E7D32);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: transform 0.3s, background 0.3s;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .back-btn:hover {
            background: #388E3C;
            transform: scale(1.05);
        }

        footer {
            margin-top: 20px;
            color: #fff;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Complaint Details</h1>
        <dl>
            <dt>Title:</dt>
            <dd><?= htmlspecialchars($complaint['title']) ?></dd>

            <dt>Description:</dt>
            <dd><?= htmlspecialchars($complaint['description']) ?></dd>

            <dt>Status:</dt>
            <dd><?= htmlspecialchars($complaint['status']) ?></dd>

            <dt>Priority:</dt>
            <dd><?= htmlspecialchars($complaint['priority']) ?></dd>

            <dt>Category:</dt>
            <dd><?= htmlspecialchars($complaint['category']) ?></dd>

            <dt>Location:</dt>
            <dd><?= htmlspecialchars($complaint['location']) ?></dd>

            <dt>Reported At:</dt>
            <dd><?= htmlspecialchars($complaint['reported_at']) ?></dd>

            <dt>Last Updated:</dt>
            <dd><?= htmlspecialchars($complaint['updated_at']) ?></dd>
        </dl>
        <a href="user_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
    <footer>&copy; 2025 Community Complaint System</footer>
</body>
</html>
