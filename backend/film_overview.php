<?php
session_start();

// Get movie ID from the URL parameter
$movie_id = $_GET['id'] ?? 'tt0120338';

// Start cURL request to call RapidAPI
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

// Execute API request
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

// Display error if one occurs
if ($err) {
    echo "cURL Error #: " . $err;
    exit();
}

// Convert JSON response to PHP array
$data = json_decode($response, true);

// Extract movie information from the JSON response
$title = $data['data']['title']['titleText']['text'] ?? 'Unknown title';
$year = $data['data']['title']['releaseYear']['year'] ?? 'N/A';
$image = $data['data']['title']['primaryImage']['url'] ?? '';
$plot = $data['data']['title']['plot']['plotText']['plainText'] ?? 'No description available';

// Fetch reviews for this movie
$reviewsUrl = "http://localhost/film-website/backend/get_reviews.php?movie_id=" . urlencode($movie_id);
$reviewsResponse = file_get_contents($reviewsUrl);
$reviews = json_decode($reviewsResponse, true);
?>
<link rel="stylesheet" href="/film-website/frontend/css/style.css">
<?php include "../includes/navbar.php"; ?>
<h1><?= htmlspecialchars($title) ?></h1>

<?php if ($image): ?>
    <img src="<?= htmlspecialchars($image) ?>" width="250"><br><br>
<?php endif; ?>

<strong>Year:</strong> <?= htmlspecialchars((string)$year) ?><br><br>

<p><?= htmlspecialchars($plot) ?></p>

<?php if (isset($_SESSION["user_id"])): ?>
    <form method="POST" action="add_to_watchlist.php">
        <input type="hidden" name="movie_id" value="<?= htmlspecialchars($movie_id) ?>">
        <button type="submit">+ Add to Watchlist</button>
    </form>
<?php else: ?>
    <p><a href="../auth/login.php">Login to add to watchlist</a></p>
<?php endif; ?>

<hr>

<h2>Reviews</h2>

<?php if (!empty($reviews)): ?>
    <?php foreach ($reviews as $review): ?>
        <div style="margin-bottom:20px;">
            <strong><?= htmlspecialchars($review['username']) ?></strong><br>
            <p><?= htmlspecialchars($review['comment_text']) ?></p>
            <small><?= htmlspecialchars($review['created_at']) ?></small>
        </div>
        <hr>
    <?php endforeach; ?>
<?php else: ?>
    <p>No reviews yet.</p>
<?php endif; ?>

<hr>

<h2>Add a Review</h2>

<?php if (isset($_SESSION["user_id"])): ?>
    <form method="POST" action="add_reviews.php">
        <input type="hidden" name="movie_id" value="<?= htmlspecialchars($movie_id) ?>">
        <input type="hidden" name="movie_title" value="<?= htmlspecialchars($title) ?>">

        <textarea name="comment_text" placeholder="Write your review here..." required></textarea><br><br>

        <button type="submit">Submit Review</button>
    </form>
<?php else: ?>
    <p><a href="../auth/login.php">Login to add a review</a></p>
<?php endif; ?>