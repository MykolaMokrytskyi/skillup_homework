<?php

/**
 * Формує контент випадаючого списку доступних дописів
 * @param array $postsDataArray - перелік дописів в форматі асоціативного масиву з json-файлу
 * @param string $htmlPostsInfo - змінна для формування контенту html-тега "select"
 * @param int $infoLength - довжина опису допису
 */
function htmlSelectPostsInfo(array $postsDataArray, string &$htmlPostsInfo, int $infoLength = 55): void
{
    foreach ($postsDataArray as $postId => $postInfo) {
        $postGeneralInfo = substr($postInfo['message'], 0, $infoLength);
        $htmlPostsInfo .= <<<HTML
<option value="{$postId}">{$postId}: {$postGeneralInfo}...</option>
HTML;
    }
}

/**
 * Перенаправляє користувача на нову сторінку
 * @param string $location - адреса, на яку буде перенаправлено користувача
 * @param bool $terminate - показник, який вказує на те, чи буде продовжено виконання сценарію
 */
function changeLocation(string $location, bool $terminate = true): void
{
    header("Location: {$location}");
    if ($terminate) {
        exit();
    }
}

/**
 * Видаляє вказаний допис та всі відповіді до нього
 * @param array $dataArray - перелік дописів у вигляді асоціативного масиву
 * @param string $deletePostId - ID-допису, який потрібно видалити
 */
function deletePosts(array &$dataArray, string $deletePostId): void
{
    unset($dataArray[$deletePostId]);
    foreach ($dataArray as $postId => $data) {
        if ((string)$data['parentPostId'] === $deletePostId) {
            deletePosts($dataArray, (string)$postId);
        }
    }
}
