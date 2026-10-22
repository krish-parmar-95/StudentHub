<?php
/* Practical 11: Student Management CRUD with Search, Filter, Pagination, CSV Export & Soft Delete */
require_once 'db.php';
session_start();

$msg = ''; $err = '';

// Intermediate: Export to CSV
if (isset($_GET['action']) && $_GET['action'] === 'export_csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="students_' . date('Ymd_His') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ID', 'Name', 'Email', 'Phone', 'Department', 'Semester', 'CGPA', 'Status']);
    $rows = $pdo->query("SELECT s.student_id, s.name, s.email, s.phone, d.dept_name, s.semester, s.cgpa, s.status FROM students s JOIN departments d ON s.dept_id = d.dept_id ORDER BY s.student_id ASC")->fetchAll();
    foreach ($rows as $r) { fputcsv($out, $r); }
    fclose($out); exit;
}

// Advanced: Soft Delete / Restore
if (isset($_GET['action']) && $_GET['action'] === 'toggle_status' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $current = $pdo->prepare("SELECT status FROM students WHERE student_id = ?");
    $current->execute([$id]);
    $st = $current->fetchColumn();
    $new_st = ($st === 'active') ? 'inactive' : 'active';
    $pdo->prepare("UPDATE students SET status = ? WHERE student_id = ?")->execute([$new_st, $id]);
    header("Location: students_crud.php?msg=" . urlencode("Student status updated to $new_st (Soft Delete handled)!")); exit;
}

// CREATE / UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = intval($_POST['student_id'] ?? 0);
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $dept  = intval($_POST['dept_id'] ?? 1);
    $sem   = intval($_POST['semester'] ?? 1);
    $cgpa  = floatval($_POST['cgpa'] ?? 0.0);

    if (empty($name) || empty($email)) {
        $err = "Name and Email are required!";
    } else {
        if ($id > 0) {
            // UPDATE
            $stmt = $pdo->prepare("UPDATE students SET name=?, email=?, phone=?, dept_id=?, semester=?, cgpa=? WHERE student_id=?");
            $stmt->execute([$name, $email, $phone, $dept, $sem, $cgpa, $id]);
            $msg = "✅ Student (ID: $id) updated successfully!";
        } else {
            // INSERT
            $hash = password_hash('password', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO students (name, email, phone, password_hash, dept_id, semester, cgpa, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'active')");
            $stmt->execute([$name, $email, $phone, $hash, $dept, $sem, $cgpa]);
            $msg = "✅ New student added successfully!";
        }
    }
}

// Edit Fetch
$edit_data = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([intval($_GET['id'])]);
    $edit_data = $stmt->fetch();
}

// Search, Filter & Pagination
$search  = trim($_GET['search'] ?? '');
$filter  = intval($_GET['dept_filter'] ?? 0);
$status_f = $_GET['status_filter'] ?? '';

$where = ["1=1"]; $params = [];
if ($search !== '') {
    $where[] = "(s.name LIKE ? OR s.email LIKE ?)";
    $params[] = "%$search%"; $params[] = "%$search%";
}
if ($filter > 0) {
    $where[] = "s.dept_id = ?";
    $params[] = $filter;
}
if ($status_f !== '') {
    $where[] = "s.status = ?";
    $params[] = $status_f;
}
$where_sql = implode(' AND ', $where);

// Pagination
$per_page = 7;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $per_page;

$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM students s WHERE $where_sql");
$count_stmt->execute($params);
$total_rows = $count_stmt->fetchColumn();
$total_pages = ceil($total_rows / $per_page);

$query = "SELECT s.*, d.dept_name FROM students s JOIN departments d ON s.dept_id = d.dept_id WHERE $where_sql ORDER BY s.student_id DESC LIMIT $per_page OFFSET $offset";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$students = $stmt->fetchAll();

