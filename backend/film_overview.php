<?php
session_start();

$movie_id = $_GET['id'] ?? 'tt0120338';

$curl = curl_init();

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
        "x-rapidapi-key: 10f110f5ecmshaec681b4b7be422p156f44jsn6f02c0336a12"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo "cURL Error #: " . $err;
    exit();
}

$data = json_decode($response, true);

$title = $data['data']['title']['titleText']['text'] ?? 'Unknown title';
$year = $data['data']['title']['releaseYear']['year'] ?? 'N/A';
$image = $data['data']['title']['primaryImage']['url'] ?? '';
$plot = $data['data']['title']['plot']['plotText']['plainText'] ?? 'No description available';

$reviewsUrl = "http://localhost/film-website/backend/get_reviews.php?movie_id=" . urlencode($movie_id);
$reviewsResponse = file_get_contents($reviewsUrl);
$reviews = json_decode($reviewsResponse, true);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>

<body>

<?php include "../includes/navbar.php"; ?>

<main class="film-page">

    <section class="film-hero">
        <div class="film-poster">
            <?php if ($image): ?>
                <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($title) ?>">
            <?php endif; ?>
        </div>

        <div class="film-info">
            <h1><?= htmlspecialchars($title) ?></h1>
            <p class="film-year">Year: <?= htmlspecialchars((string)$year) ?></p>
            <p class="film-plot"><?= htmlspecialchars($plot) ?></p>

            <?php if (isset($_SESSION["user_id"])): ?>
                <form method="POST" action="add_to_watchlist.php">
                    <input type="hidden" name="movie_id" value="<?= htmlspecialchars($movie_id) ?>">
                    <button type="submit">+ Add to Watchlist</button>
                </form>
            <?php else: ?>
                <a class="btn-outline" href="../auth/login.php">Login to add to watchlist</a>
            <?php endif; ?>
        </div>
    </section>

    <section class="reviews-section">
        <h2>Reviews</h2>

        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review-card">
                    <strong><?= htmlspecialchars($review['username']) ?></strong>
                    <p><?= htmlspecialchars($review['comment_text']) ?></p>
                    <small><?= htmlspecialchars($review['created_at']) ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty-message">No reviews yet.</p>
        <?php endif; ?>
    </section>

    <section class="add-review-section">
        <h2>Add a Review</h2>

        <?php if (isset($_SESSION["user_id"])): ?>
            <form method="POST" action="add_reviews.php" class="review-form">
                <input type="hidden" name="movie_id" value="<?= htmlspecialchars($movie_id) ?>">
                <input type="hidden" name="movie_title" value="<?= htmlspecialchars($title) ?>">

                <textarea name="comment_text" placeholder="Write your review here..." required></textarea>

                <button type="submit">Submit Review</button>
            </form>
        <?php else: ?>
            <a class="btn-outline" href="../auth/login.php">Login to add a review</a>
        <?php endif; ?>
    </section>

</main>

</body>
</html>