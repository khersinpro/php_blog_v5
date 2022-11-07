<?php

$authDb = new App\AuthDb();
$articleDb = new App\ArticleDB();

$currentUser = $authDb->isLoggedin();
if(!$currentUser) {
    header('Location: /');
}

$articles = [];
$articlesLength = $articleDb->fetchUserArticleLength($currentUser['id']);

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$page = $_GET['page'] ?? 1;

if(!filter_var($page, FILTER_VALIDATE_INT)){
    throw new Exception('Cette page n\'existe pas');
    exit;
}

$currentPage = (int)$page;
$pageLength = ceil($articlesLength / 15);
$offset = ($currentPage - 1) * 9;

if($currentPage < 0 || $currentPage > $pageLength ) {
    throw new Exception('Cette page n\'existe pas');
    exit;
}

$articles = $articleDb->fetchUserArticle($currentUser['id'], $offset);

require '../views/profile.view.php';