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
