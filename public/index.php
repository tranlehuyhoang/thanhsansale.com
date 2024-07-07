<?php

use App\Core\App;
use App\Core\ErrorMiddleware;
use App\Core\Logger;
use App\Core\Router;
use App\Services\Common\Session;

// Public\Index.php
require_once '../vendor/autoload.php'; // Assuming you're using Composer for autoloading
Session::start();
$logger = new Logger();
$router = new Router();
$errorMiddleware = new ErrorMiddleware($logger);
$app = new App($router, $errorMiddleware);
$app->run();
