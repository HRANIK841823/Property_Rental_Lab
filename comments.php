<?php
$file="comments.txt";
if($_SERVER["REQUEST_METHOD"]==="POST"){
  file_put_contents($file,$_POST["comment"].PHP_EOL, FILE_APPEND);
}
$comments=file_exists($file)?file($file, FILE_IGNORE_NEW_LINES):[];
?>
<!DOCTYPE html>
<html><body>
<h2>Stored XSS Training Demo</h2>
<form method="post">
<textarea name="comment" rows="4" cols="60"></textarea><br>
<button type="submit">Post Comment</button>
</form>
<hr>
<h3>Comments</h3>
<?php
foreach($comments as $c){
  // Intentional training flaw: output without escaping.
  echo "<div style='border:1px solid #ccc;padding:8px;margin:8px'>".$c."</div>";
}
?>
<p><b>Secure version:</b> replace the echo with htmlspecialchars().</p>
</body></html>
