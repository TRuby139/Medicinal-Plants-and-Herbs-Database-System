<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
$action = $_REQUEST['action'] ?? '';

if ($method === 'GET') {
    if ($action === 'get_categories') {
        $type = $_GET['type'] ?? '';
        $query = "SELECT * FROM categories";
        $params = [];
        $types = "";
        
        if (!empty($type)) {
            $query .= " WHERE type = ?";
            $params[] = $type;
            $types .= "s";
        }
        $query .= " ORDER BY name ASC";
        
        $stmt = mysqli_prepare($conn, $query);
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) { $data[] = $row; }
        echo json_encode(['success' => true, 'data' => $data]);
        exit();
    }
    
    if ($action === 'get_compounds') {
        $query = "SELECT * FROM compounds ORDER BY name ASC";
        $result = mysqli_query($conn, $query);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) { $data[] = $row; }
        echo json_encode(['success' => true, 'data' => $data]);
        exit();
    }
}

// POST requests require admin session
if ($method === 'POST') {
    if (!isset($_SESSION['admin_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit();
    }

    if ($action === 'add_category') {
        $name = trim($_POST['name'] ?? '');
        $type = trim($_POST['type'] ?? 'family'); // 'family' or 'medicinal_use'
        
        if (empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Name is required']);
            exit();
        }
        
        // Prevent duplicate
        $stmt = mysqli_prepare($conn, "INSERT IGNORE INTO categories (name, type) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $name, $type);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Category added']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add category']);
        }
        exit();
    }
    
    if ($action === 'add_compound') {
        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Name is required']);
            exit();
        }
        
        $stmt = mysqli_prepare($conn, "INSERT IGNORE INTO compounds (name) VALUES (?)");
        mysqli_stmt_bind_param($stmt, "s", $name);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Compound added']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add compound']);
        }
        exit();
    }
    
    if ($action === 'delete_category') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = mysqli_prepare($conn, "DELETE FROM categories WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        echo json_encode(['success' => true, 'message' => 'Deleted']);
        exit();
    }
    
    if ($action === 'delete_compound') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = mysqli_prepare($conn, "DELETE FROM compounds WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        echo json_encode(['success' => true, 'message' => 'Deleted']);
        exit();
    }
}
?>
