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
        'category' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'content' => [
            'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'flags' => FILTER_FLAG_NO_ENCODE_QUOTES
        ],
        'csrf' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
    ]);

    $title = $_POST['title'] ?? '';
    $image = $image ?? '';
    $category = $_POST['category'] ?? '';
    $content = $_POST['content'] ?? '';
    $csrfToken = $_POST['csrf'] ?? '';

    // Gestion d'image
    // Control si le fichier à bien été envoyer et qu'il n'y ai pas d'erreur
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        // Controle de la taille du fichier ( en octet , 1 000 000 octets = 1mo )
        if ($_FILES['image']['size'] > 6000000) {
            $errors['image'] = 'Taille du fichier trop élevé';
        } else {
            // Tableau de mimetype autorisé
            $allowedExtensions = [
                'png' => 'image/png',
                'jpe' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'webp' => 'image/webp'
            ];
            // Extention du fichier
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            
            // Controle de la validité du mimetype du fichier
            if (in_array($extension, $allowedExtensions) || array_key_exists($extension, $allowedExtensions)) {
                // Vérification de l'existance du dossier / création du dossier
                if ( !file_exists( __DIR__.'/../public/article_img' ) && !is_dir( __DIR__.'/../public/article_img' ) ) {
                    mkdir(__DIR__.'/../public/article_img' );       
                } 
                // Si c'est une modification d'article, on supprime l'image précedente
                if ($id) {
                    unlink(__DIR__.'/../public/article_img/'.$image);
                }
                // Ensuite on stock le fichier
                $from = $_FILES['image']['tmp_name'];
                $image = md5(uniqid()).".$extension";
                $to = __DIR__.'/../public/article_img/'.$image;
                move_uploaded_file($from, $to);
            } else {
                $errors['image'] = "Le format du fichier est incorrect.";
            }
        }
    } else {
        $errors['image'] = 'Une erreur est survenu, veuillez réessayer'.$_FILES['image']['error'];
        // Si l'utilisateur n'a pas modifier l'image de son post, on efface l'erreur de l'array
        if ($id && isset($_FILES['image']) && $_FILES['image']['error'] === 4) {
            $errors['image'] = '';
        }
    }
    // Fin de gestion d'image

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
