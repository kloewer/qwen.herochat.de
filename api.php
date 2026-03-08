<?php
/**
 * Kanban Board API
 * Handles CRUD operations for cards and checklists
 */

header('Content-Type: application/json');

require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Get request method and action
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        // === CARDS ===
        case 'get_cards':
            $stmt = $pdo->query("SELECT * FROM cards ORDER BY column_status, position");
            $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get checklists for each card
            foreach ($cards as &$card) {
                $stmt = $pdo->prepare("SELECT id, task, is_done, position FROM checklists WHERE card_id = ? ORDER BY position");
                $stmt->execute([$card['id']]);
                $card['checklist'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Get comments for each card
                $stmt = $pdo->prepare("SELECT id, comment_text, created_at FROM comments WHERE card_id = ? ORDER BY created_at ASC");
                $stmt->execute([$card['id']]);
                $card['comments'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            echo json_encode($cards);
            break;

        case 'create_card':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO cards (title, description, column_status, position) VALUES (?, ?, ?, ?)");
            $stmt->execute([$data['title'], $data['description'] ?? '', $data['column_status'] ?? 'todo', $data['position'] ?? 0]);
            echo json_encode(['id' => $pdo->lastInsertId(), 'success' => true]);
            break;

        case 'update_card':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("UPDATE cards SET title = ?, description = ?, column_status = ?, position = ? WHERE id = ?");
            $stmt->execute([$data['title'], $data['description'], $data['column_status'], $data['position'], $data['id']]);
            echo json_encode(['success' => true]);
            break;

        case 'delete_card':
            $id = $_GET['id'] ?? 0;
            $stmt = $pdo->prepare("DELETE FROM cards WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        case 'move_card':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("UPDATE cards SET column_status = ?, position = ? WHERE id = ?");
            $stmt->execute([$data['column_status'], $data['position'], $data['id']]);
            echo json_encode(['success' => true]);
            break;

        // === CHECKLISTS ===
        case 'add_checklist':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO checklists (card_id, task, is_done, position) VALUES (?, ?, ?, ?)");
            $stmt->execute([$data['card_id'], $data['task'], $data['is_done'] ?? 0, $data['position'] ?? 0]);
            echo json_encode(['id' => $pdo->lastInsertId(), 'success' => true]);
            break;

        case 'update_checklist':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("UPDATE checklists SET task = ?, is_done = ? WHERE id = ?");
            $stmt->execute([$data['task'], $data['is_done'], $data['id']]);
            echo json_encode(['success' => true]);
            break;

        case 'toggle_checklist':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("UPDATE checklists SET is_done = ? WHERE id = ?");
            $stmt->execute([$data['is_done'] ? 1 : 0, $data['id']]);
            echo json_encode(['success' => true]);
            break;

        case 'delete_checklist':
            $id = $_GET['id'] ?? 0;
            $stmt = $pdo->prepare("DELETE FROM checklists WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        // === COMMENTS ===
        case 'add_comment':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO comments (card_id, comment_text) VALUES (?, ?)");
            $stmt->execute([$data['card_id'], $data['comment_text']]);
            echo json_encode(['id' => $pdo->lastInsertId(), 'success' => true]);
            break;

        case 'delete_comment':
            $id = $_GET['id'] ?? 0;
            $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
