<?php
/*


Purpose:
This page allows users to search for movies.
It sends the search query to search_films.php which calls rapidAPI
The results are then displayed with title, poster, year and movie ID.
*/

// Array to store movie results
$movies = [];

// Check if the user entered a search query
if (isset($_GET['q']) && $_GET['q'] !== '') {

    // Encode the search query for safe use in a URL
    $query = urlencode($_GET['q']);

    // Call the backend search API endpoint
    $url = "http://localhost/film-website/backend/search_films.php?q=" . $query;

    // Get the JSON response from the API
    $response = file_get_contents($url);

    // Convert JSON response into a PHP array
    $data = json_decode($response, true);

    // The RapidAPI autocomplete response stores movie results inside 'd'
    if (isset($data['d'])) {
        $movies = $data['d'];
    }
}
?>

<h1>Search Movies</h1>

<!-- Search form for entering movie titles -->
<form method="GET">
    <input type="text" name="q" placeholder="Search movie..." required>
    <button type="submit">Search</button>
</form>

<hr>

<?php
// Loop through all movies returned from the API
// RapidAPI uses short field names:
// l = title
// y = release year
// i.imageUrl = poster image
// id = IMDb movie ID
?>

<?php foreach ($movies as $movie): ?>

<div style="margin-bottom:20px;">

<!-- Display movie poster if available -->
<?php if(isset($movie['i']['imageUrl'])): ?>
<img src="<?= $movie['i']['imageUrl'] ?>" width="120">
<?php endif; ?>

<!-- Movie title linking to the film overview page -->
<h3>
    <a href="film_overview.php?id=<?= $movie['id'] ?>">
        <?= $movie['l'] ?? "Unknown title" ?>
    </a>
</h3>

<!-- Display movie release year -->
<p>Year: <?= $movie['y'] ?? "N/A" ?></p>

<!-- Display IMDb movie ID (used to fetch detailed movie information) -->
<p>Movie ID: <?= $movie['id'] ?? "" ?></p>

</div>

<hr>

<?php endforeach; ?>