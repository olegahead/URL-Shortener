<?php

namespace Response;

class HTML implements ResponseInterface
{
    private $templatePath;

    public function setTemplate(string $templatePath)
    {
        $this->templatePath = $templatePath;
    }

    public function send()
    {
        header('Content-Type: text/html');
        echo file_get_contents(TEMPLATES_DIR . "/{$this->templatePath}");
    }
}