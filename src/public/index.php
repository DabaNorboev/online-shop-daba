<?php

use Core\Autoloader;
use Core\App;

require_once './../Core/Autoloader.php';

Autoloader::registrate(dirname(__DIR__));

$app = new App();
$app->run();
