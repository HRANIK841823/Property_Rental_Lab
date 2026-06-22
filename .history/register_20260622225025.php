<?php
include "db.php";

$message = "";
$error_detail = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST["fullname"]);
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Using a parameterized query to isolate target registration mechanisms cleanly
    $stmt = $conn->prepare(
        "INSERT INTO users (fullname, username, email, password) VALUES (?, ?, ?, ?)"
    );

    if ($stmt) {
        $stmt->bind_param("ssss", $fullname, $username, $email, $password);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Registration successful! You can now <a href='login.php'>Login</a>.</div>";
        } else {
            // Output explicit MySQL constraints info if insertion drops
            $error_detail = $stmt->error;
            $message = "<div class='alert alert-danger'>Registration failed. Error details: " . htmlspecialchars($error_detail) . "</div>";
        }
        $stmt->close();
    } else {
        $message = "<div class='alert alert-danger'>Statement preparation failed: " . htmlspecialchars($conn->error) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - Lab Environment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-5">

<div class="card shadow">
<div class="card-body">
<h2 class="text-center mb-4">Create Account</h2>

<?= $message; ?>

<form method="POST">
<div class="mb-3">
<label class="form-label">Full Name</label>
<input type="text" name="fullname" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Username</label>
<input type="text" name="username" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<button class="btn btn-danger w-100">Register</button>
</form>

<p class="mt-3 text-center">
    Already have an account? <a href="login.php">Login here</a>
</p>

</div>
</div>

</div>
</div>
</div>

</body>
</html>