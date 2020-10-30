<?php

declare(strict_types=1);
error_reporting(E_ALL);

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['username'])) {
    exit();
}

require_once(__DIR__ . '/functions.inc.php');

$filePath = $_POST['entityPath'];

if (!is_file($filePath)) {
    operationFailed(302, 'File doesn\'t exists!');
}

if (!@unlink($_POST['entityPath'])) {
    operationFailed(302, 'Can\'t remove file!');
}