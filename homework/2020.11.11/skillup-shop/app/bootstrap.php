<?php

declare(strict_types =1);
error_reporting(E_ALL);

require_once(__DIR__ . '/core/Model.php');
require_once(__DIR__ . '/core/View.php');
require_once(__DIR__ . '/core/Controller.php');
require_once(__DIR__ . '/core/Route.php');

Route::start();