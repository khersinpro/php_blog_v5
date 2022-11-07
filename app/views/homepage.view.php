<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once '../includes/head.php' ?>
    <link rel="stylesheet" href="/css/index.css">
    <title>Blog</title>
</head>

<body>
    <div class="container">
        <?php require_once '../includes/header.php' ?>
        <div class="content">
            <div class="newsfeed-container">
                <ul class="category-container">
                    <li class=<?= $selectedCat ? '' : 'cat-active' ?>><a href="/">Tous les articles</a></li>
                    <?php foreach ($categoryArray as $catName) : ?>
                        <li class=<?= $selectedCat ===  $catName ? 'cat-active' : '' ?>><a href="/?cat=<?= $catName ?>"> <?= $catName ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <div class="category-separator"></div>

                <div class="newsfeed-content">
                        <div class="articles-container">
                            <?php foreach ($actualPageArticles as $article) : ?>
                                <a href="/article?id=<?= $article['id'] ?>" class="article ">
                                    <div class="overflow">
                                        <div class="img-container" style="background-image:url(<?= $article['image'] ?>"></div>
                                    </div>
                                    <div class="article-author">
                                        <p><?= mb_strtoupper($article['category'])  ?></p>
                                        <p><?= $article['firstname'].' '.$article['lastname'] ?></p>
                                    </div>
                                    <h3><?= $article['title'] ?></h3>
                                    <button class="btn btn-small btn-primary">Lire l'article</button>
                                </a>
                            <?php endforeach; ?>
                        </div>
                </div>
                
                <div class="pagination-container">
                    <ul class="pagination-list">
                        <?php for ($i = 1; $i <= $pagesLength; $i++) : ?>
                            <li class="<?= $i === (int)$currentPage  ? "selected" : ""?>">
                                <a href="?page=<?=$i?><?= $selectedCat ? "&cat=$selectedCat" : ""?>" ><?= $i ?></a>
                            </li>
                        <?php endfor ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php require_once '../includes/footer.php' ?>
    </div>

</body>

</html>