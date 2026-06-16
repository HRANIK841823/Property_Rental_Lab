<?php
session_start();

$properties = [
    [
        "title" => "Luxury Family Apartment",
        "city" => "Dhaka",
        "price" => "25,000 BDT / Month",
        "image" => "https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800"
    ],
    [
        "title" => "Modern Studio Flat",
        "city" => "Chattogram",
        "price" => "18,000 BDT / Month",
        "image" => "https://images.unsplash.com/photo-1484154218962-a197022b5858?w=800"
    ],
    [
        "title" => "Premium Duplex House",
        "city" => "Sylhet",
        "price" => "55,000 BDT / Month",
        "image" => "https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=800"
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Property Rental Lab</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f5f7fa;
    font-family:Arial, Helvetica, sans-serif;
}

.navbar{
    background:#0d6efd;
}

.navbar-brand,.nav-link{
    color:white !important;
    font-weight:bold;
}

.hero{
    background:linear-gradient(rgba(0,0,0,.55),rgba(0,0,0,.55)),
    url('https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1600');
    background-size:cover;
    background-position:center;
    color:white;
    min-height:500px;
    display:flex;
    align-items:center;
}

.hero h1{
    font-size:3rem;
    font-weight:bold;
}

.search-box{
    background:white;
    padding:20px;
    border-radius:10px;
    margin-top:25px;
}

.section-title{
    text-align:center;
    margin:50px 0 30px;
    font-weight:bold;
}

.property-card{
    transition:0.3s;
}

.property-card:hover{
    transform:translateY(-5px);
}

.property-card img{
    height:220px;
    object-fit:cover;
}

.feature-box{
    background:white;
    padding:25px;
    border-radius:10px;
    text-align:center;
    box-shadow:0 2px 10px rgba(0,0,0,.08);
}

.review-card{
    background:white;
    border-left:5px solid #0d6efd;
    padding:20px;
    margin-bottom:15px;
    box-shadow:0 2px 8px rgba(0,0,0,.08);
}

footer{
    margin-top:60px;
    background:#212529;
    color:white;
    padding:25px;
    text-align:center;
}
</style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
<div class="container">
<a class="navbar-brand" href="#">🏠 Property Rental Lab</a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navmenu">
<ul class="navbar-nav ms-auto align-items-center">

    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
    <li class="nav-item"><a class="nav-link" href="comments.php">Reviews</a></li>
    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>

    <!-- USER INFO (TOP RIGHT) -->
    <?php if(isset($_SESSION['full_name'])): ?>
        <li class="nav-item ms-3">
            <span class="nav-link text-white">
                👤 <?php echo htmlspecialchars($_SESSION['full_name']); ?>
            </span>
        </li>
    <?php else: ?>
        <li class="nav-item ms-3">
            <span class="nav-link text-white">
                👤 Guest
            </span>
        </li>
    <?php endif; ?>

</ul>
</div>
</div>
</nav>

<!-- Hero -->
<section class="hero">
<div class="container">
<div class="row">
<div class="col-lg-7">
<h1>Find Your Dream Rental Property</h1>
<p class="lead">
Browse hundreds of verified apartments, family homes and luxury villas across Bangladesh.
</p>

<div class="search-box">
<form method="GET" action="#">
<div class="row">
<div class="col-md-5 mb-2">
<input type="text" class="form-control" name="city"
placeholder="Enter city">
</div>
<div class="col-md-5 mb-2">
<input type="text" class="form-control" name="keyword"
placeholder="Property name">
</div>
<div class="col-md-2 mb-2">
<button class="btn btn-primary w-100">Search</button>
</div>
</div>
</form>
</div>

</div>
</div>
</div>
</section>

<!-- Featured Properties -->
<div class="container">
<h2 class="section-title">Featured Properties</h2>

<div class="row">
<?php foreach($properties as $property): ?>
<div class="col-md-4 mb-4">
<div class="card property-card shadow-sm">
<img src="<?= $property['image']; ?>" class="card-img-top">
<div class="card-body">
<h5><?= $property['title']; ?></h5>
<p>📍 <?= $property['city']; ?></p>
<h6 class="text-primary"><?= $property['price']; ?></h6>
<a href="#" class="btn btn-outline-primary">View Details</a>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
</div>

<!-- Why Choose Us -->
<div class="container">
<h2 class="section-title">Why Choose Us?</h2>

<div class="row g-4">
<div class="col-md-4">
<div class="feature-box">
<h4>🏘️ Verified Listings</h4>
<p>Carefully selected rental properties with detailed information.</p>
</div>
</div>

<div class="col-md-4">
<div class="feature-box">
<h4>💰 Affordable Pricing</h4>
<p>Find apartments and houses that match your budget.</p>
</div>
</div>

<div class="col-md-4">
<div class="feature-box">
<h4>📞 24/7 Support</h4>
<p>Dedicated support for tenants and property owners.</p>
</div>
</div>
</div>
</div>

<!-- Reviews -->
<div class="container">
<h2 class="section-title">What Our Users Say</h2>

<div class="review-card">
<h5>⭐⭐⭐⭐⭐ - Rahim Uddin</h5>
<p>Very easy to find a rental apartment. The property listings are well organized.</p>
</div>

<div class="review-card">
<h5>⭐⭐⭐⭐ - Nusrat Jahan</h5>
<p>Good platform with lots of rental choices and detailed information.</p>
</div>

<div class="text-center mt-4">
<a href="comments.php" class="btn btn-primary">
View All Reviews & Leave a Comment
</a>
</div>

</div>

<!-- Footer -->
<footer>
<div class="container">
<h4>Property Rental Lab</h4>
<p>Educational property rental demo application for local development and cyber security training.</p>
<p>© <?php echo date("Y"); ?> Property Rental Lab. All Rights Reserved.</p>
</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>