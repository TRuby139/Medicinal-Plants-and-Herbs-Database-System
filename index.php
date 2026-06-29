<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue - Medicinal Plants and Herbs</title>
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <header class="site-header">
        <div class="container flex justify-between items-center flex-wrap">
            <div class="logo">
                <a href="index.php">Botanica</a>
            </div>
            <nav class="main-nav" id="mobile-nav">
                <ul class="flex gap-8 items-center">
                    <li><a href="index.php" class="active-link">Home</a></li>
                    <li><a href="about.php">About</a></li>
                </ul>
            </nav>
            <div class="header-actions flex items-center gap-4">
                <a href="login.php" class="btn btn-primary">Admin Login</a>
                <button class="mobile-menu-btn" onclick="toggleMobileNav()">Menu</button>
            </div>
        </div>
    </header>

    <main>
        <section class="hero flex items-center justify-center flex-col">
            <div class="hero-content text-center">
                <h1>Medicinal Plants & Herbs</h1>
                <p>Discover nature's remedies through our comprehensive apothecary database.</p>
                <div class="search-bar">
                    <input type="text" placeholder="Search by common or botanical name..." id="searchInput">
                    <button class="btn btn-primary">Search</button>
                </div>
            </div>
        </section>

        <section class="catalogue-section container">
            <div class="layout-grid">
                <aside class="sidebar" style="display: flex; flex-direction: column; gap: 15px;">
                    <h3>Filters</h3>
                    
                    <details class="filter-group">
                        <summary class="btn btn-outline" style="width: 100%; text-align: left; border-radius: 4px; box-sizing: border-box; margin-bottom: 0;">Plant Family</summary>
                        <ul class="filter-list" id="filter-family-list" style="margin-top: 10px;">
                            <li><small>Loading...</small></li>
                        </ul>
                    </details>
                    
                    <details class="filter-group">
                        <summary class="btn btn-outline" style="width: 100%; text-align: left; border-radius: 4px; box-sizing: border-box; margin-bottom: 0;">Medicinal Use</summary>
                        <ul class="filter-list" id="filter-uses-list" style="margin-top: 10px;">
                            <li><small>Loading...</small></li>
                        </ul>
                    </details>

                    <details class="filter-group">
                        <summary class="btn btn-outline" style="width: 100%; text-align: left; border-radius: 4px; box-sizing: border-box; margin-bottom: 0;">Compounds</summary>
                        <ul class="filter-list" id="filter-compounds-list" style="margin-top: 10px;">
                            <li><small>Loading...</small></li>
                        </ul>
                    </details>

                    <button class="btn btn-primary btn-full" id="clear-filters-btn" style="margin-top: 10px;">Clear Filters</button>
                </aside>

                <div class="main-content">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2>Plant Catalogue</h2>
                        <select id="sortOrder" class="btn btn-outline" style="border-radius: 4px; padding: 5px 10px; border-color: #ddd; background: transparent; cursor: pointer;">
                            <option value="alpha_asc">Alphabetical (A-Z)</option>
                            <option value="date_desc">Newest First</option>
                        </select>
                    </div>
                    <div class="plant-grid" id="plant-grid-container">
                        <!-- Plant cards will be loaded here via JavaScript fetch -->
                        <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                            <p>Loading catalogue...</p>
                        </div>
                    </div>
                    
                    <!-- Pagination (Displays when results > 6) -->
                    <div class="pagination flex justify-center items-center gap-2" id="pagination-container" style="display: none;">
                        <button class="page-btn" disabled>&laquo;</button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">&raquo;</button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container text-center">
            <p>&copy; 2026 Medicinal Plants and Herbs Database System. All rights reserved.</p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
