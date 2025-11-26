<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin_login.php');
    exit;
}

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("DELETE FROM movies WHERE movie_id = ?");
$stmt->execute([$id]);

header("Location: admin_dashboard.php");
exit;
?>
