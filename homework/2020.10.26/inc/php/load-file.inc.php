<?php

declare(strict_types=1);
error_reporting(E_ALL);

$parentDirectoryPath = $_POST['parentDirectoryPath'] ?? null;
$attachment = $_FILES['attachment'] ?? null;

if (!$attachment || !$parentDirectoryPath) {
    exit();
}

@move_uploaded_file($attachment['tmp_name'], "{$_POST['parentDirectoryPath']}/{$attachment['name']}");
