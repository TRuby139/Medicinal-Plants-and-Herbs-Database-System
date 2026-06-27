<?php
session_start();
// Redirect to dashboard if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: admin-dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Medicinal Plants Database</title>
    <meta name="description" content="Administrator login for the Medicinal Plants and Herbs Database System.">
    <link rel="stylesheet" href="assets/css/index.css">
    <style>
        .alert { padding: 10px; border-radius: 4px; margin-bottom: 15px; display: none; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body class="login-body">
    <main class="login-card">
        <form id="login-form">
            <h2>Admin Access</h2>
            <div id="login-alert" class="alert alert-error"></div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="hidden" name="action" value="login">
            <button type="submit" class="btn btn-primary btn-full">Login</button>
            <div class="text-center" style="margin-top: 20px;">
                <a href="index.php" class="back-link">&larr; Back to Public Catalogue</a>
            </div>
        </form>
    </main>

    <script>
        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const alertBox = document.getElementById('login-alert');
            
            fetch('api/auth.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'admin-dashboard.php';
                } else {
                    alertBox.textContent = data.message;
                    alertBox.style.display = 'block';
                }
            })
            .catch(error => {
                alertBox.textContent = 'An error occurred during login.';
                alertBox.style.display = 'block';
            });
        });
    </script>
</body>
</html>
