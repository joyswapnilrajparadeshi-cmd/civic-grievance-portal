<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Display success or error messages
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $message = isset($_GET['message']) ? urldecode($_GET['message']) : '';

    if ($status === 'success') {
        echo '<div class="alert alert-success">Notification sent successfully!</div>';
    } elseif ($status === 'error') {
        echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($message) . '</div>';
    }
}

// Fetch data for dashboard
$statusQuery = "SELECT status, COUNT(*) AS count FROM complaints GROUP BY status";
$statusResult = $conn->query($statusQuery);
$statusData = [];
while ($row = $statusResult->fetch_assoc()) {
    $statusData[] = $row;
}

$priorityQuery = "SELECT priority, COUNT(*) AS count FROM complaints GROUP BY priority";
$priorityResult = $conn->query($priorityQuery);
$priorityData = [];
while ($row = $priorityResult->fetch_assoc()) {
    $priorityData[] = $row;
}

$totalComplaintsQuery = "SELECT COUNT(*) AS total FROM complaints";
$totalComplaintsResult = $conn->query($totalComplaintsQuery);
$totalComplaints = $totalComplaintsResult->fetch_assoc()['total'];

// Fetch all complaints for admin view
$complaintsQuery = "SELECT id, title, status, reported_at FROM complaints ORDER BY reported_at DESC";
$complaintsResult = $conn->query($complaintsQuery);

// Fetch filter options
$categoriesQuery = "SELECT DISTINCT category FROM complaints";
$categoriesResult = $conn->query($categoriesQuery);

