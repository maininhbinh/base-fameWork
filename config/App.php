<?php
class App
{
    public static $config;

    static function setConfig($config)
    {
        self::$config = $config;
    }
    public static function getConfig()
    {
        return self::$config;
    }
}
