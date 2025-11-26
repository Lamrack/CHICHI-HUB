<?php
session_start();
require '../config/db.php';
require_once '../src/UserRegister.php';

// Block non-admins (same logic as other admin pages)
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: admin_login.php');
    exit;
}

$register = new UserRegister($pdo);
$successMessage = "";
$errorMessage   = "";

// Handle "Add user" form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    // Re-use existing registration logic/validation
    $result = $register->register($email, $password, $confirm);

    if ($result['success']) {
        $successMessage = $result['message'];
    } else {
        $errorMessage = $result['message'];
    }
}

// Handle delete user (GET ?delete=ID)
if (isset($_GET['delete'])) {
    $userId = $_GET['delete'];

    if (ctype_digit($userId)) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $successMessage = "User deleted successfully.";
    } else {
        $errorMessage = "Invalid user id.";
    }
}

// Load user list
$stmt = $pdo->query("SELECT user_id, email FROM users ORDER BY user_id ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin · Manage Users · CHICHI HUB</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-main {
            padding: 24px;
            max-width: 1100px;
            margin: 0 auto;
            color: #e5e7eb;
        }

        .admin-page-title {
            font-size: 1.6rem;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .flex-row {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
        }

        .card {
            background: rgba(31, 41, 55, 0.9);
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(75, 85, 99, 0.7);
            flex: 1;
            min-width: 280px;
        }

        .card h2 {
            margin-top: 0;
            font-size: 1.2rem;
            margin-bottom: 12px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }

        .input {
            width: 100%;
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #4b5563;
            background: #111827;
            color: #e5e7eb;
        }

        .btn-teal {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-danger {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            background-color: #b91c1c;
            color: #fee2e2;
            font-size: 0.8rem;
        }

        .btn-danger:hover {
            background-color: #ef4444;
        }

        table.user-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        table.user-table th,
        table.user-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #374151;
            text-align: left;
        }

        table.user-table th {
            font-weight: 500;
            color: #9ca3af;
        }

        .alert {
            margin-bottom: 12px;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid #10b981;
            color: #a7f3d0;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid #ef4444;
            color: #fecaca;
        }
    </style>
</head>
<body>

<?php require '../admin/admin_header.php'; ?>

<main class="admin-main">
    <h1 class="admin-page-title">Manage Users</h1>

    <?php if ($successMessage): ?>
        <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div class="alert alert-error"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <div class="flex-row">

        <!-- Add user form -->
        <section class="card">
            <h2>Add New User</h2>
            <form method="post" action="manage_users.php">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="input" type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input class="input" type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm">Confirm Password</label>
                    <input class="input" type="password" id="confirm" name="confirm" required>
                </div>

                <button type="submit" class="btn-teal">Create User</button>
            </form>
        </section>

        <!-- User list -->
        <section class="card">
            <h2>Existing Users</h2>

            <?php if (count($users) === 0): ?>
                <p style="font-size:.9rem;color:#9ca3af;">No users found.</p>
            <?php else: ?>
                <table class="user-table">
                    <thead>
                        <tr>
                            <th style="width:60px;">ID</th>
                            <th>Email</th>
                            <th style="width:80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?= (int)$u['user_id'] ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td>
                                    <a class="btn-danger"
                                       href="manage_users.php?delete=<?= (int)$u['user_id'] ?>"
                                       onclick="return confirm('Delete this user?');">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>

    </div>
</main>

</body>
</html>
