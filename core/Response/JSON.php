<?php

namespace Response;

class JSON implements ResponseInterface
{

    const RESPONSE_OK = 0;
    const RESPONSE_INFO = 1;
    const RESPONSE_WARN = 2;
    const RESPONSE_ERROR = 3;

    private $data = [];

    public function send()
    {
        header('Content-Type: application/json');

        echo json_encode($this->data, JSON_UNESCAPED_UNICODE);
    }

    public function setCode(int $code)
    {
        $this->data['code'] = $code;
    }

    public function setMessage(string $message)
    {
        $this->data['message'] = $message;
    }

    public function setPayload($payload)
    {
        $this->data['payload'] = $payload;
    }

}