<?php

class App
{
    public static function run($withUriHandling = true)
    {
        Settings::load();

        if ($withUriHandling) {

            $controller = Router::getController();

            $method_name = static::getCurrentRequestMethod();

            /** @var \Response\ResponseInterface $response */
            $controller->$method_name();

            $response = $controller->getResponse();

            if ($response) {
                $response->send();
            }

        }

    }

    private static function getCurrentRequestMethod() : string
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST' ? 'POST' : 'GET';
    }

}