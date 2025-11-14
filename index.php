<?php

include 'includes/header.php';


$page = isset($_GET['page']) ? $_GET['page'] : 'home';


if (!preg_match('/^[a-zA-Z0-9_]+$/', $page)) {
    echo '<div style="text-align:center; margin:50px;"><h2>Page invalide</h2></div>';
    include 'includes/footer.php';
    exit;
}


$pagePath = __DIR__ . '/pages/' . $page . '.php';


if (file_exists($pagePath)) {
    include $pagePath;
} else {
    echo '<div style="text-align:center; margin:50px;"><h2>Page introuvable</h2></div>';
}


include 'includes/footer.php';
?>
