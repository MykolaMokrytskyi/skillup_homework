<?php

declare(strict_types=1);
error_reporting(E_ALL);

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['username'])) {
    exit();
}

require_once(__DIR__ . '/functions.inc.php');

if (!isset($_POST['operationType']) || empty($_POST['operationType'])) {
    operationFailed(302, 'Operation error!');
}

if (!isset($_POST['parentDirectoryPath']) || empty($_POST['parentDirectoryPath'])) {
    operationFailed(302, 'Content error!');
}

if (!isset($_POST['newDirectoryName']) || empty($_POST['newDirectoryName'])
    || !preg_match('/^[A-Z\s0-9]+$/i', $_POST['newDirectoryName'])
    || preg_match('/^[\s]+$/', $_POST['newDirectoryName'])) {
    operationFailed(302, 'Something is wrong with directory\'s name!');;
}

$newDirectoryPath = "{$_POST['parentDirectoryPath']}/{$_POST['newDirectoryName']}";

if ($_POST['operationType'] === 'add') {
    if (is_dir($newDirectoryPath)) {
        operationFailed(302, 'Directory already exists!');
    }
    if (!@mkdir($newDirectoryPath, 0777, false, null)) {
        operationFailed(302, 'Directory creation failed!');
    }
}

if ($_POST['operationType'] === 'remove') {
    if (!is_dir($newDirectoryPath)) {
        operationFailed(302, 'Directory doesn\'t exist!');
    }
    if (!@rmdir($newDirectoryPath)) {
        operationFailed(302, 'Directory removing failed!');
    }
}