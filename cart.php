<?php
session_start();


// Initialize cart session if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $id = $_POST['product_id'];
        $name = $_POST['product_name'];
        $price = floatval($_POST['product_price']);
        
        // If product exists in cart, increment qty, else add new
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $name,
                'price' => $price,
                'quantity' => 1
            ];
        }
        header("Location: cart.php");
        exit();
    }

    // Handle update quantity
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantities'] as $id => $qty) {
            $qty = intval($qty);
            if ($qty <= 0) {
                unset($_SESSION['cart'][$id]);
            } else {
                $_SESSION['cart'][$id]['quantity'] = $qty;
            }
        }
        header("Location: cart.php");
        exit();
    }

    // Handle remove item
    if (isset($_POST['remove_item'])) {
        $id = $_POST['remove_item'];
        unset($_SESSION['cart'][$id]);
        header("Location: cart.php");
        exit();
    }
}

// Calculate total price
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<?php include 'includes/header.php' ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Your Cart - Dress Well</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <style>
    body {
      background: #f8f9fa;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding: 20px;
      font-family: 'Arial', sans-serif;
    }
    .cart-container {
      max-width: 900px;
      margin: auto;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    .quantity-input {
      width: 70px;
    }
  </style>
</head>
<body>

<div class="cart-container" data-aos="fade-up" data-aos-duration="1000">
  <h2>Your Shopping Cart</h2>

  <?php if (empty($_SESSION['cart'])): ?>
    <p>Your cart is empty. <a href="products.php">Go shop now!</a></p>
  <?php else: ?>
    <form method="POST" action="cart.php">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Product</th>
            <th>Price (LKR)</th>
            <th>Quantity</th>
            <th>Subtotal (LKR)</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($_SESSION['cart'] as $id => $item): ?>
            <tr data-aos="fade-up" data-aos-delay="100">
              <td><?= htmlspecialchars($item['name']) ?></td>
              <td><?= number_format($item['price'], 2) ?></td>
              <td>
                <input
                  type="number"
                  name="quantities[<?= $id ?>]"
                  value="<?= $item['quantity'] ?>"
                  min="0"
                  class="form-control quantity-input"
                />
              </td>
              <td><?= number_format($item['price'] * $item['quantity'], 2) ?></td>
              <td>
                <button type="submit" name="remove_item" value="<?= $id ?>" class="btn btn-danger btn-sm">
                  Remove
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="d-flex justify-content-between align-items-center mt-4">
        <h4>Total: USD <?= number_format($total, 2) ?></h4>
        <div>
          <a href="products.php" class="btn btn-secondary">Continue Shopping</a>
          <button type="submit" name="update_cart" class="btn btn-primary me-2">Checkout</button>
        </div>
      </div>
    </form>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();
</script>

</body>
</html>

<?php include 'includes/footer.php'; ?>