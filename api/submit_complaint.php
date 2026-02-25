<?php
session_start();
include('../db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $reporter_name = $_SESSION['username']; // Fetch from session

    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $priority = $_POST['priority'] ?? 'Low';
    $category = $_POST['category'] ?? '';
    $location = $_POST['location'] ?? '';
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;
    $incident_date = $_POST['incident_date'] ?? null;

    $evidence = $_FILES['evidence'] ?? null;
    $evidence_path = null;

    // Handle file upload (if any)
    if ($evidence && $evidence['tmp_name']) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $evidence_path = $upload_dir . basename($evidence['name']);
        if (!move_uploaded_file($evidence['tmp_name'], $evidence_path)) {
            header("Location: ../new_complaint.php?error=Failed to upload evidence.");
            exit();
        }
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO complaints (user_id, title, description, priority, category, location, latitude, longitude, reporter_name, evidence, reported_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        header("Location: ../new_complaint.php?error=" . urlencode($conn->error));
        exit();
    }

    $stmt->bind_param("issssssddss", $user_id, $title, $desc, $priority, $category, $location, $latitude, $longitude, $reporter_name, $evidence_path, $incident_date);

    if ($stmt->execute()) {
        header("Location: ../user_dashboard.php?success=Complaint submitted successfully.");
        exit();
    } else {
        header("Location: ../new_complaint.php?error=" . urlencode($stmt->error));
        exit();
    }
} else {
    header("Location: ../new_complaint.php");
    exit();
}
