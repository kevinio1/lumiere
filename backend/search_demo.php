<?php
/*


allows users to search for movies; sends search query to search_films.php which then calls rapidAPI
The results are then displayed with title, poster, year ect
*/

// store movie results
$movies = [];

// check if the user entered a search query
if (isset($_GET['q']) && $_GET['q'] !== '') {

    // Encode the search query for safe use in url
    $query = urlencode($_GET['q']);
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

<!DOCTYPE html>
<html>
<head>
    <title>Search Movies</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>


<?php include "../includes/navbar.php"; ?>
<h1>Search Movies</h1>

<!-- search form for entering movie titles -->
<form method="GET">
    <input type="text" name="q" placeholder="Search movie..." required>
    <button type="submit">Search</button>
</form>



<?php
// RapidAPI uses short field names:
// l =title
// y =release year
// i.imageUrl =poster image
// id =movie ID
?>

<?php foreach ($movies as $movie): ?>

<div style="margin-bottom:20px;">

<!-- display the movie poster if available -->
<?php if(isset($movie['i']['imageUrl'])): ?>
<img src="<?= $movie['i']['imageUrl'] ?>" width="120">
<?php endif; ?>

<!-- movie title linking to film overview -->
<h3>
    <a href="film_overview.php?id=<?= $movie['id'] ?>">
        <?= $movie['l'] ?? "Unknown title" ?>
    </a>
</h3>

<!-- Display movie release year -->
<p>Year: <?= $movie['y'] ?? "N/A" ?></p>

<!-- display movie ID (can be used to fetch further movie info) -->
<p>Movie ID: <?= $movie['id'] ?? "" ?></p>

</div>

</body>
</html>

<?php endforeach; ?>

