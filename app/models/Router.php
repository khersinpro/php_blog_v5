<?php
namespace App;

class Router 
{
    // Variable de stockage des routes
    private array $routes;

    // Stockage du path et de l'action a effectuer dans l'array $routes
    public function register(string $path, callable $action): void
    {
        $this->routes[$path] = $action;
    }

    // Execution de l'action a effectuer selon le path du $_SERVER['REQUEST_URI']
    public function resolve(string $uri): mixed
    {
        $path = parse_url($uri)['path'];
        $action = $this->routes[$path] ?? null;

        if ($action) {
            return $action();  
        } else {
            return throw new \Exception('Cette page n\'existe pas.');
        }
    }
}