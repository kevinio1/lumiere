<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div style="padding: 10px; border-bottom: 1px solid #ccc; margin-bottom: 20px; font-family: Arial;">
    <a href="/film-website/">Home</a> |
    <a href="/film-website/backend/search_demo.php">Search</a> |
    <a href="/film-website/backend/genre_demo.php">Genres</a> |
    <a href="/film-website/backend/trending_demo.php">Trending</a> |
    <a href="/film-website/backend/watchlist.php">Watchlist</a> |

    <?php if (isset($_SESSION["username"])): ?>
        Logged in as <strong><?= htmlspecialchars($_SESSION["username"]) ?></strong> |
        <a href="/film-website/auth/logout.php">Logout</a>
    <?php else: ?>
        <a href="/film-website/auth/login.php">Login</a> |
        <a href="/film-website/auth/register.php">Register</a>
    <?php endif; ?>
</div>