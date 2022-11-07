<?php 
$authDb = new App\AuthDb();
$sessionId = $_COOKIE["session"] ?? "";

if($sessionId) {
    $authDb->logout($sessionId);
    header('Location: /login');
}