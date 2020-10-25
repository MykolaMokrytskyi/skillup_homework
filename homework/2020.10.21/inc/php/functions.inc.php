<?php

/**
 * Формує дерево вкладень з файлами, директоріями відносно початкової директорії
 * @param string $rootPath - шлях, у вмісті якого буде перевірятися контент
 * @param array $excludedEntities - перелік файлів, директорій, які буде виключено з переліку контенту
 * @param bool $firstLevel - показник, який вказує, на якому рівні буду фільтруватися файли, директорії
 * @return array - сформоване дерево вкладень з файлів і директорій
 */
function filteredEntitiesList(string $rootPath, array $excludedEntities, bool $firstLevel = true): array
{
    $directories = [];
    $excludedEntities = $firstLevel ? $excludedEntities : ['.', '..',];
    $directoryEntities = array_filter(scandir($rootPath), static function($item) use($excludedEntities): bool  {
        return !in_array($item, $excludedEntities);
    });
    foreach ($directoryEntities as $entity) {
        $subDirectory = "{$rootPath}/{$entity}";
        if (is_dir($subDirectory)) {
            $directories[] = $entity;
            $directoryEntities[$entity] = filteredEntitiesList($subDirectory, $excludedEntities, false);
        }
    }
    $directoryEntities = array_filter($directoryEntities, static function($item) use($directories): bool  {
        return !in_array($item, $directories);
    });
    return $directoryEntities;
}

/**
 * Функція повертає іконку для візуальної орієнтації на контент файлу
 * @param string $mimeContentGroup - узагальнююча група mime-контенту
 * @return string - html-тег з посиланням на зображення
 */
function getMimeTypeIcon(string $mimeContentGroup = 'directory'): string
{
    switch ($mimeContentGroup) {
        case 'directory':
            $iconPath = 'inc/img/directory.png';
            break;
        case 'newDirectory':
            $iconPath = 'inc/img/new_dir.png';
            break;
        case 'text':
            $iconPath = 'inc/img/txt.png';
            break;
        case 'image':
            $iconPath = 'inc/img/image.png';
            break;
        default:
            $iconPath = '';
    }
    return $iconPath ? "<img src='{$iconPath}' alt='image'>" : '';
}

/**
 * Функція формує html-список з сформованого масиву контенту
 * @param array $contentArray - масив контенту директорії сформований функцією filteredEntitiesList
 * @param string $path - шлях відносно дерева вкладень
 * @return string - сформований html-список
 */
function createContentHtmlList(array $contentArray, string $path = ''): string
{
    $htmlList = '<ul>';
    foreach ($contentArray as $contentKey => $contentValue) {
        if (is_array($contentValue)) {
            $icon = getMimeTypeIcon();
            $newDirectory = getMimeTypeIcon('newDirectory');
            $htmlList .= ($path === '') ? '' : '<div>';
            $htmlList .= <<<HTML
<span>{$icon}{$contentKey}
    <a href="#" onclick="addNewItem('{$path}{$contentKey}', 'directory')">{$newDirectory}</a>
</span>
HTML;
            $htmlList .= createContentHtmlList($contentValue, "{$path}{$contentKey}/");
            $htmlList .= ($path === '') ? '' : '</div>';
        } else {
            preg_match('/[^\/]+/', mime_content_type("{$path}{$contentValue}"), $matches);
            $mimeContentGroup = $matches[0];
            $icon = getMimeTypeIcon($mimeContentGroup);
            $htmlList .= <<<HTML
<li>
    <a href="#" onclick="getContent('{$path}{$contentValue}', '{$mimeContentGroup}')">{$icon}{$contentValue}</a>
</li>
HTML;
        }
    }
    return "{$htmlList}</ul>";
}

/**
 * Фукнція для виведення інформації про помилку при отриманні контенту
 * @param string $errorInfo - інформація про помилку, яку буде виведено користувачу
 * @param bool $terminate - змінна, яка вказує, чи буде продовжено виконання сценарію
 */
function getContentError(string $errorInfo, bool $terminate = true): void
{
    echo $errorInfo;
    if ($terminate) {
        exit();
    }
}