<?php
/* Practical 9: Secure Registration with DB Insert, Duplicate Check, Password Hashing */
require_once 'db.php';
session_start();
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$errors = []; $success = ''; $old = ['name'=>'','email'=>'','phone'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Invalid CSRF token.";
    }

    $name  = trim(htmlspecialchars($_POST['name'] ?? ''));
    $email = trim(htmlspecialchars($_POST['email'] ?? ''));
    $phone = trim(htmlspecialchars($_POST['phone'] ?? ''));
    $pass  = $_POST['password'] ?? '';
    $cpass = $_POST['confirm_password'] ?? '';
    $dept  = intval($_POST['dept_id'] ?? 0);
    $old = compact('name','email','phone');

    // Validation
    if (empty($name) || !preg_match('/^[a-zA-Z\s]{2,50}$/', $name)) $errors['name'] = "Valid name required (2-50 letters).";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Valid email required.";
    if (empty($phone) || !preg_match('/^[0-9]{10}$/', $phone)) $errors['phone'] = "10-digit phone required.";
    if (empty($pass) || strlen($pass) < 8) $errors['password'] = "Password must be 8+ characters.";
    if ($pass !== $cpass) $errors['confirm_password'] = "Passwords do not match.";
    if ($dept < 1 || $dept > 5) $errors['dept_id'] = "Select a department.";

    // Duplicate email check (Prepared Statement)
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT student_id FROM students WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) $errors['email'] = "This email is already registered!";
    }

    // Insert into DB
    if (empty($errors)) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO students (name, email, phone, password_hash, dept_id) VALUES (:name, :email, :phone, :hash, :dept)");
        $stmt->execute(['name'=>$name, 'email'=>$email, 'phone'=>$phone, 'hash'=>$hash, 'dept'=>$dept]);

        // Audit log (Advanced)
        $pdo->prepare("INSERT INTO audit_log (action, email, ip_address) VALUES ('REGISTER', :email, :ip)")
            ->execute(['email'=>$email, 'ip'=>$_SERVER['REMOTE_ADDR']]);

        $success = "✅ Registration successful! Welcome, $name!";
        $old = ['name'=>'','email'=>'','phone'=>''];
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

// Get departments
$depts = $pdo->query("SELECT * FROM departments ORDER BY dept_name")->fetchAll();
// Get recent registrations
$recent = $pdo->query("SELECT s.name, s.email, d.dept_name, s.created_at FROM students s JOIN departments d ON s.dept_id = d.dept_id ORDER BY s.created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 9 — Secure Registration | StudentHub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <h1>🎓 StudentHub</h1>
    <nav>
        <a href="index.php">Form P7</a>
        <a href="test_connection.php">DB Test</a>
        <a href="register.php"><strong>Secure Register</strong></a>
    </nav>
</header>
<main class="container">
    <div class="form-card">
        <h2>🔐 Secure Student Registration</h2>
        <p style="margin-bottom:15px;color:#666;">Practical 9: Database Insert, Duplicate Email Check, Password Hashing</p>

        <?php if ($success): ?><div class="msg-success"><?= $success ?></div><?php endif; ?>
        <?php if (isset($errors[0])): ?><div class="msg-error"><?= $errors[0] ?></div><?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" value="<?= $old['name'] ?>" required>
                <?php if (isset($errors['name'])): ?><p class="field-error"><?= $errors['name'] ?></p><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" value="<?= $old['email'] ?>" required>
                <?php if (isset($errors['email'])): ?><p class="field-error"><?= $errors['email'] ?></p><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number *</label>
                <input type="tel" id="phone" name="phone" value="<?= $old['phone'] ?>" required>
                <?php if (isset($errors['phone'])): ?><p class="field-error"><?= $errors['phone'] ?></p><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="dept_id">Department *</label>
                <select id="dept_id" name="dept_id" required>
                    <option value="">-- Select --</option>
                    <?php foreach ($depts as $d): ?>
                    <option value="<?= $d['dept_id'] ?>"><?= htmlspecialchars($d['dept_name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['dept_id'])): ?><p class="field-error"><?= $errors['dept_id'] ?></p><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Password * (min 8 chars)</label>
                <input type="password" id="password" name="password" required>
                <?php if (isset($errors['password'])): ?><p class="field-error"><?= $errors['password'] ?></p><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <?php if (isset($errors['confirm_password'])): ?><p class="field-error"><?= $errors['confirm_password'] ?></p><?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Register Securely</button>
        </form>
    </div>

    <?php if (!empty($recent)): ?>
    <div class="form-card">
        <h2>👥 Recent Registrations</h2>
        <table>
            <thead><tr><th>Name</th><th>Email</th><th>Department</th><th>Date</th></tr></thead>
            <tbody>
                <?php foreach ($recent as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['name']) ?></td>
                    <td><?= htmlspecialchars($r['email']) ?></td>
                    <td><?= htmlspecialchars($r['dept_name']) ?></td>
                    <td><?= $r['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</main>
<footer><p>&copy; 2026 StudentHub. Practical 9.</p></footer>
</body>
</html>
