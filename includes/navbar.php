<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="site-header">
    <div class="brand-row">
        <img src="/film-website/frontend/assets/logo.png" class="logo" alt="Lumière Logo">
        <h1 class="brand-title">Lumière</h1>
    </div>

    <nav class="main-nav">
        <a href="/film-website/index.php">Home</a>
        <a href="/film-website/backend/search_demo.php">Search</a>
        <a href="/film-website/backend/genre_demo.php">Genres</a>
        <a href="/film-website/backend/trending_demo.php">Trending</a>
        <a href="/film-website/backend/watchlist.php">Watchlist</a>

        <?php if (isset($_SESSION['username'])): ?>
            <a href="/film-website/auth/logout.php">Logout</a>
        <?php else: ?>
            <a href="/film-website/auth/login.php">Login</a>
            <a href="/film-website/auth/register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>