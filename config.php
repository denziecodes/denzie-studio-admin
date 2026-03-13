<?php
session_start();

/**
 * Base directory
 * Adjust paths relative to the root of the repo
 */
define('BASE_DIR', __DIR__);

/**
 * Database schema path
 */
define('DB_SCHEMA', BASE_DIR . '/database/schema.sql');

/**
 * Database configuration
 * Use environment variables if available, fallback to defaults
 */
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'denzie_user');
define('DB_PASS', getenv('DB_PASS') ?: 'StrongPassword123');
define('DB_NAME', getenv('DB_NAME') ?: 'denzie_studio');

/**
 * Admin credentials
 * Pre-hashed password (replace with your own hash if needed)
 * Generate hash using: php -r "echo password_hash('admin_password', PASSWORD_DEFAULT).PHP_EOL;"
 */
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', '$2y$10$6XkQ0kj9/0wT0oPZ0uC8qez/6LXoQmEDHdY2vyr/HeF0lHpn9D4iq'); // hash of 'admin_password'

/**
 * Connect to database using PDO
 * Returns PDO instance
 */
function connectDB() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Login function
 * Returns true on success
 */
function login($username, $password) {
    if ($username === ADMIN_USER && password_verify($password, ADMIN_PASS)) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        return true;
    }
    return false;
}

/**
 * Logout function
 * Destroys session and redirects to login
 */
function logout() {
    session_destroy();
    header("Location: ?page=login");
    exit;
}
?>