$depts = $pdo->query("SELECT * FROM departments ORDER BY dept_name")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Practical 11 — Student Management CRUD</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .badge-active { background:#27ae60; color:#fff; padding:3px 8px; border-radius:4px; font-size:12px; }
        .badge-inactive { background:#e74c3c; color:#fff; padding:3px 8px; border-radius:4px; font-size:12px; }
        .flex-bar { display:flex; gap:10px; flex-wrap:wrap; align-items:center; margin-bottom:15px; }
        .btn-sm { padding:4px 8px; font-size:12px; text-decoration:none; }
    </style>
</head>
<body>
<header>
    <h1>🎓 StudentHub Admin</h1>
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="students_crud.php"><strong>Student CRUD</strong></a>
        <a href="logout.php">Logout</a>
    </nav>
</header>
<main class="container" style="max-width:1000px;">

    <?php if ($msg || isset($_GET['msg'])): ?>
        <div class="msg-success"><?= htmlspecialchars($msg ?: $_GET['msg']) ?></div>
    <?php endif; ?>
    <?php if ($err): ?><div class="msg-error"><?= htmlspecialchars($err) ?></div><?php endif; ?>

    <!-- ADD / EDIT FORM -->
    <div class="form-card">
        <h2><?= $edit_data ? '✏️ Edit Student (ID: '.$edit_data['student_id'].')' : '➕ Add New Student' ?></h2>
        <form method="POST">
            <input type="hidden" name="student_id" value="<?= $edit_data['student_id'] ?? 0 ?>">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($edit_data['name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($edit_data['email'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($edit_data['phone'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <select name="dept_id">
                        <?php foreach ($depts as $d): ?>
                        <option value="<?= $d['dept_id'] ?>" <?= ($edit_data && $edit_data['dept_id'] == $d['dept_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($d['dept_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Semester (1-8)</label>
                    <input type="number" name="semester" min="1" max="8" value="<?= $edit_data['semester'] ?? 1 ?>">
                </div>
                <div class="form-group">
                    <label>CGPA (0.0 - 10.0)</label>
                    <input type="number" step="0.1" name="cgpa" min="0" max="10" value="<?= $edit_data['cgpa'] ?? '8.0' ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Update Record' : 'Save Student' ?></button>
            <?php if ($edit_data): ?>
                <a href="students_crud.php" class="btn" style="background:#7f8c8d;color:white;text-decoration:none;">Cancel</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- CONTROLS & TABLE -->
    <div class="form-card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
            <h2>📋 Students List (<?= $total_rows ?> total)</h2>
            <a href="students_crud.php?action=export_csv" class="btn btn-primary btn-sm">📥 Export to CSV</a>
        </div>

        <!-- SEARCH & FILTER -->
        <form method="GET" class="flex-bar">
            <input type="text" name="search" placeholder="Search by name/email..." value="<?= htmlspecialchars($search) ?>" style="flex:1;padding:8px;">
            <select name="dept_filter" style="padding:8px;">
                <option value="0">All Departments</option>
                <?php foreach ($depts as $d): ?>
                <option value="<?= $d['dept_id'] ?>" <?= $filter == $d['dept_id'] ? 'selected' : '' ?>><?= htmlspecialchars($d['dept_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="status_filter" style="padding:8px;">
                <option value="">All Status</option>
                <option value="active" <?= $status_f==='active'?'selected':'' ?>>Active</option>
                <option value="inactive" <?= $status_f==='inactive'?'selected':'' ?>>Inactive (Soft-Deleted)</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="students_crud.php" class="btn btn-sm" style="background:#ddd;color:#333;">Reset</a>
        </form>

        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Name</th><th>Email</th><th>Dept</th><th>Sem</th><th>CGPA</th><th>Status</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr><td colspan="8" style="text-align:center;">No students found.</td></tr>
                    <?php else: foreach ($students as $s): ?>
                    <tr>
                        <td><?= $s['student_id'] ?></td>
                        <td><?= htmlspecialchars($s['name']) ?></td>
                        <td><?= htmlspecialchars($s['email']) ?></td>
                        <td><?= htmlspecialchars($s['dept_name']) ?></td>
                        <td><?= $s['semester'] ?></td>
                        <td><?= $s['cgpa'] ?></td>
                        <td><span class="badge-<?= $s['status'] ?>"><?= ucfirst($s['status']) ?></span></td>
                        <td>
                            <a href="students_crud.php?action=edit&id=<?= $s['student_id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="students_crud.php?action=toggle_status&id=<?= $s['student_id'] ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Confirm <?= $s['status'] === 'active' ? 'Soft Delete (Deactivate)' : 'Restore (Activate)' ?> student?');">
                               <?= $s['status'] === 'active' ? 'Deactivate' : 'Restore' ?>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <?php if ($total_pages > 1): ?>
        <div style="margin-top:15px;display:flex;gap:5px;align-items:center;">
            <?php for ($i=1; $i<=$total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&dept_filter=<?= $filter ?>&status_filter=<?= $status_f ?>" 
                   style="padding:6px 12px;border:1px solid #ddd;text-decoration:none;<?= $page==$i ? 'background:#3498db;color:#fff;' : 'background:#fff;color:#333;' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</main>
<footer><p>&copy; 2026 StudentHub. Practical 11.</p></footer>
</body>
</html>
