<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';

// get id from url
$movie_id = $_GET['id'] ?? null;

if (!$movie_id) {
    die("Movie not found.");
}

// fetch that movie (now also fetching streaming links)
$stmt = $pdo->prepare("SELECT movie_id, title, genre, release_year, poster_url, description, netflix_url, prime_url, disney_url FROM movies WHERE movie_id = ?");
$stmt->execute([$movie_id]);
$movie = $stmt->fetch();

if (!$movie) {
    die("Movie not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($movie['title']) ?> · CHICHI HUB</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* in-case global css didn't load this page's layout */
        .movie-details-card {
            background: rgba(38,38,38,0.7);
            border: 1px solid rgba(255,255,255,0.03);
            border-radius: 18px;
            padding: 24px;
            display: flex;
            gap: 28px;
            align-items: flex-start;
            max-width: 1000px;
            margin: 20px auto;
        }
        .movie-details-left {
            flex: 0 0 280px;
        }
        .movie-details-left img {
            width: 100%;
            max-width: 280px;
            border-radius: 14px;
            object-fit: cover;
            display: block;
            background: #1d1d1d;
            box-shadow: 0 6px 20px rgba(0,0,0,.5);
        }
        .movie-details-right {
            flex: 1;
            min-width: 250px;
        }
        .platform-links {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .platform {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 600;
            color: #fff;
            text-decoration: none;
            box-shadow: 0 6px 16px rgba(0,0,0,0.35);
            transition: transform .15s ease, opacity .15s ease;
        }
        .platform:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .platform.netflix { background: #e50914; }
        .platform.prime { background: #00a8e1; }
        .platform.disney { background: #113ccf; }

        @media (max-width: 780px) {
            .movie-details-card {
                flex-direction: column;
                align-items: center;
            }
            .movie-details-left {
                width: 100%;
                text-align: center;
            }
            .movie-details-left img {
                margin: 0 auto;
                max-width: 80vw;
            }
            .platform-links {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<main class="page-shell">
    <section class="movie-details-card">
        <div class="movie-details-left">
            <?php if (!empty($movie['poster_url'])): ?>
                <img src="<?= htmlspecialchars($movie['poster_url']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
            <?php endif; ?>
        </div>

        <div class="movie-details-right">
            <h1><?= htmlspecialchars($movie['title']) ?></h1>
            <p>
                <?php if (!empty($movie['genre'])): ?>
                    <strong>Genre:</strong> <?= htmlspecialchars($movie['genre']) ?><br>
                <?php endif; ?>
                <?php if (!empty($movie['release_year'])): ?>
                    <strong>Year:</strong> <?= htmlspecialchars($movie['release_year']) ?><br>
                <?php endif; ?>
            </p>

            <h3>Description</h3>
            <p>
                <?= $movie['description'] ? nl2br(htmlspecialchars($movie['description'])) : 'No description available.' ?>
            </p>

            <?php if (!empty($movie['netflix_url']) || !empty($movie['prime_url']) || !empty($movie['disney_url'])): ?>
                <h3 style="margin-top:14px;">Available On</h3>
                <div class="platform-links">
                    <?php if (!empty($movie['netflix_url'])): ?>
                        <a href="<?= htmlspecialchars($movie['netflix_url']) ?>" target="_blank" class="platform netflix">🎞 Netflix</a>
                    <?php endif; ?>
                    <?php if (!empty($movie['prime_url'])): ?>
                        <a href="<?= htmlspecialchars($movie['prime_url']) ?>" target="_blank" class="platform prime">📺 Prime Video</a>
                    <?php endif; ?>
                    <?php if (!empty($movie['disney_url'])): ?>
                        <a href="<?= htmlspecialchars($movie['disney_url']) ?>" target="_blank" class="platform disney">🌟 Disney+</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <a href="index.php" class="btn-ghost" style="margin-top:18px;display:inline-block;">← Back to movies</a>
        </div>
    </section>
</main>

</body>
</html>
