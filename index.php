<?php
session_start();

// Store popular movie IDs
$movies = [];

// get popular movie paths from backend endpoint
$response = file_get_contents("http://localhost/film-website/backend/get_popular_movies.php");
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
used to fetch title and poster for a movie using its movie IDs
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

<h1>Lumiere</h1>

<hr>

<?php if (isset($_SESSION["username"])): ?>
    <p>Logged in as <strong><?= htmlspecialchars($_SESSION["username"]) ?></strong></p>
    <a href="auth/logout.php">Logout</a>
<?php else: ?>
    <p>You are not logged in.</p>
    <a href="auth/login.php">Login</a><br>
    <a href="auth/register.php">Register</a>
<?php endif; ?>

<hr>

<h2>Search Movies</h2>

<form method="GET" action="backend/search_demo.php">
    <input type="text" name="q" placeholder="Search for a movie..." required>
    <button type="submit">Search</button>
</form>

<hr>

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

<hr>

<h2>Browse by Genre</h2>

<ul>
    <li><a href="backend/genre_demo.php?genre=Action">Action</a></li>
    <li><a href="backend/genre_demo.php?genre=Comedy">Comedy</a></li>
    <li><a href="backend/genre_demo.php?genre=Drama">Drama</a></li>
    <li><a href="backend/genre_demo.php?genre=Sci-Fi">Sci-Fi</a></li>
</ul>

<hr>

<h2>Other Features</h2>

<ul>
    <li><a href="backend/search_demo.php">Search Movies Demo</a></li>
    <li><a href="backend/genre_demo.php">Browse by Genre Demo</a></li>
    <li><a href="backend/trending_demo.php">Trending / Popular Movies Demo</a></li>
    <li><a href="backend/add_reviews.php">Add Review</a></li>
</ul>