<?php

namespace Core;

class Autoloader
{
    public static function register(string $directory): void
    {

        $autoloader = function (string $className) use ($directory)
        {
            $path = str_replace('\\', DIRECTORY_SEPARATOR, $className);

            $path = $directory . '/' . $path . '.php';

            if (file_exists($path)) {
                require_once $path;
                return true;
            } else {
                return false;
            }
        };

        spl_autoload_register($autoloader);
    }
}