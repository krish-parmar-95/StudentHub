<?php
/* Practical 12: Event Management CRUD with Poster Upload and File Validation */
require_once 'db.php';
session_start();

$msg = ''; $err = '';
$upload_dir = __DIR__ . '/uploads/posters/';

// Helper: Audit Log (Advanced)
function log_event_action($pdo, $act, $details) {
    try {
        $pdo->prepare("INSERT INTO audit_log (action, email, ip_address) VALUES (?, ?, ?)")
            ->execute([$act . ': ' . $details, $_SESSION['name'] ?? 'Admin', $_SERVER['REMOTE_ADDR']]);
    } catch(Exception $e){}
}

// DELETE EVENT
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // delete old poster file
    $p_stmt = $pdo->prepare("SELECT poster, title FROM events WHERE event_id = ?");
    $p_stmt->execute([$id]);
    $ev = $p_stmt->fetch();
    if ($ev && !empty($ev['poster']) && file_exists($upload_dir . $ev['poster'])) {
        unlink($upload_dir . $ev['poster']);
    }
    $pdo->prepare("DELETE FROM events WHERE event_id = ?")->execute([$id]);
    log_event_action($pdo, 'DELETE_EVENT', $ev['title'] ?? "ID $id");
    header("Location: events_crud.php?msg=" . urlencode("Event deleted successfully!")); exit;
}

// CREATE / UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = intval($_POST['event_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $type  = $_POST['event_type'] ?? 'Academic';
    $date  = $_POST['event_date'] ?? date('Y-m-d');
    $time  = trim($_POST['time'] ?? '10:00 AM');
    $venue = trim($_POST['venue'] ?? 'Main Auditorium');
    $desc  = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'open';

    if (empty($title) || empty($date)) {
        $err = "Title and Event Date are required.";
    } else {
        $poster_filename = null;

        // Poster Upload & Validation (Practical 12 Core)
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
            $file_tmp  = $_FILES['poster']['tmp_name'];
            $file_size = $_FILES['poster']['size'];
            $file_name = $_FILES['poster']['name'];
            $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
            $max_size = 2 * 1024 * 1024; // 2MB

            if (!in_array($file_ext, $allowed_exts)) {
                $err = "Invalid file type! Only JPG, PNG, WEBP allowed.";
            } elseif ($file_size > $max_size) {
                $err = "File size exceeds 2MB limit!";
            } else {
                // Advanced: Unique file renaming
                $poster_filename = 'poster_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $file_ext;
                move_uploaded_file($file_tmp, $upload_dir . $poster_filename);
            }
        }

        if (empty($err)) {
            if ($id > 0) {
                // UPDATE
                if ($poster_filename) {
                    $stmt = $pdo->prepare("UPDATE events SET title=?, event_type=?, event_date=?, time=?, venue=?, description=?, status=?, poster=? WHERE event_id=?");
                    $stmt->execute([$title, $type, $date, $time, $venue, $desc, $status, $poster_filename, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE events SET title=?, event_type=?, event_date=?, time=?, venue=?, description=?, status=? WHERE event_id=?");
                    $stmt->execute([$title, $type, $date, $time, $venue, $desc, $status, $id]);
                }
                log_event_action($pdo, 'UPDATE_EVENT', "$title (ID $id)");
                $msg = "✅ Event updated successfully!";
            } else {
                // INSERT
                $stmt = $pdo->prepare("INSERT INTO events (title, event_type, event_date, time, venue, description, status, poster) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $type, $date, $time, $venue, $desc, $status, $poster_filename]);
                log_event_action($pdo, 'CREATE_EVENT', $title);
                $msg = "✅ New event created successfully!";
            }
        }
    }
}

// Edit Fetch
$edit_data = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = ?");
    $stmt->execute([intval($_GET['id'])]);
    $edit_data = $stmt->fetch();
}

// Intermediate: Category & Open/Closed Filter
$cat_f = $_GET['cat_filter'] ?? '';
$st_f  = $_GET['status_filter'] ?? '';

$where = ["1=1"]; $params = [];
if ($cat_f !== '') { $where[] = "event_type = ?"; $params[] = $cat_f; }
if ($st_f !== '')  { $where[] = "status = ?"; $params[] = $st_f; }
$where_sql = implode(' AND ', $where);

