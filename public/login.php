<?php
session_start();
require '../config/db.php';
require '../src/Auth.php';


$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $auth   = new Auth($pdo);
    $result = $auth->attemptLogin($email, $password);

    if ($result['success']) {
        // Password is correct – go to step 2 (favourite movie)
        $_SESSION['pending_user_id'] = $result['user_id'];
        header('Location: verify_movie.php');
        exit;
    } else {
        $error = $result['message'];
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - CHICHI HUB</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body class="auth-body">

<div class="auth-card">
    <div class="logo-circle">
        <img src="../public/uploads/chichi_logo.jpg" alt="CHICHI HUB Logo">
    </div>

    <h1 class="auth-title">CHICHI <span class="hub">HUB</span></h1>
    <div class="subtitle">Log in to your account</div>

    <?php if ($error): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
        <div class="form-group">
            <label>Email</label>
            <input
                name="email"
                type="email"
                class="input"
                required
                value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"
            >
        </div>

        <div class="form-group">
            <label>Password</label>
            <input
                name="password"
                type="password"
                class="input"
                required
            >
        </div>

        <button class="btn-teal w-full" type="submit">Log In</button>
    </form>

    <div class="text-center mt-2">
        <a class="btn-ghost" href="register.php">Don't have an account? Sign up</a>
    </div>
</div>

</body>
</html>
