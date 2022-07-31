<?php

namespace App\Routes;

use App\Controllers as Controllers;

class Router
{
    private array $routes;

    public function __construct()
    {
    }

    public function callRoute(): void
    {
        $this->declareRoutes();
        $urlStr = $this->getURLString();
        $controllerData = [];

        foreach ($this->routes as $key => $value) {
            if ($key === $urlStr) {
                $controllerData = $value;
                break;
            }
        }

        $this->callController($controllerData);
    }

    private function callController(array $controllerData): void
    {
        $controllerCtrClassname = $controllerData['classname'];
        $controllerClassname = NAMESPACE_CONTROLLER_PREFIX . $controllerCtrClassname;
        $controllerMethod = $controllerData['method'];
        $controllerParamValues = $controllerData['params'] ?? [];

        if ($controllerCtrClassname !== null) {
            $controller = new  $controllerClassname($controllerCtrClassname);
            if ($controllerMethod !== "" && $controllerParamValues === null) {
                $controller->$controllerMethod();
            } else {
                $controller->$controllerMethod($controllerParamValues);
            }
        }
    }

    private function getURLString(): string
    {
        $urlElements = parse_url($_SERVER['REQUEST_URI']);
        $urlPath = $urlElements["path"];
        $urlPath = substr($urlPath, 1);
        $urlPathElements = explode('/', $urlPath);
        $urlMask = '';

        foreach ($urlPathElements as $el) {
            $urlMask = $urlMask . '/' . $el;
        }

        return $urlMask;
    }

    private function declareRoutes(): void
    {
        $pageNumber = $_POST['page'] ?? 1;

        $this->routes = [
            '/' => [
                'classname' => 'MainPage',
                'method' => 'showStartPage',
                'params' => null
            ],
            '/log-in' => [
                'classname' => 'LoginPage',
                'method' => 'showLoginPage',
                'params' => null
            ],
            '/log-in-result' => [
                'classname' => 'LoginPage',
                'method' => 'showLoginResult',
                'params' => null
            ],
            '/log-out' => [
                'classname' => 'LoginPage',
                'method' => 'logout',
                'params' => null
            ],
            '/change-card' => [
                'classname' => 'CardController',
                'method' => 'changeCard',
                'params' => null
            ],
            '/add-card' => [
                'classname' => 'CardController',
                'method' => 'add',
                'params' => null
            ]
        ];
    }

}