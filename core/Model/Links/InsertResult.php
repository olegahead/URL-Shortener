<?php

namespace Model\Links;

class InsertResult
{
    private $link;
    private $isAlreadyExists = false;

    public function getLink() : string
    {
        return $this->link;
    }

    public function setLink(string $link) : void
    {
        $this->link = $link;
    }

    public function isAlreadyExists() : bool
    {
        return $this->isAlreadyExists;
    }

    public function setAlreadyExists(bool $isAlreadyExists) : void
    {
        $this->isAlreadyExists = $isAlreadyExists;
    }

}