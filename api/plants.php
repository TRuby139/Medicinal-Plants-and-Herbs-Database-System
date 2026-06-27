<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

// Handle GET requests (Fetching plants, categories, compounds)
if ($method === 'GET') {
    $action = $_GET['action'] ?? 'get_plants';

    if ($action === 'get_plants') {
        // Optional filters
        $search = $_GET['search'] ?? '';
        $category_id = $_GET['category_id'] ?? '';
        $compound_id = $_GET['compound_id'] ?? '';

        $query = "SELECT p.* FROM plants p ";
        $joins = "";
        $where = " WHERE 1=1 ";
        $params = [];
        $types = "";

        if (!empty($category_id)) {
            $joins .= " JOIN plant_category pc ON p.id = pc.plant_id ";
            $where .= " AND pc.category_id = ? ";
            $params[] = $category_id;
            $types .= "i";
        }

        if (!empty($compound_id)) {
            $joins .= " JOIN plant_compound pco ON p.id = pco.plant_id ";
            $where .= " AND pco.compound_id = ? ";
            $params[] = $compound_id;
            $types .= "i";
        }

        if (!empty($search)) {
            $where .= " AND (p.common_name LIKE ? OR p.botanical_name LIKE ?) ";
            $searchTerm = "%" . $search . "%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= "ss";
        }

        $query .= $joins . $where . " ORDER BY p.common_name ASC";
        
        $stmt = mysqli_prepare($conn, $query);
        
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $plants = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $plants[] = $row;
        }
        
        echo json_encode(['success' => true, 'data' => $plants]);
        exit();
    }
    
    // Can add other GET actions like 'get_categories' or 'get_compounds' here
}

// Handle POST requests (Adding/Editing/Deleting - Requires Admin Session)
if ($method === 'POST') {
    // Security check: Must be logged in as admin
    if (!isset($_SESSION['admin_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized access. Please login.']);
        exit();
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'add_plant') {
        $common_name = trim($_POST['common_name'] ?? '');
        $botanical_name = trim($_POST['botanical_name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        if (empty($common_name) || empty($botanical_name)) {
            echo json_encode(['success' => false, 'message' => 'Common and botanical names are required.']);
            exit();
        }

        $image_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../assets/images/plants/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $file_name = time() . '_' . basename($_FILES['image']['name']);
            $target_file = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = 'assets/images/plants/' . $file_name;
            }
        }

        $stmt = mysqli_prepare($conn, "INSERT INTO plants (common_name, botanical_name, description, image_path) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $common_name, $botanical_name, $description, $image_path);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message' => 'Plant added successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add plant.']);
        }
        mysqli_stmt_close($stmt);
    }
}
?>
