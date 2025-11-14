<?php
$motDePasseEnBase = '$2y$10$JXH2A2oTgNT1uX3O1h5n.BZs1tCzVpX8ZG3VJwBZlUO...'; 
$motDePasseTest = 'alice123';

if (password_verify($motDePasseTest, $motDePasseEnBase)) {
    echo "✅ Mot de passe correct";
} else {
    echo "❌ Mot de passe incorrect";
}