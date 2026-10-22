<?php
/* Practical 14: Admin Dashboard with RBAC, Dynamic Menu, Statistics, Audit Log & Charts */
require_once 'db.php';
session_start();

// Strict RBAC: Restrict to Admin only
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php?msg=unauthorized");
    exit;
}

// Intermediate: Toggle active/inactive user status directly from dashboard
if (isset($_GET['action']) && $_GET['action'] === 'toggle_user' && isset($_GET['id'])) {
    $uid = intval($_GET['id']);
    $st = $pdo->prepare("SELECT status, name FROM students WHERE student_id = ?");
    $st->execute([$uid]);
    $u = $st->fetch();
    if ($u) {
        $new_st = ($u['status'] === 'active') ? 'inactive' : 'active';
        $pdo->prepare("UPDATE students SET status = ? WHERE student_id = ?")->execute([$new_st, $uid]);
        $pdo->prepare("INSERT INTO audit_log (action, email, ip_address) VALUES (?, ?, ?)")
            ->execute(["STATUS_TOGGLE: {$u['name']} is now $new_st", $_SESSION['name'], $_SERVER['REMOTE_ADDR']]);
        header("Location: admin_dashboard.php?msg=" . urlencode("User status toggled to $new_st!")); exit;
    }
}

// 1. Dynamic Aggregated Statistics
$total_students = $pdo->query("SELECT COUNT(*) FROM students WHERE role = 'student'")->fetchColumn();
$active_students = $pdo->query("SELECT COUNT(*) FROM students WHERE status = 'active' AND role = 'student'")->fetchColumn();
$total_events   = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$total_regs     = $pdo->query("SELECT COUNT(*) FROM registrations")->fetchColumn();

