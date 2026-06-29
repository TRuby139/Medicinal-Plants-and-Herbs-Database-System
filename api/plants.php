<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

// Helper functions for tags
function getOrInsertCategory($conn, $name, $type) {
    $name = ucfirst(strtolower(trim($name, " \t\n\r\0\x0B.")));
    $stmt = mysqli_prepare($conn, "SELECT id FROM categories WHERE name = ? AND type = ?");
    mysqli_stmt_bind_param($stmt, "ss", $name, $type);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) return $row['id'];
    
    $stmt2 = mysqli_prepare($conn, "INSERT INTO categories (name, type) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt2, "ss", $name, $type);
    mysqli_stmt_execute($stmt2);
    return mysqli_insert_id($conn);
}
function getOrInsertCompound($conn, $name) {
    $name = ucfirst(strtolower(trim($name, " \t\n\r\0\x0B.")));
    $stmt = mysqli_prepare($conn, "SELECT id FROM compounds WHERE name = ?");
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) return $row['id'];
    
    $stmt2 = mysqli_prepare($conn, "INSERT INTO compounds (name) VALUES (?)");
    mysqli_stmt_bind_param($stmt2, "s", $name);
    mysqli_stmt_execute($stmt2);
    return mysqli_insert_id($conn);
}
function processTags($conn, $plant_id, $family_str, $uses_str, $compounds_str) {
    mysqli_query($conn, "DELETE FROM plant_category WHERE plant_id = $plant_id");
    mysqli_query($conn, "DELETE FROM plant_compound WHERE plant_id = $plant_id");
    
    if (!empty($family_str)) {
        $family = trim($family_str);
        $cat_id = getOrInsertCategory($conn, $family, 'family');
        mysqli_query($conn, "INSERT IGNORE INTO plant_category (plant_id, category_id) VALUES ($plant_id, $cat_id)");
    }
    if (!empty($uses_str)) {
        $uses = array_map('trim', explode(',', $uses_str));
        foreach($uses as $use) {
            if (empty($use)) continue;
            $cat_id = getOrInsertCategory($conn, $use, 'medicinal_use');
            mysqli_query($conn, "INSERT IGNORE INTO plant_category (plant_id, category_id) VALUES ($plant_id, $cat_id)");
        }
    }
    if (!empty($compounds_str)) {
        $comps = array_map('trim', explode(',', $compounds_str));
        foreach($comps as $comp) {
            if (empty($comp)) continue;
            $comp_id = getOrInsertCompound($conn, $comp);
            mysqli_query($conn, "INSERT IGNORE INTO plant_compound (plant_id, compound_id) VALUES ($plant_id, $comp_id)");
        }
    }
}

function handleImageUpload($fileArr) {
    global $upload_debug;
    $upload_debug = [];
    
    if (!isset($fileArr) || $fileArr['error'] !== UPLOAD_ERR_OK) {
        $upload_debug['fail'] = 'error_check';
        $upload_debug['error_val'] = $fileArr['error'] ?? 'not set';
        return null;
    }
    if ($fileArr['size'] > 10485760) { // 10MB limit
        $upload_debug['fail'] = 'size_check';
        return null;
    }
    $upload_debug['step1'] = 'error_check passed';
    
    $image_info = getimagesize($fileArr['tmp_name']);
    if ($image_info === false) {
        $upload_debug['fail'] = 'getimagesize';
        $upload_debug['tmp_name'] = $fileArr['tmp_name'];
        $upload_debug['tmp_exists'] = file_exists($fileArr['tmp_name']);
        return null;
    }
    $upload_debug['step2'] = 'getimagesize passed, type=' . $image_info[2];
    
    // If getimagesize() succeeded, the file is a valid image — accept all common formats
    // Type IDs: 1=GIF, 2=JPEG, 3=PNG, 6=BMP, 15=WBMP, 16=XBM, 17=ICO, 18=WEBP, 19=AVIF
    $allowed_types = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP, IMAGETYPE_GIF, IMAGETYPE_BMP, IMAGETYPE_AVIF];
    if (!in_array($image_info[2], $allowed_types)) {
        $upload_debug['fail'] = 'type_check';
        $upload_debug['detected_type'] = $image_info[2];
        return null;
    }
    $upload_debug['step3'] = 'type_check passed';
    
    $upload_dir = '../assets/images/plants/';
    if (!is_dir($upload_dir)) {
        $made = mkdir($upload_dir, 0755, true);
        $upload_debug['mkdir'] = $made;
    }
    $upload_debug['dir_exists'] = is_dir($upload_dir);
    $upload_debug['dir_writable'] = is_writable($upload_dir);
    $upload_debug['dir_realpath'] = realpath($upload_dir);
    
    $file_name = time() . '_' . preg_replace("/[^a-zA-Z0-9.-]/", "_", basename($fileArr['name']));
    $target_file = $upload_dir . $file_name;
    $upload_debug['target_file'] = $target_file;
    
    if (move_uploaded_file($fileArr['tmp_name'], $target_file)) {
        $upload_debug['step4'] = 'move succeeded';
        return 'assets/images/plants/' . $file_name;
    }
    $upload_debug['fail'] = 'move_uploaded_file';
    $upload_debug['php_last_error'] = error_get_last();
    return null;
}

