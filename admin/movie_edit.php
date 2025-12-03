<?php
// admin/movie_edit.php
session_start();
require '../config/db.php';
require_once '../src/movie_validation.php';
require_once 'admin_header.php';

// block non-admins
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin_login.php');
    exit;
}

// get movie id from query
$movie_id = $_GET['id'] ?? null;
if (!$movie_id || !ctype_digit($movie_id)) {
    die("Invalid movie ID.");
}

// fetch movie
$stmt = $pdo->prepare("SELECT * FROM movies WHERE movie_id = ?");
$stmt->execute([$movie_id]);
$movie = $stmt->fetch();

if (!$movie) {
    die("Movie not found.");
}

$error   = "";
$success = "";

// populate defaults from DB
$title        = $movie['title'] ?? "";
$genre        = $movie['genre'] ?? "";
$release_year = $movie['release_year'] ?? "";
$description  = $movie['description'] ?? "";
$poster_url   = $movie['poster_url'] ?? "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title        = trim($_POST['title'] ?? '');
    $genre        = trim($_POST['genre'] ?? '');
    $release_year = trim($_POST['release_year'] ?? '');
    $description  = trim($_POST['description'] ?? '');
    $poster_url   = trim($_POST['poster_url'] ?? '');

     // new streaming links
    $netflix_url  = $_POST['netflix_url'] ?? null;
    $prime_url    = $_POST['prime_url'] ?? null;
    $disney_url   = $_POST['disney_url'] ?? null;

    // 1. validate
    $error = validateMovie($title, $genre, $release_year, $description, $poster_url);

    if ($error === "") {
        $update = $pdo->prepare("
            UPDATE movies
            SET title = ?, genre = ?, description = ?, release_year = ?, poster_url = ?,netflix_url = ?, prime_url = ?,disney_url = ?
            WHERE movie_id = ?
        ");

        $ok = $update->execute([
        $title,
        $genre,
        $description,
        $release_year,
        $poster_url,
        $netflix_url,
        $prime_url,
        $disney_url,
        $movie_id
        ]);

        if ($ok) {
            $success = "Movie updated successfully.";
        } else {
            $error = "Database error: could not update movie. ";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Movie - CHICHI HUB Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>



<main class="page-shell">

    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">Edit Movie</h1>
            <p class="page-desc">Update the details for this movie.</p>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="success-box"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-group">
            <label class="field-label">Title</label>
            <input type="text" name="title" class="input-field"
                   value="<?php echo htmlspecialchars($title); ?>" required>
        </div>

        <div class="form-group">
            <label class="field-label">Genre</label>
            <input type="text" name="genre" class="input-field"
                   value="<?php echo htmlspecialchars($genre); ?>">
        </div>

        <div class="form-group">
            <label class="field-label">Release Year</label>
            <input type="text" name="release_year" class="input-field"
                   value="<?php echo htmlspecialchars($release_year); ?>">
                
        </div>
        <!-- NEW STREAMING LINKS -->
        <label>
            <div style="color:#9ca3af;font-size:.9rem;">Netflix URL</div>
            <input class="input" style="width:100%" name="netflix_url"
                   value="<?= htmlspecialchars($movie['netflix_url'] ?? '') ?>"
                   placeholder="https://www.netflix.com/...">
        </label>

        <label>
            <div style="color:#9ca3af;font-size:.9rem;">Amazon Prime URL</div>
            <input class="input" style="width:100%" name="prime_url"
                   value="<?= htmlspecialchars($movie['prime_url'] ?? '') ?>"
                   placeholder="https://www.primevideo.com/...">
        </label>

        <label>
            <div style="color:#9ca3af;font-size:.9rem;">Disney+ URL</div>
            <input class="input" style="width:100%" name="disney_url"
                   value="<?= htmlspecialchars($movie['disney_url'] ?? '') ?>"
                   placeholder="https://www.disneyplus.com/...">
        </label>

        <div class="form-group">
            <label class="field-label">Poster URL</label>
            <input type="text" name="poster_url" class="input-field"
                   value="<?php echo htmlspecialchars($poster_url); ?>">
        </div>

        <div class="form-group">
            <label class="field-label">Description</label>
            <textarea name="description" class="input-field" rows="4"><?php
                echo htmlspecialchars($description);
            ?></textarea>
        </div>

        <button type="submit" class="btn-primary">Save Changes</button>

    </form>

</main>

</body>
</html>
