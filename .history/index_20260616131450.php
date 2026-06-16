<?php
$properties = [
 ["title"=>"Modern Apartment","city"=>"Dhaka","price"=>"25000 BDT"],
 ["title"=>"Luxury Villa","city"=>"Chattogram","price"=>"60000 BDT"]
];
?>
<!DOCTYPE html>
<html>
<head>
<title>Property Rental Lab</title>
<style>
body{font-family:Arial;max-width:900px;margin:auto;padding:20px}
.card{border:1px solid #ddd;padding:15px;margin:10px 0}
</style>
</head>
<body>
<h1>Property Rental Lab</h1>
<p>Educational local cybersecurity practice application.</p>
<p><a href="comments.php">Comment Demo</a></p>
<?php foreach($properties as $p): ?>
<div class="card">
<h3><?= $p["title"] ?></h3>
<p><?= $p["city"] ?> - <?= $p["price"] ?></p>
</div>
<?php endforeach; ?>
</body>
</html>
