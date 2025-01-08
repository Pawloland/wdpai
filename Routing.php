<?php

require_once 'src/controllers/DefaultController.php';
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/UploadController.php';
require_once 'src/controllers/ReservationController.php';

class Router
{

    public static array $routes;

    public static function get(string $url, string $view): void
    {
        self::$routes[$url] = $view;
    }

    public static function post(string $url, string $view): void
    {
        self::$routes[$url] = $view;
    }

    public static function run(string $url): void
    {

        $urlParts = explode("/", $url);
        $action = $urlParts[0];

        if (!array_key_exists($action, self::$routes)) {
//            die("Wrong url!");
            $controller = self::$routes[''];
            $action = 'not_found';
        } else {
            $controller = self::$routes[$action];
        }

        $object = new $controller;
        $action = $action ?: 'index';

        $id = $urlParts[1] ?? '';

        $object->$action($id);
    }
}