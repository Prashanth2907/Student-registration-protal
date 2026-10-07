<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../views/login.php');
    exit();
}

$student_id = intval($_GET['id'] ?? $_POST['id'] ?? 0);

if ($student_id > 0) {
    $stmt = $pdo->prepare('SELECT aadhaar_file FROM users WHERE id = ? AND role = "student"');
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();

    if ($student) {
        if (!empty($student['aadhaar_file'])) {
            $file_path = '../uploads/' . $student['aadhaar_file'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $delete_stmt = $pdo->prepare('DELETE FROM users WHERE id = ? AND role = "student"');
        $delete_stmt->execute([$student_id]);

        $_SESSION['success'] = 'Student record deleted successfully.';
    } else {
        $_SESSION['error'] = 'Student record not found.';
    }
} else {
    $_SESSION['error'] = 'Invalid request.';
}

header('Location: ../views/admin_dashboard.php');
exit();
