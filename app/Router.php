<?php

namespace Natom\Ecoride;

class Router
{
    private array $routes = [];

    public function __construct()
    {
        $this->routes = [

            // Accueil
            ''      => ['controller' => 'HomeController', 'method' => 'index'],
            'home'  => ['controller' => 'HomeController', 'method' => 'index'],

            // Connexion / Déconnexion
            'connexion' => ['controller' => 'AuthController', 'method' => 'connexion'],
            'logout'    => ['controller' => 'AuthController', 'method' => 'logout'],

            // Inscription
            'inscription' => ['controller' => 'AuthController', 'method' => 'inscription'],

            // Profil
            'profil' => ['controller' => 'ProfilController', 'method' => 'index'],

            // Trajets
            'proposer'  => ['controller' => 'TrajetController', 'method' => 'proposer'],
            'recherche' => ['controller' => 'TrajetController', 'method' => 'recherche'],
            'reserver'  => ['controller' => 'TrajetController', 'method' => 'reserver'],
            'details'   => ['controller' => 'TrajetController', 'method' => 'details'],
        ];
    }

    public function run(string $url): void
    {
        $url = trim($url, '/');

        if (!array_key_exists($url, $this->routes)) {
            http_response_code(404);
            echo "<h1>404 - Page non trouvée</h1>";
            return;
        }

        $route = $this->routes[$url];

        $controllerName = "Natom\\Ecoride\\Controllers\\" . $route['controller'];
        $method = $route['method'];

        if (!class_exists($controllerName)) {
            http_response_code(500);
            echo "<h1>Erreur : contrôleur introuvable ($controllerName)</h1>";
            return;
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $method)) {
            http_response_code(500);
            echo "<h1>Erreur : méthode '$method' introuvable dans $controllerName</h1>";
            return;
        }

        $controller->$method();
    }
}
