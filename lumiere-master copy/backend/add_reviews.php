<?php
session_start();
include "../database/db.php";

// user must be logged in to submit a review
if (!isset($_SESSION["user_id"])) {
    echo "You must be logged in to add a review.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $username = $_SESSION["username"];
    $movie_id = $_POST["movie_id"] ?? '';
    $movie_title = $_POST["movie_title"] ?? '';
    $comment_text = trim($_POST["comment_text"] ?? '');

    // check review isnt empty
    if (empty($comment_text)) {
        echo "Review cannot be empty.";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO comments (username, comment_text, created_at, users_id, movie_id, movie_title) VALUES (?, ?, NOW(), ?, ?, ?)");
    $stmt->bind_param("ssiss", $username, $comment_text, $user_id, $movie_id, $movie_title);

    if ($stmt->execute()) {
        // redirect to movie page after submitting review
        header("Location: film_overview.php?id=" . urlencode($movie_id));
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>