// 2. Latest Event Registrations with JOIN
$latest_regs = $pdo->query("SELECT r.reg_id, s.name as student_name, s.email, e.title as event_title, r.registered_at 
                            FROM registrations r 
                            JOIN students s ON r.student_id = s.student_id 
                            JOIN events e ON r.event_id = e.event_id 
                            ORDER BY r.registered_at DESC LIMIT 5")->fetchAll();

// 3. User Moderation List
$users_list = $pdo->query("SELECT student_id, name, email, role, status FROM students ORDER BY student_id DESC LIMIT 6")->fetchAll();

// 4. Audit Log Entries (Traceability)
$audit_logs = $pdo->query("SELECT * FROM audit_log ORDER BY created_at DESC LIMIT 6")->fetchAll();

// 5. Advanced: Chart Data (Registrations by Category / Month)
$chart_data = $pdo->query("SELECT e.event_type, COUNT(r.reg_id) as count 
                           FROM events e 
                           LEFT JOIN registrations r ON e.event_id = r.event_id 
                           GROUP BY e.event_type")->fetchAll();
$chart_labels = array_column($chart_data, 'event_type');
$chart_counts = array_column($chart_data, 'count');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 14 — Admin Dashboard & RBAC</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-grid { display:grid; grid-template-columns: repeat(4, 1fr); gap:15px; margin-bottom:20px; }
        .stat-box { background:#fff; padding:18px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.08); text-align:center; border-top:4px solid #3498db; }
        .stat-box h3 { font-size:26px; color:#2c3e50; margin:5px 0; }
        .stat-box p { color:#7f8c8d; font-size:13px; text-transform:uppercase; font-weight:600; }
        .badge-admin { background:#e74c3c; color:#fff; padding:2px 7px; border-radius:3px; font-size:11px; }
        .badge-student { background:#3498db; color:#fff; padding:2px 7px; border-radius:3px; font-size:11px; }
        .badge-active { background:#27ae60; color:#fff; padding:2px 7px; border-radius:3px; font-size:11px; }
        .badge-inactive { background:#7f8c8d; color:#fff; padding:2px 7px; border-radius:3px; font-size:11px; }
        .btn-sm { padding:3px 7px; font-size:11px; text-decoration:none; }
    </style>
</head>
<body>

<!-- Dynamic Role-Based Navigation Header -->
<header>
    <h1>🎓 StudentHub <span style="font-size:14px;background:#e74c3c;padding:3px 8px;border-radius:4px;vertical-align:middle;">ADMIN</span></h1>
    <nav>
        <a href="admin_dashboard.php"><strong>Dashboard</strong></a>
        <a href="students_crud.php">Manage Students</a>
        <a href="events_crud.php">Manage Events</a>
        <a href="fetch_events.html">Live Fetch API</a>
        <a href="logout.php" style="background:#c0392b;color:#fff;padding:4px 10px;border-radius:4px;">Logout (<?= htmlspecialchars($_SESSION['name']) ?>)</a>
    </nav>
</header>

<main class="container" style="max-width:1050px;">
    <?php if (isset($_GET['msg'])): ?>
        <div class="msg-success">✅ <?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <!-- 1. Key Metrics / Aggregated Statistics -->
    <div class="stat-grid">
        <div class="stat-box" style="border-color:#3498db;">
            <p>Total Students</p>
            <h3><?= $total_students ?></h3>
            <small style="color:#27ae60;">Active: <?= $active_students ?></small>
        </div>
        <div class="stat-box" style="border-color:#9b59b6;">
            <p>Total Events</p>
            <h3><?= $total_events ?></h3>
            <small>Active Sem 2026</small>
        </div>
        <div class="stat-box" style="border-color:#e67e22;">
            <p>Registrations</p>
            <h3><?= $total_regs ?></h3>
            <small>Student Participation</small>
        </div>
        <div class="stat-box" style="border-color:#2ecc71;">
            <p>System Security</p>
            <h3>RBAC</h3>
            <small>Role: Administrator</small>
        </div>
    </div>

    <!-- 2. Analytics Chart (Advanced Extension) -->
    <div class="form-card">
        <h2>📈 Event Registrations by Category (Chart.js Analytics)</h2>
        <div style="max-height:260px; max-width:650px; margin:0 auto;">
            <canvas id="regChart"></canvas>
        </div>
    </div>

    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
        <!-- 3. User Moderation & Status Toggle (Intermediate) -->
        <div class="form-card">
            <h2>👥 User Status Moderation</h2>
            <table>
                <thead>
                    <tr><th>User</th><th>Role</th><th>Status</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($users_list as $u): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($u['name']) ?></strong><br><small><?= htmlspecialchars($u['email']) ?></small></td>
                        <td><span class="badge-<?= $u['role'] ?>"><?= strtoupper($u['role']) ?></span></td>
                        <td><span class="badge-<?= $u['status'] ?>"><?= ucfirst($u['status']) ?></span></td>
                        <td>
                            <?php if ($u['role'] !== 'admin'): ?>
                            <a href="admin_dashboard.php?action=toggle_user&id=<?= $u['student_id'] ?>" class="btn btn-sm btn-primary">
                                <?= $u['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                            </a>
                            <?php else: ?><span style="font-size:11px;color:#888;">Protected</span><?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- 4. Latest Event Registrations -->
        <div class="form-card">
            <h2>📝 Recent Registrations</h2>
            <table>
                <thead>
                    <tr><th>Student</th><th>Event</th><th>Date</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($latest_regs as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['student_name']) ?></td>
                        <td><strong><?= htmlspecialchars($r['event_title']) ?></strong></td>
                        <td><small><?= date('M d, H:i', strtotime($r['registered_at'])) ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 5. Audit Log (Traceability) -->
    <div class="form-card">
        <h2>🛡️ Security & Activity Audit Log</h2>
        <table>
            <thead>
                <tr><th>#</th><th>Recorded Action</th><th>User/Actor</th><th>IP Address</th><th>Timestamp</th></tr>
            </thead>
            <tbody>
                <?php foreach ($audit_logs as $log): ?>
                <tr>
                    <td><?= $log['id'] ?></td>
                    <td><code><?= htmlspecialchars($log['action']) ?></code></td>
                    <td><?= htmlspecialchars($log['email']) ?></td>
                    <td><?= htmlspecialchars($log['ip_address']) ?></td>
                    <td><?= $log['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<footer><p>&copy; 2026 StudentHub. Practical 14 — Admin Dashboard & Audit Log.</p></footer>

<script>
// Analytics Chart (Practical 14 Advanced Feature)
const ctx = document.getElementById('regChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($chart_labels) ?>,
        datasets: [{
            label: 'Event Registrations',
            data: <?= json_encode($chart_counts) ?>,
            backgroundColor: ['#3498db', '#9b59b6', '#e67e22', '#2ecc71', '#e74c3c']
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>
</body>
</html>
