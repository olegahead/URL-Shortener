<?php

namespace Model\Links;

class LinksInserter
{
    /**
     * @var Storage
     */
    private $linksStorage;

    public function insert(string $link, \DateTime $ttl)
    {
        $this->linksStorage->beginTransaction();

        $result = $this->doAddLink($link, $ttl);

        $this->linksStorage->commit();

        return $result;
    }

    public function setStorage(Storage $storage) : void
    {
        $this->linksStorage = $storage;
    }

    private function doAddLink(string $link, \DateTime $ttl)
    {
        $insertResult = new InsertResult();

        $existingLinkRow = $this->linksStorage->findExistingLink('link', $link);

        // Если ссылка была ранее кем-то загружена в систему,
        // тогда просто обновляем её ttl.
        if ($existingLinkRow) {

            if ($existingLinkRow['ttl'] < $ttl) {
                $this->linksStorage->actualizeLinkTTL(
                    $link,
                    $ttl
                );
            }

            $hash = $existingLinkRow['hash'];

            $insertResult->setAlreadyExists(true);

        } else {

            $hash = $this->generateNewHash();

            if (!$this->linksStorage->insert($link, $hash, $ttl)) {
                throw new Exception();
            }

        }

        $insertResult->setLink($this->buildShortLinkWithHash($hash));

        return $insertResult;

    }

    private function generateNewHash()
    {
        $lastGeneratedHash = $this->linksStorage->getLastGeneratedHash();

        $hashGenerator = new HashGenerator();

        return $hashGenerator->generate($lastGeneratedHash);
    }

    private function buildShortLinkWithHash(string $hash) : string
    {
        $domain = \Settings::get('domain');
        return "http://{$domain}/r/{$hash}";
    }

}