# PHP Student CRUD

A PHP 8+ and MySQL/MariaDB student management website using PDO prepared statements, CSRF tokens, and escaped HTML output.

## XAMPP setup
1. Start **Apache** and **MySQL** in XAMPP.
2. Open `http://localhost/phpmyadmin`.
3. Select **Import**, choose `database.sql`, and import it (or use the SQL tab and paste its contents).
4. Copy the `php-student-crud` folder into `C:\xampp\htdocs\`.
5. Check `config.php` credentials. Default XAMPP typically uses user `root` and an empty password for local development.
6. Open `http://localhost/php-student-crud/`.

phpMyAdmin is a MySQL/MariaDB administration UI. The PHP website connects to the database directly, not to phpMyAdmin.

For local learning only: before public deployment, add authentication, authorization, HTTPS, secure session cookie settings, environment-based secrets, and an appropriately restricted database user.
