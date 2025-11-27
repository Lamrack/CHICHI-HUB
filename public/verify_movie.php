<?php
session_start();
require '../config/db.php';

// If user didn't pass step 1, send them back
if (!isset($_SESSION['pending_user_id'])) {
    header('Location: login.php');
    exit;
}

$error   = '';
$user_id = $_SESSION['pending_user_id']; // ✅ get it from session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputMovie = trim($_POST['favourite_movie'] ?? '');

    if ($inputMovie === '') {
        $error = "Please enter your favourite movie.";
    } else {
        // ✅ use correct column names: user_id, username, favourite_movie
        $stmt = $pdo->prepare("
            SELECT user_id, username, favourite_movie 
            FROM users 
            WHERE user_id = ?
        ");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $savedMovie = trim($user['favourite_movie']);

            // Case-insensitive compare
            if (strcasecmp($savedMovie, $inputMovie) === 0) {
                // ✅ 2nd step OK – now fully log in
                $_SESSION['user_id']  = $user['user_id'];
                $_SESSION['username'] = $user['username'];

                unset($_SESSION['pending_user_id']); // remove temp

                header('Location: dashboard.php'); // change path if needed
                exit;
            } else {
                $error = "Favourite movie does not match.";
            }
        } else {
            // Safety: no such user, restart login
            unset($_SESSION['pending_user_id']);
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Movie - CHICHI HUB</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body class="auth-body">

<div class="auth-card">
    <div class="logo-circle">
        <img src="../public/uploads/chichi_logo.jpg" alt="CHICHI HUB Logo">
    </div>

    <h1 class="auth-title">CHICHI <span class="hub">HUB</span></h1>
    <div class="subtitle">2-Step Verification</div>


    <?php if ($error): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
        <div class="form-group">
            <label>Favourite Movie</label>
            <input
                name="favourite_movie"
                type="text"
                class="input"
                required
            >
        </div>

        <button class="btn-teal w-full" type="submit">Verify</button>
    </form>

    <div class="text-center mt-2">
        <a class="btn-ghost" href="login.php">Back to login</a>
    </div>
</div>

</body>
</html>
