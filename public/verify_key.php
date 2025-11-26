<?php
session_start();
require '../config/db.php';

$error = '';

// If user skipped step 1, send back to login
if (empty($_SESSION['pending_user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['pending_user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['login_code'] ?? '');

    // Fetch login_code_hash + expiry for this user
    $stmt = $pdo->prepare("
        SELECT user_id, username, login_code_hash, login_code_expires_at
        FROM users
        WHERE user_id = ?
    ");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || empty($user['login_code_hash']) || empty($user['login_code_expires_at'])) {
        $error = "No active login code. Please log in again.";
    } else {
        $now        = new DateTime();
        $expires_at = new DateTime($user['login_code_expires_at']);

        if ($now > $expires_at) {
            $error = "This code has expired. Please log in again.";
        } elseif (!password_verify($code, $user['login_code_hash'])) {
            $error = "Invalid code.";
        } else {
            // ✅ Step 2 passed – fully log in
            $_SESSION['user_id']  = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            // Clear pending data
            unset($_SESSION['pending_user_id'], $_SESSION['pending_username'], $_SESSION['pending_email']);

            // Clear code from DB (optional but recommended)
            $clear = $pdo->prepare("
                UPDATE users
                SET login_code_hash = NULL, login_code_expires_at = NULL
                WHERE user_id = ?
            ");
            $clear->execute([$user['user_id']]);

            // Clear dev code from session if you are using it
            unset($_SESSION['last_login_code']);

            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Two-Step Verification - CHICHI HUB</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body class="auth-body">

    <div class="auth-card">

        <div class="logo-circle">
            <img src="../public/uploads/chichi_logo.jpg" alt="CHICHI HUB Logo">
        </div>

        <h1 class="auth-title">CHICHI <span class="hub">HUB</span></h1>
        <div class="subtitle">Two-Step Verification</div>

        <p class="subtitle" style="font-size: 0.9rem;">
            Please enter the login code to complete login.
        </p>

        <?php if (!empty($error)): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['last_login_code'])): ?>
            <div class="success-msg">
                DEV CODE: <strong><?= htmlspecialchars($_SESSION['last_login_code']) ?></strong><br>
                (In production this would be sent by email.)
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Login Code</label>
                <input
                    type="text"
                    name="login_code"
                    class="input"
                    required
                    minlength="4"
                    maxlength="10"
                    placeholder="Enter the code"
                >
            </div>

            <button class="btn-teal w-full">Verify</button>
        </form>

        <div class="text-center mt-2">
            <a href="login.php" class="btn-ghost">Back to Login</a>
        </div>
    </div>

</body>
</html>
