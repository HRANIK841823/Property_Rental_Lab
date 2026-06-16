<?php
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST["fullname"]);
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO users(fullname, username, email, password)
         VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param("ssss", $fullname, $username, $email, $password);

    if ($stmt->execute()) {
        $message = "Registration successful. You can now log in.";
    } else {
        $message = "Registration failed. Username or email may already exist.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-5">

<div class="card shadow">
<div class="card-body">
<h2 class="text-center mb-4">Create Account</h2>

<?php if($message): ?>
<div class="alert alert-info">
    <?= $message; ?>
</div>
<?php endif; ?>

<form method="POST">
<div class="mb-3">
<label>Full Name</label>
<input type="text" name="fullname" class="form-control" required>
</div>

<div class="mb-3">
<label>Username</label>
<input type="text" name="username" class="form-control" required>
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<button class="btn btn-primary w-100">
Register
</button>
</form>

<p class="mt-3 text-center">
Already have an account?
<a href="login.php">Login</a>
</p>

</div>
</div>

</div>
</div>
</div>

</body>
</html>