<?php

class Settings
{

    private static $data;

    public static function load()
    {

        $settings = [];

        require SITE_DIR . "/config/config.php";
        require SITE_DIR . "/config/{$_SERVER['HOST_TYPE']}/config.php";

        self::$data = $settings;

    }

    public static function get(string $key)
    {
        return self::$data[$key];
    }

}