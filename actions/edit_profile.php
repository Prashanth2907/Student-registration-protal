<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../views/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = trim($_POST['name'] ?? '');
    $dob = $_POST['dob'] ?? null;
    $gender = $_POST['gender'] ?? '';
    $qualification = $_POST['qualification'] ?? '';
    $interests = isset($_POST['interests']) && is_array($_POST['interests']) ? implode(',', $_POST['interests']) : ($_POST['interests'] ?? '');
    $class = trim($_POST['class'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $marks = isset($_POST['marks']) && $_POST['marks'] !== '' ? intval($_POST['marks']) : null;

    if (empty($name)) {
        $_SESSION['error'] = 'Name cannot be empty.';
        header('Location: ../views/student_dashboard.php');
        exit();
    }

    $unique_filename = null;
    if (isset($_FILES['aadhaar_file']) && $_FILES['aadhaar_file']['error'] === UPLOAD_ERR_OK) {
        $file_info = pathinfo($_FILES['aadhaar_file']['name']);
        $extension = strtolower($file_info['extension'] ?? '');

        if ($extension !== 'pdf') {
            $_SESSION['error'] = 'Only PDF documents are allowed for Aadhaar upload.';
            header('Location: ../views/student_dashboard.php');
            exit();
        }

        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $unique_filename = 'aadhaar_' . time() . '_' . bin2hex(random_bytes(6)) . '.pdf';
        $target_path = $upload_dir . $unique_filename;

        if (!move_uploaded_file($_FILES['aadhaar_file']['tmp_name'], $target_path)) {
            $_SESSION['error'] = 'Failed to upload new document.';
            header('Location: ../views/student_dashboard.php');
            exit();
        }
    }

    if ($unique_filename) {
        $stmt = $pdo->prepare('UPDATE users SET name = ?, dob = ?, gender = ?, qualification = ?, interests = ?, class = ?, subject = ?, marks = ?, aadhaar_file = ? WHERE id = ? AND role = "student"');
        $stmt->execute([$name, $dob, $gender, $qualification, $interests, $class, $subject, $marks, $unique_filename, $user_id]);
    } else {
        $stmt = $pdo->prepare('UPDATE users SET name = ?, dob = ?, gender = ?, qualification = ?, interests = ?, class = ?, subject = ?, marks = ? WHERE id = ? AND role = "student"');
        $stmt->execute([$name, $dob, $gender, $qualification, $interests, $class, $subject, $marks, $user_id]);
    }

    $_SESSION['name'] = $name;
    $_SESSION['success'] = 'Profile updated successfully.';
    header('Location: ../views/student_dashboard.php');
    exit();
} else {
    header('Location: ../views/student_dashboard.php');
    exit();
}
