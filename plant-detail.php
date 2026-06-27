<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peppermint (Mentha &times; piperita) - Botanica</title>
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
                <img src="https://images.unsplash.com/photo-1628258334105-2a0b3d6efee1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Peppermint Plant">
            </div>

            <!-- Right Side: Content -->
            <div class="detail-content-side">
                <a href="index.php" class="back-link">&larr; Back to Catalogue</a>
                
                <div class="plant-header">
                    <h1 class="botanical-name-large">Mentha &times; piperita</h1>
                    <h2 class="common-name-large">Peppermint</h2>
                    <div class="badges">
                        <span class="badge family-badge">Family: Lamiaceae</span>
                        <span class="badge compound-badge">Menthol</span>
                    </div>
                </div>

                <div class="plant-description">
                    <p>Peppermint is a hybrid mint, a cross between watermint and spearmint. Indigenous to Europe and the Middle East, the plant is now widely spread and cultivated in many regions of the world. It is known for its high menthol content, giving it a strong, sweet, and refreshing aroma and taste.</p>
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
                            <ul>
                                <li><strong>Digestive Aid:</strong> Relieves symptoms of irritable bowel syndrome (IBS), including indigestion, gas, and bloating.</li>
                                <li><strong>Headache Relief:</strong> Topical application of peppermint oil can alleviate tension headaches.</li>
                                <li><strong>Respiratory Health:</strong> Menthol acts as a decongestant, helping to clear the respiratory tract.</li>
                                <li><strong>Antimicrobial:</strong> Demonstrates mild antibacterial and antiviral properties.</li>
                            </ul>
                        </div>

                        <div id="preparation" class="tab-pane">
                            <h3>Preparation & Dosage</h3>
                            <ul>
                                <li><strong>Infusion (Tea):</strong> Pour 1 cup of boiling water over 1-2 teaspoons of dried leaves. Steep for 5-10 minutes. Drink 2-3 times daily.</li>
                                <li><strong>Essential Oil:</strong> Use topically (diluted with a carrier oil) for headaches or muscle pain. Inhale for respiratory issues. <em>Do not ingest essential oil without professional supervision.</em></li>
                                <li><strong>Tincture:</strong> 2-3 ml (40-60 drops) three times a day.</li>
                            </ul>
                        </div>

                        <div id="precautions" class="tab-pane">
                            <h3>Precautions & Side Effects</h3>
                            <ul>
                                <li><strong>Gastroesophageal Reflux Disease (GERD):</strong> Peppermint can relax the sphincter between the stomach and esophagus, potentially worsening heartburn.</li>
                                <li><strong>Gallbladder Issues:</strong> Avoid if you have gallstones or inflammation of the gallbladder.</li>
                                <li><strong>Infants & Small Children:</strong> Do not apply peppermint oil to the faces or chests of infants/children, as menthol can cause breathing spasms.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </main>

    <script src="assets/js/main.js"></script>
</body>
</html>
