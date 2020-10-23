<?php

preg_match_all('/[^\/]+/', $_SERVER['SCRIPT_NAME'], $scriptNameParts);
$scriptNameParts = array_slice($scriptNameParts[0], 0, -1);

$redirectBaseParameters =
    [
        'accessProtocol' => isset($_SERVER['HTTPS']) ? 'https://' : 'http://',
        'hostName' => $_SERVER['HTTP_HOST'],
        'scriptPath' => '/' . implode('/', $scriptNameParts) . '/',
    ];

return [
    'redirectBase' => implode('', $redirectBaseParameters),
];
