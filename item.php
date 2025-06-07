<?php 
include 'includes/header.php'; 
include 'includes/db.php';

// Get item ID from the URL
$id = $_GET['id'] ?? 0;

// Fetch the product
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();
?>


<div class="container mt-5">
  <?php if ($product): ?>
    <div class="row">
      <div class="col-md-6" data-aos="fade-right">
        <img src="<?= $product['image'] ?>" class="card-img-top" style="height: 500px; object-fit: cover;" alt="<?= htmlspecialchars($product['name']) ?>">

      </div>
      <div class="col-md-6" data-aos="fade-left">
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <p class="text-muted">$<?= number_format($product['price'], 2) ?></p>
        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        <form method="POST" action="cart.php" class="add-to-cart-form">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
            <input type="hidden" name="product_price" value="<?= $product['price'] ?>">
            <button type="submit" name="add_to_cart" class="btn btn-dark" data-aos="fade-up">
                Add to Cart
            </button>
            </form>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-danger">Product not found.</div>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
