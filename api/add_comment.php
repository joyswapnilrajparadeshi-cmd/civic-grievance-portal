<?php
session_start();
include('../db.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $complaint_id = filter_input(INPUT_POST, 'complaint_id', FILTER_VALIDATE_INT);
    $commenter_name = htmlspecialchars(trim($_POST['commenter_name']));
    $comment_text = htmlspecialchars(trim($_POST['comment_text']));

    if (!$complaint_id || empty($commenter_name) || empty($comment_text)) {
        echo "All fields are required.";
        exit();
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO comments (complaint_id, commenter_name, comment_text) VALUES (?, ?, ?)");
    if ($stmt === false) {
        echo "Error preparing the SQL statement.";
        exit();
    }

    // Bind parameters and execute
    $stmt->bind_param("iss", $complaint_id, $commenter_name, $comment_text);
    if ($stmt->execute()) {
        // Redirect back to the complaint page
        header("Location: ../view_complaint.php?id=$complaint_id");
        exit();
    } else {
        echo "Error adding the comment. Please try again.";
    }
} else {
    echo "Invalid request method.";
}
?>
