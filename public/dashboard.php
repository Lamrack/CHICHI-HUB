<?php
require_once '../config/db.php';
require_once '../includes/header.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

//  saved movies will come.
$stmt = $pdo->prepare("
    SELECT m.movie_id, m.title, m.genre, m.release_year, m.poster_url ,m.netflix_url,
        m.prime_url,
        m.disney_url
    FROM watchlist w
    JOIN movies m ON w.movie_id = m.movie_id
    WHERE w.user_id = ?
");
$stmt->execute([$user_id]);
$watchlist = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Movies</title>
       <link rel="stylesheet" href="../css/style1.css">

</head>
<body>
<div class="watchlist-wrapper">

    <h2 class="watchlist-title">My Watchlist</h2>

    <?php if (!$watchlist): ?>
        <p style="color:#bbb;">No movies in your watchlist yet.</p>

    <?php else: ?>
    <div class="watchlist-grid">

        <?php foreach ($watchlist as $m): ?>
        <div class="watchlist-card">

            <div class="watchlist-poster">
                <?php if (!empty($m['poster_url'])): ?>
                    <img src="<?php echo htmlspecialchars($m['poster_url']); ?>"

                        alt="<?php echo htmlspecialchars($m['title']); ?>">
                <?php endif; ?>
                
            </div>

            <h3><?php echo htmlspecialchars($m['title']); ?></h3>

            <div class="watchlist-meta">
                <?php echo htmlspecialchars($m['genre']); ?> • 
                <?php echo htmlspecialchars($m['release_year']); ?>
            </div>

            <form method="post" action="remove_from_watchlist.php">
                <input type="hidden" name="movie_id" value="<?php echo $m['movie_id']; ?>">
                <button type="submit" class="remove-btn">Remove</button>
               <?php
$watch_link = $m['netflix_url']
           ?: $m['prime_url']
           ?: $m['disney_url']
           ?: '';
?>
<?php if (!empty($watch_link)): ?>
    <a href="<?php echo htmlspecialchars($watch_link); ?>" 
       class="watch-btn" 
       target="_blank" 
>       Watch Now
    </a>
<?php endif; ?>



           
            </form>

        </div>
        <?php endforeach; ?>

    </div>
    <?php endif; ?>

    <a href="index.php" class="back-home">← Back to Home</a>

</div>

</body>
</html>
