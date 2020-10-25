<?php

declare(strict_types=1);
error_reporting(E_ALL);

require_once(__DIR__ . '/inc/php/functions.inc.php');
$excludedEntities = require_once(__DIR__ . '/inc/php/excluded-entities.inc.php');

$content = filteredEntitiesList(__DIR__, $excludedEntities);
$list = createContentHtmlList($content);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Домашнє завдання: 2020.10.21</title>
        <link rel="stylesheet" type="text/css"
              href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="styles.css?<?= mt_rand() ?>">
    </head>
    <body>
        <div id="content">
            <h4>Доступні для перегляду директорії та файли</h4>
            <?= $list ?>
        </div>
        <div id="response_div"></div>
        <script type="text/javascript">
            <?php require_once(__DIR__ . '/inc/js/scripts.js'); ?>
        </script>
    </body>
</html>
