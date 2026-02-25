<?php
session_start();
include 'db.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $userId = $_SESSION['user_id'];
    $complaintId = $_POST['complaint_id'];
    $rating = $_POST['rating'];
    $comments = trim($_POST['comments']);

    // Validate input
    if (empty($complaintId) || empty($rating) || empty($comments)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: submit_feedback.php");
        exit();
    }

    // Check if feedback already exists for this complaint
    $checkQuery = "SELECT id FROM feedback WHERE user_id = ? AND complaint_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $userId, $complaintId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "You have already submitted feedback for this complaint.";
        $stmt->close();
        header("Location: submit_feedback.php");
        exit();
    }
    $stmt->close();

    // Insert feedback into the database
    $insertQuery = "INSERT INTO feedback (user_id, complaint_id, rating, comments) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iiis", $userId, $complaintId, $rating, $comments);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Feedback submitted successfully!";
        header("Location: submit_feedback.php");
    } else {
        $_SESSION['error'] = "An error occurred while submitting your feedback. Please try again.";
        header("Location: submit_feedback.php");
    }

    $stmt->close();
} else {
    header("Location: submit_feedback.php");
    exit();
}
?>
