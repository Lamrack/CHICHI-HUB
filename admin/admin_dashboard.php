<?php
session_start();
require '../config/db.php';
require_once '../admin/admin_header.php';

// block non-admins
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin_login.php');
    exit;
}

// fetch movies
$stmt = $pdo->query("SELECT movie_id, title, genre, release_year, poster_url FROM movies ORDER BY movie_id ASC");
$movies = $stmt->fetchAll();

/**
 * Build the poster URL for the browser and check if it exists on disk.
 * We assume:
 *   - admin_dashboard.php is in /admin/
 *   - poster files are in /admin/uploads/
 *   - DB stores paths like "uploads/inception.jpg"
 */
function buildPosterInfo($posterUrl) {
    // no poster in DB at all
    if (empty($posterUrl)) {
        return [false, null];
    }

    // file path on disk relative to THIS script
    $diskPath = __DIR__ . '/' . $posterUrl; // e.g. /var/www/.../admin/uploads/inception.jpg

    // browser path for <img src=""> when viewing /admin/admin_dashboard.php
    // since admin_dashboard.php is already in /admin, "uploads/..." is correct in the HTML
    $browserPath = $posterUrl; // e.g. "uploads/inception.jpg"

    if (file_exists($diskPath)) {
        return [true, $browserPath];
    } else {
        return [false, null];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin · CHICHI HUB</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* admin movie table images */
.poster-cell {
    display: flex;
    align-items: center;
    justify-content: center;
}

.poster-thumb {
    width: 70px;           /* adjust size here */
    height: 100px;
    object-fit: cover;     /* crop to fit nicely */
    border-radius: 6px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.25);
    background: #111827;
}

    </style>

    
</head>
<body>

    <!-- Main content -->
    <main class="page-shell">

        <section class="movies-card">
            <div class="card-header">
                <div class="card-header-left">
                    <p class="card-title">Movies Library</p>
                    <p class="card-subtitle">
                        All movies in your database (<?= count($movies) ?> total)
                    </p>
                </div>

                <div class="card-header-right">
                    <!-- future search/filter etc -->
                </div>
            </div>

            <div class="movie-table-wrapper">
                <table class="movie-table">
                    <thead>
                        <tr>
                            <th style="width:60px;">ID</th>
                            <th style="width:120px;">Poster</th>
                            <th>Title</th>
                            <th>Genre</th>
                            <th style="width:80px;">Year</th>
                            <th style="width:160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($movies) > 0): ?>
                            <?php foreach ($movies as $row): ?>
                                <?php
                                    list($hasPoster, $posterSrc) = buildPosterInfo($row['poster_url']);
                                ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($row['movie_id']) ?></td>

                                    <td>
                                        <div class="poster-cell">
                                            <?php if ($hasPoster): ?>
                                                <img
                                                    src="<?= htmlspecialchars($posterSrc) ?>"
                                                    alt="<?= htmlspecialchars($row['title']) ?> poster"
                                                    class="poster-thumb">
                                            <?php else: ?>
                                                <div>
                                                    No<br>img
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <td><?= htmlspecialchars($row['title']) ?></td>
                                    <td><?= htmlspecialchars($row['genre']) ?></td>
                                    <td><?= htmlspecialchars($row['release_year']) ?></td>


                                    <td>
                                        <div class="row-actions">
                                            <a class="btn-teal" href="movie_edit.php?id=<?= $row['movie_id'] ?>">Edit</a>

                                            <a class="btn-teal"
                                               href="movie_delete.php?id=<?= $row['movie_id'] ?>"
                                               onclick="return confirm('Delete this movie?');">
                                               Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align:center;color:#6b7280;padding:40px 16px;font-size:.9rem;">
                                    No movies yet. Click “Add Movie” to create your first one ✨
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <footer class="admin-footer">
        <div>CHICHI HUB <span class="brand">Admin</span> · Movie Watchlist Dashboard</div>
    </footer>

</body>
</html>
