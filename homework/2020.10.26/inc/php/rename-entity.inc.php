<?php

declare(strict_types=1);
error_reporting(E_ALL);

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['username'])) {
    exit();
}

require_once(__DIR__ . '/functions.inc.php');

$entityPath = $_POST['entityPath'] ?? null;
$elementType = $_POST['elementType'] ?? null;
$newEntityName = $_POST['entityName'] ?? null;

if (!$entityPath || !$elementType || !$newEntityName || !preg_match('/^[A-Z\s0-9\-]+$/i', $newEntityName)) {
    operationFailed(302, 'Operation error!');
}

$newEntityPath = dirname($entityPath);
$fileExtension = pathinfo($entityPath, PATHINFO_EXTENSION);

if ($elementType === 'directory' && is_dir("{$newEntityPath}/{$newEntityName}")) {
    operationFailed(302, "Directory {$newEntityName} already exists!");
}

if ($elementType === 'file' && is_file("{$newEntityPath}/{$newEntityName}.{$fileExtension}")) {
    operationFailed(302, "File {$newEntityName}.{$fileExtension} already exists!");
}

$level = $_POST['level'] ?? null;

if ($level === null) {
    operationFailed(302, 'Operation error!');
}

$level = (int)$_POST['level'] < 0 ? 0 : (int)$_POST['level'];
$fileExtension = ($elementType === 'file') ? ".{$fileExtension}" : '';
$entityNewName = "{$newEntityName}{$fileExtension}";

if (!@rename($entityPath, "{$newEntityPath}/{$entityNewName}")) {
    operationFailed(302, "Can't rename {$elementType}!");
} else {
    $permissions = require(__DIR__ . '/entities-filter.inc.php');
    $entityOldName = basename($entityPath);

    foreach ($permissions as $user => $userPermissions) {
        if (array_key_exists($level, $userPermissions)) {
            foreach ($userPermissions[$level]['entities'] as $permissionsEntity => $entityPermissions) {
                if ($permissionsEntity === $entityOldName) {
                    $permissions[$user][$level]['entities'][$entityNewName] = $entityPermissions;
                    unset($permissions[$user][$level]['entities'][$entityOldName]);
                    break;
                }
            }
        }
    }

    $arrayString = rtrim(str_replace(['array (', '),'], ['[', '],'], var_export($permissions, true)), ')');
    $arrayString = '<?php' . PHP_EOL . PHP_EOL . "return {$arrayString}];";

    file_put_contents(__DIR__ . '/entities-filter.inc.php', $arrayString);
}