<?php 
$currentUser = $currentUser ?? false;
?>

<header>
    <a href="/" class="logo">Mon Blog</a>
    <div class="header-mobile">
        <div class="header-mobile-icon">
            <img src="/image/mobile-menu.png" alt="">
        </div>
        <ul class="header-mobile-list">
            <?php if($currentUser): ?>
                <li class="<?= $_SERVER['REQUEST_URI'] === '/profile' ? 'active' : '' ?> ">
                    <a href="/profile">Mon espace</a>
                </li>
                <li class=<?= $_SERVER['REQUEST_URI'] === '/article/form' ? 'active' : '' ?>>
                    <a href="/article/form">Écrire un article</a>
                </li>
                <li>
                    <a href="/logout">Déconnexion</a>
                </li>
            <?php else: ?>
                <li class=<?= $_SERVER['REQUEST_URI'] === '/register' ? 'active' : '' ?>>
                    <a href="/register">Inscription</a>
                </li>
                <li class=<?= $_SERVER['REQUEST_URI'] === '/login' ? 'active' : '' ?>>
                    <a href="/login">Connexion</a>
                </li>
            <?php endif ?>
        </ul>
    </div>
    <ul class="header-menu">
        <?php if($currentUser): ?>
            <li class="<?= parse_url($_SERVER['REQUEST_URI'])['path'] === '/' ? 'active' : '' ?>">
                <a href="/">Acceuil</a>
            </li>
            <li class=<?= $_SERVER['REQUEST_URI'] === '/article/form' ? 'active' : '' ?>>
                <a href="/article/form">Écrire un article</a>
            </li>
            <li>
                <a href="/logout">Déconnexion</a>
            </li>
            <li class="<?= $_SERVER['REQUEST_URI'] === '/profile' ? 'active' : '' ?> header-profile">
                <a href="/profile"><?= mb_strtoupper($currentUser["firstname"][0].$currentUser["lastname"][0]) ?></a>
            </li>
        <?php else: ?>
            <li class="<?= parse_url($_SERVER['REQUEST_URI'])['path'] === '/' ? 'active' : '' ?>">
                <a href="/">Acceuil</a>
            </li>
            <li class=<?= $_SERVER['REQUEST_URI'] === '/register' ? 'active' : '' ?>>
                <a href="/register">Inscription</a>
            </li>
            <li class=<?= $_SERVER['REQUEST_URI'] === '/login' ? 'active' : '' ?>>
                <a href="/login">Connexion</a>
            </li>
        <?php endif ?>    
    </ul>
</header>