<?php
/*
 allows users to browse movies by genre.
It first calls the backend endpoint search_by_genre.php to retrieve movie IDs.
Then it fetches detailed movie information (title, poster, year) using the RapidAPI overview endpoint
*/

// Array to store movie results
$movies = [];

/*
Function: getMovieDetails

Purpose:
Takes a movie ID and sends a request to the RapidAPI endpoint to retrieve detailed information
about the movie (title, poster, year,..)
*/
function getMovieDetails($movieId) {
    $curl = curl_init();

    // Configure API request
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

    // Execute the API request
    $response = curl_exec($curl);
    $err = curl_error($curl);

    // Close cURL connection
    curl_close($curl);

    // If an error occurred, return it
    if ($err) {
        return ["error" => $err];
    }

    // Convert JSON response into a PHP array
    return json_decode($response, true);
}

/*
Check if the user selected a genre from the URL.
Example:
genre_demo.php?genre=Action
*/
if (isset($_GET['genre']) && $_GET['genre'] !== '') {

    // Encode genre for safe use in URL
    $genre = urlencode($_GET['genre']);

    // Call the backend endpoint that retrieves movies by genre
    $url = "http://localhost/film-website/backend/search_by_genre.php?genre=" . $genre;

    // Get API response
    $response = file_get_contents($url);

    // Convert JSON response to PHP array
    $data = json_decode($response, true);

    /*
    The API returns movie IDs inside:
    data -> advancedTitleSearch -> edges
    */
    if (isset($data['data']['advancedTitleSearch']['edges'])) {

        // Limit results to first 6 movies for performance
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

<hr>

<?php
// Loop through the movies returned from the genre search
?>

<?php foreach ($movies as $movie): ?>

<?php
// Extract IMDb movie ID from the API response
$movieId = $movie['node']['title']['id'] ?? '';

// Skip if no movie ID was found
if ($movieId === '') {
    continue;
}

// Fetch detailed information for this movie
$details = getMovieDetails($movieId);

// Handle API errors
if (isset($details['error'])) {
    $title = 'Error loading movie';
    $year = 'N/A';
    $image = '';
} else {

    // Extract movie information from API response
    $title = $details['data']['title']['titleText']['text'] ?? 'Unknown title';
    $year = $details['data']['title']['releaseYear']['year'] ?? 'N/A';
    $image = $details['data']['title']['primaryImage']['url'] ?? '';
}
?>

<div style="margin-bottom:20px;">

    <!-- Display movie poster if available -->
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

    <!-- Display movie release year -->
    <p>Year: <?= htmlspecialchars((string)$year) ?></p>

    <!-- Display IMDb movie ID (used for API requests) -->
    <p>Movie ID: <?= htmlspecialchars($movieId) ?></p>

</div>

<hr>

<?php endforeach; ?>