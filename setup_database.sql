-- Medicinal Plants and Herbs Database System Schema
-- Run this script to set up the database and default admin user

CREATE DATABASE IF NOT EXISTS medicinal_plants_db;
USE medicinal_plants_db;

-- Admins Table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin user (password: admin123)
-- The password hash was generated using password_hash('admin123', PASSWORD_DEFAULT)
INSERT IGNORE INTO admins (username, password_hash) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Plants Table
CREATE TABLE IF NOT EXISTS plants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    common_name VARCHAR(100) NOT NULL,
    botanical_name VARCHAR(100) NOT NULL,
    habitat VARCHAR(100),
    description TEXT,
    preparation_methods TEXT,
    dosages TEXT,
    precautions TEXT,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories Table (Types: 'family', 'medicinal_use')
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('family', 'medicinal_use') NOT NULL,
    UNIQUE KEY unique_name_type (name, type)
);

-- Compounds Table
CREATE TABLE IF NOT EXISTS compounds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Junction Table: Plants to Categories
CREATE TABLE IF NOT EXISTS plant_category (
    plant_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (plant_id, category_id),
    FOREIGN KEY (plant_id) REFERENCES plants(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Junction Table: Plants to Compounds
CREATE TABLE IF NOT EXISTS plant_compound (
    plant_id INT NOT NULL,
    compound_id INT NOT NULL,
    PRIMARY KEY (plant_id, compound_id),
    FOREIGN KEY (plant_id) REFERENCES plants(id) ON DELETE CASCADE,
    FOREIGN KEY (compound_id) REFERENCES compounds(id) ON DELETE CASCADE
);

-- ==========================================
-- Data Seeding Script (Initial Dummy Data)
-- ==========================================

-- Seed Categories
INSERT IGNORE INTO categories (id, name, type) VALUES 
(1, 'Lamiaceae', 'family'),
(2, 'Asteraceae', 'family'),
(3, 'Asphodelaceae', 'family'),
(4, 'Zingiberaceae', 'family'),
(5, 'Digestive', 'medicinal_use'),
(6, 'Anti-inflammatory', 'medicinal_use'),
(7, 'Respiratory', 'medicinal_use'),
(8, 'Sedative', 'medicinal_use'),
(9, 'Skin Care', 'medicinal_use');

-- Seed Compounds
INSERT IGNORE INTO compounds (id, name) VALUES 
(1, 'Menthol'),
(2, 'Rosmarinic Acid'),
(3, 'Curcumin'),
(4, 'Aloin'),
(5, 'Echinacoside');

-- Seed Plants
INSERT IGNORE INTO plants (id, common_name, botanical_name, habitat, description, preparation_methods, dosages, precautions, image_path) VALUES 
(1, 'Peppermint', 'Mentha piperita', 'Europe, Middle East', 'Peppermint is a hybrid mint known for its high menthol content, giving it a strong, sweet, and refreshing aroma.', 'Infusion (Tea): Pour 1 cup boiling water over 1-2 tsp dried leaves.', 'Drink 2-3 times daily.', 'Can worsen GERD or heartburn. Do not apply essential oil to infants faces.', 'https://images.unsplash.com/photo-1628258334105-2a0b3d6efee1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'),
(2, 'Lavender', 'Lavandula angustifolia', 'Mediterranean', 'A highly aromatic shrub known for its calming properties and fragrant purple flowers.', 'Infusion or essential oil.', '1-2 tsp dried flowers per cup of water.', 'May cause drowsiness.', 'https://images.unsplash.com/photo-1596649718428-c1780f2d93e7?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'),
(3, 'Aloe Vera', 'Aloe barbadensis', 'Arabian Peninsula', 'A succulent plant species that is widely used in cosmetics and alternative medicine.', 'Topical gel application.', 'Apply topically as needed.', 'Oral ingestion can cause cramping.', NULL),
(4, 'Turmeric', 'Curcuma longa', 'Indian subcontinent', 'A flowering plant of the ginger family, its roots are used in cooking and medicine.', 'Powder in food or capsules.', '500-2000mg per day.', 'High doses may cause stomach upset.', NULL);

-- Seed Junction Tables
-- Peppermint (Family: Lamiaceae. Uses: Digestive, Respiratory. Compounds: Menthol, Rosmarinic Acid)
INSERT IGNORE INTO plant_category (plant_id, category_id) VALUES (1, 1), (1, 5), (1, 7);
INSERT IGNORE INTO plant_compound (plant_id, compound_id) VALUES (1, 1), (1, 2);

-- Lavender (Family: Lamiaceae. Uses: Sedative. Compounds: Rosmarinic Acid)
INSERT IGNORE INTO plant_category (plant_id, category_id) VALUES (2, 1), (2, 8);
INSERT IGNORE INTO plant_compound (plant_id, compound_id) VALUES (2, 2);

-- Aloe Vera (Family: Asphodelaceae. Uses: Skin Care. Compounds: Aloin)
INSERT IGNORE INTO plant_category (plant_id, category_id) VALUES (3, 3), (3, 9);
INSERT IGNORE INTO plant_compound (plant_id, compound_id) VALUES (3, 4);

-- Turmeric (Family: Zingiberaceae. Uses: Anti-inflammatory. Compounds: Curcumin)
INSERT IGNORE INTO plant_category (plant_id, category_id) VALUES (4, 4), (4, 6);
INSERT IGNORE INTO plant_compound (plant_id, compound_id) VALUES (4, 3);

