<?php
session_start();
require 'db_connection.php'; // Your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Correct password - create session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['user_name'];

        header("Location: index.php"); // redirect after login
        exit();
    } else {
        // Invalid credentials
        echo "Invalid email or password";
    }
}
