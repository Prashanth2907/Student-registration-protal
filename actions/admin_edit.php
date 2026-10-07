<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../views/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id'] ?? 0);
    $dob = $_POST['dob'] ?? null;
    $gender = $_POST['gender'] ?? '';
    $qualification = $_POST['qualification'] ?? '';
    $interests = isset($_POST['interests']) && is_array($_POST['interests']) ? implode(',', $_POST['interests']) : ($_POST['interests'] ?? '');
    $class = trim($_POST['class'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $marks = isset($_POST['marks']) && $_POST['marks'] !== '' ? intval($_POST['marks']) : null;

    if ($student_id <= 0) {
        $_SESSION['error'] = 'Invalid student ID.';
        header('Location: ../views/admin_dashboard.php');
        exit();
    }

    $unique_filename = null;
    if (isset($_FILES['aadhaar_file']) && $_FILES['aadhaar_file']['error'] === UPLOAD_ERR_OK) {
        $file_info = pathinfo($_FILES['aadhaar_file']['name']);
        $extension = strtolower($file_info['extension'] ?? '');

        if ($extension === 'pdf') {
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $unique_filename = 'aadhaar_' . time() . '_' . bin2hex(random_bytes(6)) . '.pdf';
            move_uploaded_file($_FILES['aadhaar_file']['tmp_name'], $upload_dir . $unique_filename);
        }
    }

    if ($unique_filename) {
        $stmt = $pdo->prepare('UPDATE users SET dob = ?, gender = ?, qualification = ?, interests = ?, class = ?, subject = ?, marks = ?, aadhaar_file = ? WHERE id = ? AND role = "student"');
        $stmt->execute([$dob, $gender, $qualification, $interests, $class, $subject, $marks, $unique_filename, $student_id]);
    } else {
        $stmt = $pdo->prepare('UPDATE users SET dob = ?, gender = ?, qualification = ?, interests = ?, class = ?, subject = ?, marks = ? WHERE id = ? AND role = "student"');
        $stmt->execute([$dob, $gender, $qualification, $interests, $class, $subject, $marks, $student_id]);
    }

    $_SESSION['success'] = 'Student record updated successfully.';
    header('Location: ../views/admin_dashboard.php');
    exit();
} else {
    header('Location: ../views/admin_dashboard.php');
    exit();
}
