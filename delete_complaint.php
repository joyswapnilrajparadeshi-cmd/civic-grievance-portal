<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = "DELETE FROM complaints WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?status=success&message=" . urlencode("Complaint deleted successfully!"));
    } else {
        header("Location: admin_dashboard.php?status=error&message=" . urlencode("Failed to delete complaint."));
    }
    exit();
} else {
    header("Location: admin_dashboard.php?status=error&message=" . urlencode("Invalid complaint ID."));
    exit();
}
?>
