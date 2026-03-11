<?php
session_start();
include "database/db.php";
?>

<h1>Lumiere</h1>

<?php if (isset($_SESSION["username"])): ?>
    <p>Logged in as <?= htmlspecialchars($_SESSION["username"]) ?></p>
    <a href="auth/logout.php">Logout</a>
<?php else: ?>
    <p>You are not logged in.</p>
    <a href="auth/login.php">Login</a><br>
    <a href="auth/register.php">Register</a>
<?php endif; ?>

<hr>

<h2>Features</h2>

<ul>
    <li><a href="backend/search_demo.php">Search Movies</a></li>
    <li><a href="backend/genre_demo.php">Browse by Genre</a></li>
    <li><a href="backend/film_overview.php">Film Overview Demo</a></li>
    <li><a href="backend/add_reviews.php">Add Review</a></li>
</ul>