$stmt = $pdo->prepare("SELECT * FROM events WHERE $where_sql ORDER BY event_date ASC");
$stmt->execute($params);
$events = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Practical 12 — Event Management CRUD</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .badge-open { background:#27ae60; color:#fff; padding:3px 8px; border-radius:4px; font-size:12px; }
        .badge-closed { background:#e74c3c; color:#fff; padding:3px 8px; border-radius:4px; font-size:12px; }
        .thumb { width:55px; height:55px; object-fit:cover; border-radius:4px; border:1px solid #ccc; }
        .btn-sm { padding:4px 8px; font-size:12px; text-decoration:none; }
    </style>
</head>
<body>
<header>
    <h1>🎓 StudentHub Admin</h1>
    <nav>
        <a href="students_crud.php">Students</a>
        <a href="events_crud.php"><strong>Events CRUD</strong></a>
        <a href="logout.php">Logout</a>
    </nav>
</header>
<main class="container" style="max-width:1000px;">

    <?php if ($msg || isset($_GET['msg'])): ?>
        <div class="msg-success"><?= htmlspecialchars($msg ?: $_GET['msg']) ?></div>
    <?php endif; ?>
    <?php if ($err): ?><div class="msg-error"><?= htmlspecialchars($err) ?></div><?php endif; ?>

    <!-- ADD/EDIT FORM WITH FILE UPLOAD -->
    <div class="form-card">
        <h2><?= $edit_data ? '✏️ Edit Event (ID: '.$edit_data['event_id'].')' : '➕ Create New Event' ?></h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="event_id" value="<?= $edit_data['event_id'] ?? 0 ?>">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
                <div class="form-group">
                    <label>Event Title *</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($edit_data['title'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Category (Intermediate)</label>
                    <select name="event_type">
                        <?php foreach (['Academic','Cultural','Sports','Workshop','Technical'] as $t): ?>
                        <option value="<?= $t ?>" <?= ($edit_data && $edit_data['event_type']==$t)?'selected':'' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date *</label>
                    <input type="date" name="event_date" value="<?= $edit_data['event_date'] ?? date('Y-m-d') ?>" required>
                </div>
                <div class="form-group">
                    <label>Time</label>
                    <input type="text" name="time" value="<?= htmlspecialchars($edit_data['time'] ?? '10:00 AM') ?>">
                </div>
                <div class="form-group">
                    <label>Venue</label>
                    <input type="text" name="venue" value="<?= htmlspecialchars($edit_data['venue'] ?? 'Auditorium') ?>">
                </div>
                <div class="form-group">
                    <label>Registration Status</label>
                    <select name="status">
                        <option value="open" <?= ($edit_data && $edit_data['status']=='open')?'selected':'' ?>>Open</option>
                        <option value="closed" <?= ($edit_data && $edit_data['status']=='closed')?'selected':'' ?>>Closed</option>
                    </select>
                </div>
            </div>

            <!-- Poster File Upload (Practical 12 Core) -->
            <div class="form-group">
                <label>Event Poster (JPG, PNG, max 2MB)</label>
                <input type="file" name="poster" accept=".jpg,.jpeg,.png,.webp">
                <?php if ($edit_data && !empty($edit_data['poster'])): ?>
                    <p style="font-size:12px;color:#555;margin-top:5px;">Current: <code><?= htmlspecialchars($edit_data['poster']) ?></code></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="2"><?= htmlspecialchars($edit_data['description'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Update Event' : 'Save Event & Upload Poster' ?></button>
            <?php if ($edit_data): ?>
                <a href="events_crud.php" class="btn" style="background:#7f8c8d;color:white;text-decoration:none;">Cancel</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- FILTER & TABLE -->
    <div class="form-card">
        <h2>📅 Events List (<?= count($events) ?> records)</h2>
        <form method="GET" style="display:flex;gap:10px;margin-bottom:15px;align-items:center;">
            <select name="cat_filter" style="padding:7px;">
                <option value="">All Categories</option>
                <?php foreach (['Academic','Cultural','Sports','Workshop','Technical'] as $c): ?>
                <option value="<?= $c ?>" <?= $cat_f===$c?'selected':'' ?>><?= $c ?></option>
                <?php endforeach; ?>
            </select>
            <select name="status_filter" style="padding:7px;">
                <option value="">All Status</option>
                <option value="open" <?= $st_f==='open'?'selected':'' ?>>Open</option>
                <option value="closed" <?= $st_f==='closed'?'selected':'' ?>>Closed</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="events_crud.php" class="btn btn-sm" style="background:#ddd;color:#333;">Reset</a>
        </form>

        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr><th>Poster</th><th>Title</th><th>Category</th><th>Date/Time</th><th>Venue</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($events)): ?>
                        <tr><td colspan="7" style="text-align:center;">No events found.</td></tr>
                    <?php else: foreach ($events as $e): ?>
                    <tr>
                        <td>
                            <?php if (!empty($e['poster']) && file_exists($upload_dir . $e['poster'])): ?>
                                <img src="uploads/posters/<?= htmlspecialchars($e['poster']) ?>" class="thumb" alt="Poster">
                            <?php else: ?>
                                <span style="font-size:11px;color:#888;">No poster</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($e['title']) ?></strong></td>
                        <td><?= htmlspecialchars($e['event_type']) ?></td>
                        <td><?= $e['event_date'] ?><br><small><?= htmlspecialchars($e['time']) ?></small></td>
                        <td><?= htmlspecialchars($e['venue']) ?></td>
                        <td><span class="badge-<?= $e['status'] ?>"><?= ucfirst($e['status']) ?></span></td>
                        <td>
                            <a href="events_crud.php?action=edit&id=<?= $e['event_id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="events_crud.php?action=delete&id=<?= $e['event_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this event?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<footer><p>&copy; 2026 StudentHub. Practical 12.</p></footer>
</body>
</html>
