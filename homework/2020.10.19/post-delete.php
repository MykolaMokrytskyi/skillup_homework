<?php

declare(strict_types=1);
error_reporting(E_ALL);

session_start();
$_SESSION['successDelete'] = true;

require_once(__DIR__ . '/inc/php/functions.inc.php');
$config = require_once(__DIR__ . '/inc/php/config.inc.php');
$redirectBase = $config['redirectBase'];
$postsData = file_get_contents(__DIR__ . '/posts-pivot-base.json');

if (!isset($_GET['deletePostID']) || empty($_GET['deletePostID']) || strlen($postsData) < 1) {
    changeLocation("{$redirectBase}?successDelete");
}

$postsData = json_decode($postsData, true);
deletePosts($postsData, $_GET['deletePostID']);
file_put_contents(__DIR__ . '/posts-pivot-base.json', $postsData ? json_encode($postsData) : '');
changeLocation("{$redirectBase}?successDelete");