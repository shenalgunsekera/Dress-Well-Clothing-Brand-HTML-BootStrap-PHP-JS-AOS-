<?php 
session_start();
include 'includes/header.php'; 
include 'includes/db.php';

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC LIMIT 3");
$products = $stmt->fetchAll();
?>

<!-- Wrap everything in a flex column layout -->


  <!-- Page content goes inside main -->
  <main class="flex-grow-1">

      <!-- Banner -->
      <div class="buttons-container position-relative" style="height: 300px;">
        <!-- Video fills the container -->
        <video class="w-100 h-100 position-absolute top-0 start-0 object-fit-cover" autoplay loop muted playsinline>
          <source src="images/1.mp4" type="video/mp4" />
        </video>

        <div class="position-absolute top-50 start-50 translate-middle">
          <img src="images/logo.png" alt="Dress Well Logo" style="height: 250px;">
        </div>

        <!-- Button appears over the video -->
        <div class="position-absolute bottom-0 start-0 p-3">
          <a href="products.php" class="btn btn-dark radius-30 shadow-lg" style="font-size: 1.0rem; padding: 5px 15px; text-decoration: none;">
            SHOP NOW
          </a>
        </div>
      </div>

      <br>
      <hr class="w-1 bg-dark thick">
      <!-- Latest Items Section -->
      <div class="jumbo w-100 d-flex justify-content-center align-items-center text-dark pt=10" 
           data-aos="fade-up" 
           data-aos-duration="1000" 
           data-aos-easing="ease-in-out">	
        <h1>LATEST ITEMS</h1>

      </div>
          <br>
        <div class="row">
          <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
              <div class="card h-100 shadow">
                <img src="<?= $product['image'] ?>" class="card-img-top" style="height: 250px; object-fit: cover;" alt="<?= htmlspecialchars($product['name']) ?>">    
                <div class="card-body">
                  <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                  <p class="card-text">$<?= number_format($product['price'], 2) ?></p>
                  <a href="item.php?id=<?= $product['id'] ?>" class="btn btn-dark">View</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        

      </div>

    </div>
  </main>

  <?php include 'includes/footer.php'; ?>
