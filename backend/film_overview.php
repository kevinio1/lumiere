/*
//File: film_overview.php
Purpose: Displays detailed information about a movie including title,
poster, year and description using rapidAPI.
*/
<?php
// Get the movie ID from the URL parameter
// If none is provided, default to Titanic
$movie_id = $_GET['id'] ?? 'tt0120338';

// Initialize a cURL request to call the IMDb RapidAPI
$curl = curl_init();

// Configure the API request settings
curl_setopt_array($curl, [
    CURLOPT_URL => "https://online-movie-database.p.rapidapi.com/title/v2/get-overview?tconst=$movie_id&country=US&language=en-US",
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

// Close the cURL connection
curl_close($curl);

// If an error occurred, display it
if ($err) {
    echo "cURL Error #: " . $err;
    exit();
}

// Convert the JSON API response into a PHP array
$data = json_decode($response, true);

// Extract movie information from the response
$title = $data['data']['title']['titleText']['text'] ?? 'Unknown title';
$year = $data['data']['title']['releaseYear']['year'] ?? 'N/A';
$image = $data['data']['title']['primaryImage']['url'] ?? '';
$plot = $data['data']['title']['plot']['plotText']['plainText'] ?? 'No description available';
?>

<!-- Display the movie title -->
<h1><?= htmlspecialchars($title) ?></h1>

<!-- Display movie poster if available -->
<?php if ($image): ?>
    <img src="<?= htmlspecialchars($image) ?>" width="250"><br><br>
<?php endif; ?>

<!-- Display release year -->
<strong>Year:</strong> <?= htmlspecialchars((string)$year) ?><br><br>

<!-- Display movie description -->
<p><?= htmlspecialchars($plot) ?></p>