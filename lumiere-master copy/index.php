<?php
session_start();

// Store popular movie IDs
$movies = [];

// Get popular movie paths from backend endpoint
$response = file_get_contents("http://localhost/lumiere-master/backend/get_popular_movies.php");
$data = json_decode($response, true);

// Extract first 6 movie IDs
if (is_array($data)) {
    $moviePaths = array_slice($data, 0, 6);

    foreach ($moviePaths as $path) {
        if (preg_match('/tt\d+/', $path, $matches)) {
            $movies[] = $matches[0];
        }
    }
}

/*
Function: getMovieDetails
Used to fetch title and poster for a movie using its movie ID
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
    <title>Lumière</title>
    <link rel="stylesheet" href="frontend/css/style.css">
    
</head>
<body>

<?php include "includes/navbar.php"; ?>




<section class="login-panel">
    <?php if (isset($_SESSION['username'])): ?>
        <p>Logged in as <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>
        <a class="btn-outline" href="auth/logout.php">Logout</a>
    <?php else: ?>
        <p><strong>You are not logged in.</strong></p>
        <p>Login or register to access your watchlist.</p>
        <a class="btn-primary" href="auth/login.php">Login</a>
        <a class="btn-outline" href="auth/register.php">Register</a>
    <?php endif; ?>
</section>





<section class="search-section">
    <h2>Search Movies</h2>

    <form class="search-form" method="GET" action="backend/search_demo.php">
        <input type="text" name="q" placeholder="Search for a movie..." required>
        <button type="submit">Search</button>
    </form>
</section>


<section class="genre-section">
    <h2>Browse by Genre</h2>

    <div class="genre-grid">
        <a href="backend/genre_demo.php?genre=Action">Action</a>
        <a href="backend/genre_demo.php?genre=Comedy">Comedy</a>
        <a href="backend/genre_demo.php?genre=Drama">Drama</a>
        <a href="backend/genre_demo.php?genre=Sci-Fi">Sci-Fi</a>
    </div>
</section>



<p><a href="backend/watchlist.php">My Watchlist</a></p>



<h2>Trending Movies</h2>

<div style="display:flex; gap:20px; flex-wrap:wrap;">

<?php foreach ($movies as $movieId): ?>

<?php
$details = getMovieDetails($movieId);

$title = $details['data']['title']['titleText']['text'] ?? 'Unknown title';
$image = $details['data']['title']['primaryImage']['url'] ?? '';
?>

<div style="width:180px;">

    <?php if ($image): ?>
        <a href="backend/film_overview.php?id=<?= htmlspecialchars($movieId) ?>">
            <img src="<?= htmlspecialchars($image) ?>" width="180">
        </a>
    <?php endif; ?>

    <p>
        <a href="backend/film_overview.php?id=<?= htmlspecialchars($movieId) ?>">
            <?= htmlspecialchars($title) ?>
        </a>
    </p>

</div>

<?php endforeach; ?>

</div>





