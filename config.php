<?php
session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'denzie_user');
define('DB_PASS', 'StrongPassword123');
define('DB_NAME', 'denzie_studio');

// Admin credentials
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', password_hash('admin_password', PASSWORD_DEFAULT));

// Establish database connection
function connectDB() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Login function
function login($username, $password) {
    if ($username === ADMIN_USER && password_verify($password, ADMIN_PASS)) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        return true;
    }
    return false;
}

// Logout function
function logout() {
    session_destroy();
    header("Location: ?page=login");
    exit;
}
?>
