<?php
session_start();
include "../database/db.php";

if (!isset($_SESSION["user_id"])) {
    echo "You must be logged in.";
    exit();
}

$user_id = $_SESSION["user_id"];
$movie_id = $_POST["movie_id"] ?? '';

if ($movie_id === '') {
    echo "Invalid movie.";
    exit();
}

// Delete movie from watchlist
$stmt = $conn->prepare("DELETE FROM watchlist WHERE user_id = ? AND movie_id = ?");
$stmt->bind_param("is", $user_id, $movie_id);
$stmt->execute();

$stmt->close();
$conn->close();

// Redirect back
header("Location: watchlist.php");
exit();
?>