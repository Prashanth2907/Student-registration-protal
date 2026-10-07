<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $dob = $_POST['dob'] ?? null;
    $gender = $_POST['gender'] ?? '';
    $qualification = $_POST['qualification'] ?? '';
    $interests = isset($_POST['interests']) && is_array($_POST['interests']) ? implode(',', $_POST['interests']) : ($_POST['interests'] ?? '');
    $class = trim($_POST['class'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $marks = isset($_POST['marks']) && $_POST['marks'] !== '' ? intval($_POST['marks']) : null;

    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] = 'Please fill in all required fields.';
        header('Location: ../views/signup.php');
        exit();
    }

    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = 'This email is already registered.';
        header('Location: ../views/signup.php');
        exit();
    }

    if (!isset($_FILES['aadhaar_file']) || $_FILES['aadhaar_file']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'Please upload your Aadhaar document.';
        header('Location: ../views/signup.php');
        exit();
    }

    $file_info = pathinfo($_FILES['aadhaar_file']['name']);
    $extension = strtolower($file_info['extension'] ?? '');

    if ($extension !== 'pdf') {
        $_SESSION['error'] = 'Only PDF documents are allowed.';
        header('Location: ../views/signup.php');
        exit();
    }

    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $unique_filename = 'aadhaar_' . time() . '_' . bin2hex(random_bytes(6)) . '.pdf';
    $target_path = $upload_dir . $unique_filename;

    if (!move_uploaded_file($_FILES['aadhaar_file']['tmp_name'], $target_path)) {
        $_SESSION['error'] = 'Failed to upload document. Please try again.';
        header('Location: ../views/signup.php');
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $stmt_count = $pdo->query('SELECT COUNT(*) FROM users WHERE role = "student"');
    $count = (int)$stmt_count->fetchColumn() + 1;
    $user_code = 'STU' . str_pad($count, 3, '0', STR_PAD_LEFT);

    $insert_stmt = $pdo->prepare('INSERT INTO users (user_code, name, email, password, role, dob, gender, qualification, interests, class, subject, marks, aadhaar_file) VALUES (?, ?, ?, ?, "student", ?, ?, ?, ?, ?, ?, ?, ?)');
    $insert_stmt->execute([
        $user_code,
        $name,
        $email,
        $hashed_password,
        $dob,
        $gender,
        $qualification,
        $interests,
        $class,
        $subject,
        $marks,
        $unique_filename
    ]);

    $new_user_id = $pdo->lastInsertId();

    $_SESSION['user_id'] = $new_user_id;
    $_SESSION['user_code'] = $user_code;
    $_SESSION['role'] = 'student';
    $_SESSION['name'] = $name;

    header('Location: ../views/student_dashboard.php');
    exit();
} else {
    header('Location: ../views/signup.php');
    exit();
}
