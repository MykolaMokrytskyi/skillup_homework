<?php

declare(strict_types=1);
error_reporting(E_ALL);

require(__DIR__ . '/inc/php/functions.inc.php');
$entitiesFilter = require(__DIR__ . '/inc/php/entities-filter.inc.php');

$entitiesList = scandir(__DIR__);
$allowedEntities = $entitiesFilter[0]['allowed'];
$filterEntitiesList = $entitiesFilter[0]['entities'];
$entitiesList = arrayFilter($entitiesList, $filterEntitiesList, $allowedEntities);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>File manager v.2</title>
    <link rel="stylesheet" type="text/css"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="inc/css/styles.css?<?= mt_rand() ?>">
</head>
<body>
<div id="content">
    <h4>Content tree</h4>
    <?= htmlListByLevel($entitiesList, __DIR__) ?>
</div>
<div id="response-div"></div>
<script type="text/javascript">
    <?php require(__DIR__ . '/inc/js/scripts.js') ?>
</script>
</body>
</html>
