<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once '../includes/head.php' ?>
    <link rel="stylesheet" href="/css/profile.css">
    <title>Profile</title>
</head>

<body>
    <div class="container">
        <?php require_once '../includes/header.php' ?>
        <div class="content">
            <h1>Mon espace</h1>
            <h2>Mes informations</h2>
            <div class="info-container">
                <ul>
                    <li>
                        <strong>Prénom : </strong>
                        <p><?= $currentUser['firstname'] ?></p>
                    </li>
                    <li>
                        <strong>Nom : </strong>
                        <p><?= $currentUser['lastname'] ?></p>
                    </li>
                    <li>
                        <strong>Email : </strong>
                        <p><?= $currentUser['email'] ?></p>
                    </li>
                </ul>
            </div>
            <h2>Mes articles</h2>
            <div class="articles-list">
                <ul>
                    <?php foreach($articles as $a): ?>
                        <li>
                            <span><?= $a['title'] ?></span>
                            <div class="article-actions">
                                <a href="/article/form?id=<?= $a['id'] ?>" class="btn btn-primary btn-small">Modifier</a>
                                <form action="/article/delete?id=<?= $a['id'] ?>" method="post"> 
                                    <input type="hidden" value="<?= $currentUser['csrfToken'] ?>" name="csrf" >
                                    <button class="btn btn-secondary btn-small" type="submit">Supprimer</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
            <div class="pagination-container">
                    <ul class="pagination-list">
                        <?php for ($i = 1; $i <= $pageLength; $i++) : ?>
                            <li class="<?= $i === (int)$currentPage  ? "selected" : ""?>">
                                <a href="/profile?page=<?=$i?>" ><?= $i ?></a>
                            </li>
                        <?php endfor ?>
                    </ul>
                </div>
        </div>
        <?php require_once '../includes/footer.php' ?>
    </div>

</body>

</html>