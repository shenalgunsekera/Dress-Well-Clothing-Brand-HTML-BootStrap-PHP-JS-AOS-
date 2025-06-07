<?php
session_start();
require 'includes/db.php'; // This should define $pdo

// Initialize variables for error messages
$login_error = $signup_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Handle Login
        $email = $_POST['login_email'] ?? '';
        $password = $_POST['login_password'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Success - create session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['fullname'];
            header("Location: index.php");
            exit();
        } else {
            $login_error = "Invalid email or password.";
        }
    }

    if (isset($_POST['signup'])) {
        // Handle Signup
        $fullname = $_POST['signup_fullname'] ?? '';
        $email = $_POST['signup_email'] ?? '';
        $password = $_POST['signup_password'] ?? '';
        $confirm_password = $_POST['signup_confirm_password'] ?? '';

        // Basic validation
        if ($password !== $confirm_password) {
            $signup_error = "Passwords do not match.";
        } else {
            // Check if email exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $signup_error = "Email already registered.";
            } else {
                // Insert new user
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password_hash) VALUES (?, ?, ?)");
                if ($stmt->execute([$fullname, $email, $password_hash])) {
                    // Signup success - log them in
                    $_SESSION['user_id'] = $pdo->lastInsertId();
                    $_SESSION['user_name'] = $fullname;
                    header("Location: index.php");
                    exit();
                } else {
                    $signup_error = "Failed to create account. Please try again.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login / Sign Up - Dress Well</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- AOS CSS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <style>
    body {
      background: #f8f9fa;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      font-family: 'Arial', sans-serif;
    }
    .form-container {
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      max-width: 450px;
      width: 100%;
    }
    .form-toggle {
      cursor: pointer;
      color: #0d6efd;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="form-container" data-aos="fade-up" data-aos-duration="1000">

    <!-- Login Form -->
    <form id="login-form" method="POST" style="display: block;">
      <h3 class="mb-4">Login</h3>
      <?php if ($login_error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($login_error) ?></div>
      <?php endif; ?>
      <div class="mb-3">
        <label for="login_email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="login_email" name="login_email" required />
      </div>
      <div class="mb-3">
        <label for="login_password" class="form-label">Password</label>
        <input type="password" class="form-control" id="login_password" name="login_password" required />
      </div>
      <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
      <p class="mt-3 text-center">Don't have an account? <span class="form-toggle" onclick="toggleForms()">Sign Up</span></p>
    </form>

    <!-- Signup Form -->
    <form id="signup-form" method="POST" style="display: none;">
      <h3 class="mb-4">Sign Up</h3>
      <?php if ($signup_error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($signup_error) ?></div>
      <?php endif; ?>
      <div class="mb-3">
        <label for="signup_fullname" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="signup_fullname" name="signup_fullname" required />
      </div>
      <div class="mb-3">
        <label for="signup_email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="signup_email" name="signup_email" required />
      </div>
      <div class="mb-3">
        <label for="signup_password" class="form-label">Password</label>
        <input type="password" class="form-control" id="signup_password" name="signup_password" required />
      </div>
      <div class="mb-3">
        <label for="signup_confirm_password" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="signup_confirm_password" name="signup_confirm_password" required />
      </div>
      <button type="submit" name="signup" class="btn btn-success w-100">Sign Up</button>
      <p class="mt-3 text-center">Already have an account? <span class="form-toggle" onclick="toggleForms()">Login</span></p>
    </form>

  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- AOS JS -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init();

    function toggleForms() {
      const loginForm = document.getElementById('login-form');
      const signupForm = document.getElementById('signup-form');
      if (loginForm.style.display === "none") {
        loginForm.style.display = "block";
        signupForm.style.display = "none";
      } else {
        loginForm.style.display = "none";
        signupForm.style.display = "block";
      }
    }
  </script>
</body>
</html>
