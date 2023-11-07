<?php
class my_autoload
{
    function __construct()
    {
        spl_autoload_register([$this, 'autoload']);
    }

    private function autoload($class)
    {
        $path = App::getConfig()['autoload'];
        $parts = explode('\\', $class);
        $className = end($parts);
        $pathName = str_replace($className, '', $class);
        $filePath = $path . '\\' . $pathName . $className . '.php';
        if (file_exists($filePath)) {
            require_once $filePath;
        }
    }
}
