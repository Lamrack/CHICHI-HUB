<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';

// 1. Get the search term from the URL (?q=something)
$searchTerm = isset($_GET['q']) ? trim($_GET['q']) : "";

// 2. Build query depending on whether user is searching or not
if ($searchTerm === "") {
    // no filter -> get all movies
    $stmt = $pdo->query("
        SELECT movie_id, title, genre, release_year, poster_url
        FROM movies
        ORDER BY created_at DESC
    ");
    $movies = $stmt->fetchAll();
} else {
    // filter -> use LIKE on title, genre, release_year
    $like = '%' . $searchTerm . '%';

    $stmt = $pdo->prepare("
        SELECT movie_id, title, genre, release_year, poster_url
        FROM movies
        WHERE title        LIKE ?
           OR genre        LIKE ?
           OR release_year LIKE ?
        ORDER BY created_at DESC
    ");
    $stmt->execute([$like, $like, $like]);
    $movies = $stmt->fetchAll();
}

// 3. If logged in, build list of movie_ids already in this user's watchlist
$userWatchlistIds = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stmt2 = $pdo->prepare("SELECT movie_id FROM watchlist WHERE user_id = ?");
    $stmt2->execute([$user_id]);
    $userWatchlistIds = $stmt2->fetchAll(PDO::FETCH_COLUMN);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Movies</title>
        <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<main class="page-shell">

    <!-- Top bar: title + search on the right -->
    <section class="index-top-bar">
        <h2 class="page-title">All Movies</h2>

        <form method="get" action="index.php" class="index-header-search">
            <input
                class="input-field index-header-search-input"
                type="text"
                name="q"
                value="<?php echo htmlspecialchars($searchTerm); ?>"
                placeholder="e.g. Inception, Thriller, 2019"
            >
            <button type="submit" class="btn-teal index-header-search-btn">Search</button>
            <a href="index.php" class="btn-teal index-header-search-clear">Clear</a>
        </form>
    </section>

    <?php if (empty($movies)): ?>
        <section class="auth-card" style="max-width:400px;">
            <div class="small-text text-center muted">No movies found.</div>
        </section>
    <?php else: ?>

        <!-- Movie grid -->
        <section class="content-grid">
            <?php foreach ($movies as $m): ?>
                <article class="movie-card">

                    <a href="movie_details.php?id=<?= (int)$m['movie_id'] ?>" class="movie-poster-wrap">
                        <?php if (!empty($m['poster_url'])): ?>
                            <img
                                src="<?= htmlspecialchars($m['poster_url']); ?>"
                                alt="<?= htmlspecialchars($m['title']); ?>"
                            >
                        <?php else: ?>
                            <div>No Poster</div>
                        <?php endif; ?>
                    </a>

                    <div class="movie-body">
                        <div class="movie-title">
                            <?= htmlspecialchars($m['title']); ?>
                        </div>

                        <div class="movie-meta">
                            <?php if (!empty($m['genre'])): ?>
                                <span class="badge-genre"><?= htmlspecialchars($m['genre']); ?></span>
                            <?php endif; ?>

                            <?php if (!empty($m['release_year'])): ?>
                                <span class="badge-year"><?= htmlspecialchars($m['release_year']); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="movie-actions">
                            <?php if (isset($_SESSION['user_id'])): ?>

                                <?php if (in_array($m['movie_id'], $userWatchlistIds)): ?>
                                    <span class="card-btn">
                                        In Watchlist ✓
                                    </span>
                                <?php else: ?>
                                    <form method="post" action="add_to_watchlist.php">
                                        <input type="hidden" name="movie_id" value="<?= (int)$m['movie_id']; ?>">
                                        <button type="submit" class="card-btn">
                                            + Add to Watchlist
                                        </button>
                                    </form>
                                <?php endif; ?>

                            <?php else: ?>
                                <a href="login.php" class="card-btn">
                                    Login to add
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                </article>
            <?php endforeach; ?>
        </section>

    <?php endif; ?>

</main>

</body>
</html>
