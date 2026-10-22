<?php
/* Practical 13: RESTful JSON API Endpoint with Proper HTTP Status Codes */
header('Content-Type: application/json; charset=UTF-8');
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

function jsonResponse($success, $data_or_msg, $code = 200) {
    http_response_code($code);
    echo json_encode(['success' => $success, 'data' => $data_or_msg]);
    exit;
}

try {
    switch ($method) {
        // GET: List or Search with Debounce
        case 'GET':
            $search = trim($_GET['search'] ?? '');
            if ($search !== '') {
                $stmt = $pdo->prepare("SELECT * FROM events WHERE title LIKE ? OR description LIKE ? ORDER BY event_id DESC");
                $stmt->execute(["%$search%", "%$search%"]);
            } else {
                $stmt = $pdo->query("SELECT * FROM events ORDER BY event_id DESC");
            }
            jsonResponse(true, $stmt->fetchAll(), 200);
            break;

        // POST: Add new event
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) $input = $_POST;

            $title = trim($input['title'] ?? '');
            $type  = $input['event_type'] ?? 'Academic';
            $date  = $input['event_date'] ?? date('Y-m-d');
            $venue = trim($input['venue'] ?? 'Campus Hall');

            if (empty($title)) {
                jsonResponse(false, 'Event title is required!', 422);
            }

            $stmt = $pdo->prepare("INSERT INTO events (title, event_type, event_date, venue, status) VALUES (?, ?, ?, ?, 'open')");
            $stmt->execute([$title, $type, $date, $venue]);
            jsonResponse(true, ['id' => $pdo->lastInsertId(), 'message' => 'Event added via API!'], 201);
            break;

        // PUT: Update event
        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            $id    = intval($input['event_id'] ?? 0);
            $title = trim($input['title'] ?? '');
            $venue = trim($input['venue'] ?? '');

            if ($id <= 0 || empty($title)) {
                jsonResponse(false, 'Valid Event ID and title required!', 422);
            }

            $stmt = $pdo->prepare("UPDATE events SET title = ?, venue = ? WHERE event_id = ?");
            $stmt->execute([$title, $venue, $id]);
            jsonResponse(true, 'Event updated via API!', 200);
            break;

        // DELETE: Delete event
        case 'DELETE':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = intval($input['event_id'] ?? ($_GET['id'] ?? 0));

            if ($id <= 0) {
                jsonResponse(false, 'Valid Event ID required for delete!', 422);
            }

            $stmt = $pdo->prepare("DELETE FROM events WHERE event_id = ?");
            $stmt->execute([$id]);
            jsonResponse(true, 'Event deleted via API!', 200);
            break;

        default:
            jsonResponse(false, 'Method Not Allowed', 405);
    }
} catch (Exception $e) {
    jsonResponse(false, 'Database Error: ' . $e->getMessage(), 500);
}
