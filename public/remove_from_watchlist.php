<?php
session_start();
require '../config/db.php';
require_once '../src/watchlist_validation.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = (string)$_SESSION['user_id'];

$movie_id = '';
if (isset($_POST['movie_id'])) {
    $movie_id = trim($_POST['movie_id']);
} 


$error = validateWatchlistAction($user_id, (string)$movie_id);

if ($error !== "") {
    echo "Error: " . htmlspecialchars($error);
    exit;
}

$stmt = $pdo->prepare("
    DELETE FROM watchlist
    WHERE user_id = ? AND movie_id = ?
");
$stmt->execute([$user_id, $movie_id]);

header('Location: dashboard.php');
exit;
