<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="site-header">
    <div class="brand-row">
        <img src="/lumiere-master/frontend/assets/logo.png" class="logo" alt="Lumière Logo">
        <h1 class="brand-title">Lumière</h1>
    </div>

    <nav class="main-nav">
        <a href="/lumiere-master/index.php">Home</a>
        <a href="/lumiere-master/backend/search_demo.php">Search</a>
        <a href="/lumiere-master/backend/genre_demo.php">Genres</a>
        <a href="/lumiere-master/backend/trending_demo.php">Trending</a>
        <a href="/lumiere-master/backend/watchlist.php">Watchlist</a>

        <?php if (isset($_SESSION['username'])): ?>
            <a href="/lumiere-master/auth/logout.php">Logout</a>
        <?php else: ?>
            <a href="/lumiere-master/auth/login.php">Login</a>
            <a href="/lumiere-master/auth/register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>