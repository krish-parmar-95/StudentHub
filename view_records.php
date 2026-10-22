<?php
/* ============================================
   Practical 7: View Stored Records
   Displays CSV and JSON stored registration data
   ============================================ */

$json_file = __DIR__ . '/data/registrations.json';
$csv_file  = __DIR__ . '/data/registrations.csv';

// Load JSON records
$json_records = [];
if (file_exists($json_file)) {
    $json_records = json_decode(file_get_contents($json_file), true) ?? [];
}

// Load CSV records
$csv_records = [];
if (file_exists($csv_file)) {
    $fp = fopen($csv_file, 'r');
    $headers = fgetcsv($fp); // first row = headers
    while (($row = fgetcsv($fp)) !== false) {
        if (count($row) === count($headers)) {
            $csv_records[] = array_combine($headers, $row);
        }
    }
    fclose($fp);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Records — StudentHub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>🎓 StudentHub</h1>
    <nav>
        <a href="index.php">Register</a>
        <a href="view_records.php"><strong>View Records</strong></a>
    </nav>
</header>

<main class="container">

    <!-- JSON Records -->
    <div class="form-card">
        <h2>📄 JSON Records (<?php echo count($json_records); ?>)</h2>
        <p style="margin-bottom:10px; color:#666;">Source: <code>data/registrations.json</code></p>

        <?php if (empty($json_records)): ?>
            <p>No records found. <a href="index.php">Submit a registration</a> first.</p>
        <?php else: ?>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($json_records as $i => $r): ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td><?php echo htmlspecialchars($r['name']); ?></td>
                            <td><?php echo htmlspecialchars($r['email']); ?></td>
                            <td><?php echo htmlspecialchars($r['phone']); ?></td>
                            <td><?php echo htmlspecialchars($r['course']); ?></td>
                            <td><?php echo htmlspecialchars($r['message'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($r['date']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- CSV Records -->
    <div class="form-card">
        <h2>📊 CSV Records (<?php echo count($csv_records); ?>)</h2>
        <p style="margin-bottom:10px; color:#666;">Source: <code>data/registrations.csv</code></p>

        <?php if (empty($csv_records)): ?>
            <p>No CSV records found.</p>
        <?php else: ?>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($csv_records as $i => $r): ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td><?php echo htmlspecialchars($r['name']); ?></td>
                            <td><?php echo htmlspecialchars($r['email']); ?></td>
                            <td><?php echo htmlspecialchars($r['phone']); ?></td>
                            <td><?php echo htmlspecialchars($r['course']); ?></td>
                            <td><?php echo htmlspecialchars($r['message'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($r['date']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Raw JSON Preview -->
    <div class="form-card">
        <h2>🔍 Raw JSON File Preview</h2>
        <pre style="background:#f4f4f4; padding:15px; border-radius:5px; overflow-x:auto; font-size:13px;"><?php
            if (file_exists($json_file)) {
                echo htmlspecialchars(file_get_contents($json_file));
            } else {
                echo "No JSON file found yet.";
            }
        ?></pre>
    </div>

</main>

<footer>
    <p>&copy; 2026 StudentHub. Practical 7 — View Stored Records.</p>
</footer>

</body>
</html>
