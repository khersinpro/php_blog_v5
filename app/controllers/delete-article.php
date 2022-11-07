<?php
$authDb = new App\AuthDb();
$currentUser = $authDb->isLoggedin();

if($currentUser && $_SERVER['REQUEST_METHOD'] === 'POST'){
    $_POST= filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id = $_GET['id'] ?? '';
    $csrfToken = $_POST['csrf'] ?? '';

    $csrfProtection = $authDb->csrfProtection($csrfToken, $currentUser['csrfToken']);
    if (!$csrfProtection) {
        header('Location: /');
        exit;
    }

    $articleDB = new App\ArticleDB();

    if ($id) {
        $article = $articleDB->fetchOne($id);
        if($article['author'] === $currentUser['id']){
            $articleDB->deleteOne($id);
        }
    }
}

header('Location: /');
