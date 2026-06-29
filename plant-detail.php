<?php
require_once 'config/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: index.php");
    exit();
}

$stmt = mysqli_prepare($conn, "SELECT * FROM plants WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$plant = mysqli_fetch_assoc($result);

if (!$plant) {
    // 404
    header("HTTP/1.0 404 Not Found");
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Plant Not Found - Botanica</title>
        <link rel="stylesheet" href="assets/css/index.css">
    </head>
    <body class="detail-page-body flex flex-col items-center justify-center" style="min-height: 100vh; text-align: center; padding: 20px;">
        <div class="404-container" style="background: rgba(255,255,255,0.9); padding: 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); backdrop-filter: blur(10px); max-width: 500px; width: 100%;">
            <h1 style="font-size: 4rem; color: var(--color-primary); margin-bottom: 10px;">404</h1>
            <h2 style="margin-bottom: 20px;">Oops! Plant not found</h2>
            <p style="margin-bottom: 30px; color: var(--color-text-light);">We searched our entire database, but couldn't find the botanical specimen you're looking for.</p>
            <a href='index.php' class='btn btn-primary' style="display: inline-block;">&larr; Return to Catalogue</a>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// Fetch Family
$family = '';
$f_res = mysqli_query($conn, "SELECT c.name FROM categories c JOIN plant_category pc ON c.id=pc.category_id WHERE pc.plant_id=$id AND c.type='family' LIMIT 1");
if ($f_row = mysqli_fetch_assoc($f_res)) $family = $f_row['name'];

// Fetch Uses
$uses_res = mysqli_query($conn, "SELECT c.name FROM categories c JOIN plant_category pc ON c.id=pc.category_id WHERE pc.plant_id=$id AND c.type='medicinal_use'");
$uses = [];
while($r = mysqli_fetch_assoc($uses_res)) $uses[] = $r['name'];

// Fetch Compounds
$comp_res = mysqli_query($conn, "SELECT c.name FROM compounds c JOIN plant_compound pc ON c.id=pc.compound_id WHERE pc.plant_id=$id");
$compounds = [];
while($r = mysqli_fetch_assoc($comp_res)) $compounds[] = $r['name'];

$image_path = $plant['image_path'] ? $plant['image_path'] : 'assets/images/Not_uploaded.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($plant['common_name']) ?> (<?= htmlspecialchars($plant['botanical_name']) ?>) - Botanica</title>
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body class="detail-page-body">
    <header class="site-header">
        <div class="container flex justify-between items-center flex-wrap">
            <div class="logo">
                <a href="index.php">Botanica</a>
            </div>
            <nav class="main-nav" id="mobile-nav">
                <ul class="flex gap-8 items-center">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                </ul>
            </nav>
            <div class="header-actions flex items-center gap-4">
                <a href="login.php" class="btn btn-primary">Admin Login</a>
                <button class="mobile-menu-btn" onclick="toggleMobileNav()">Menu</button>
            </div>
        </div>
    </header>

    <main class="detail-main">
        <div class="detail-split-layout">
            <!-- Left Side: Image -->
            <div class="detail-image-side">
                <img src="<?= htmlspecialchars($image_path) ?>" alt="<?= htmlspecialchars($plant['common_name']) ?>">
            </div>

            <!-- Right Side: Content -->
            <div class="detail-content-side">
                <a href="index.php" class="back-link">&larr; Back to Catalogue</a>
                
                <div class="plant-header">
                    <h1 class="botanical-name-large"><?= htmlspecialchars($plant['botanical_name']) ?></h1>
                    <h2 class="common-name-large"><?= htmlspecialchars($plant['common_name']) ?></h2>
                    <div class="badges">
                        <?php if($family): ?>
                            <span class="badge family-badge">Family: <?= htmlspecialchars($family) ?></span>
                        <?php endif; ?>
                        
                        <?php foreach($compounds as $c): ?>
                            <span class="badge compound-badge"><?= htmlspecialchars($c) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="plant-description">
                    <p><?= nl2br(htmlspecialchars($plant['description'] ?? 'No description available.')) ?></p>
                    
                    <?php if($plant['habitat']): ?>
                    <p style="margin-top: 15px;"><strong>Habitat/Origin:</strong> <?= htmlspecialchars($plant['habitat']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Tabs Section -->
                <div class="tabs-container">
                    <div class="tabs-header">
                        <button class="tab-btn active" data-tab="uses">Medicinal Uses</button>
                        <button class="tab-btn" data-tab="preparation">Preparation</button>
                        <button class="tab-btn" data-tab="precautions">Precautions</button>
                    </div>

                    <div class="tabs-content">
                        <div id="uses" class="tab-pane active">
                            <h3>Medicinal Uses</h3>
                            <?php if(!empty($uses)): ?>
                            <ul>
                                <?php foreach($uses as $use): ?>
                                    <li><?= htmlspecialchars($use) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php else: ?>
                            <p>No medicinal uses listed.</p>
                            <?php endif; ?>
                        </div>

                        <div id="preparation" class="tab-pane">
                            <h3>Preparation & Dosage</h3>
                            <?php if($plant['preparation_methods'] || $plant['dosages']): ?>
                            <ul>
                                <?php if($plant['preparation_methods']): ?>
                                <li><strong>Methods:</strong> <?= htmlspecialchars($plant['preparation_methods']) ?></li>
                                <?php endif; ?>
                                <?php if($plant['dosages']): ?>
                                <li><strong>Dosage:</strong> <?= htmlspecialchars($plant['dosages']) ?></li>
                                <?php endif; ?>
                            </ul>
                            <?php else: ?>
                            <p>No preparation information listed.</p>
                            <?php endif; ?>
                        </div>

                        <div id="precautions" class="tab-pane">
                            <h3>Precautions & Side Effects</h3>
                            <?php if($plant['precautions']): ?>
                            <p><?= nl2br(htmlspecialchars($plant['precautions'])) ?></p>
                            <?php else: ?>
                            <p>No precautions listed.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </main>

    <script src="assets/js/main.js"></script>
</body>
</html>
