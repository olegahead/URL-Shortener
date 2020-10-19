<?php

namespace Controllers\Api;

use Request\JSON as RequestJSON;
use Response\JSON as ResponseJSON;
use \Model\Links\Facade as LinksFacade;

class AddLink extends \Controllers\ControllerAbstract
{
    public function POST()
    {
        $response = new ResponseJSON();
        $this->setResponse($response);

        $request = new RequestJSON();

        $request_fields = $request->getBody();
        $parse_request_error_message = $request->getErrorMessage();

        if ($parse_request_error_message) {
            $response->setMessage($parse_request_error_message);
            $response->setCode(ResponseJSON::RESPONSE_ERROR);
            return;
        }


        if (empty($request_fields['link'])) {
            $response->setMessage('Не передана ссылка.');
            $response->setCode(ResponseJSON::RESPONSE_ERROR);
            return;
        }

        if (strlen($request_fields['link']) > 2000) {
            $response->setMessage('Ссылка превышает допустимый размер.');
            $response->setCode(ResponseJSON::RESPONSE_ERROR);
            return;
        }

        if (!$this->validateURL($request_fields['link'])) {
            $response->setMessage('Недопустимый формат ссылки. Она должна начинаться с http(s)://...');
            $response->setCode(ResponseJSON::RESPONSE_ERROR);
            return;
        }

        if (empty($request_fields['ttl'])) {
            $response->setMessage('Не указано время жизни ссылки.');
            $response->setCode(ResponseJSON::RESPONSE_ERROR);
            return;
        }

        $date = \DateTime::createFromFormat('U', $request_fields['ttl']);

        if (!$date) {
            $response->setMessage("Недопустимое значение параметра 'ttl'. Там должен быть Unix Timestamp.");
            $response->setCode(ResponseJSON::RESPONSE_ERROR);
            return;
        }

        try {

            $linksFacade = new LinksFacade();

            if ($linksFacade->extractShortLinkHashFromUrl($request_fields['link'])) {
                $response->setMessage("Нельзя минимизировать минимизированную ссылку.");
                $response->setCode(ResponseJSON::RESPONSE_ERROR);
                return;
            }

            $result = $linksFacade->insertLink($request_fields['link'], $date);

            if ($result->isAlreadyExists()) {
                $response->setMessage('Данная ссылка уже раннее была кем-то добавлена.');
                $response->setCode(ResponseJSON::RESPONSE_WARN);
            } else {
                $response->setMessage('Ссылка успешно добавлена.');
                $response->setCode(ResponseJSON::RESPONSE_OK);
            }

            $response->setPayload($result->getLink());

        } catch (\Throwable $e) {
            $response->setMessage('Произошла непредвиденная ошибка. Попробуйте позже.');
            $response->setCode(ResponseJSON::RESPONSE_ERROR);
        }

        return $response;
    }

    private function validateURL(string $url) : bool
    {
        return !!preg_match('/^https?:\/\/.+/i', $url);
    }

}