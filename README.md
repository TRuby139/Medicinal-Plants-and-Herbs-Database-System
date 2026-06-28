# Web Tech Project: Medicinal Plants and Herbs Database System

This project is a dynamic, web-based database system for medicinal plants and herbs. It is built using a classic LAMP/XAMPP stack architecture: **HTML5, CSS3, Vanilla JavaScript, PHP, and MySQL**.

## Features
- **Public Catalogue:** An interactive homepage where guests can browse plants.
- **Dynamic Filtering:** Search for plants by Name, Family, Medicinal Uses, or Active Compounds.
- **Plant Profiles:** Server-side rendered profile pages containing detailed information on each plant.
- **Admin Dashboard:** A secure, session-based dashboard where administrators can add, edit, and delete plants and tags.
- **Smart Tagging:** Easily link plants to multiple categories and compounds without manually manipulating complex relational tables.

## Installation & Setup Instructions

To run this project locally, you must use a local web server environment like **XAMPP**, WAMP, or MAMP.

### 1. File Placement
1. Start your XAMPP Control Panel.
2. Ensure both **Apache** and **MySQL** are running.
3. Copy this entire project folder into your XAMPP `htdocs` directory (typically `C:\xampp\htdocs\`). 
4. Rename the project folder to `Web Tech Project` if it isn't already.

### 2. Database Setup
1. Open your browser and navigate to **phpMyAdmin** (usually `http://localhost/phpmyadmin`).
2. You do not need to create a database manually; the SQL script will do it for you.
3. Go to the **Import** tab.
4. Choose the `setup_database.sql` file located in the root of this project folder.
5. Click **Import/Go**. This script will:
   - Create the `medicinal_plants_db` database.
   - Build all necessary tables (plants, categories, compounds, etc.).
   - Seed the database with the default Admin user.
   - Seed the database with initial dummy data (e.g., Peppermint, Lavender, Aloe Vera).

### 3. Running the Application
1. Open your web browser.
2. Navigate to: `http://localhost/Web Tech Project/`
3. You should now see the public homepage populated with the dummy data!

## Administrator Access

To manage the database, navigate to the Admin Login page (accessible via the button in the top right of the homepage).

- **Username:** `admin`
- **Password:** `admin123`

*(Note: In a production environment, you should immediately change this password or delete this default user and create a new one).*
