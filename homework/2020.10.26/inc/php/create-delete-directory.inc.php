<?php

declare(strict_types=1);
error_reporting(E_ALL);

if (!isset($_POST['operationType']) || empty($_POST['operationType'])) {
    exit();
}

if (!isset($_POST['parentDirectoryPath']) || empty($_POST['parentDirectoryPath'])) {
    exit();
}

if (!isset($_POST['newDirectoryName']) || empty($_POST['newDirectoryName'])
    || !preg_match('/^[A-Z\s0-9]+$/i', $_POST['newDirectoryName'])) {
    exit();
}

$newDirectoryPath = "{$_POST['parentDirectoryPath']}/{$_POST['newDirectoryName']}";

if ($_POST['operationType'] === 'add' && !is_dir($newDirectoryPath)) {
    @mkdir($newDirectoryPath, 0777, false, null);
    exit();
}

if ($_POST['operationType'] === 'remove' && is_dir($newDirectoryPath)) {
    @rmdir($newDirectoryPath);
    exit();
}