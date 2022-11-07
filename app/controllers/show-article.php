<?php
$authDb = new App\AuthDb();
$articleDB = new App\ArticleDB();

$currentUser = $authDb->isLoggedin();
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

if (!$id) {
    header('Location: /');
} else {
    $article = $articleDB->fetchOne($id);
    !$article && header('Location: /error');
}

require '../views/article.view.php';


