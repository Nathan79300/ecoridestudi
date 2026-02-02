<?php
$motdepasse = "chauffeur123";
$hash = password_hash($motdepasse, PASSWORD_DEFAULT);

echo "<h2>Hash généré pour chauffeur123 :</h2>";
echo "<pre>$hash</pre>";
