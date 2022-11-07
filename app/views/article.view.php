<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once '../includes/head.php' ?>
    <link rel="stylesheet" href="/css/show-article.css">
    <title>Article</title>
</head>

<body>
    <div class="container">
        <?php require_once '../includes/header.php' ?>
        <div class="content">
            <div class="article-container">
                <a class="article-back" href="/">Retour à la liste des articles</a>
                <div class="article-cover-img" style="background-image:url(<?= $article['image'] ?>)"></div>
                <h1 class="article-title"><?= $article['title'] ?></h1>
                <div class="separator"></div>
                <p class="article-content"><?= $article['content'] ?></p>
                <p class="article-author"><?= $article["firstname"]." ".$article["lastname"] ?></p> 
                <?php if($currentUser && $currentUser['id'] === $article['author']): ?>
                    <div class="action">
                        <form action="/article/delete?id=<?= $article['id'] ?>" method="post"> 
                            <input type="hidden" value="<?= $currentUser['csrfToken'] ?>" name="csrf" >
                            <button class="btn btn-secondary" type="submit">Supprimer</button>
                        </form>
                        <a class="btn btn-primary" href="/article/form?id=<?= $article['id'] ?>">Editer l'article</a>
                    </div>
                <?php endif ?>    
            </div>
        </div>
        <?php require_once '../includes/footer.php' ?>
    </div>

</body>

</html>