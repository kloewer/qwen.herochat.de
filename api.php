<?php
/**
 * Kanban Board API
 * Handles CRUD operations for cards and checklists
 */

header('Content-Type: application/json');

require_once 'config.php';

// Get current user
$currentUser = getCurrentUser();
$userEmail = $currentUser['email'] ?? '';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Get request method and action
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        // === CARDS ===
        case 'get_cards':
            $filterUser = $_GET['user'] ?? '';
            
            if ($filterUser && $filterUser !== 'all') {
                // Filter cards by assigned user
                $stmt = $pdo->prepare("
                    SELECT DISTINCT c.* FROM cards c
                    LEFT JOIN card_assignments ca ON c.id = ca.card_id
                    WHERE ca.user_email = ? OR c.created_by = ?
                    ORDER BY c.column_status, c.position
                ");
                $stmt->execute([$filterUser, $filterUser]);
            } else {
                $stmt = $pdo->query("SELECT * FROM cards ORDER BY column_status, position");
            }
            $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get checklists, comments, and assignments for each card
            foreach ($cards as &$card) {
                // Get checklists
                $stmt = $pdo->prepare("SELECT id, task, is_done, position, due_date, assigned_user, linked_card_id, checked_at FROM checklists WHERE card_id = ? ORDER BY position");
                $stmt->execute([$card['id']]);
                $card['checklist'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Get comments with user info
                $stmt = $pdo->prepare("SELECT id, comment_text, user_email, created_at FROM comments WHERE card_id = ? ORDER BY created_at ASC");
                $stmt->execute([$card['id']]);
                $card['comments'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Get assigned users
                $stmt = $pdo->prepare("SELECT user_email FROM card_assignments WHERE card_id = ?");
                $stmt->execute([$card['id']]);
                $card['assigned_users'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
            }

            echo json_encode($cards);
            break;

        case 'create_card':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO cards (title, description, column_status, position, due_date, created_by) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$data['title'], $data['description'] ?? '', $data['column_status'] ?? 'todo', $data['position'] ?? 0, $data['due_date'] ?? null, $userEmail]);

            // Assign users if provided
            if (!empty($data['assigned_users']) && is_array($data['assigned_users'])) {
                foreach ($data['assigned_users'] as $assignedEmail) {
                    $stmt = $pdo->prepare("INSERT IGNORE INTO card_assignments (card_id, user_email) VALUES (?, ?)");
                    $stmt->execute([$pdo->lastInsertId(), $assignedEmail]);
                }
            }

            echo json_encode(['id' => $pdo->lastInsertId(), 'success' => true]);
            break;

        case 'update_card':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("UPDATE cards SET title = ?, description = ?, column_status = ?, position = ?, due_date = ? WHERE id = ?");
            $stmt->execute([$data['title'], $data['description'], $data['column_status'], $data['position'], $data['due_date'] ?? null, $data['id']]);
            
            // Update assigned users
            if (isset($data['assigned_users']) && is_array($data['assigned_users'])) {
                // Remove existing assignments
                $stmt = $pdo->prepare("DELETE FROM card_assignments WHERE card_id = ?");
                $stmt->execute([$data['id']]);
                
                // Add new assignments
                foreach ($data['assigned_users'] as $assignedEmail) {
                    $stmt = $pdo->prepare("INSERT IGNORE INTO card_assignments (card_id, user_email) VALUES (?, ?)");
                    $stmt->execute([$data['id'], $assignedEmail]);
                }
            }
            
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
            $stmt = $pdo->prepare("UPDATE cards SET column_status = ?, position = ?, due_date = ? WHERE id = ?");
            $stmt->execute([$data['column_status'], $data['position'], $data['due_date'] ?? null, $data['id']]);
            echo json_encode(['success' => true]);
            break;

        // === CHECKLISTS ===
        case 'add_checklist':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO checklists (card_id, task, is_done, position, due_date, assigned_user) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$data['card_id'], $data['task'], $data['is_done'] ?? 0, $data['position'] ?? 0, $data['due_date'] ?? null, $data['assigned_user'] ?? null]);
            echo json_encode(['id' => $pdo->lastInsertId(), 'success' => true]);
            break;

        case 'update_checklist':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("UPDATE checklists SET task = ?, is_done = ?, due_date = ?, assigned_user = ? WHERE id = ?");
            $stmt->execute([$data['task'], $data['is_done'], $data['due_date'] ?? null, $data['assigned_user'] ?? null, $data['id']]);
            echo json_encode(['success' => true]);
            break;

        case 'toggle_checklist':
            $data = json_decode(file_get_contents('php://input'), true);
            $checkedAt = $data['is_done'] ? date('Y-m-d H:i:s') : null;
            $stmt = $pdo->prepare("UPDATE checklists SET is_done = ?, checked_at = ? WHERE id = ?");
            $stmt->execute([$data['is_done'] ? 1 : 0, $checkedAt, $data['id']]);
            echo json_encode(['success' => true]);
            break;

        case 'delete_checklist':
            $id = $_GET['id'] ?? 0;
            $stmt = $pdo->prepare("DELETE FROM checklists WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        case 'convert_checklist_to_card':
            $data = json_decode(file_get_contents('php://input'), true);
            // Get the checklist item
            $stmt = $pdo->prepare("SELECT * FROM checklists WHERE id = ?");
            $stmt->execute([$data['checklist_id']]);
            $checklist = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($checklist) {
                // Create new card with same title
                $stmt = $pdo->prepare("INSERT INTO cards (title, description, column_status, position, created_by) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$checklist['task'], 'Converted from checklist item', 'todo', 0, $userEmail]);
                $newCardId = $pdo->lastInsertId();
                
                // Link checklist to new card
                $stmt = $pdo->prepare("UPDATE checklists SET linked_card_id = ? WHERE id = ?");
                $stmt->execute([$newCardId, $data['checklist_id']]);
                
                echo json_encode(['success' => true, 'new_card_id' => $newCardId]);
            } else {
                echo json_encode(['error' => 'Checklist not found']);
            }
            break;

        case 'set_checklist_due_date':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("UPDATE checklists SET due_date = ? WHERE id = ?");
            $stmt->execute([$data['due_date'], $data['id']]);
            echo json_encode(['success' => true]);
            break;

        case 'assign_checklist':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("UPDATE checklists SET assigned_user = ? WHERE id = ?");
            $stmt->execute([$data['assigned_user'], $data['id']]);
            echo json_encode(['success' => true]);
            break;

        // === COMMENTS ===
        case 'add_comment':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO comments (card_id, comment_text, user_email) VALUES (?, ?, ?)");
            $stmt->execute([$data['card_id'], $data['comment_text'], $userEmail]);
            echo json_encode(['id' => $pdo->lastInsertId(), 'success' => true, 'user_email' => $userEmail]);
            break;

        case 'delete_comment':
            $id = $_GET['id'] ?? 0;
            $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        // === USER ASSIGNMENTS ===
        case 'assign_user':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT IGNORE INTO card_assignments (card_id, user_email) VALUES (?, ?)");
            $stmt->execute([$data['card_id'], $data['user_email']]);
            echo json_encode(['success' => true]);
            break;

        case 'unassign_user':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("DELETE FROM card_assignments WHERE card_id = ? AND user_email = ?");
            $stmt->execute([$data['card_id'], $data['user_email']]);
            echo json_encode(['success' => true]);
            break;

        // === AUTH ===
        case 'get_current_user':
            echo json_encode([
                'email' => $userEmail,
                'display_name' => $currentUser['display_name'] ?? '',
                'avatar_url' => $currentUser['avatar_url'] ?? ''
            ]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
