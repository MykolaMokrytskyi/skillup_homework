<?php

declare(strict_types=1);
error_reporting(E_ALL);

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['username'])) {
    exit();
}

$statistic = file_get_contents(__DIR__ . '/../json/statistic.json');

try {
    $statistic = json_decode($statistic, true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
    exit('<span class="warning">Oops! Something went wrong...</span>');
}

uksort($statistic, static function($a, $b) {
    return strtotime($a) - strtotime($b);
});

try {
    $statistic = json_encode($statistic, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
    exit('<span class="warning">Oops! Something went wrong...</span>');
}

header('Content-Type: application/json');
echo $statistic;
exit();