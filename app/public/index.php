<?php 
require '../vendor/autoload.php';

$router = new App\Router;

$router->register('/', function () {
    require '../controllers/homepage.php';
});
$router->register('/article/form', function () {
    require '../controllers/form-article.php';
});
$router->register('/article/delete', function () {
    require '../controllers/delete-article.php';
});
$router->register('/article', function () {
    require '../controllers/show-article.php';
});
$router->register('/profile', function () {
    require '../controllers/profile.php';
});
$router->register('/logout', function () {
    require '../controllers/auth-logout.php';
});
$router->register('/login', function () {
    require '../controllers/auth-login.php';
});
$router->register('/register', function () {
    require '../controllers/auth-register.php';
});
$router->register('/error', function () {
    require '../views/error.php';
});

try {
    $router->resolve($_SERVER['REQUEST_URI']);
} catch (Exception $e) {
    // header('Location: /error');
    echo $e->getMessage();
}



