<?php

namespace Controllers;

use Model\Links\Statistic\Storage as StatisticStorage;

class PageNotFoundController extends ControllerAbstract
{

    public function GET()
    {

        $linksFacade = new \Model\Links\Facade();

        $linkHash = $linksFacade->extractShortLinkHashFromUrl($_SERVER['REQUEST_URI']);

        if (
            $linkHash
            and $linkRow = $linksFacade->getLinkRowByHash($linkHash)
            and $linkRow['ttl'] >= new \DateTime('now', new \DateTimeZone('GMT+0'))
        ) {

            (new StatisticStorage())->logLinkVisit($linkRow['id'], $_SERVER['REMOTE_ADDR']);
            header("Location: {$linkRow['link']}", 302);

        } else {

            header("HTTP/1.1 404 Not Found");

            $response = new \Response\HTML();
            $this->setResponse($response);

            $response->setTemplate('404.html');

        }

    }

    public function POST()
    {
        return $this->GET();
    }

}