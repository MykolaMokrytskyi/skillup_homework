<?php

declare(strict_types=1);
error_reporting(E_ALL);

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['username'])) {
    exit();
}

require(__DIR__ . '/functions.inc.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('<span class="warning">access method isn\'t allowed</span>');
}

if (!isset($_POST['contentPath']) || empty($_POST['contentPath']) || !is_file($_POST['contentPath'])) {
    exit('<span class="warning">Something wrong with content...</span>');
}

$contentPath = $_POST['contentPath'];
$contentName = basename($_POST['contentPath']);
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

$path = dirname($_SERVER['SCRIPT_NAME']);

echo <<<HTML
<div>
    <span class="warning">This file's mime type isn't allowed...you can try to download 
        <a href="{$path}/download-file.inc.php?rout={$contentPath}" target="_blank">{$contentName}</a>.</span>
</div>
HTML;
