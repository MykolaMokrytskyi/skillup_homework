<?php

declare(strict_types=1);
error_reporting(E_ALL);

require_once(__DIR__ . '/user-auth-check.inc.php');

if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_SESSION['username'])) {
    exit();
}

$filePath = $_GET['rout'] ?? null;

if (!$filePath) {
    exit('File doesn\'t exist!');
}

$mimeType = mime_content_type($filePath);
$fileName = basename($filePath);
$fileSize = filesize($filePath);

header("Content-Type: {$mimeType}");
header("Content-Disposition: attachment; filename={$fileName}");
header("Content-Length: {$fileSize}");
header('Pragma: no-cache');

readfile($filePath);
exit();