<?php
/* ============================================
   Practical 7: PHP Form Processing with 
   Server-Side Validation and CSV/JSON File Storage
   ============================================ */

// Start session for CSRF token
session_start();

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// File paths for storage
$csv_file = __DIR__ . '/data/registrations.csv';
$json_file = __DIR__ . '/data/registrations.json';

// Create data directory if not exists
if (!is_dir(__DIR__ . '/data')) {
    mkdir(__DIR__ . '/data', 0777, true);
}

// Initialize variables
$errors = [];
$success = '';
$old = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'course' => '',
    'message' => ''
];

// =====================
// PROCESS FORM (POST)
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- CSRF Token Validation (Advanced) ---
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Invalid CSRF token. Please refresh and try again.";
    }

    // --- Get & Sanitize Inputs ---
    $name    = trim(htmlspecialchars($_POST['name'] ?? ''));
    $email   = trim(htmlspecialchars($_POST['email'] ?? ''));
    $phone   = trim(htmlspecialchars($_POST['phone'] ?? ''));
    $course  = trim(htmlspecialchars($_POST['course'] ?? ''));
    $message = trim(htmlspecialchars($_POST['message'] ?? ''));

    // Store old values to repopulate form
    $old = compact('name', 'email', 'phone', 'course', 'message');

    // --- Server-Side Validation ---

    // Name: required, letters and spaces only, 2-50 chars
    if (empty($name)) {
        $errors['name'] = "Name is required.";
    } elseif (!preg_match('/^[a-zA-Z\s]{2,50}$/', $name)) {
        $errors['name'] = "Name must be 2-50 letters only.";
    }

    // Email: required, valid format
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Enter a valid email address.";
    }

    // Phone: required, 10 digits
    if (empty($phone)) {
        $errors['phone'] = "Phone number is required.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors['phone'] = "Phone must be exactly 10 digits.";
    }

    // Course: required, must be from allowed list
    $allowed_courses = ['Computer Science', 'Information Technology', 'Electronics', 'Mechanical', 'Civil'];
    if (empty($course)) {
        $errors['course'] = "Please select a course.";
    } elseif (!in_array($course, $allowed_courses)) {
        $errors['course'] = "Invalid course selected.";
    }

    // Message: optional, max 500 chars
    if (!empty($message) && strlen($message) > 500) {
        $errors['message'] = "Message must be under 500 characters.";
    }

    // --- If No Errors, Save Data ---
    if (empty($errors)) {

        $record = [
            'id'        => time(),
            'name'      => $name,
            'email'     => $email,
            'phone'     => $phone,
            'course'    => $course,
            'message'   => $message,
            'date'      => date('Y-m-d H:i:s')
        ];

        // --- Save to CSV ---
        $is_new_csv = !file_exists($csv_file);
        $fp = fopen($csv_file, 'a');
        if ($is_new_csv) {
            // Write header row
            fputcsv($fp, array_keys($record));
        }
        fputcsv($fp, array_values($record));
        fclose($fp);

        // --- Save to JSON ---
        $json_data = [];
        if (file_exists($json_file)) {
            $json_data = json_decode(file_get_contents($json_file), true) ?? [];
        }
        $json_data[] = $record;
        file_put_contents($json_file, json_encode($json_data, JSON_PRETTY_PRINT));

        // Success
        $success = "Registration submitted successfully! Data saved to CSV and JSON.";

        // Reset form
        $old = ['name' => '', 'email' => '', 'phone' => '', 'course' => '', 'message' => ''];

        // Regenerate CSRF token after successful submit
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

// =====================
// Load stored records for display (Intermediate feature)
// =====================
$stored_records = [];
if (file_exists($json_file)) {
    $stored_records = json_decode(file_get_contents($json_file), true) ?? [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 7 — PHP Form Processing | StudentHub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>🎓 StudentHub</h1>
    <nav>
        <a href="index.php"><strong>Register</strong></a>
        <a href="view_records.php">View Records</a>
    </nav>
</header>

<main class="container">

    <!-- Registration Form -->
    <div class="form-card">
        <h2>📝 Student Registration Form</h2>
        <p style="margin-bottom:15px; color:#666;">Practical 7: PHP Form Processing with Server-Side Validation and CSV/JSON File Storage</p>

        <!-- Success Message -->
        <?php if ($success): ?>
            <div class="msg-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- General Errors -->
        <?php if (isset($errors[0])): ?>
            <div class="msg-error"><?php echo $errors[0]; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <!-- CSRF Token (Advanced Feature) -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <!-- Name -->
            <div class="form-group">
                <label for="name">Full Name <span style="color:red;">*</span></label>
                <input type="text" id="name" name="name" value="<?php echo $old['name']; ?>" placeholder="Enter your full name" required>
                <?php if (isset($errors['name'])): ?>
                    <p class="field-error"><?php echo $errors['name']; ?></p>
                <?php endif; ?>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email Address <span style="color:red;">*</span></label>
                <input type="email" id="email" name="email" value="<?php echo $old['email']; ?>" placeholder="Enter your email" required>
                <?php if (isset($errors['email'])): ?>
                    <p class="field-error"><?php echo $errors['email']; ?></p>
                <?php endif; ?>
            </div>

            <!-- Phone -->
            <div class="form-group">
                <label for="phone">Phone Number <span style="color:red;">*</span></label>
                <input type="tel" id="phone" name="phone" value="<?php echo $old['phone']; ?>" placeholder="10-digit number" required>
                <?php if (isset($errors['phone'])): ?>
                    <p class="field-error"><?php echo $errors['phone']; ?></p>
                <?php endif; ?>
            </div>

            <!-- Course -->
            <div class="form-group">
                <label for="course">Course / Department <span style="color:red;">*</span></label>
                <select id="course" name="course" required>
                    <option value="">-- Select Course --</option>
                    <option value="Computer Science" <?php echo ($old['course'] === 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                    <option value="Information Technology" <?php echo ($old['course'] === 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                    <option value="Electronics" <?php echo ($old['course'] === 'Electronics') ? 'selected' : ''; ?>>Electronics</option>
                    <option value="Mechanical" <?php echo ($old['course'] === 'Mechanical') ? 'selected' : ''; ?>>Mechanical</option>
                    <option value="Civil" <?php echo ($old['course'] === 'Civil') ? 'selected' : ''; ?>>Civil</option>
                </select>
                <?php if (isset($errors['course'])): ?>
                    <p class="field-error"><?php echo $errors['course']; ?></p>
                <?php endif; ?>
            </div>

            <!-- Message -->
            <div class="form-group">
                <label for="message">Message (Optional)</label>
                <textarea id="message" name="message" rows="4" placeholder="Any additional message..."><?php echo $old['message']; ?></textarea>
                <?php if (isset($errors['message'])): ?>
                    <p class="field-error"><?php echo $errors['message']; ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Submit Registration</button>
        </form>
    </div>

    <!-- Display Stored Records (Intermediate Feature) -->
    <?php if (!empty($stored_records)): ?>
    <div class="form-card">
        <h2>📋 Stored Registrations (<?php echo count($stored_records); ?> records)</h2>
        <p style="margin-bottom:10px; color:#666;">Data loaded from <code>data/registrations.json</code></p>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Course</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stored_records as $i => $r): ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><?php echo htmlspecialchars($r['name']); ?></td>
                        <td><?php echo htmlspecialchars($r['email']); ?></td>
                        <td><?php echo htmlspecialchars($r['phone']); ?></td>
                        <td><?php echo htmlspecialchars($r['course']); ?></td>
                        <td><?php echo htmlspecialchars($r['date']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

</main>

<footer>
    <p>&copy; 2026 StudentHub. Practical 7 — PHP Form Processing.</p>
</footer>

</body>
</html>
