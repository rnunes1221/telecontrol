<?php

require_once 'controllers/ProductController.php';
require_once 'controllers/ClientController.php';
require_once 'controllers/OrderController.php';
require_once 'controllers/AuthController.php';

$routes = [
    'GET' => [
        '/products' => ['ProductController', 'index'],
        '/clients' => ['ClientController', 'index'],
        '/orders' => ['OrderController', 'index'],
        '/orders/find/{id}' => ['OrderController', 'find'],
        '/products/find/{id}' => ['ProductController', 'find'],
        '/clients/find/{id}' => ['ClientController', 'find'],
    ],
    'POST' => [
        '/products' => ['ProductController', 'store'],
        '/clients' => ['ClientController', 'store'],
        '/login' => ['AuthController', 'login'],
        '/orders' => ['OrderController', 'store'],
    ],
    'PUT' => [
        '/products/{id}' => ['ProductController', 'update'],
        '/clients/{id}' => ['ClientController', 'update'],
        '/orders/{id}' => ['OrderController', 'update'],
    ],
    'DELETE' => [
        '/products/{id}' => ['ProductController', 'delete'],
        '/clients/{id}' => ['ClientController', 'delete'],
        '/orders/{id}' => ['OrderController', 'delete'],
    ],
];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


$ext = pathinfo($uri, PATHINFO_EXTENSION);
if (in_array($ext, ['css', 'js', 'png', 'jpg', 'json', 'ico', 'svg', 'map'])) {
    return false; 
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$path = '/' . trim(preg_replace('#^' . preg_quote($scriptName) . '#', '', $uri), '/');
$path = preg_replace('#^/index\.php#', '', $path);
if ($path === '') $path = '/';

if (!isset($routes[$method])) {
    http_response_code(404);
    echo json_encode(['error' => "Route not found: $method $path"]);
    exit;
}

foreach ($routes[$method] as $route => $handler) {
    $pattern = preg_replace('#\{[^}]+\}#', '([^/]+)', $route);
    $regex = '#^' . $pattern . '$#';

    if (preg_match($regex, $path, $matches)) {
        array_shift($matches);

        list($controllerName, $action) = $handler;

        if (!class_exists($controllerName)) {
            http_response_code(500);
            echo json_encode(['error' => "Controller $controllerName not found"]);
            exit;
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $action)) {
            http_response_code(500);
            echo json_encode(['error' => "Method $action not found in controller $controllerName"]);
            exit;
        }

        call_user_func_array([$controller, $action], $matches);
        exit;
    }
}

http_response_code(404);
echo json_encode(['error' => "Route not found: $method $path"]);