$statuses = ['Pending Review', 'In Progress', 'Resolved'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Inline CSS */
        .logout-container {
            display: flex;
            justify-content: flex-end;
            padding: 10px 20px;
            background: linear-gradient(135deg, #ffdd57, #ffc107);
            border-bottom: 3px solid #ffa000;
        }

        .logout-button {
            font-size: 1rem;
            font-weight: bold;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            background: #d32f2f;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .logout-button:hover {
            background: #c62828;
            transform: scale(1.05);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
        }
        .dashboard-container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 30px;
            color: #ffdd57;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-size: 1rem;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .alert-success {
            background-color: #4caf50;
            color: #fff;
        }
        .alert-danger {
            background-color: #f44336;
            color: #fff;
        }
        .cards {
            display: flex;
            justify-content: space-around;
            gap: 20px;
        }
        .card {
            background: #ffffff;
            color: #333;
            border-radius: 12px;
            padding: 20px;
            width: 200px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        .card h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        .charts {
            margin-top: 40px;
        }
        canvas {
    max-width: 800px; /* Reduce the maximum width */
    max-height: 500px; /* Set a maximum height */
    margin: 20px 0;
    padding: 15px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin: 30px 0;
        }
        .filter-form select, .filter-form input, .filter-form button {
            padding: 10px 15px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .filter-form select, .filter-form input {
            background: #f4f4f4;
            color: #333;
        }
        .filter-form button {
            background: #ffdd57;
            color: #333;
            font-weight: bold;
            cursor: pointer;
        }
        .filter-form button:hover {
            background: #ffc107;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            color: #333;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #4caf50;
            color: #fff;
        }
        tr:hover {
            background: #f1f1f1;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        a:hover {
            color: #0056b3;
        }
        .notification-form {
            background: #ffffff;
            color: #333;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            margin-top: 40px;
        }
        .notification-form label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        .notification-form textarea {
            width: 100%;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            resize: none;
        }
        .notification-form button {
            width: 100%;
            padding: 15px;
            background: #007bff;
            color: #fff;
            border: none;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .notification-form button:hover {
            background: #0056b3;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <!-- Logout Container -->
        <div class="logout-container">
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
        <h1>Admin Dashboard</h1>

        <!-- Cards Section -->
        <div class="cards">
            <div class="card">
                <h2>Total Complaints</h2>
                <p><?php echo $totalComplaints; ?></p>
            </div>
        </div>

        <!-- Charts Section -->
<div class="charts">
    <div>
        <h3>Status Overview</h3>
        <canvas id="statusChart" width="300" height="300"></canvas> <!-- Adjusted size -->
    </div>
    <div>
        <h3>Priority Overview</h3>
        <canvas id="priorityChart" width="300" height="300"></canvas> <!-- Adjusted size -->
    </div>
</div>


        <!-- Filter Form -->
        <form method="GET" class="filter-form" action="admin_dashboard.php">
            <input type="text" name="keyword" placeholder="Search by keyword" value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
            <select name="category">
                <option value="">All Categories</option>
                <?php while ($row = $categoriesResult->fetch_assoc()): ?>
                    <option value="<?= $row['category'] ?>" <?= isset($_GET['category']) && $_GET['category'] === $row['category'] ? 'selected' : '' ?>>
                        <?= $row['category'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <select name="status">
                <option value="">All Status</option>
                <?php foreach ($statuses as $status): ?>
                    <option value="<?= $status ?>" <?= isset($_GET['status']) && $_GET['status'] === $status ? 'selected' : '' ?>>
                        <?= $status ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="date" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">
            <button type="submit">Filter</button>
        </form>

        <!-- Complaints Table -->
        <div class="complaints-table">
            <h2>Complaints</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Reported At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Prepare SQL query with filters
                    $sql = "SELECT * FROM complaints WHERE 1";
                    $params = [];
                    $types = '';

                    if (!empty($_GET['keyword'])) {
                        $sql .= " AND (title LIKE ? OR description LIKE ?)";
                        $params[] = '%' . $_GET['keyword'] . '%';
                        $params[] = '%' . $_GET['keyword'] . '%';
                        $types .= 'ss';
                    }
                    if (!empty($_GET['category'])) {
                        $sql .= " AND category = ?";
                        $params[] = $_GET['category'];
                        $types .= 's';
                    }
                    if (!empty($_GET['status'])) {
                        $sql .= " AND status = ?";
                        $params[] = $_GET['status'];
                        $types .= 's';
                    }
                    if (!empty($_GET['date'])) {
                        $sql .= " AND DATE(reported_at) = ?";
                        $params[] = $_GET['date'];
                        $types .= 's';
                    }

                    $stmt = $conn->prepare($sql);
                    if ($params) {
                        $stmt->bind_param($types, ...$params);
                    }
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($complaint = $result->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?= $complaint['id'] ?></td>
                            <td><?= htmlspecialchars($complaint['title']) ?></td>
                            <td><?= htmlspecialchars($complaint['category']) ?></td>
                            <td><?= htmlspecialchars($complaint['status']) ?></td>
                            <td><?= htmlspecialchars($complaint['reported_at']) ?></td>
                            <td>
                                <a href="edit_complaint.php?id=<?= $complaint['id'] ?>">Edit</a>
                                <a href="delete_complaint.php?id=<?= $complaint['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Notification Form -->
        <div class="notification-form">
            <h2>Send Notification to Users</h2>
            <form method="POST" action="send_notification.php">
                <label for="user_id">Select User:</label>
                <select name="user_id" id="user_id" required>
                    <?php
                    $query = "SELECT id, username FROM users";
                    $result = $conn->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['username']}</option>";
                    }
                    ?>
                </select>

                <label for="complaint_id">Link to Complaint (Optional):</label>
                <select name="complaint_id" id="complaint_id">
                    <option value="">None</option>
                    <?php
                    $query = "SELECT id, title FROM complaints";
                    $result = $conn->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['title']}</option>";
                    }
                    ?>
                </select>

                <label for="message">Message:</label>
                <textarea name="message" id="message" required></textarea>

                <button type="submit">Send Notification</button>
            </form>
        </div>
    </div>
<!-- Feedback Table Section -->
<div class="feedback-table">
    <h2>User Feedback</h2>
    <table>
        <thead>
            <tr>
                <th>Feedback ID</th>
                <th>User Name</th>
                <th>Complaint Title</th>
                <th>Rating</th>
                <th>Comments</th>
                <th>Submitted At</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $feedbackQuery = "
                SELECT f.id, u.full_name, c.title AS complaint_title, f.rating, f.comments, f.created_at
                FROM feedback f
                JOIN users u ON f.user_id = u.id
                JOIN complaints c ON f.complaint_id = c.id
                ORDER BY f.created_at DESC
            ";
            $feedbackResult = $conn->query($feedbackQuery);
            if ($feedbackResult && $feedbackResult->num_rows > 0):
                while ($row = $feedbackResult->fetch_assoc()):
            ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['complaint_title']) ?></td>
                <td><?= htmlspecialchars($row['rating']) ?>/5</td>
                <td><?= htmlspecialchars($row['comments']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
            </tr>
            <?php
                endwhile;
            else:
            ?>
            <tr>
                <td colspan="6">No feedback available.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


    <!-- ChartJS -->
    <script>
        // Status Chart
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_column($statusData, 'status')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($statusData, 'count')); ?>,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            }
        });

        // Priority Chart
        new Chart(document.getElementById('priorityChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($priorityData, 'priority')); ?>,
                datasets: [{
                    label: 'Count',
                    data: <?php echo json_encode(array_column($priorityData, 'count')); ?>,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            }
        });
    </script>
</body>
</html>
