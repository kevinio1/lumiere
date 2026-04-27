<?php
session_start();
include "../database/db.php";

// Must be logged in
if (!isset($_SESSION["user_id"])) {
    echo "You must be logged in.";
    exit();
}

$user_id = $_SESSION["user_id"];
$movie_id = $_POST["movie_id"] ?? '';

// Prevent empty
if ($movie_id === '') {
    echo "Invalid movie.";
    exit();
}

// Prevent duplicates
$stmt = $conn->prepare("SELECT * FROM watchlist WHERE user_id = ? AND movie_id = ?");
$stmt->bind_param("is", $user_id, $movie_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: film_overview.php?id=" . urlencode($movie_id));
    exit();
}

// Insert movie
$stmt = $conn->prepare("INSERT INTO watchlist (user_id, movie_id) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $movie_id);
$stmt->execute();

$stmt->close();
$conn->close();

// Redirect back
header("Location: film_overview.php?id=" . urlencode($movie_id));
exit();
?>