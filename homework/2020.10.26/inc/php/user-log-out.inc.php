<?php

declare(strict_types=1);
error_reporting(E_ALL);

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['username'])) {
    exit();
}

session_unset();
session_destroy();
