<?php
session_start();
include "db.php";

// Redirect to login if user session does not exist
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$status_msg = "";

// =========================================================================
// VULNERABILITY 1: CSRF (Fund Transfer Feature)
// FLAW: State-changing transactions occur without validating random tokens
// =========================================================================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'transfer') {
    $recipient = $_POST['recipient'];
    $amount = floatval($_POST['amount']);

    $balance_check = $conn->query("SELECT balance FROM users WHERE id = $user_id");
    $user_profile = $balance_check->fetch_assoc();
    
    if ($user_profile['balance'] >= $amount && $amount > 0) {
        $conn->query("UPDATE users SET balance = balance - $amount WHERE id = $user_id");
        $conn->query("UPDATE users SET balance = balance + $amount WHERE username = '$recipient'");
        $status_msg = "<div class='alert alert-success'>Transferred $amount BDT to target account '$recipient' successfully!</div>";
    } else {
        $status_msg = "<div class='alert alert-danger'>Transaction failed: Insufficient funds or invalid input value.</div>";
    }
}

// Fetch balance data dynamically
$wallet_query = $conn->query("SELECT balance FROM users WHERE id = $user_id");
$wallet = $wallet_query->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Property Rental Lab</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{ background:#f5f7fa; font-family:Arial, sans-serif; }
.navbar{ background:#0d6efd; }
.navbar-brand, .nav-link{ color:white !important; font-weight:bold; }
.hero{
    background:linear-gradient(rgba(0,0,0,.55),rgba(0,0,0,.55)), url('https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1600');
    background-size:cover; background-position:center; color:white; min-height:450px; display:flex; align-items:center;
}
.search-box{ background:white; padding:20px; border-radius:10px; margin-top:25px; color:#222; }
.section-title{ text-align:center; margin:50px 0 30px; font-weight:bold; }
.lab-box { border: 2px dashed #dc3545; background: #fff; padding: 20px; border-radius: 10px; margin-bottom: 30px; }
footer{ margin-top:60px; background:#212529; color:white; padding:25px; text-align:center; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
<div class="container">
<a class="navbar-brand" href="#">🏠 Property Rental Lab</a>
<div class="collapse navbar-collapse" id="navmenu">
<ul class="navbar-nav ms-auto align-items-center">
    <li class="nav-item"><a class="nav-link" href="homepage.php">Home</a></li>
    <li class="nav-item"><a class="nav-link" href="comments.php">Reviews</a></li>
    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
    <li class="nav-item ms-3">
        <span class="badge bg-warning text-dark font-monospace fs-6">👤 <?= htmlspecialchars($_SESSION['fullname']); ?> | Balance: <?= $wallet['balance']; ?> BDT</span>
    </li>
</ul>
</div>
</div>
</nav>

<section class="hero">
<div class="container">
<div class="row">
<div class="col-lg-8">
<h1>Find Your Dream Rental Property</h1>
<p class="lead">Simulated Environment for Application Vulnerability Testing</p>

<div class="search-box shadow-sm">
    <form method="GET" action="homepage.php">
        <div class="row g-2">
            <div class="col-md-9">
                <input type="text" class="form-control" name="city" placeholder="Enter city name (e.g., Dhaka or SQL Injection payloads)" value="<?= isset($_GET['city']) ? htmlspecialchars($_GET['city']) : ''; ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-danger w-100">Search Query</button>
            </div>
        </div>
    </form>
</div>

</div>
</div>
</div>
</section>

<div class="container mt-4">
    <?= $status_msg; ?>

    <?php
    if (isset($_GET['city']) && $_GET['city'] !== '') {
        $search_city = $_GET['city'];
        
        // Vulnerable Raw SQL Query Construction
        $sqli_raw_query = "SELECT title, city, price FROM properties WHERE city = '$search_city'";
        
        echo "<div class='alert alert-dark font-monospace my-3' style='font-size:0.85rem;'><strong>Active Backend Query:</strong> " . htmlspecialchars($sqli_raw_query) . "</div>";
        
        $search_result = $conn->query($sqli_raw_query);
        
        echo "<div class='row mb-4'>";
        if ($search_result && $search_result->num_rows > 0) {
            while ($row = $search_result->fetch_assoc()) {
                echo "<div class='col-md-4 mb-2'><div class='card p-3 shadow-sm'>";
                echo "<h5>" . (isset($row['title']) ? $row['title'] : 'N/A') . "</h5>";
                echo "<p class='mb-1 text-muted'>📍 " . (isset($row['city']) ? $row['city'] : 'N/A') . "</p>";
                echo "<strong class='text-primary'>" . (isset($row['price']) ? $row['price'] : 'N/A') . "</strong>";
                echo "</div></div>";
            }
        } else {
            echo "<div class='col-12'><div class='alert alert-warning'>No records returned. DB Error Context: " . $conn->error . "</div></div>";
        }
        echo "</div>";
    }
    ?>

    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            <div class="lab-box shadow-sm">
                <h4 class="text-danger border-bottom pb-2">🛡️ Component: CSRF Wire Transfer</h4>
                <p class="text-muted small">No anti-CSRF structures validation present on execution endpoints here.</p>
                <form method="POST" action="homepage.php">
                    <input type="hidden" name="action" value="transfer">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Recipient Username</label>
                            <input type="text" name="recipient" class="form-control" placeholder="e.g., attacker" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount (BDT)</label>
                            <input type="number" name="amount" class="form-control" placeholder="e.g., 5000" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger mt-3 w-100">Submit Direct POST Request</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container">
<h2 class="section-title">Featured Listings Showcase</h2>
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm"><img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800" class="card-img-top"><div class="card-body"><h5>Luxury Family Apartment</h5><p>📍 Dhaka</p><h6 class="text-primary">25,000 BDT / Month</h6></div></div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm"><img src="https://images.unsplash.com/photo-1484154218962-a197022b5858?w=800" class="card-img-top"><div class="card-body"><h5>Modern Studio Flat</h5><p>📍 Chattogram</p><h6 class="text-primary">18,000 BDT / Month</h6></div></div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm"><img src="https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=800" class="card-img-top"><div class="card-body"><h5>Premium Duplex House</h5><p>📍 Sylhet</p><h6 class="text-primary">55,000 BDT / Month</h6></div></div>
    </div>
</div>
</div>

<footer>
    <h4>Property Rental Lab</h4>
    <p class="mb-0">© <?= date("Y"); ?> Local Lab Deployment. Intended solely for academic evaluation.</p>
</footer>

</body>
</html>