<?php

declare(strict_types=1);
error_reporting(E_ALL);

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['username'])) {
    exit();
}

require(__DIR__ . '/functions.inc.php');
$entitiesFilter = require(__DIR__ . '/entities-filter.inc.php');

$entitiesList = scandir($_POST['levelPath']);
$levelNumber = (int)$_POST['levelNumber'];

$filterEntitiesList = [];

if (array_key_exists($levelNumber, $entitiesFilter[$_SESSION['username']])) {
    $allowedEntities = $entitiesFilter[$_SESSION['username']][$levelNumber]['allowed'];
    $filterEntitiesList = $entitiesFilter[$_SESSION['username']][$levelNumber]['entities'];
    $entitiesList = arrayFilter($entitiesList, array_keys($filterEntitiesList), $allowedEntities);
}

echo htmlListByLevel($entitiesList, $filterEntitiesList, $_POST['levelPath'], $levelNumber);
