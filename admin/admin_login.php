<?php
session_start();
require '../config/db.php';

$error = "";

/**
 * Middle-layer validation for admin login
 */
function validateAdminLogin(string $username, string $password): string
{
    $error = "";

    if (strlen($username) === 0) {
        $error .= "Username may not be blank. ";
    }
    if (strlen($username) > 100) {
        $error .= "Username must be less than 100 characters. ";
    }

    if (strlen($password) === 0) {
        $error .= "Password may not be blank. ";
    }
    if (strlen($password) > 255) {
        $error .= "Password must be less than 255 characters. ";
    }

    return $error;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // 1. Validate
    $error = validateAdminLogin($username, $password);

    if ($error === "") {
        // 2. Check DB for admin
        $stmt = $pdo->prepare("
            SELECT admin_id, username, password_hash
            FROM admins
            WHERE username = ?
        ");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id']      = $admin['admin_id'];
            $_SESSION['is_admin']      = true;
            $_SESSION['admin_username'] = $admin['username'];

            header('Location: admin_dashboard.php');
            exit;
        } else {
            $error = "Invalid admin username or password. ";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - CHICHI HUB</title>
    <link rel="stylesheet" href="../css/admin_login.css">
</head>
<body class="auth-body">

<div class="auth-card">

    <div class="logo-circle">
        <img src="../public/uploads/chichi_logo.jpg" alt="CHICHI HUB">
    </div>

    <h2 class="auth-title">CHICHI <span class="hub">HUB</span></h2>
    <p class="subtitle">Admin Panel Login</p>

    <?php if (!empty($error)): ?>
        <div class="error-msg">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Admin Username</label>
            <input type="text" name="username" class="input"
                   value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="input" required>
        </div>

        <button type="submit" class="btn-teal">Login</button>

        <div class="text-center mt-2">
            <a href="../index.php" class="btn-ghost">Back to site</a>
        </div>
    </form>

</div>

</body>
</html>
