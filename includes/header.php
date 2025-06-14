
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Clothing Brand</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="/assets/css/style.css">
  <!-- AOS CSS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg navbar-light bg-light position-relative" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1); height: 80px; z-index: 1050;">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <img src="images/12.png" alt="Dress Well Logo" style="height: 40px;">
    </a>
   <button class="navbar-toggler bg-white border-0 shadow-sm" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarNav" aria-controls="navbarNav"
        aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
</button>

    <div class="collapse navbar-collapse bg-white border-0 shadow-sm" id="navbarNav">
  <ul class="navbar-nav ms-auto align-items-center">
    <?php if (isset($_SESSION['user_id'])): ?>
      <li class="nav-item">
        <a class="nav-link">👋 Hi, <?= htmlspecialchars($_SESSION['user_name']) ?>!</a>
      </li>
    <?php endif; ?>

    <!-- Divider -->
    <li class="w-100 d-lg-none"><hr class="m-0 border-top border-dark opacity-100"></li>

    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
    <li class="w-100 d-lg-none"><hr class="m-0 border-top border-dark opacity-100"></li>
    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
    <li class="w-100 d-lg-none"><hr class="m-0 border-top border-dark opacity-100"></li>
    <?php if (isset($_SESSION['user_id'])): ?>
    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
    <?php endif; ?>
    <li class="w-100 d-lg-none"><hr class="m-0 border-top border-dark opacity-100"></li>
    <?php if (isset($_SESSION['user_id'])): ?>
    <li class="nav-item"><a class="nav-link" href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
    <?php endif; ?>
    <li class="w-100 d-lg-none"><hr class="m-0 border-top border-dark opacity-100"></li>
    <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
    <li class="w-100 d-lg-none"><hr class="m-0 border-top border-dark opacity-100"></li>
    <?php if (isset($_SESSION['user_id'])): ?>
      <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
    <?php else: ?>
      <li class="w-100 d-lg-none"><hr class="m-0 border-top border-dark opacity-100"></li>
      <li class="nav-item"><a class="nav-link" href="login.php">Login / Sign Up</a></li>
    <?php endif; ?>
  </ul>
</div>

  </div>
</nav>
