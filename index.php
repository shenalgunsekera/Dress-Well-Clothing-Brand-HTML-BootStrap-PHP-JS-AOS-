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
      <div class="w-100">
        <img 
          src="images/banner.png" 
          class="img-fluid w-100" 
          alt="Banner Image"
          style="height: 400px; object-fit: cover; display: block;"
          data-aos="fade-down"
          data-aos-duration="1000"
          data-aos-easing="ease-in-out"
        >
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
