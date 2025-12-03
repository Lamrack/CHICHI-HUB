<?php
session_start();
require '../config/db.php';
require_once '../src/watchlist_validation.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = (string)$_SESSION['user_id'];

// Accept movie_id OR id for safety
$user_id = (string)$_SESSION['user_id'];

$movie_id = '';
if (isset($_POST['movie_id'])) {
    $movie_id = trim($_POST['movie_id']);
} elseif (isset($_GET['id'])) {             
    $movie_id = trim($_GET['id']);
}


$error = validateWatchlistAction($user_id, (string)$movie_id);

if ($error !== "") {
    echo "Error: " . htmlspecialchars($error);
    exit;
}

// Check that the movie exists
$checkMovie = $pdo->prepare("SELECT movie_id FROM movies WHERE movie_id = ?");
$checkMovie->execute([$movie_id]);
$exists = $checkMovie->fetch();

if (!$exists) {
    echo "Error: Movie does not exist.";
    exit;
}

// Insert into watchlist (handle duplicates via unique constraint)
try {
    $stmt = $pdo->prepare("
        INSERT INTO watchlist (user_id, movie_id)
        VALUES (?, ?)
    ");
    $stmt->execute([$user_id, $movie_id]);
} catch (PDOException $e) {
    // if duplicate, ignore and continue
}

header('Location: index.php');
exit;
