# Web Tech Project: Medicinal Plants and Herbs Database System

This project is a dynamic, web-based database system for medicinal plants and herbs. It is built using **HTML5, CSS3, Vanilla JavaScript, PHP, and MySQL**. The project is designed to be served by either **Apache (via XAMPP)** or an **Nginx Web Server**.

## Features
- **Public Catalogue:** An interactive homepage where guests can browse plants.
- **Dynamic Filtering:** Search for plants by Name, Family, Medicinal Uses, or Active Compounds.
- **Plant Profiles:** Server-side rendered profile pages containing detailed information on each plant.
- **Admin Dashboard:** A secure, session-based dashboard where administrators can add, edit, and delete plants and tags.
- **Smart Tagging:** Easily link plants to multiple categories and compounds without manually manipulating complex relational tables.

## Installation & Setup Instructions

The project requires a local web server with PHP support and a MySQL database. You can run this project using **XAMPP (Apache)** or **Nginx**.

### 1. Database Setup (Required for both XAMPP and Nginx)
1. Ensure your **MySQL** server is running.
2. Open your database management tool (e.g., **phpMyAdmin** at `http://localhost/phpmyadmin` if using XAMPP, or a desktop client like MySQL Workbench/HeidiSQL).
3. Create a new database manually if needed, or simply let the import script handle it.
4. Go to the **Import** tab.
5. Select the `medicinal_plants_db.sql` file located in the root of this project folder.
6. Click **Import/Go**. This script will:
   - Create the `medicinal_plants_db` database.
   - Build all necessary tables (`plants`, `categories`, `compounds`, `plant_categories`, `plant_compounds`, `users`).
   - Insert all current records and admin credentials into the database.

### 2. Running the Application via XAMPP (Apache)
If you are using XAMPP:
1. Start the **XAMPP Control Panel**.
2. Start both the **Apache** and **MySQL** modules.
3. Copy this entire project folder into your XAMPP `htdocs` directory (typically `C:\xampp\htdocs\`).
4. Rename the project folder to `Web Tech Project` (so the path is `C:\xampp\htdocs\Web Tech Project`).
5. Open your web browser and navigate to:
   `http://localhost/Web Tech Project/`
6. The application should now be live!

### 3. Running the Application via Nginx
If you are using a standalone Nginx web server:
1. Ensure **Nginx**, **PHP-FPM**, and **MySQL** are installed and running.
2. Copy the contents of this project to your web root directory (e.g., `C:\nginx\html\Web Tech Project` on Windows or `/var/www/html/medicinal-plants` on Linux).
3. A sample Nginx configuration file is provided in the project root as `nginx.conf`.
4. Copy or include the contents of `nginx.conf` into your main Nginx configuration file (`nginx.conf` or a site-specific config in `sites-available`).
5. Update the `root` directive in the configuration to match the absolute path where you placed the project folder.
6. Update the `fastcgi_pass` directive to point to your PHP-FPM socket or IP/Port (e.g., `127.0.0.1:9000`).
7. Reload or restart the Nginx server.
8. Open your browser and navigate to `http://localhost/` (or whichever `server_name` you configured).

## Default Administrator Access
To manage the database, navigate to the Admin Login page (accessible via the button in the top right of the homepage or by navigating to `login.php`).

- **Username:** `admin`
- **Password:** `admin123`

*(Note: In a production environment, you should immediately change this password or delete this default user and create a new one).*
