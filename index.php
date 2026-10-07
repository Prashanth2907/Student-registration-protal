<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: views/admin_dashboard.php');
        exit();
    } else {
        header('Location: views/student_dashboard.php');
        exit();
    }
}

header('Location: views/login.php');
exit();
