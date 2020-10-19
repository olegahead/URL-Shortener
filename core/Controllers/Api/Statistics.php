<?php

namespace Controllers\Api;

use Model\Links\Facade as LinksFacade;
use Model\Links\Statistic\Storage as StatisticsStorage;
use Request\JSON as RequestJSON;
use Response\JSON as ResponseJSON;

class Statistics extends \Controllers\ControllerAbstract
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

        try {

            $linkRow = (new LinksFacade())->getLinkRowByUrl($request_fields['link']);

            if ($linkRow) {

                $statisticsStorage = new StatisticsStorage();

                $response->setPayload([
                    'today' => $statisticsStorage->getReferralsCountForLastNumDays($linkRow['id']),
                    'last_7_days' => $statisticsStorage->getReferralsCountForLastNumDays($linkRow['id'], 6),
                    'total' => $statisticsStorage->getReferralsCountForLastNumDays($linkRow['id'], 29)
                ]);

                $response->setCode(ResponseJSON::RESPONSE_OK);

            } else {
                $response->setMessage('Информация о ссылке отсутствует.');
                $response->setCode(ResponseJSON::RESPONSE_ERROR);
                return;
            }

        } catch (\Throwable $e) {
            $response->setMessage('Произошла непредвиденная ошибка. Попробуйте позже.');
            $response->setCode(ResponseJSON::RESPONSE_ERROR);
        }

    }

}