if ($method === 'GET') {
    $action = $_GET['action'] ?? 'get_plants';

    if ($action === 'get_plants') {
        $search = $_GET['search'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = max(1, (int)($_GET['limit'] ?? 6));
        $offset = ($page - 1) * $limit;

        $joins = "";
        $where = " WHERE 1=1 ";
        $params = [];
        $types = "";

        if (!empty($_GET['category_id'])) {
            $cats = is_array($_GET['category_id']) ? $_GET['category_id'] : [$_GET['category_id']];
            $joins .= " JOIN plant_category pc ON p.id = pc.plant_id ";
            $in = str_repeat('?,', count($cats) - 1) . '?';
            $where .= " AND pc.category_id IN ($in) ";
            foreach($cats as $c) { $params[] = $c; $types .= "i"; }
        }

        if (!empty($_GET['compound_id'])) {
            $comps = is_array($_GET['compound_id']) ? $_GET['compound_id'] : [$_GET['compound_id']];
            $joins .= " JOIN plant_compound pco ON p.id = pco.plant_id ";
            $in = str_repeat('?,', count($comps) - 1) . '?';
            $where .= " AND pco.compound_id IN ($in) ";
            foreach($comps as $c) { $params[] = $c; $types .= "i"; }
        }

        if (!empty($search)) {
            $where .= " AND (p.common_name LIKE ? OR p.botanical_name LIKE ?) ";
            $searchTerm = "%" . $search . "%";
            $params[] = $searchTerm; $params[] = $searchTerm;
            $types .= "ss";
        }

        $sort = $_GET['sort'] ?? 'alpha_asc';
        $orderBy = " ORDER BY p.common_name ASC";
        if ($sort === 'date_desc') {
            $orderBy = " ORDER BY p.id DESC";
        }

        // Get total count for pagination
        $countQuery = "SELECT COUNT(DISTINCT p.id) as total FROM plants p " . $joins . $where;
        $countStmt = mysqli_prepare($conn, $countQuery);
        if (!empty($params)) mysqli_stmt_bind_param($countStmt, $types, ...$params);
        mysqli_stmt_execute($countStmt);
        $total_rows = mysqli_fetch_assoc(mysqli_stmt_get_result($countStmt))['total'];
        $total_pages = ceil($total_rows / $limit);

        // Get paginated data
        $query = "SELECT DISTINCT p.* FROM plants p " . $joins . $where . $orderBy . " LIMIT ?, ?";
        $params[] = $offset; $params[] = $limit;
        $types .= "ii";
        
        $stmt = mysqli_prepare($conn, $query);
        if (!empty($params)) mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $plants = [];
        while ($row = mysqli_fetch_assoc($result)) {
            // Also fetch basic tags for admin list display
            $id = $row['id'];
            $f_res = mysqli_query($conn, "SELECT c.name FROM categories c JOIN plant_category pc ON c.id=pc.category_id WHERE pc.plant_id=$id AND c.type='family' LIMIT 1");
            $row['family'] = ($f_row = mysqli_fetch_assoc($f_res)) ? $f_row['name'] : '';
            $plants[] = $row;
        }
        
        echo json_encode(['success' => true, 'data' => $plants, 'total_pages' => $total_pages, 'current_page' => $page]);
        exit();
    }
}

if ($method === 'POST') {
    if (!isset($_SESSION['admin_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit();
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'add_plant' || $action === 'edit_plant') {
        $id = (int)($_POST['id'] ?? 0);
        $common_name = trim(htmlspecialchars($_POST['common-name'] ?? $_POST['common_name'] ?? ''));
        $botanical_name = trim(htmlspecialchars($_POST['botanical-name'] ?? $_POST['botanical_name'] ?? ''));
        $description = trim(htmlspecialchars($_POST['description'] ?? ''));
        $habitat = trim(htmlspecialchars($_POST['habitat'] ?? ''));
        $preparation = trim(htmlspecialchars($_POST['preparation'] ?? ''));
        $dosages = trim(htmlspecialchars($_POST['dosages'] ?? ''));
        $precautions = trim(htmlspecialchars($_POST['precautions'] ?? ''));
        
        // Tags
        $family = trim(htmlspecialchars($_POST['family'] ?? ''));
        $uses = trim(htmlspecialchars($_POST['uses'] ?? ''));
        $compounds = trim(htmlspecialchars($_POST['compounds'] ?? ''));

        if (strlen($common_name) < 2 || strlen($botanical_name) < 2) {
            echo json_encode(['success' => false, 'message' => 'Common and Botanical names must be at least 2 characters.']);
            exit();
        }

        $image_path = handleImageUpload($_FILES['plant-image'] ?? $_FILES['image'] ?? null);
        if (isset($_FILES['plant-image']) && $_FILES['plant-image']['error'] === UPLOAD_ERR_OK && !$image_path) {
            echo json_encode(['success' => false, 'message' => 'Invalid image format or file exceeds 10MB limit.']);
            exit();
        }
        
        // Temporary debug — will remove after fixing
        global $upload_debug;
        $debug_info = [
            'files_keys' => array_keys($_FILES),
            'has_plant_image' => isset($_FILES['plant-image']),
            'image_path_result' => $image_path,
            'upload_steps' => $upload_debug
        ];
        if (isset($_FILES['plant-image'])) {
            $debug_info['file_error'] = $_FILES['plant-image']['error'];
            $debug_info['file_size'] = $_FILES['plant-image']['size'];
            $debug_info['file_name'] = $_FILES['plant-image']['name'];
        }

        if ($action === 'add_plant') {
            $stmt = mysqli_prepare($conn, "INSERT INTO plants (common_name, botanical_name, habitat, description, preparation_methods, dosages, precautions, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssssssss", $common_name, $botanical_name, $habitat, $description, $preparation, $dosages, $precautions, $image_path);
            if (mysqli_stmt_execute($stmt)) {
                $plant_id = mysqli_insert_id($conn);
                processTags($conn, $plant_id, $family, $uses, $compounds);
                echo json_encode(['success' => true, 'message' => 'Plant added', 'debug' => $debug_info]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add plant']);
            }
        } else {
            // Edit
            if ($image_path) {
                $stmt = mysqli_prepare($conn, "UPDATE plants SET common_name=?, botanical_name=?, habitat=?, description=?, preparation_methods=?, dosages=?, precautions=?, image_path=? WHERE id=?");
                mysqli_stmt_bind_param($stmt, "ssssssssi", $common_name, $botanical_name, $habitat, $description, $preparation, $dosages, $precautions, $image_path, $id);
            } else {
                $stmt = mysqli_prepare($conn, "UPDATE plants SET common_name=?, botanical_name=?, habitat=?, description=?, preparation_methods=?, dosages=?, precautions=? WHERE id=?");
                mysqli_stmt_bind_param($stmt, "sssssssi", $common_name, $botanical_name, $habitat, $description, $preparation, $dosages, $precautions, $id);
            }
            if (mysqli_stmt_execute($stmt)) {
                processTags($conn, $id, $family, $uses, $compounds);
                echo json_encode(['success' => true, 'message' => 'Plant updated', 'debug' => $debug_info]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update plant']);
            }
        }
        exit();
    }
    
    if ($action === 'delete_plant') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = mysqli_prepare($conn, "DELETE FROM plants WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        if(mysqli_stmt_execute($stmt)) {
             echo json_encode(['success' => true, 'message' => 'Deleted']);
        } else {
             echo json_encode(['success' => false, 'message' => 'Failed to delete']);
        }
        exit();
    }
    
    // single fetch for edit form
    if ($action === 'get_plant') {
        $id = (int)($_POST['id'] ?? 0);
        $res = mysqli_query($conn, "SELECT * FROM plants WHERE id=$id");
        if ($plant = mysqli_fetch_assoc($res)) {
            // fetch tags as strings
            $f_res = mysqli_query($conn, "SELECT c.name FROM categories c JOIN plant_category pc ON c.id=pc.category_id WHERE pc.plant_id=$id AND c.type='family' LIMIT 1");
            $plant['family'] = ($r = mysqli_fetch_assoc($f_res)) ? $r['name'] : '';
            
            $u_res = mysqli_query($conn, "SELECT c.name FROM categories c JOIN plant_category pc ON c.id=pc.category_id WHERE pc.plant_id=$id AND c.type='medicinal_use'");
            $u_arr = []; while($r = mysqli_fetch_assoc($u_res)) $u_arr[] = $r['name'];
            $plant['uses'] = implode(', ', $u_arr);
            
            $c_res = mysqli_query($conn, "SELECT c.name FROM compounds c JOIN plant_compound pc ON c.id=pc.compound_id WHERE pc.plant_id=$id");
            $c_arr = []; while($r = mysqli_fetch_assoc($c_res)) $c_arr[] = $r['name'];
            $plant['compounds'] = implode(', ', $c_arr);
            
            echo json_encode(['success'=>true, 'data'=>$plant]);
        } else {
            echo json_encode(['success'=>false]);
        }
        exit();
    }
}
?>
