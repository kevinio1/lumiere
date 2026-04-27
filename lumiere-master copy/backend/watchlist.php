<?php
session_start();
include __DIR__ . "/../database/db.php";

if (!isset($_SESSION["user_id"])) {
    echo "You must be logged in.";
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch saved movie IDs
$stmt = $conn->prepare("SELECT movie_id FROM watchlist WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$movies = [];

while ($row = $result->fetch_assoc()) {
    $movies[] = $row['movie_id'];
}

$stmt->close();
$conn->close();

/*
Function: getMovieDetails
Fetches movie title + image from API
*/
function getMovieDetails($movieId) {

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://online-movie-database.p.rapidapi.com/title/v2/get-overview?tconst=$movieId&country=US&language=en-US",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: online-movie-database.p.rapidapi.com",
            "x-rapidapi-key: cf3356ca88msh51f5db0eefae431p19cb45jsnc81b800f8dc8"
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Trending/ Popular Movies</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>

<?php include "../includes/navbar.php"; ?>
<h1>My Watchlist</h1>





<?php foreach ($movies as $movieId): ?>

<?php
$details = getMovieDetails($movieId);

$title = $details['data']['title']['titleText']['text'] ?? 'Unknown title';
$image = $details['data']['title']['primaryImage']['url'] ?? '';
?>

<div style="width:180px;">

    <?php if ($image): ?>
        <a href="film_overview.php?id=<?= htmlspecialchars($movieId) ?>">
            <img src="<?= htmlspecialchars($image) ?>" width="180">
        </a>
    <?php endif; ?>

    <p>
        <a href="film_overview.php?id=<?= htmlspecialchars($movieId) ?>">
            <?= htmlspecialchars($title) ?>
        </a>
    </p>

    <!-- REMOVE BUTTON -->
    <form method="POST" action="remove_from_watchlist.php">
        <input type="hidden" name="movie_id" value="<?= htmlspecialchars($movieId) ?>">
        <button type="submit">Remove</button>
    </form>

</div>

</body>
</html>

<?php endforeach; ?>

</div>