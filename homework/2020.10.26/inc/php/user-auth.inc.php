<?php

declare(strict_types=1);
error_reporting(E_ALL);

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || isset($_SESSION['username'])) {
    exit();
}

require_once(__DIR__ . '/functions.inc.php');

$errorRedirect = 'Location: ../../?error';
$usersBase = file_get_contents(__DIR__ . '/../json/users-base.json');

try {
    $usersBase = json_decode($usersBase, true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
    changeLocation($errorRedirect);
}

$login = $_POST['login'] ?? null;
$password = $_POST['password'] ?? null;

if (!$login || !$password || !array_key_exists($login, $usersBase)) {
    changeLocation($errorRedirect);
}

$userPasswordHash = $usersBase[$login];

if (!password_verify($password, $userPasswordHash)) {
    changeLocation($errorRedirect);
}

$_SESSION['username'] = $login;
$redirectAddress = dirname($_SERVER['SCRIPT_NAME'], 3);

$statistic = file_get_contents(__DIR__ . '/../json/statistic.json');

try {
    $statistic = json_decode($statistic, true, 512, JSON_THROW_ON_ERROR) ?? [];
} catch (JsonException $e) {
    changeLocation($redirectAddress);
}

$currentDate = date('d.m.Y');

if (!array_key_exists((string)$currentDate, $statistic)) {
    $statistic[$currentDate] = [$login => 1];
} elseif (array_key_exists($login, $statistic[$currentDate])) {
    $statistic[$currentDate][$login]++;
} else {
    $statistic[$currentDate][$login] = 1;
}

try {
    file_put_contents(__DIR__ . '/../json/statistic.json', json_encode($statistic, JSON_THROW_ON_ERROR));
} catch (JsonException $e) {}

changeLocation($redirectAddress);