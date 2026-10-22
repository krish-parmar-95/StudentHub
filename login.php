<?php
/* Practical 10: Secure Login/Logout, Sessions, RBAC, Timeout, Remember-Me */
require_once 'db.php';
session_start();

$timeout = 600; // 10 minutes session timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset(); session_destroy();
    header("Location: login.php?msg=timeout"); exit;
}
$_SESSION['last_activity'] = time();

// Auto-login via Remember-Me Cookie (Advanced)
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    $token_hash = hash('sha256', $_COOKIE['remember_me']);
    $stmt = $pdo->prepare("SELECT * FROM students WHERE remember_token = :token");
    $stmt->execute(['token' => $token_hash]);
    if ($u = $stmt->fetch()) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $u['student_id'];
        $_SESSION['name'] = $u['name'];
        $_SESSION['role'] = $u['role'];
        header("Location: " . ($u['role'] === 'admin' ? 'admin_dashboard.php' : 'student_dashboard.php')); exit;
    }
}

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : 'student_dashboard.php')); exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    $stmt = $pdo->prepare("SELECT * FROM students WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true); // Prevent session fixation
        $_SESSION['user_id'] = $user['student_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();

        // Update last-login timestamp (Intermediate)
        $pdo->prepare("UPDATE students SET last_login = NOW() WHERE student_id = :id")->execute(['id' => $user['student_id']]);

        // Remember-Me Token (Advanced)
        if ($remember) {
            $random_token = bin2hex(random_bytes(32));
            $token_hash = hash('sha256', $random_token);
            $pdo->prepare("UPDATE students SET remember_token = :token WHERE student_id = :id")->execute(['token' => $token_hash, 'id' => $user['student_id']]);
            setcookie('remember_me', $random_token, time() + (86400 * 30), "/", "", false, true); // 30 days HttpOnly
        }

        header("Location: " . ($user['role'] === 'admin' ? 'admin_dashboard.php' : 'student_dashboard.php')); exit;
    } else {
        $error = "❌ Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Practical 10 — Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header><h1>🎓 StudentHub</h1><nav><a href="login.php"><strong>Login</strong></a><a href="register.php">Register</a></nav></header>
<main class="container">
    <div class="form-card">
        <h2>🔑 Secure Login (Practical 10)</h2>
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'timeout'): ?><div class="msg-error">⚠️ Session timed out due to inactivity. Please log in again.</div><?php endif; ?>
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'unauthorized'): ?><div class="msg-error">🚫 Access Denied! Please log in first.</div><?php endif; ?>
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'logout'): ?><div class="msg-success">✅ Successfully logged out.</div><?php endif; ?>
        <?php if ($error): ?><div class="msg-error"><?= $error ?></div><?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="e.g. arjun@student.edu.in or admin@student.edu.in" required>
            </div>
            <div class="form-group">
                <label>Password (Default: <code>password</code>)</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="remember" style="width:auto;"> Remember Me (Secure Cookie)</label>
            </div>
            <button type="submit" class="btn btn-primary">Log In</button>
        </form>
        <p style="margin-top:15px;font-size:13px;color:#666;">
            <strong>Demo Accounts:</strong><br>
            Student: <code>arjun@student.edu.in</code> | Pass: <code>password</code><br>
            Admin: <code>admin@student.edu.in</code> | Pass: <code>password</code>
        </p>
    </div>
</main>
<footer><p>&copy; 2026 StudentHub. Practical 10.</p></footer>
</body>
</html>
