<?php

error_reporting(E_ALL);
session_start();

require_once(__DIR__ . '/inc/php/functions.inc.php');
$currentDirectory = str_replace('/var/www', '', __DIR__);

$username = isset($_SESSION['username']) ? " value=\"{$_SESSION['username']}\"" : '';
$message = $_SESSION['message'] ?? '';

$usernameError = (isset($_GET['usernameValidationError']) && isset($_SESSION['username']))
                    ? "<span class=\"warning\"> (невалідний логін - {$_SESSION['username']})</span>" : '';

$messageError = '';

if (isset($_GET['emptyMessage']) && isset($_SESSION['message'])) {
    $messageError = "<span class=\"warning\"> (пустий допис)</span>";
} elseif (isset($_GET['messageValidationError']) && isset($_SESSION['message'])) {
    $messageError = "<br><span class=\"warning\"> (текст містить нецензурні слова)</span>";
}

$postsExist = file_exists(__DIR__ . '/posts-pivot-base.json') &&
                strlen(file_get_contents(__DIR__ .'/posts-pivot-base.json')) > 0;

if ($postsExist) {
    $postsDataArray = json_decode(file_get_contents(__DIR__ . '/posts-pivot-base.json'), true);
    $htmlPostsInfo = '';
    htmlSelectPostsInfo($postsDataArray, $htmlPostsInfo);
}
?>
<!DOCTYPE html>
<html lang="ua">
    <head>
        <meta charset="UTF-8">
        <title>Домашнє завдання: 2020.10.19</title>
        <link rel="stylesheet" type="text/css"
              href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="styles.css?<?= mt_rand() ?>">
    </head>
    <body>
        <?php if (!isset($_GET['obsceneWordsCriticalError']) && !isset($_GET['insertCriticalError'])): ?>
            <h4>
            <?php if (isset($_GET['notPostAccessMethod'])): ?>
                <span class="warning">Ви потрапили на цю сторінку після спроби доступу
                    до файлу опрацювання форми невідповідним методом!</span><br><br>
                    Для початку заповніть форму додавання дописів
            <?php else: ?>
                Форма додавання дописів
            <?php endif;?>
            </h4>
            <form method="post" id="post_form"
                  action="<?= $currentDirectory ?>/form-processing.php">
                <label for="username" class="form-check-label">Автор допису<?= $usernameError ?></label>
                <input type="text" name="username" id="username" required
                       placeholder="логін користувача" class="form-control"
                       title="Лише латинські/кириличні літери в будь-якому регістрі"<?= $username ?>>
                <label for="post_type" class="form-check-label">Тип допису</label>
                <select name="post_type" id="post_type" onchange="showHidePostsList(this)" class="form-control">
                    <option value="new_post">новий</option>
                    <option value="sub_post">доповнення</option>
                </select>
                <label id="current_posts_list_label" for="current_posts_list"
                       class="form-check-label">Перелік існуючих дописів</label>
                <select name="current_posts_list" id="current_posts_list" class="form-control">
                <?php if ($postsExist): ?>
                    <?= $htmlPostsInfo ?>
                <?php endif; ?>
                </select>
                <label for="post_message">Текст допису<?= $messageError ?></label>
                <textarea name="post_message" id="post_message" cols="27" rows="6" class="form-control" required
                          placeholder="текст допису не може містити нецензурну лексику"
                          title="Перелік нецензурних слів - у файлі obscene-words"><?= $message ?></textarea>
                <div>
                    <button type="submit" id="submit_btn" class="btn btn-primary">Додати новий допис</button>
                </div>
            </form>
            <?php require_once(__DIR__ . '/inc/php/main-content.inc.php'); ?>
            <script type="text/javascript">
                <?php require_once(__DIR__ . '/inc/js/scripts.js'); ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <?php require_once(__DIR__ . '/inc/js/form-reset.js'); ?>
                <?php endif; ?>
            </script>
        <?php else: ?>
            <h4 class="warning">
                <?php if (isset($_GET['obsceneWordsCriticalError'])): ?>
                    Критична помилка при перевірці на наявність нецензурної лексики.<br>
                <? elseif (isset($_GET['insertCriticalError'])): ?>
                    Критична помилка при додаванні нового поста.<br>
                <?php endif; ?>
                Функціонал працює некоректно. Зверніться до адміністратора для вирішення проблеми...<br><br>
                <span class="something_is_wrong">&#129300;</span>
            </h4>
        <?php endif; ?>
    </body>
</html>
<?php
session_unset();
session_destroy();
?>
