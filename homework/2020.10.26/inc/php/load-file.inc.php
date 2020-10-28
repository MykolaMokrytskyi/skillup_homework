<?php

declare(strict_types=1);
error_reporting(E_ALL);

require(__DIR__ . '/functions.inc.php');

$parentDirectoryPath = $_POST['parentDirectoryPath'] ?? null;
$attachment = isset($_FILES['attachment']) ? reArrayFiles($_FILES['attachment']) : null;

if (!$attachment || !$parentDirectoryPath) {
    exit();
}

foreach ($attachment as $attach) {
    @move_uploaded_file($attach['tmp_name'], "{$_POST['parentDirectoryPath']}/{$attach['name']}");
}
