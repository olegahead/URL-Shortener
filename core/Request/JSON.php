<?php

namespace Request;

class JSON
{
    private $errorMessage;

    public function getBody() :? array
    {
        $request_body = file_get_contents('php://input');

        if (!$request_body) {
            $this->errorMessage = 'Отсутствует тело запроса.';
            return null;
        }

        $json = json_decode($request_body, true);

        if (!$json) {
            $this->errorMessage = 'Некорректное тело запроса.';
            return null;
        }

        return $json;

    }

    public function getErrorMessage() :? string
    {
        return $this->errorMessage;
    }

}