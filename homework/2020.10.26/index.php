<?php

declare(strict_types=1);
error_reporting(E_ALL);

require(__DIR__ . '/inc/php/user-auth-check.inc.php');

require(__DIR__ . '/inc/php/functions.inc.php');
$entitiesFilter = require(__DIR__ . '/inc/php/entities-filter.inc.php');

$entitiesList = scandir(__DIR__);
$allowedEntities = $entitiesFilter[$_SESSION['username']][0]['allowed'];
$filterEntitiesList = $entitiesFilter[$_SESSION['username']][0]['entities'];
$entitiesList = arrayFilter($entitiesList, array_keys($filterEntitiesList), $allowedEntities);

$statistic = file_get_contents(__DIR__ . '/inc/json/statistic.json');

try {
    $statistic = json_decode($statistic, true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {}

$statistic = $statistic[(string)date('d.m.Y')];

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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body>
<div id="nav">
    <span><?= mb_strtoupper("You're logged in as {$_SESSION['username']}") ?></span>
    <button class="btn btn-primary btn-sm" onclick="userLogout()">LOG OUT</button>
    <button class="btn btn-primary btn-sm" onclick="showStatistic()">DETAIL STATISTIC</button>
</div>
<div id="content">
    <h4>Content tree</h4>
    <?= htmlListByLevel($entitiesList, $filterEntitiesList, __DIR__) ?>
</div>
<div id="statistic">
    <span>TODAY'S VISITS INFO: UNIQUE VISITORS - <?= count($statistic) ?>,
        VISITS AT ALL - <?= array_sum($statistic) ?></span>
</div>
<div id="response-div"><div id="chart"></div></div>
<script type="text/javascript">
    <?php require(__DIR__ . '/inc/js/scripts.js') ?>
</script>
</body>
</html>
