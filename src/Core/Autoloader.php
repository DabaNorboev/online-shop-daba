<?php

class Autoloader
{
    public function registrate(): void
    {
        $autoloadController = function (string $className): bool {
            $path = "./../Controller/$className.php";
            if (file_exists($path)) {
                require_once $path;
                return true;
            } else {
                return false;
            }
        };

        $autoloadModel = function (string $className): bool {
            $path = "./../Model/$className.php";
            if (file_exists($path)) {
                require_once $path;
                return true;
            } else {
                return false;
            }
        };
        $autoloadCore = function (string $className): bool {
            $path = "./../Core/$className.php";
            if (file_exists($path)) {
                require_once $path;
                return true;
            } else {
                return false;
            }
        };
        spl_autoload_register($autoloadController);
        spl_autoload_register($autoloadModel);
        spl_autoload_register($autoloadCore);
    }
}