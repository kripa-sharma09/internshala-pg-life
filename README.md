# PG Life (Internshala-style training project)

A beginner-friendly PHP + MySQL starter project for XAMPP. This is a clean implementation of common PG Life features, not a copy of Internshala's private course source code. Sample property details and prices are illustrative.

## Features
- Home page with city search and popular properties
- City-based listings and rent/rating sorting
- Property detail page with amenities
- Signup, login, logout using PHP sessions and password hashing
- Dashboard with saved/interested properties
- PDO prepared statements
- MySQL schema and seed data

## Requirements
- XAMPP with Apache, PHP 8+ recommended, and MySQL/MariaDB
- Browser
- Optional: VS Code

## Setup on Windows

1. Copy the `pglife` project folder into `C:\xampp_new\htdocs\pglife`.
2. Open XAMPP Control Panel and start **Apache** and **MySQL**.
3. Open phpMyAdmin at `http://localhost/phpmyadmin/`.
4. Select the `pglife` database. If it does not exist, create a database named `pglife`.
5. Open the **Import** tab and select `pglife_backup.sql` from your Desktop.
6. Click **Import** or **Go**.
7. Open `includes/config.php` and check the database connection settings. The default XAMPP username is `root` and the password is empty.
8. Visit `http://localhost/pglife/` in your browser.

## Test the site
- Search or choose a city on the home page.
- Open a listing and view its details.
- Create an account using your own email and a password of at least 8 characters.
- Log in and mark properties as interested.
- Open Dashboard to see saved PGs.

There is no seeded login account; register through the signup page.

## Add your assets
- Amenity SVGs belong in `img/amenities/`. The included SQL refers to the expected filenames.
- Property photos belong in `img/properties/`. Change each property's `image_path` in phpMyAdmin or SQL to e.g. `img/properties/sunrise.jpg`.
- Keep image paths relative to the project root.

## Important notes
- The listings are demo data, not real availability or verified rental offers.
- Bootstrap is loaded from a CDN, so the browser needs internet access for Bootstrap styling/scripts.
- Before production deployment, add CSRF protection, rate limiting, email verification, stricter redirect allowlisting, secure HTTPS/session-cookie settings, and an admin workflow for maintaining listings.
- Do not use this demo with real sensitive user data until security hardening and testing are complete.
