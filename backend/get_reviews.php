<?php
include "../database/db.php";

if (!isset($_GET["movie_id"])) {
    echo "Movie ID is required.";
    exit();
}

$movie_id = $_GET["movie_id"];

$stmt = $conn->prepare("SELECT username, comment_text, created_at FROM comments WHERE movie_id = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $movie_id);
$stmt->execute();

$result = $stmt->get_result();

$reviews = [];

while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}

echo json_encode($reviews);

$stmt->close();
$conn->close();
?>