<?php

declare(strict_types=1);
error_reporting(E_ALL);

session_start();

require_once(__DIR__ . '/inc/php/functions.inc.php');
$config = require_once(__DIR__ . '/inc/php/config.inc.php');
$redirectBase = $config['redirectBase'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    changeLocation("{$redirectBase}?notPostAccessMethod");
}

if (!file_exists(__DIR__ . '/inc/php/obscene-words.inc.php')) {
    changeLocation("{$redirectBase}?obsceneWordsCriticalError");
}

if (!file_exists(__DIR__ . '/posts-pivot-base.json')) {
    changeLocation("{$redirectBase}?insertCriticalError");
}

$username = $_POST['username'] ?? '';
$message = htmlspecialchars(trim($_POST['post_message'] ?? ''));
$_SESSION['username'] = $username;
$_SESSION['message'] = $message;
$errors = [];

if (!preg_match('/^[A-ZА-Я]+$/iu', $username)) {
    $errors[] = 'usernameValidationError';
}

if (!$message) {
    $errors[] = 'emptyMessage';
}

/* Для тих, хто хоче збагатитися новими словами - welcome */
$obsceneWordsArray = require_once(__DIR__ . '/inc/php/obscene-words.inc.php');
$obsceneWords = implode(')|(', $obsceneWordsArray);

if (preg_match("/({$obsceneWords})/iu", $message)) {
    $errors[] = 'messageValidationError';
}

if ($errors) {
    $errors = implode('&', $errors);
    changeLocation("{$redirectBase}?{$errors}");
}

$postsData = file_get_contents(__DIR__ . '/posts-pivot-base.json');
$postsExist = strlen($postsData) > 0;
$postDataArray = $postsExist ? json_decode($postsData, true) : [];
$recordId = $postsExist ? max(array_keys($postDataArray)) + 1 : 1;

$postDataArray[$recordId] =
    [
        'username' => $username,
        'message' => $message,
        'unixTimeCreated' => time(),
        'parentPostId' => ($_POST['post_type'] === 'new_post') ? null : (int)$_POST['current_posts_list'],
    ];

file_put_contents(__DIR__ . '/posts-pivot-base.json', json_encode($postDataArray));
$_SESSION['success'] = true;
changeLocation("{$redirectBase}?success");
