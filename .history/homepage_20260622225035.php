<?php
session_start(); // Restored to track real logins
include "db.php";

$status_msg = "";

// HYBRID AUTHENTICATION: Use the logged-in user's session if it exists; 
// otherwise, fall back to ID 1 so sqlmap doesn't get blocked or redirected.
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $current_user_name = $_SESSION['fullname'];
} else {
    $user_id = 1; 
    $current_user_name = "Guest (Automated Scan Mode)";
}

// =========================================================================
// VULNERABILITY 1: CSRF / Missing State Verification (Email Update Only)
// =========================================================================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'update_email') {
    $new_email = $_POST['email'];
    
    // Completely insecure query execution string (Vulnerable to SQLi and CSRF)
    $update_query = "UPDATE users SET email = '$new_email' WHERE id = $user_id";
    
    if ($conn->query($update_query)) {
        $status_msg = "<div class='alert alert-success'>Email updated successfully!</div>";
    } else {
        $status_msg = "<div class='alert alert-danger'>Update failed: " . $conn->error . "</div>";
    }
}

// Fetch user entry dynamically for verification based on the active ID
$profile_query = $conn->query("SELECT email FROM users WHERE id = $user_id");
$user_profile = $profile_query ? $profile_query->fetch_assoc() : ['email' => 'None'];
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
.navbar{ background:#dc3545; }
.navbar-brand, .nav-link{ color:white !important; font-weight:bold; }
.hero{
    background:linear-gradient(rgba(0,0,0,.55),rgba(0,0,0,.55)), url('https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1600');
    background-size:cover; background-position:center; color:white; min-height:400px; display:flex; align-items:center;
}
.search-box{ background:white; padding:20px; border-radius:10px; margin-top:25px; color:#222; }
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
    <li class="nav-item me-3"><a class="nav-link" href="logout.php">Logout</a></li>
    <li class="nav-item">
        <span class="badge bg-warning text-dark font-monospace fs-6">👤 Active: <?= htmlspecialchars($current_user_name); ?></span>
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
<p class="lead font-monospace text-warning">Target Parameter: ?city=</p>

<div class="search-box shadow-sm">
    <form method="GET" action="homepage.php">
        <div class="row g-2">
            <div class="col-md-9">
                <input type="text" class="form-control" name="city" placeholder="Enter city name (e.g., Dhaka)" value="<?= isset($_GET['city']) ? htmlspecialchars($_GET['city']) : ''; ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-danger w-100">Search</button>
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
        
        // =========================================================================
        // VULNERABILITY 2: SQL INJECTION (Raw Parameter Concatenation)
        // =========================================================================
        $sqli_raw_query = "SELECT title, city, price FROM properties WHERE city = '$search_city'";
        
        echo "<div class='alert alert-dark font-monospace my-3' style='font-size:0.85rem;'><strong>Active Backend Query:</strong> " . $sqli_raw_query . "</div>";
        
        $search_result = $conn->query($sqli_raw_query);
        
        if (!$search_result) {
            echo "<div class='alert alert-danger font-monospace'><strong>Database Error:</strong> " . $conn->error . "</div>";
        }

        echo "<div class='row mb-4'>";
        if ($search_result && $search_result->num_rows > 0) {
            while ($row = $search_result->fetch_assoc()) {
                echo "<div class='col-md-4 mb-2'><div class='card p-3 shadow-sm'>";
                echo "<h5>" . $row['title'] . "</h5>";
                echo "<p class='mb-1 text-muted'>📍 " . $row['city'] . "</p>";
                echo "<strong class='text-primary'>" . $row['price'] . " BDT</strong>";
                echo "</div></div>";
            }
        } else if ($search_result) {
            echo "<div class='col-12'><div class='alert alert-warning'>No properties found in '" . $search_city . "'.</div></div>";
        }
        echo "</div>";
    }
    ?>

    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="lab-box shadow-sm">
                <h4 class="text-danger border-bottom pb-2">🛡️ Component: Update Email (CSRF Vulnerable)</h4>
                <p class="text-muted small">
                    Target Profile ID Context: <b><?= $user_id; ?></b><br>
                    Current Registered Email: <b><?= htmlspecialchars($user_profile['email'] ?? 'None'); ?></b>
                </p>
                <form method="POST" action="homepage.php">
                    <input type="hidden" name="action" value="update_email">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user_profile['email'] ?? ''); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Update Account Email</button>
                </form>
            </div>
        </div>
    </div>
</div>

<footer>
    <h4>Property Rental Lab Platform</h4>
    <p class="mb-0">© <?= date("Y"); ?> Local isolated training environment.</p>
</footer>

</body>
</html>