<?php

declare(strict_types=1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit();
}

if (!isset($_POST['newDirPath']) || empty($_POST['newDirPath'])) {
    exit();
}

if (!isset($_POST['newItemName']) || empty($_POST['newItemName'])
        || !preg_match('/^[A-ZА-Я\s]+$/iu', $_POST['newItemName'])) {
    exit();
}

if (!isset($_POST['operationType']) || empty($_POST['operationType'])) {
    exit();
}

$newItemName = strtolower($_POST['newItemName']);
$dirPath = "{$_POST['newDirPath']}/{$newItemName}";

if ($_POST['operationType'] === 'add') {
    if (!is_dir($dirPath)) {
        mkdir($dirPath);
    }
}
if ($_POST['operationType'] === 'remove') {
    if (is_dir($dirPath)) {
        rmdir($dirPath);
    }
}