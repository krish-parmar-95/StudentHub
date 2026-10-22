<?php
/* ============================================
   Practical 8: Test Database Connection
   Shows tables, data, and prepared statements
   ============================================ */

require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 8 — DB Connection Test | StudentHub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>🎓 StudentHub</h1>
    <nav>
        <a href="index.php">Register</a>
        <a href="test_connection.php"><strong>DB Test</strong></a>
        <a href="view_records.php">View Records</a>
    </nav>
</header>

<main class="container">

    <!-- Connection Status -->
    <div class="form-card">
        <h2>🔌 Database Connection Test</h2>
        <?php
        try {
            $pdo->query("SELECT 1");
            echo '<div class="msg-success">✅ Connected to MySQL database <strong>student_hub</strong> successfully using PDO!</div>';
        } catch (Exception $e) {
            echo '<div class="msg-error">❌ Connection failed: ' . $e->getMessage() . '</div>';
        }
        ?>
    </div>

    <!-- Database Tables -->
    <div class="form-card">
        <h2>📋 Database Tables</h2>
        <?php
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo '<p>Found <strong>' . count($tables) . '</strong> tables:</p><ul>';
        foreach ($tables as $t) {
            $count = $pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
            echo "<li><strong>$t</strong> — $count rows</li>";
        }
        echo '</ul>';
        ?>
    </div>

    <!-- Students Table (Prepared Statement) -->
    <div class="form-card">
        <h2>👨‍🎓 Students (Prepared Statement)</h2>
        <?php
        // Using Prepared Statement (Intermediate requirement)
        $stmt = $pdo->prepare("SELECT s.student_id, s.name, s.email, s.semester, s.cgpa, d.dept_name 
                               FROM students s 
                               JOIN departments d ON s.dept_id = d.dept_id 
                               ORDER BY s.name");
        $stmt->execute();
        $students = $stmt->fetchAll();
        ?>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr><th>ID</th><th>Name</th><th>Email</th><th>Sem</th><th>CGPA</th><th>Department</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $s): ?>
                    <tr>
                        <td><?= $s['student_id'] ?></td>
                        <td><?= htmlspecialchars($s['name']) ?></td>
                        <td><?= htmlspecialchars($s['email']) ?></td>
                        <td><?= $s['semester'] ?></td>
                        <td><?= $s['cgpa'] ?></td>
                        <td><?= htmlspecialchars($s['dept_name']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Events Table -->
    <div class="form-card">
        <h2>📅 Events</h2>
        <?php
        $events = $pdo->query("SELECT * FROM events ORDER BY event_date")->fetchAll();
        ?>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr><th>ID</th><th>Title</th><th>Type</th><th>Date</th><th>Venue</th><th>Time</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $e): ?>
                    <tr>
                        <td><?= $e['event_id'] ?></td>
                        <td><?= htmlspecialchars($e['title']) ?></td>
                        <td><?= htmlspecialchars($e['event_type']) ?></td>
                        <td><?= $e['event_date'] ?></td>
                        <td><?= htmlspecialchars($e['venue']) ?></td>
                        <td><?= htmlspecialchars($e['time']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Registrations with JOIN -->
    <div class="form-card">
        <h2>📝 Event Registrations (JOIN Query)</h2>
        <?php
        $regs = $pdo->query("SELECT r.reg_id, s.name AS student_name, e.title AS event_title, r.registered_at 
                             FROM registrations r 
                             JOIN students s ON r.student_id = s.student_id 
                             JOIN events e ON r.event_id = e.event_id 
                             ORDER BY r.registered_at DESC")->fetchAll();
        ?>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr><th>#</th><th>Student</th><th>Event</th><th>Registered At</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($regs as $i => $r): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($r['student_name']) ?></td>
                        <td><?= htmlspecialchars($r['event_title']) ?></td>
                        <td><?= $r['registered_at'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Stored Procedure Test (Advanced) -->
    <div class="form-card">
        <h2>⚙️ Stored Procedure: GetStudentsByDept (Advanced)</h2>
        <?php
        $stmt = $pdo->prepare("CALL GetStudentsByDept(:dept_id)");
        $stmt->execute(['dept_id' => 1]); // Computer Science
        $cs_students = $stmt->fetchAll();
        ?>
        <p>Calling <code>CALL GetStudentsByDept(1)</code> — Computer Science students:</p>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr><th>ID</th><th>Name</th><th>Email</th><th>Sem</th><th>CGPA</th><th>Dept</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($cs_students as $s): ?>
                    <tr>
                        <td><?= $s['student_id'] ?></td>
                        <td><?= htmlspecialchars($s['name']) ?></td>
                        <td><?= htmlspecialchars($s['email']) ?></td>
                        <td><?= $s['semester'] ?></td>
                        <td><?= $s['cgpa'] ?></td>
                        <td><?= htmlspecialchars($s['dept_name']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<footer>
    <p>&copy; 2026 StudentHub. Practical 8 — MySQL Schema, ER Model, PDO Connectivity.</p>
</footer>

</body>
</html>
