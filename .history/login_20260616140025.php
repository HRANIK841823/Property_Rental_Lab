<?php
session_start();
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT id, fullname, password
         FROM users
         WHERE username=?"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["fullname"] = $user["fullname"];
            $_SESSION['user_name'] = $user['name']; // or username
            header("Location: homepage.php");
            exit;
        }
    }

    $message = "Invalid username or password.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-5">

<div class="card shadow">
<div class="card-body">

<h2 class="text-center mb-4">Login</h2>

<?php if($message): ?>
<div class="alert alert-danger">
    <?= $message; ?>
</div>
<?php endif; ?>

<form method="POST">

<div class="mb-3">
<label>Username</label>
<input type="text" name="username" class="form-control" required>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<button class="btn btn-primary w-100">
Login
</button>

</form>

<p class="mt-3 text-center">
Don't have an account?
<a href="register.php">Register</a>
</p>

</div>
</div>

</div>
</div>
</div>

</body>
</html>