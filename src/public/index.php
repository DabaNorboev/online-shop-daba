<?php

use Core\Autoloader;
use Core\App;

require_once './../Core/Autoloader.php';

$autoloader = new Autoloader();
$autoloader->registrate(dirname(__DIR__));

$app = new App();
$app->run();
