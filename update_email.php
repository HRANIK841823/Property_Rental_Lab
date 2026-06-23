<?php
session_start();
include "db.php";

$status_msg = "";

// HYBRID AUTHENTICATION FALLBACK
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $current_user_name = $_SESSION['fullname'];
} else {
    $user_id = 1; 
    $current_user_name = "Guest (Automated Scan Mode)";
}

// =========================================================================
// VULNERABILITY: CSRF / Missing State Verification (Isolated Target)
// =========================================================================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'update_email') {
    $new_email = $_POST['email'];
    
    // Insecure raw database assignment string
    $update_query = "UPDATE users SET email = '$new_email' WHERE id = $user_id";
    
    if ($conn->query($update_query)) {
        $status_msg = "<div class='alert alert-success'>Email updated successfully!</div>";
    } else {
        $status_msg = "<div class='alert alert-danger'>Update failed: " . $conn->error . "</div>";
    }
}

// Dynamically fetch current value for state validation showcase
$profile_query = $conn->query("SELECT email FROM users WHERE id = $user_id");
$user_profile = $profile_query ? $profile_query->fetch_assoc() : ['email' => 'None'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Settings - Update Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{ background:#f5f7fa; font-family:Arial, sans-serif; }
        .navbar{ background:#dc3545; }
        .navbar-brand, .nav-link{ color:white !important; font-weight:bold; }
        .lab-box { border: 2px dashed #dc3545; background: #fff; padding: 20px; border-radius: 10px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg mb-4">
<div class="container">
    <a class="navbar-brand" href="#">🏠 Property Rental Lab</a>
    <div class="collapse navbar-collapse">
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

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <a href="homepage.php" class="btn btn-secondary btn-sm mb-3">← Back to Homepage</a>
            
            <?= $status_msg; ?>

            <div class="lab-box shadow-sm">
                <h4 class="text-danger border-bottom pb-2">🛡️ Component: Update Email (CSRF Target)</h4>
                <p class="text-muted small">
                    Target Profile ID Context: <b><?= $user_id; ?></b><br>
                    Current Registered Email: <b><?= htmlspecialchars($user_profile['email'] ?? 'None'); ?></b>
                </p>
                
                <form method="POST" action="update_email.php">
                    <input type="hidden" name="action" value="update_email">
                    <div class="mb-3">
                        <label class="form-label">New Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user_profile['email'] ?? ''); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Update Account Email</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>