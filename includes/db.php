<?php
// includes/db.php

$host = 'localhost';
$dbname = 'dresswell';  // Your actual database name
$user = 'root';          // Default XAMPP user
$pass = '';              // Default XAMPP password (empty)
$charset = 'utf8mb4';    // Recommended charset for modern apps

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on DB errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch results as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use real prepared statements (safer)
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $conn = $pdo; // Optional: for backwards compatibility if some files use $conn
} catch (PDOException $e) {
    exit('Database connection failed: ' . $e->getMessage());
}
