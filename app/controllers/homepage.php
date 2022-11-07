<?php
$authDb = new App\AuthDb();
$currentUser = $authDb->isLoggedin();

$articleDB = new App\ArticleDB();
$categories = [];
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$categoryArray = ['technologie', 'nature', 'politique'];
$selectedCat = $_GET['cat'] ?? '';

$page = $_GET['page'] ?? 1;
if (!filter_var($page, FILTER_VALIDATE_INT)) {
    throw new Exception("La page n'existe pas");
    exit;
}

$currentPage = (int)$page;
if ($currentPage <= 0) {
    throw new Exception("La page n'existe pas");
    exit;
}

$offset = ($currentPage -1)*9;
if ($selectedCat) {
    if (in_array($selectedCat, $categoryArray, true)) {
        $articles = $articleDB->fetchAllPerCatgory($selectedCat);
        $pagesLength = ceil($articles / 9);
        if ($currentPage > $pagesLength) {
            throw new Exception("La page n'existe pas");
            exit;
        }
        $actualPageArticles = $articleDB->fetchPageArticlesPerCategory($offset, $selectedCat);
    } else {
        header('Location: /');
        http_response_code(301);
        exit;
    }
} else {
    $articles = $articleDB->fetchAll();
    $pagesLength = ceil($articles / 9);
    if ($currentPage > $pagesLength) {
        throw new Exception("La page n'existe pas");
        exit;
    }
    $actualPageArticles = $articleDB->fetchPageArticles($offset);
}

require '../views/homepage.view.php';