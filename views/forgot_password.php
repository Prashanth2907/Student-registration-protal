<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg portal-navbar">
        <div class="container-fluid">
            <span class="navbar-brand me-4">Unified Login</span>
            <div class="collapse navbar-collapse show">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.php">Signup</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="auth-card">
            <h3 class="card-title-custom">Reset Password</h3>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="../actions/forgot_password.php" method="POST">
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Registered Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your registered email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">New Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter new password" required>
                </div>
                <div class="mb-4">
                    <label class="form-label text-secondary small fw-bold">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 mb-3">Reset Password</button>
                <div class="text-center">
                    <a href="login.php" class="text-decoration-none small text-primary">Back to Login</a>
                </div>
            </form>
        </div>
    </div>

    <footer class="portal-footer">
        Basic Coding Test - 1 | Unified Login | Task Document
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
