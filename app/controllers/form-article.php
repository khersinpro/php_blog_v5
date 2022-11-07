<?php
$authDb = new App\AuthDb();
$currentUser = $authDb->isLoggedin();
if(!$currentUser) {
    header('Location: /');
}
$articleDB = new App\ArticleDB();
const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_TITLE_TOO_SHORT = 'Le titre est trop court';
const ERROR_CONTENT_TOO_SHORT = 'L\'article est trop court';
const ERROR_IMAGE_URL = 'L\'image doit être une url valide';
$errors = [
    'title' => '',
    'image' => '',
    'category' => '',
    'content' => ''
];
$category = '';

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';
if ($id) {
    $article = $articleDB->fetchOne($id);
    if($article['author'] !== $currentUser['id']){
        header('Location: /');
    }
    $title = $article['title'];
    $image = $article['image'];
    $category = $article['category'];
    $content = $article['content'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $_POST = filter_input_array(INPUT_POST, [
        'title' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'image' => FILTER_SANITIZE_URL,
        'category' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'content' => [
            'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'flags' => FILTER_FLAG_NO_ENCODE_QUOTES
        ],
        'csrf' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
    ]);
    $title = $_POST['title'] ?? '';
    $image = $_POST['image'] ?? '';
    $category = $_POST['category'] ?? '';
    $content = $_POST['content'] ?? '';
    $csrfToken = $_POST['csrf'] ?? '';

    $csrfProtection = $authDb->csrfProtection($csrfToken, $currentUser['csrfToken']);
    if (!$csrfProtection) {
        header('Location: /');
        exit;
    }

    if (!$title) {
        $errors['title'] = ERROR_REQUIRED;
    } elseif (mb_strlen($title) < 5) {
        $errors['title'] = ERROR_TITLE_TOO_SHORT;
    }

    if (!$image) {
        $errors['image'] = ERROR_REQUIRED;
    } elseif (!filter_var($image, FILTER_VALIDATE_URL)) {
        $errors['image'] = ERROR_IMAGE_URL;
    }

    if (!$category) {
        $errors['category'] = ERROR_REQUIRED;
    }

    if (!$content) {
        $errors['content'] = ERROR_REQUIRED;
    } elseif (mb_strlen($content) < 50) {
        $errors['content'] = ERROR_CONTENT_TOO_SHORT;
    }


    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
        if ($id) {
            $article['title'] = $title;
            $article['image'] = $image;
            $article['category'] = $category;
            $article['content'] = $content;
            $article['author'] = $currentUser['id'];
            $articleDB->updateOne($article);
        } else {
            $articleDB->createOne([
                'title' => $title,
                'content' => $content,
                'category' => $category,
                'image' => $image,
                'author' => $currentUser['id']
            ]);
        }
        header('Location: /');
    }
}

require '../views/form-article.view.php';
