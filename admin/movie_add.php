<?php
// admin/movie_add.php
session_start();
require '../config/db.php';
require_once '../src/movie_validation.php';
require_once 'admin_header.php'; 

// block non-admins
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin_login.php');
    exit;
}

$error   = "";
$success = "";

// defaults for sticky form
$title        = "";
$genre        = "";
$release_year = "";
$description  = "";
$poster_url   = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title        = trim($_POST['title'] ?? '');
    $genre        = trim($_POST['genre'] ?? '');
    $release_year = trim($_POST['release_year'] ?? '');
    $description  = trim($_POST['description'] ?? '');
    $poster_url   = trim($_POST['poster_url'] ?? ''); // if you store relative path manually

    //  platform links
    $netflix_url  = $_POST['netflix_url'] ?? null;
    $prime_url    = $_POST['prime_url'] ?? null;
    $disney_url   = $_POST['disney_url'] ?? null;

    // 1. middle-layer validation
    $error = validateMovie($title, $genre, $release_year, $description, $poster_url);

    if ($error === "") {

        $stmt = $pdo->prepare("
            INSERT INTO movies (title, genre, description, release_year, poster_url)
            VALUES (?, ?, ?, ?, ?)
        ");

        $ok = $stmt->execute([
            $title,
            $genre !== "" ? $genre : null,
            $description !== "" ? $description : null,
            $release_year !== "" ? $release_year : null,
            $poster_url !== "" ? $poster_url : null,
        ]);

        if ($ok) {
            $success = "Movie added successfully.";
            // clear form fields after success
            $title        = "";
            $genre        = "";
            $release_year = "";
            $description  = "";
            $poster_url   = "";
        } else {
            $error = "Database error: could not add movie. ";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Movie - CHICHI HUB Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>



<main class="page-shell">

    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">Add New Movie</h1>
            <p class="page-desc">Fill in the fields below to create a new movie entry.</p>
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
            <input type="text" style="width:100%" name="title" class="input-field"
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

        <div class="form-group">
            <label class="field-label">Poster URL (relative path e.g. uploads/inception.jpg)</label>
            <input type="text" name="poster_url" class="input-field"
                   value="<?php echo htmlspecialchars($poster_url); ?>">
        </div>

        <label>
            <div style="color:#9ca3af;font-size:.9rem;">Netflix URL</div>
            <input class="input" style="width:100%" name="netflix_url" placeholder="https://www.netflix.com/...">
        </label>

        <label>
            <div style="color:#9ca3af;font-size:.9rem;">Amazon Prime URL</div>
            <input class="input" style="width:100%" name="prime_url" placeholder="https://www.primevideo.com/...">
        </label>

        <label>
            <div style="color:#9ca3af;font-size:.9rem;">Disney+ URL</div>
            <input class="input" style="width:100%" name="disney_url" placeholder="https://www.disneyplus.com/...">
        </label>


        <div class="form-group">
            <label class="field-label">Description</label>
            <textarea name="description" class="input-field" rows="4"><?php
                echo htmlspecialchars($description);
            ?></textarea>
        </div>

        <button type="submit" class="btn-primary">Add Movie</button>

    </form>

</main>

</body>
</html>
