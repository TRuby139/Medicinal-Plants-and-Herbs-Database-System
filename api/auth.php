<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Determine action (login or logout)
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Username and password are required.']);
            exit();
        }

        // Use prepared statement to prevent SQL injection
        $stmt = mysqli_prepare($conn, "SELECT id, password_hash FROM admins WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Verify password
            if (password_verify($password, $row['password_hash'])) {
                // Password is correct, create session
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_username'] = $username;
                echo json_encode(['success' => true, 'message' => 'Login successful.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
        }
        
        mysqli_stmt_close($stmt);

    } elseif ($action === 'logout') {
        session_unset();
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Logout successful.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    }
} else {
    // Only accept POST requests for auth actions
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
