<?php

declare(strict_types=1);
error_reporting(E_ALL);

require(__DIR__ . '/functions.inc.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit();
}

if (!isset($_POST['contentPath']) || empty($_POST['contentPath']) || !is_file($_POST['contentPath'])) {
    exit();
}

$contentPath = $_POST['contentPath'];
$mimeTypeGroup = getMimeTypeGroup(mime_content_type($_POST['contentPath']));

if ($mimeTypeGroup === 'image') {
    $config = require(__DIR__ . '/config.inc.php');
    $contentPath = str_replace($config['baseDir'], '', $contentPath);
    echo "<img src='{$contentPath}' alt='404: not found'>";
    exit();
}

if ($mimeTypeGroup === 'text') {
    $txtBase = '<p>';
    $file = fopen($contentPath, 'rb');
    while (($row = fgets($file)) !== false) {
        $txtBase .= nl2br($row);
    }
    fclose($file);
    $txtBase .= '</p>';
    echo $txtBase;
    exit();
}

echo '<span class="warning">This file\'s mime type isn\'t allowed...</span>';
