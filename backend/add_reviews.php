<?php
session_start();
include "../database/db.php";

if (!isset($_SESSION["user_id"])) {
    echo "You must be logged in to add a review.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $username = $_SESSION["username"];
    $movie_id = $_POST["movie_id"];
    $movie_title = $_POST["movie_title"];
    $comment_text = trim($_POST["comment_text"]);

    if (empty($comment_text)) {
        echo "Review cannot be empty.";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO comments (username, comment_text, created_at, users_id, movie_id, movie_title) VALUES (?, ?, NOW(), ?, ?, ?)");
    $stmt->bind_param("ssiss", $username, $comment_text, $user_id, $movie_id, $movie_title);

    if ($stmt->execute()) {
        echo "Review added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
<form method="POST" action="">
    <input type="text" name="movie_id" placeholder="Movie ID (e.g. tt0120338)" required><br><br>
    <input type="text" name="movie_title" placeholder="Movie Title" required><br><br>
    <textarea name="comment_text" placeholder="Write your review here" required></textarea><br><br>
    <button type="submit">Add Review</button>
</form>