<?php
/* Practical 10: Secure Logout */
require_once 'db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    // Clear remember-me token in DB
    $pdo->prepare("UPDATE students SET remember_token = NULL WHERE student_id = :id")->execute(['id' => $_SESSION['user_id']]);
}

// Clear cookie
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, '/');
}

// Destroy session
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}
session_destroy();

header("Location: login.php?msg=logout");
exit;
