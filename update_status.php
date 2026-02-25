<?php
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $comments = $_POST['comments'];

    $query = "UPDATE complaints SET status = ?, comments = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssi', $status, $comments, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Complaint status updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update complaint status.";
    }
    header("Location: view_complaints.php");
    exit();
}
?>
