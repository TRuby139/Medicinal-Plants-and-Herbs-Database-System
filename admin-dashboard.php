<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Medicinal Plants Database</title>
    <meta name="description" content="Administrator dashboard for managing the Medicinal Plants and Herbs Database.">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <header class="site-header">
        <div class="container flex justify-between items-center flex-wrap">
            <div class="logo">
                <a href="admin-dashboard.php">Admin Dashboard</a>
            </div>
            <nav class="main-nav" id="mobile-nav">
                <ul class="flex gap-8 items-center">
                    <li><a href="admin-dashboard.php" class="active-link">Dashboard</a></li>
                    <li><a href="index.php">View Public Site</a></li>
                </ul>
            </nav>
            <div class="header-actions flex items-center gap-4">
                <a href="#" onclick="logout(event)" class="btn btn-outline">Logout</a>
                <button class="mobile-menu-btn" onclick="toggleMobileNav()">Menu</button>
            </div>
        </div>
    </header>

    <main class="container" style="padding: 40px 20px;">
        <div class="admin-tabs" style="display: flex; gap: 10px; margin-bottom: 30px; border-bottom: 1px solid var(--color-divider); padding-bottom: 10px;">
            <button class="tab-btn active" onclick="switchTab('plants')" style="background: none; border: none; font-size: 1.1rem; padding: 10px 20px; cursor: pointer; border-bottom: 3px solid var(--color-primary); font-weight: bold;">Plant Inventory</button>
            <button class="tab-btn" onclick="switchTab('categories')" style="background: none; border: none; font-size: 1.1rem; padding: 10px 20px; cursor: pointer; border-bottom: 3px solid transparent;">Categories</button>
            <button class="tab-btn" onclick="switchTab('compounds')" style="background: none; border: none; font-size: 1.1rem; padding: 10px 20px; cursor: pointer; border-bottom: 3px solid transparent;">Compounds</button>
        </div>

        <div id="plants-tab" class="tab-content active" style="display: block;">
            <div class="admin-header flex justify-between items-center" style="margin-bottom: 20px;">
                <h2>Plant Inventory</h2>
                <button class="btn btn-primary" data-modal-target="add-plant-modal">Add New Plant</button>
            </div>

        <div class="admin-toolbar flex gap-4" style="margin-bottom: 20px; flex-wrap: wrap;">
            <input type="text" placeholder="Search inventory..." style="flex: 1; min-width: 200px; padding: 10px; border-radius: var(--radius); border: 1px solid var(--color-divider);">
            <select style="width: auto; padding: 10px; border-radius: var(--radius); border: 1px solid var(--color-divider);">
                <option value="">All Statuses</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>
            <button class="btn btn-outline">Filter</button>
        </div>

        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Common Name</th>
                        <th>Botanical Name</th>
                        <th>Family</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="admin-plants-tbody">
                    <!-- Loaded dynamically -->
                </tbody>
            </table>
        </div> <!-- End of plants-tab -->

        <div id="categories-tab" class="tab-content" style="display: none;">
            <div class="admin-header flex justify-between items-center" style="margin-bottom: 20px;">
                <h2>Categories (Families)</h2>
                <button class="btn btn-primary" data-modal-target="add-category-modal">Add New Category</button>
            </div>
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Family Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="admin-categories-tbody">
                        <!-- Loaded dynamically -->
                    </tbody>
                </table>
            </div>
        </div> <!-- End of categories-tab -->

        <div id="compounds-tab" class="tab-content" style="display: none;">
            <div class="admin-header flex justify-between items-center" style="margin-bottom: 20px;">
                <h2>Active Compounds</h2>
                <button class="btn btn-primary" data-modal-target="add-compound-modal">Add New Compound</button>
            </div>
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Compound Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="admin-compounds-tbody">
                        <!-- Loaded dynamically -->
                    </tbody>
                </table>
            </div>
        </div> <!-- End of compounds-tab -->
    </main>

    <!-- Add Category Modal -->
    <div id="add-category-modal" class="modal-overlay">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3>Category Details</h3>
                <button class="close-btn close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="add-category-form">
                    <div class="form-group">
                        <label for="category-name">Family Name <span style="color: red;">*</span></label>
                        <input type="text" id="category-name" name="category-name">
                        <span class="error-msg" style="color: red; font-size: 0.85rem; display: none;"></span>
                    </div>
                    <div class="form-group">
                        <label for="category-desc">Description</label>
                        <textarea id="category-desc" name="category-desc" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline close-modal">Cancel</button>
                <button class="btn btn-primary" onclick="submitCategoryForm(event)">Save Category</button>
            </div>
        </div>
    </div>

    <!-- Add Compound Modal -->
    <div id="add-compound-modal" class="modal-overlay">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h3>Compound Details</h3>
                <button class="close-btn close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="add-compound-form">
                    <div class="form-group">
                        <label for="compound-name">Compound Name <span style="color: red;">*</span></label>
                        <input type="text" id="compound-name" name="compound-name">
                        <span class="error-msg" style="color: red; font-size: 0.85rem; display: none;"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline close-modal">Cancel</button>
                <button class="btn btn-primary" onclick="submitCompoundForm(event)">Save Compound</button>
            </div>
        </div>
    </div>    <!-- Add/Edit Plant Modal -->
    <div id="add-plant-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Plant Details</h3>
                <button class="close-btn close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="plant-form">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="common-name">Common Name <span style="color: red;">*</span></label>
                            <input type="hidden" id="plant-id" name="id" value="0">
                            <input type="text" id="common-name" name="common-name" required>
                            <span class="error-msg" id="common-name-error" style="color: red; font-size: 0.85rem; display: none;"></span>
                        </div>
                        <div class="form-group">
                            <label for="botanical-name">Botanical Name <span style="color: red;">*</span></label>
                            <input type="text" id="botanical-name" name="botanical-name" required>
                            <span class="error-msg" id="botanical-name-error" style="color: red; font-size: 0.85rem; display: none;"></span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="family">Family</label>
                            <input type="text" id="family" name="family">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="uses">Uses (comma separated)</label>
                        <input type="text" id="uses" name="uses">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label for="habitat">Habitat/Origin</label>
                            <input type="text" id="habitat" name="habitat">
                        </div>
                        <div class="form-group">
                            <label for="compounds">Active Compounds (comma separated)</label>
                            <input type="text" id="compounds" name="compounds">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="preparation">Preparation Methods & Dosages</label>
                        <textarea id="preparation" name="preparation" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="precautions">Precautions</label>
                        <textarea id="precautions" name="precautions" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Plant Image</label>
                        <div class="image-upload-container">
                            <input type="file" id="plant-image" name="plant-image" accept="image/*">
                            <div class="upload-text">
                                <p>Drag and drop an image here or click to select</p>
                                <p style="font-size: 0.85rem; color: var(--color-text-light);">(Max size: 2MB)</p>
                            </div>
                            <!-- Image preview will be injected here by JS -->
                        </div>
                        <span class="error-msg" id="plant-image-error" style="color: red; font-size: 0.85rem; display: none;"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline close-modal">Cancel</button>
                <button class="btn btn-primary" onclick="submitPlantForm(event)">Save Plant</button>
            </div>
        </div>
    </div>

    <!-- Toast Notifications Container -->
    <div id="toast-container" class="toast-container"></div>

    <script src="assets/js/main.js"></script>
    <script>
        function logout(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('action', 'logout');
            fetch('api/auth.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                window.location.href = 'login.php';
            });
        }
    </script>
</body>
</html>
