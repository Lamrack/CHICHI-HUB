<?php
session_start();
require '../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Fetch user by email
    $stmt = $pdo->prepare("SELECT user_id, username, password_hash FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {

        // Generate a 6-digit login code
        $code = random_int(100000, 999999);
        $code_hash = password_hash((string)$code, PASSWORD_DEFAULT);

        // Code expires in 10 minutes
        $expires_at = (new DateTime('+10 minutes'))->format('Y-m-d H:i:s');

        // Store code in database
        $upd = $pdo->prepare("
            UPDATE users
            SET login_code_hash = ?, login_code_expires_at = ?
            WHERE user_id = ?
        ");
        $upd->execute([$code_hash, $expires_at, $user['user_id']]);

        // 🔧 DEV ONLY: store the plain code in session so we can show it on the verify page
        $_SESSION['last_login_code'] = $code;

        // Store pending login info
        $_SESSION['pending_user_id']  = $user['user_id'];
        $_SESSION['pending_username'] = $user['username'];
        $_SESSION['pending_email']    = $email;

        // (We are NOT using mail() for now since localhost is not configured)
        header('Location: verify_key.php');
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>
    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login - CHICHI HUB</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body class="auth-body">

    <div class="auth-card">

        <div class="logo-circle">
            <img src="../public/uploads/chichi_logo.jpg" alt="CHICHI HUB Logo">
        </div>

        <h1 class="auth-title">CHICHI <span class="hub">HUB</span></h1>
        <div class="subtitle">User Login</div>

        <?php if (!empty($error)): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Email</label>
                <input name="email" type="email" class="input" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input name="password" type="password" class="input" required>
            </div>

            <button class="btn-teal w-full">Log In</button>
        </form>

        <div class="text-center mt-2">
            <a href="register.php" class="btn-ghost">Create an Account</a>
        </div>
    </div>

</body>
</html>
