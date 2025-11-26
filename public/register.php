<?php
session_start();
require '../config/db.php';

$error = '';
$success = '';

// 🔒 Password strength check 
function validate_password_strength(string $password, string &$msg): bool {
    if (strlen($password) < 8) {
        $msg = "Password must be at least 8 characters long.";
        return false;
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $msg = "Password must contain at least one uppercase letter.";
        return false;
    }
    if (!preg_match('/[a-z]/', $password)) {
        $msg = "Password must contain at least one lowercase letter.";
        return false;
    }
    if (!preg_match('/[0-9]/', $password)) {
        $msg = "Password must contain at least one number.";
        return false;
    }
    if (!preg_match('/[\W_]/', $password)) {
        $msg = "Password must contain at least one special character.";
        return false;
    }
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    // --------------------------
    // VALIDATION START
    // --------------------------

    if ($password !== $confirm) {
        $error = "Passwords don't match.";
    } 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } 
   
elseif (!preg_match('/^[A-Za-z][A-Za-z0-9._%+-]*@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/', $email)) {
    $error = "Email must start with a letter and be in a valid format.";
}
elseif (!checkdnsrr(substr(strrchr($email, "@"), 1), "MX")) {
    $error = "Email domain is not valid. Please use a real email provider.";
}
else {


        // Password strength check
        $pwdMsg = '';
        if (!validate_password_strength($password, $pwdMsg)) {
            $error = $pwdMsg;
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
                    INSERT INTO users (email, password_hash)
                    VALUES (?, ?)
                ");
                $ok = $ins->execute([$email, $password_hash]);

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


        <button class="btn-teal w-full">Sign Up</button>
    </form>

    <div class="text-center mt-2">
        <a class="btn-ghost" href="login.php">Already have an account? Log in</a>
    </div>
</div>

