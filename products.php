<?php 
include 'includes/header.php'; 
include 'includes/db.php';

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>

<div class="container mt-5">
  <h2 class="text-center mb-4" data-aos="fade-up">Our Collection</h2>
  <div class="row">
    <?php foreach ($products as $product): ?>
      <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card h-100 shadow">
          <img src="<?= $product['image'] ?>" class="card-img-top" style="height: 250px; object-fit: cover;" alt="<?= htmlspecialchars($product['name']) ?>">    
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
            <p class="card-text">$<?= number_format($product['price'], 2) ?></p>
            <div class="d-flex justify-content-between">
            <a href="item.php?id=<?= $product['id'] ?>" class="btn btn-dark">View</a>
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
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include 'includes/footer.php'; ?>