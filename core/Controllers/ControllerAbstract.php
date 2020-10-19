<?php

namespace Controllers;

abstract class ControllerAbstract
{

    private $response;

    public function GET()
    {
        header("HTTP/1.1 405 Method Not Allowed");
        echo 'Method Not Allowed';

    }

    public function POST()
    {
        header("HTTP/1.1 405 Method Not Allowed");
        echo 'Method Not Allowed';
    }

    protected function setResponse(\Response\ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getResponse() :? \Response\ResponseInterface
    {
        return $this->response;
    }

}