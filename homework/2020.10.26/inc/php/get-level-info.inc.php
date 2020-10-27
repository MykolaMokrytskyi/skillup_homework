<?php

declare(strict_types=1);
error_reporting(E_ALL);

require(__DIR__ . '/functions.inc.php');
$entitiesFilter = require(__DIR__ . '/entities-filter.inc.php');

$entitiesList = scandir($_POST['levelPath']);
$levelNumber = (int)$_POST['levelNumber'];

if (array_key_exists($levelNumber, $entitiesFilter)) {
    $allowedEntities = $entitiesFilter[$levelNumber]['allowed'];
    $filterEntitiesList = $entitiesFilter[$levelNumber]['entities'];
    $entitiesList = arrayFilter($entitiesList, $filterEntitiesList, $allowedEntities);
}
echo htmlListByLevel($entitiesList, $_POST['levelPath'], $levelNumber);
