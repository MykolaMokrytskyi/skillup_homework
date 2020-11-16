<?php

class Route
{

    protected static bool $routeCheck = false;

    protected static function routeCheck(string $route): array
    {
        if (!$route || preg_match('/^[\/]+$|^(\/)[^\/]+[\/]?$/', $route)) {
            return [];
        }
        self::$routeCheck = true;
        preg_match_all('/[^\/]+/', $route, $matches);
        return $matches[0];
    }

    protected static function nameTemplating(string $name): string
    {
        $name = ucwords($name, '-');
        return str_replace('-', '', $name);
    }

    protected static function errorPage404(): void
    {
        header("Location: /skillup-shop/main/not-found");
        exit();
    }

    public static function start(): void
    {
        var_dump($_GET);
        exit();

        $modelName = 'Main';
        $modelPath = __DIR__ . '/../models/';
        $controllerName = 'Main';
        $controllerPath = __DIR__ . '/../controllers/';
        $actionName = 'Index';
        $parametersList = [];

        $routes = self::routeCheck($_GET['route']);

        if (self::$routeCheck) {
            $controllerName = self::nameTemplating(array_shift($routes));
            $modelName = $controllerName;
            $actionName = self::nameTemplating(array_shift($routes));
            $parametersList = $routes;
        }

        $controllerName .= 'Controller';
        $actionName = "action{$actionName}";
        $modelPath = "{$modelPath}{$modelName}.php";
        $controllerPath = "{$controllerPath}{$controllerName}.php";

        file_exists($modelPath) ? include($modelPath) : '';
        file_exists($controllerPath) ? include($controllerPath) : self::errorPage404();
        $controller = new $controllerName();
        method_exists($controller, $actionName) ? $controller->$actionName($parametersList) : self::errorPage404();
    }
}