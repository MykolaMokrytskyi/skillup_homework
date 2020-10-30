<?php

declare(strict_types=1);
error_reporting(E_ALL);

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['username'])) {
    exit();
}

require(__DIR__ . '/functions.inc.php');

$parentDirectoryPath = $_POST['parentDirectoryPath'] ?? null;
$attachment = isset($_FILES['attachment']) ? reArrayFiles($_FILES['attachment']) : null;

if (!$attachment || !$parentDirectoryPath) {
    operationFailed(302, 'Access error!');
}

$loadCheck = true;

foreach ($attachment as $attach) {
    if (!@move_uploaded_file($attach['tmp_name'], "{$_POST['parentDirectoryPath']}/{$attach['name']}")) {
        $loadCheck = false;
    }
}

if (!$loadCheck) {
    operationFailed(302, 'File loading error!');
}
