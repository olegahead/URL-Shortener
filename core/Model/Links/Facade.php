<?php

namespace Model\Links;

class Facade
{
    private $linksInserterInstance;
    private $linksStorageInstance;

    private function linksInserter()
    {
        if (!$this->linksInserterInstance) {

            $this->linksInserterInstance = new LinksInserter();
            $this->linksInserterInstance->setStorage($this->storage());

        }

        return $this->linksInserterInstance;
    }

    private function storage()
    {
        if (!$this->linksStorageInstance) {
            $this->linksStorageInstance = new Storage();
        }

        return $this->linksStorageInstance;
    }

    public function insertLink(string $url, \DateTime $ttl)
    {
        return $this->linksInserter()->insert($url, $ttl);
    }

    public function getLinkRowByUrl(string $url) :? array
    {
        $linkRow = $this->storage()->findExistingLink('link', $url);

        if (
            !$linkRow
            and $linkHash = $this->extractShortLinkHashFromUrl($url)
        ) {
            $linkRow = $this->getLinkRowByHash($linkHash);
        }

        return $linkRow ?? null;
    }

    public function getLinkRowByHash(string $hash) :? array
    {
        return $this->storage()->findExistingLink('hash', $hash);
    }

    public function extractShortLinkHashFromUrl(string $url) :? string
    {
        $domain = preg_quote(\Settings::get('domain'));

        preg_match('/^(?:https?:\/\/' . $domain . ')?\/r\/(\w+)\/?$/', $url, $matches);
        return $matches[1] ?? null;
    }

}