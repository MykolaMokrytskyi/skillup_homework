<?php

function htmlSelectPostsInfo(array $postsDataArray, string &$htmlPostsInfo, int $infoLength = 55): void
{
    foreach ($postsDataArray as $postId => $postInfo) {
        $postGeneralInfo = substr($postInfo['message'], 0, $infoLength);
        $htmlPostsInfo .= <<<HTML
<option value="{$postId}">{$postId}: {$postGeneralInfo}...</option>
HTML;
    }
}

function changeLocation(string $location, bool $terminate = true): void
{
    header("Location: {$location}");
    if ($terminate) {
        exit();
    }
}

function deletePosts(array &$dataArray, string $deletePostId): void
{
    unset($dataArray[$deletePostId]);
    foreach ($dataArray as $postId => $data) {
        if ((string)$data['parentPostId'] === $deletePostId) {
            deletePosts($dataArray, (string)$postId);
        }
    }
}
