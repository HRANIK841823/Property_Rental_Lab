<?php
session_start();
include "db.php";

// =========================================================================
// VULNERABILITY 3: Stored XSS & Logging (Reviews System)
// FLAW: Database execution inserts unescaped values and renders them directly.
// Extracted logic from cookies.txt logs parameters locally if specified.
// =========================================================================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['comment'])) {
    $raw_comment = $_POST['comment'];
    // Insecure raw database insert
    $conn->query("INSERT INTO comments (comment_text) VALUES ('$raw_comment')");
}

// Injected feature from cookies.txt: Logs incoming cookie data locally when queried via GET
if (isset($_GET['cookie'])) {
    $cookie = $_GET['cookie'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $ua = $_SERVER['HTTP_USER_AGENT'];
    $time = date("Y-m-d H:i:s");

    $log = "[{$time}] IP: {$ip} | UA: {$ua} | Cookie: {$cookie}\n";
    file_put_contents("cookies.txt", $log, FILE_APPEND);
    
    // Terminate early if it's an explicit background logger request
    if (isset($_GET['mode']) && $_GET['mode'] === 'quiet') {
        echo "OK";
        exit;
    }
}

// Fetch all comment logs to display
$comments_query = $conn->query("SELECT comment_text FROM comments ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stored XSS Training Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container bg-white p-4 rounded shadow-sm" style="max-width: 700px;">
    <a href="homepage.php" class="btn btn-secondary btn-sm mb-3">← Back to Home</a>
    
    <h2 class="text-danger border-bottom pb-2">Stored XSS Training Feed</h2>
    
    <?php if (isset($_GET['cookie'])): ?>
        <div class="alert alert-info font-monospace small">
            <strong>Logged Parameter:</strong> <?= htmlspecialchars($_GET['cookie']); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="comments.php" class="mb-4">
        <div class="mb-3">
            <label class="form-label fw-bold">Leave your feedback or comment</label>
            <textarea name="comment" class="form-control" rows="4" placeholder="Try payloads like: <script>alert('XSS')</script>" required></textarea>
        </div>
        <button type="submit" class="btn btn-danger">Post Comment</button>
    </form>

    <hr>
    <h3>All Comments</h3>
    
    <div class="mt-3">
        <?php if ($comments_query && $comments_query->num_rows > 0): ?>
            <?php while($row = $comments_query->fetch_assoc()): ?>
                <div class="alert alert-secondary text-dark shadow-sm">
                    <?= $row['comment_text']; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted small">No comments logged in the database yet.</p>
        <?php endif; ?>
    </div>

    <p class="mt-5 text-muted small border-top pt-2">
        <b>Remediation lesson:</b> To safe-patch this output context from evaluation exploitation, wrap the variable echo block inside a <code>htmlspecialchars()</code> method wrapper.
    </p>
</div>

</body>
</html>