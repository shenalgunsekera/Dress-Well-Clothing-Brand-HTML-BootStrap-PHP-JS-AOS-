<?php
// admin.php

include 'includes/db.php';

define('ADMIN_USER', 'chamod');
define('ADMIN_PASS', '123');

$loggedIn = false;
$message = '';

// Handle logout
if (isset($_GET['logout'])) {
    header('Location: admin.php');
    exit;
}

// Handle deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$deleteId]);
    header('Location: admin.php'); // Refresh after delete
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === ADMIN_USER && $password === ADMIN_PASS) {
        $loggedIn = true;

        if (isset($_POST['product_name'], $_POST['product_description'], $_POST['product_price'])) {
            $name = trim($_POST['product_name']);
            $description = trim($_POST['product_description']);
            $price = floatval($_POST['product_price']);

            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'images/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $tmpName = $_FILES['product_image']['tmp_name'];
                $fileName = basename($_FILES['product_image']['name']);
                $filePath = $uploadDir . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $fileName);

                if (move_uploaded_file($tmpName, $filePath)) {
                    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
                    if ($stmt->execute([$name, $description, $price, $filePath])) {
                        $message = "✅ Product added!";
                    } else {
                        $message = "❌ DB insert error.";
                    }
                } else {
                    $message = "❌ Image upload failed.";
                }
            } else {
                $message = "❌ Please choose an image.";
            }
        }
    } else {
        $message = "⚠️ Invalid login.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <style>
    body, html {
      background: #f0f2f5;
    }
    .login-container, .form-container {
      max-width: 500px;
      margin-top: 60px;
      padding: 30px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    }
    .fade-in {
      animation: fadeIn 0.8s ease-out forwards;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .product-image {
      height: 60px;
      object-fit: cover;
      border-radius: 5px;
    }
  </style>
</head>
<body>
<div class="container mt-4">
  <?php if (!$loggedIn): ?>
    <!-- Login Form -->
    <div class="login-container mx-auto fade-in">
      <h3 class="text-center mb-4">🔐 Admin Login</h3>
      <?php if ($message): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>
      <form method="POST" autocomplete="off">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" required autofocus />
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  <?php else: ?>
    <!-- Add Product Form -->
    <div class="form-container mx-auto fade-in mb-5">
      <div class="d-flex justify-content-between mb-3">
        <h4>Add Product</h4>
        <a href="?logout=1" class="btn btn-outline-danger btn-sm">Logout</a>
      </div>
      <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>
      <form method="POST" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="username" value="<?= htmlspecialchars(ADMIN_USER) ?>" />
        <input type="hidden" name="password" value="<?= htmlspecialchars(ADMIN_PASS) ?>" />
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="product_name" class="form-control" required />
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="product_description" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Price (USD)</label>
          <input type="number" step="0.01" name="product_price" class="form-control" required />
        </div>
        <div class="mb-3">
          <label class="form-label">Image</label>
          <input type="file" name="product_image" class="form-control" accept="image/*" required />
        </div>
        <button type="submit" class="btn btn-success w-100">Add Product</button>
      </form>
    </div>

    <!-- Product List -->
    <div class="card shadow fade-in">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">🛍 Product List</h5>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Image</th>
              <th>Name</th>
              <th>Description</th>
              <th>Price ($)</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
            $products = $stmt->fetchAll();
            if ($products):
              foreach ($products as $index => $product):
            ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><img src="<?= htmlspecialchars($product['image']) ?>" class="product-image" /></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= htmlspecialchars($product['description']) ?></td>
                <td><?= number_format($product['price'], 2) ?></td>
                <td>
                  <a href="?delete=<?= $product['id'] ?>" onclick="return confirm('Delete this product?')" class="btn btn-sm btn-danger">Delete</a>
                </td>
              </tr>
            <?php
              endforeach;
            else:
            ?>
              <tr><td colspan="6" class="text-center">No products yet.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>
</div>
<br>
<div class="mt-3 text-center">
  <a href="index.php" class="btn btn-outline-danger">← Back to Site</a>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init();</script>
</body>
</html>
