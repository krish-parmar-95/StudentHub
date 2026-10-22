<?php
/* Practical 10: Student Dashboard (Protected RBAC) */
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php?msg=unauthorized"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Student Dashboard</title><link rel="stylesheet" href="css/style.css"></head>
<body>
<header>
    <h1>🎓 StudentHub</h1>
    <nav><span>Logged in as: <strong><?= htmlspecialchars($_SESSION['name']) ?> (<?= $_SESSION['role'] ?>)</strong></span> <a href="logout.php" class="btn btn-danger" style="color:white;padding:5px 12px;margin-left:15px;">Logout</a></nav>
</header>
<main class="container">
    <div class="form-card">
        <h2>👨‍🎓 Student Dashboard</h2>
        <div class="msg-success">✅ Welcome, <?= htmlspecialchars($_SESSION['name']) ?>! Session ID: <code><?= session_id() ?></code></div>
        <p>You have accessed the student portal with role: <strong><?= $_SESSION['role'] ?></strong>.</p>
        <p style="margin-top:10px;"><a href="logout.php" class="btn btn-danger">Log Out</a></p>
    </div>
</main>
<footer><p>&copy; 2026 StudentHub. Practical 10.</p></footer>
</body>
</html>
