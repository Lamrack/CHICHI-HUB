<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="app-header">
    <div class="header-left">
        <!-- App Logo -->
        <img src="../public/uploads/chichi_logo.jpg" alt="CHICHI HUB Logo" class="brand-logo">
        <a href="index.php" class="brand-link">
            <div class="header-title">
                <div class="header-title-top">
                    CHICHI <span class="hub">HUB</span>
                </div>
                <link rel="stylesheet" href="../css/style.css">
            </div>
        </a>
    </div>    

    <div class="header-actions">
        <?php if (isset($_SESSION['user_id'])): ?>

            <!-- 🔹 Username shown when logged in -->
            <span class="btn-ghost">
                <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>
            </span>

            <a href="dashboard.php" class="btn-teal">My Watchlist</a>
            <a href="logout.php" class="btn-teal">Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn-teal">Login</a>
            <a href="register.php" class="btn-ghost">Register</a>
        <?php endif; ?>
    </div>
</header>
