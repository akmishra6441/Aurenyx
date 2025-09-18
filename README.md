# Aurenyx

Aurenyx is a simple, professional-focused social network built with PHP (OOP), MySQL, jQuery, HTML, and CSS.
Core features: signup/login, profile management, image posts, AJAX-based post creation/removal, and dynamic likes/dislikes.

Features

Secure Signup (Full Name, Email unique, Password hashed with bcrypt, Age, Profile picture upload)

Login with session management

Profile page showing Name, Email (non-editable), Age, Profile picture

Edit profile fields (Name, Age) and update profile picture

Create posts with image + description

View, delete posts

Like / Dislike per post (AJAX updates)

Client-side & server-side validation, secure file upload handling

Technology Stack

Backend: PHP (OO approach — classes, autoloading recommended)

Frontend: HTML, CSS, jQuery, AJAX

Database: MySQL / MariaDB

Server: Apache / Nginx (XAMPP, LAMP, MAMP recommended for local dev)

Quick Setup (local)

Install XAMPP/LAMP/MAMP and enable Apache + MySQL.

Copy project folder to server webroot (e.g., htdocs/civora).

Create database and import schema:

Use the SQL below (or database/schema.sql) to create tables.

Configure DB credentials in config/config.php (or .env):

Ensure uploads/ (profile pics and post images) folder exists and is writable.

Start Apache & MySQL, open http://localhost/aurenyx/ in browser.

Important Implementation Notes
Security

Use password_hash($password, PASSWORD_BCRYPT) for storing passwords and password_verify() on login.

Validate/sanitize all inputs server-side (use prepared statements / PDO with bound params).

Restrict upload types to images only (mime check + extension check) and limit size (e.g., 2–5 MB).

Sanitize filenames and store with randomized unique names: uniqid() . '-' . bin2hex(random_bytes(6)) . '.' . $ext.

Prevent directory traversal and ensure uploaded directories are not executable.

Use jQuery $.ajax() to add/remove posts and to change reactions; return JSON responses.

Endpoints to implement:

POST /ajax/create_post.php — receives description + image (use FormData)

POST /ajax/delete_post.php — receives post_id

POST /ajax/reaction.php — receives post_id, reaction (1 or -1)

POST /ajax/update_profile.php — receives fields to update (name, age, profile image)

UX & Validation

Client-side validation: jQuery form validation (check required fields, email format, password strength).

Show inline validation messages and use hover icons for editable fields (CSS :hover + small edit icon).

Provide immediate feedback for AJAX actions (spinners, toast messages).

Extensibility & Next Steps

Add pagination / infinite scroll for posts.

Add comments on posts (separate comments table).

Add notifications and follower/following relationships.

Add CSRF protection tokens for all forms.

Add image optimization and CDN support for production.

Contribution & License

This repository is intended for academic/project use following the original assignment. Choose a license (MIT recommended) if you plan to publish.