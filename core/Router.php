<?php

class Router
{

    public static function getController() : \Controllers\ControllerAbstract
    {

        $controller_class_name = static::getControllerClassName();

        if (!$controller_class_name) {
            $controller_class_name = Controllers\PageNotFoundController::class;
        }

        $controller = new $controller_class_name();

        return $controller;

    }

    private static function getControllerClassName()
    {

        $menu = [];

        require __DIR__ . '/../menu/menu.php';

        $path = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        return $menu[$path] ?? null;

    }

}