<?php
session_start();
include 'db.php';

// Helper functions (you may have these elsewhere)
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Check admin access
if (!isLoggedIn() || !isAdmin()) {
    header("Location: login.php");
    exit();
}

// Check if complaint ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Complaint ID missing.";
    header("Location: admin_dashboard.php");
    exit();
}

$complaint_id = intval($_GET['id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['status'] ?? '';
    $valid_statuses = ['Pending Review', 'In Progress', 'Resolved'];

    if (!in_array($new_status, $valid_statuses)) {
        $_SESSION['error'] = "Invalid status selected.";
        header("Location: change_status.php?id=$complaint_id");
        exit();
    }

    // Update status in DB
    $stmt = $conn->prepare("UPDATE complaints SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $new_status, $complaint_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Status updated successfully.";
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to update status.";
    }
}

// Fetch complaint for display
$stmt = $conn->prepare("SELECT id, title, status FROM complaints WHERE id = ?");
$stmt->bind_param('i', $complaint_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Complaint not found.";
    header("Location: admin_dashboard.php");
    exit();
}

$complaint = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Change Complaint Status</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; }
        .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 8px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        select, button { padding: 10px; font-size: 16px; margin-top: 10px; width: 100%; }
        button { background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .message { margin-bottom: 15px; padding: 10px; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        a { display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="container">
    <h2>Change Status for Complaint: <?= htmlspecialchars($complaint['title']) ?></h2>

    <?php
    if (isset($_SESSION['error'])) {
        echo "<div class='message error'>" . $_SESSION['error'] . "</div>";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo "<div class='message success'>" . $_SESSION['success'] . "</div>";
        unset($_SESSION['success']);
    }
    ?>

    <form method="POST" action="">
        <label for="status">Select New Status:</label>
        <select name="status" id="status" required>
            <?php
            $statuses = ['Pending Review', 'In Progress', 'Resolved'];
            foreach ($statuses as $status) {
                $selected = ($complaint['status'] === $status) ? 'selected' : '';
                echo "<option value=\"$status\" $selected>$status</option>";
            }
            ?>
        </select>
        <button type="submit">Update Status</button>
    </form>

    <a href="admin_dashboard.php">&larr; Back to Dashboard</a>
</div>
</body>
</html>
