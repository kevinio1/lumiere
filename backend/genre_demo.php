<?php
/*
 allows users to browse movies by genre
first it call search_by_genre.php to retrieve movie IDs, then fetches movie info (title, poster, year) using rapidAPI overview endpoint
*/

//store movie results
$movies = [];

/*
getMovieDetails- takes movie ID and sends request to RapidAPI endpoint to get movie info
*/
function getMovieDetails($movieId) {
    $curl = curl_init();

    // API request
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

    // Execute API request
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    // If error occurrs return it
    if ($err) {
        return ["error" => $err];
    }

    // convert JSON into PHP array
    return json_decode($response, true);
}

/*
check if user selects genre from url
*/
if (isset($_GET['genre']) && $_GET['genre'] !== '') {

    $genre = urlencode($_GET['genre']);

    // calls the backend endpoint that retrieves movies by genre
    $url = "http://localhost/film-website/backend/search_by_genre.php?genre=" . $genre;
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    /*
API returns movie IDs inside data, advancedtitleSearch, edges
    */
    if (isset($data['data']['advancedTitleSearch']['edges'])) {

        // limit results to first 6 movies to improve performance
        $movies = array_slice($data['data']['advancedTitleSearch']['edges'], 0, 6);
    }
}
?>

<h1>Browse by Genre</h1>

<ul>
    <li><a href="genre_demo.php?genre=Action">Action</a></li>
    <li><a href="genre_demo.php?genre=Comedy">Comedy</a></li>
    <li><a href="genre_demo.php?genre=Drama">Drama</a></li>
    <li><a href="genre_demo.php?genre=Sci-Fi">Sci-Fi</a></li>
</ul>
<?php if (isset($_GET['genre']) && $_GET['genre'] !== ''): ?>
    <h2>
        Popular <strong><?= htmlspecialchars($_GET['genre']) ?></strong> Movies:
    </h2>
<?php else: ?>
    <h2>Select a genre</h2>
<?php endif; ?>
<hr>

<?php
// loop through the movies returned from the genre search
?>

<?php foreach ($movies as $movie): ?>

<?php
// get movie ID from the API response
$movieId = $movie['node']['title']['id'] ?? '';

// skip if no movie ID was found
if ($movieId === '') {
    continue;
}

// get movie details
$details = getMovieDetails($movieId);

// handle API errors
if (isset($details['error'])) {
    $title = 'Error loading movie';
    $year = 'N/A';
    $image = '';
} else {

    // get movie info from API
    $title = $details['data']['title']['titleText']['text'] ?? 'Unknown title';
    $year = $details['data']['title']['releaseYear']['year'] ?? 'N/A';
    $image = $details['data']['title']['primaryImage']['url'] ?? '';
}
?>

<div style="margin-bottom:20px;">

    <!-- display movie poster  -->
    <?php if ($image): ?>
        <a href="film_overview.php?id=<?= htmlspecialchars($movieId) ?>">
            <img src="<?= htmlspecialchars($image) ?>" width="120">
        </a><br><br>
    <?php endif; ?>

    <!-- Movie title linking to detailed movie page -->
    <h3>
        <a href="film_overview.php?id=<?= htmlspecialchars($movieId) ?>">
            <?= htmlspecialchars($title) ?>
        </a>
    </h3>

    <!-- display movie release year -->
    <p>Year: <?= htmlspecialchars((string)$year) ?></p>


</div>

<hr>

<?php endforeach; ?>