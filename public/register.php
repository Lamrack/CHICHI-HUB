<?php
session_start();
require '../config/db.php';
require '../src/validation.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';
    $favMovie = trim($_POST['favourite_movie'] ?? '');

    // --------------------------
    // VALIDATION START
    // --------------------------

    if ($username === '') {
        $error = "Username is required.";
    }
    elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters.";
    }
    elseif ($favMovie === '') {
        $error = "Please enter your favourite movie.";
    }
    elseif ($password !== $confirm) {
        $error = "Passwords don't match.";
    } 
    else {
        // email validation (shared)
        $msg = '';
        if (!validate_email($email, $msg)) {
            $error = $msg;
        }
        // password strength (shared)
        elseif (!validate_password_strength($password, $msg)) {
            $error = $msg;
        } else {
            // Check if email already exists
            $check = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
            $check->execute([$email]);
            $exists = $check->fetch();

            if ($exists) {
                $error = "That email is already registered.";
            } else {

                // Hash password 
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                $ins = $pdo->prepare("
                    INSERT INTO users (username, email, password_hash, favourite_movie)
                    VALUES (?, ?, ?, ?)
                ");
                $ok = $ins->execute([$username, $email, $password_hash, $favMovie]);

                if ($ok) {
                    $success = "Account created successfully! You can now log in.";
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account - CHICHI HUB</title>
    <link rel="stylesheet" href="../css/register.css">
</head>
<body class="auth-body">

<div class="auth-card">
    <div class="logo-circle">
        <img src="../public/uploads/chichi_logo.jpg" alt="CHICHI HUB Logo">
    </div>

    <h1 class="auth-title">CHICHI <span class="hub">HUB</span></h1>
    <div class="subtitle">Create an account</div>

    <?php if ($error): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success-msg"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" novalidate>

        <div class="form-group">
            <label>Username</label>
            <input
                name="username"
                type="text"
                class="input"
                required
                minlength="3"
                value="<?= isset($username) ? htmlspecialchars($username) : '' ?>"
            >
        </div>

        <div class="form-group">
            <label>Email</label>
            <input
                name="email"
                type="email"
                class="input"
                required
                pattern="^[A-Za-z][A-Za-z0-9._%+-]*@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$"
                title="Enter a valid email like example@domain.com"
                value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"
            >
        </div>

        <div class="form-group">
            <label>Password</label>
            <input
                name="password"
                id="password"
                type="password"
                class="input"
                required
                minlength="8"
                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}"
                title="At least 8 characters, with upper & lowercase letters, a number and a special character."
            >
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input
                name="confirm"
                id="confirm"
                type="password"
                class="input"
                required
            >
            <small id="confirmHint" class="hint"></small>
        </div>

        <div class="form-group">
            <label>Favourite Movie (for verification)</label>
            <input
                name="favourite_movie"
                type="text"
                class="input"
                required
                value="<?= isset($favMovie) ? htmlspecialchars($favMovie) : '' ?>"
            >
        </div>

        <button class="btn-teal w-full" type="submit">Sign Up</button>
    </form>

    <div class="text-center mt-2">
        <a class="btn-ghost" href="login.php">Already have an account? Log in</a>
    </div>
</div>
</body>
</html>
