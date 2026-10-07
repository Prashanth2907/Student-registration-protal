<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = 'Please fill in all fields.';
        header('Location: ../views/forgot_password.php');
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = 'Passwords do not match.';
        header('Location: ../views/forgot_password.php');
        exit();
    }

    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['error'] = 'No account found with this email address.';
        header('Location: ../views/forgot_password.php');
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $update_stmt = $pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
    $update_stmt->execute([$hashed_password, $email]);

    $_SESSION['success'] = 'Password reset successfully. Please log in with your new password.';
    header('Location: ../views/login.php');
    exit();
} else {
    header('Location: ../views/forgot_password.php');
    exit();
}
