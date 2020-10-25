<?php

declare(strict_types=1);
error_reporting(E_ALL);

require_once(__DIR__ . '/inc/php/functions.inc.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    getContentError('Помилка доступу!');
}

if (!isset($_POST['contentPath']) || empty($_POST['contentPath'])) {
    getContentError('Помилка обробки даних');
}

if (!isset($_POST['contentMimeTypeGroup']) || empty($_POST['contentMimeTypeGroup'])) {
    getContentError('Помилка обробки даних');
}

$contentPath = $_POST['contentPath'];
$contentMimeTypeGroup = $_POST['contentMimeTypeGroup'];

if ($contentMimeTypeGroup === 'image') {
    echo "<img src='{$contentPath}' alt='404: файл не знайдено'>";
    exit();
}

if ($contentMimeTypeGroup === 'text') {
    $txtBase = '<p>';
    $file = fopen($contentPath, 'rb');
    while (($row = fgets($file)) != false) {
        $txtBase .= "{$row}<br/>";
    }
    fclose($file);
    $txtBase .= '</p>';
    echo $txtBase;
    exit();
}

echo 'Даний тип не підтримується';