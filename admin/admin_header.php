<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only allow admins
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit;
}
?>

<header class="admin-header">
    <div class="admin-header-left">
        <div class="admin-logo-circle">
            <img src="../public/uploads/chichi_logo.jpg" alt="CHICHI HUB" class="admin-logo">

        </div>
        
        <div class="admin-title">
            <div class="admin-title-top">CHICHI <span class="hub">HUB</span></div>
            <div class="admin-subtitle">Admin Dashboard</div>

        </div>
    </div>

    <div class="admin-header-right">
        <a href="admin_dashboard.php" class="btn-teal">Home</a>
        <a href="movie_add.php" class="btn-teal">+ Add Movie</a>
        <a href="admin_logout.php" class="btn-teal">Logout</a>
        <a href="manage_users.php" class="btn-teal">Manage Users</a>
        

        
    </div>
</header>

<style>
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: rgba(38,38,38,0.7);
        backdrop-filter: blur(8px);
        padding: 16px 24px;
        border-bottom: 1px solid rgba(255,255,255,0.07);
        box-shadow:
            0 10px 30px rgba(0,0,0,0.8),
            0 0 16px rgba(0,163,163,0.2);
    }

    .admin-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .admin-logo-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        overflow: hidden;
        background-color: #2f2f2f;
        border: 2px solid var(--accent-teal);
        box-shadow: 0 0 10px rgba(0,163,163,.6);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .admin-logo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .admin-title {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .admin-title-top {
        font-size: 1rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-light);
        letter-spacing: -0.03em;
    }

    .admin-title-top .hub {
        color: var(--accent-orange);
        text-shadow: 0 2px 6px rgba(245,124,32,.5);
    }

    .admin-subtitle {
        font-size: .75rem;
        color: var(--text-dim);
        font-weight: 400;
    }

    .admin-header-right {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .logged-as {
        font-size: .8rem;
        color: var(--text-dim);
        margin-right: 8px;
    }
</style>
