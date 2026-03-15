<?php
$moviePaths = [];
$movieIds = [];

function getMovieDetails($movieId) {
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://online-movie-database.p.rapidapi.com/title/v2/get-overview?tconst=$movieId&country=US&language=en-US",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: online-movie-database.p.rapidapi.com",
            "x-rapidapi-key: cf3356ca88msh51f5db0eefae431p19cb45jsnc81b800f8dc8"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return ["error" => $err];
    }

    return json_decode($response, true);
}

// Get popular movie IDs from backend endpoint
$url = "http://localhost/film-website/backend/get_popular_movies.php";
$response = file_get_contents($url);
$data = json_decode($response, true);

// Store returned paths
if (is_array($data)) {
    $moviePaths = array_slice($data, 0, 6);
}

// Extract IMDb IDs from paths like /title/tt15940132/
foreach ($moviePaths as $path) {
    if (preg_match('/tt\d+/', $path, $matches)) {
        $movieIds[] = $matches[0];
    }
}
?>

<h1>Trending / Popular Movies</h1>
<hr>

<?php foreach ($movieIds as $movieId): ?>

<?php
$details = getMovieDetails($movieId);

if (isset($details['error'])) {
    $title = 'Error loading movie';
    $year = 'N/A';
    $image = '';
} else {
    $title = $details['data']['title']['titleText']['text'] ?? 'Unknown title';
    $year = $details['data']['title']['releaseYear']['year'] ?? 'N/A';
    $image = $details['data']['title']['primaryImage']['url'] ?? '';
}
?>

<div style="display:inline-block; width:220px; margin:20px; vertical-align:top;">

    <?php if ($image): ?>
        <a href="film_overview.php?id=<?= htmlspecialchars($movieId) ?>">
            <img src="<?= htmlspecialchars($image) ?>" width="180">
        </a><br><br>
    <?php endif; ?>

    <h3>
        <a href="film_overview.php?id=<?= htmlspecialchars($movieId) ?>">
            <?= htmlspecialchars($title) ?>
        </a>
    </h3>

    <p>Year: <?= htmlspecialchars((string)$year) ?></p>

</div>

<?php endforeach; ?>