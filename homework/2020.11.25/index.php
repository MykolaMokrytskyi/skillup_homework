<?php

declare(strict_types=1);
error_reporting(E_ALL);

require_once(__DIR__ . '/vendor/autoload.php');

use app\core\Dispatcher;
use app\core\Route;

$requestAddress = $_SERVER['REQUEST_URI'];

if (preg_match('/(\/index.php)/i', $requestAddress)) {
    $requestAddress = substr($requestAddress, strlen('/index.php'));
}

$route = new Route((new Dispatcher($requestAddress))->getRequestInfo());