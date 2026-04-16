<?php
$mot = 'employe123';
$hash = password_hash($mot, PASSWORD_DEFAULT);

echo "<pre>";
echo "Mot en clair : {$mot}\n";
echo "Hash généré :\n{$hash}\n";
echo "Longueur : " . strlen($hash) . "\n";
echo "</pre>";
