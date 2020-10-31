<?php

/**
 * Returns general group of mime type
 * @param string $mimeType - mime type
 * @return string - mime type group
 */
function getMimeTypeGroup(string $mimeType): string
{
    preg_match('/[^\/]+/u', $mimeType, $matches);
    return $matches[0];
}

/**
 * Returns html-tag that visually represents file's content
 * @param string $mimeType - mime type
 * @param string $currentPath - path to current entity
 * @param integer $level - content tree level
 * @return string - final html-tag
 */
function getMimeTypeIcon(string $mimeType = 'directory', string $currentPath = '', int $level = 99999): string
{
    $mimeTypeGroup = getMimeTypeGroup($mimeType);
    switch ($mimeTypeGroup) {
        case 'directory':
            $icon = '<span class="icon dir-icon">&#128194;</span>';
            break;
        case 'addItem':
            $icon = <<<HTML
<span class="icon add-item-icon" title="add new directory or file"
        onclick="addRemoveEntity('add', '{$currentPath}', this)">&plus;</span>
HTML;
            break;
        case 'removeItem':
            $icon = <<<HTML
<span class="icon remove-item-icon"
        title="remove subdirectory" onclick="addRemoveEntity('remove', '{$currentPath}', this)">&minus;</span>
HTML;
            break;
        case 'updateDirectoryName':
            $icon = <<<HTML
<span class="rename-file" title="rename" 
        onclick="updateItem('{$currentPath}', this, 'directory', '{$level}')">&#9999;</span>
HTML;
            break;
        case 'updateFileName':
            $icon = <<<HTML
<span class="rename-file" title="rename" 
        onclick="updateItem('{$currentPath}', this, 'file', '{$level}')">&#9999;</span>
HTML;
            break;
        case 'image':
            $icon = '<span class="icon img-icon">&#128443;</span>';
            break;
        case 'text':
            $icon = '<span class="icon txt-icon">&#128462;</span>';
            break;
        default:
            $icon = '<span class="icon unknown-type-icon">&quest;</span>';
            break;
    }
    return $icon;
}

/**
 * Filters array relatively to filter array
 * @param array $arrayToFilter - array to be filtered
 * @param array $filterArray - filter array
 * @param bool $inArray - allows to choose how to filter array relatively to filter array
 * @return array - filtered array
 */
function arrayFilter(array $arrayToFilter, array $filterArray, bool $inArray = true): array
{
    return array_filter($arrayToFilter, static function ($item) use($filterArray, $inArray): bool {
        return $inArray ? in_array($item, $filterArray, true) : !in_array($item, $filterArray, true);
    });
}

/**
 * Creates html-list for chosen level of content tree
 * @param array $contentNames - entities' names array
 * @param array $permissions - user's permissions to modify entity
 * @param string $path - directory where entities are placed
 * @param int $level - content tree level
 * @param string $wrapper - can be ul or ol
 * @return string - final html-list
 */
function htmlListByLevel(array $contentNames,
                         array $permissions = [],
                         string $path = '',
                         int $level = 0,
                         string $wrapper = 'ul'): string
{
    $contentNames = arrayFilter($contentNames, ['.', '..',], false);
    $htmlList = "<{$wrapper}>";
    foreach ($contentNames as $name) {
        $entityPermissions = array_key_exists($name, $permissions) ? $permissions[$name] : false;
        $entityPermissions = ($level > 0 && is_bool($entityPermissions))
                                ? ['insert', 'update', 'delete'] : $entityPermissions;
        $insert = in_array('insert', $entityPermissions, false);
        $update = in_array('update', $entityPermissions, false);
        $remove = in_array('delete', $entityPermissions, false);
        $entityPath = "{$path}/{$name}";
        if (is_dir($entityPath)) {
            $pattern = file_get_contents(__DIR__ . '/../tpl/dir-level.inc.tpl');
            $icon = getMimeTypeIcon();
            $addEntity = $insert ? getMimeTypeIcon('addItem', $entityPath) : '';
            $updateEntity = $update ? getMimeTypeIcon('updateDirectoryName', $entityPath, $level) : '';
            $removeEntity = $remove ? getMimeTypeIcon('removeItem', $entityPath) : '';
            $htmlList .= sprintf($pattern,
                                $entityPath,
                                $level + 1,
                                $icon,
                                $name,
                                $updateEntity,
                                $addEntity,
                                $removeEntity);
        } else {
            $icon = getMimeTypeIcon(mime_content_type($entityPath));
            $updateEntity = $update ? getMimeTypeIcon('updateFileName', $entityPath, $level) : '';
            $removeEntity = <<<HTML
<span class="remove-file" title="remove" onclick="removeFile(this, '{$entityPath}')">&minus;</span>
HTML;
            $removeEntity = $remove ? $removeEntity : '';
            $htmlList .= <<<HTML
<div>
    <li onclick="getContent('{$entityPath}')">{$icon}{$name}</li>
    {$updateEntity}
    {$removeEntity}
</div>
HTML;
        }
    }
    $htmlList .= "</{$wrapper}>";
    return $htmlList;
}

/**
 * Rebuilds array (php.net function)
 * @param $filePost - array to be rebuilt
 * @return array - rebuilt array
 */
function reArrayFiles(array $filePost): array
{
    $fileArray = [];
    $fileCount = count($filePost['name']);
    $fileKeys = array_keys($filePost);
    for ($i = 0; $i < $fileCount; $i++) {
        foreach ($fileKeys as $key) {
            $fileArray[$i][$key] = $filePost[$key][$i];
        }
    }
    return $fileArray;
}

/**
 * Redirects user to another page
 * @param string $location - new address
 * @param bool $terminate - true/false: if true - PHP-script will be stopped immediately
 */
function changeLocation(string $location, bool $terminate = true): void
{
    header("Location: {$location}");
    if ($terminate) {
        exit();
    }
}

/**
 * @param int $code - HTTP response status code
 * @param string $errorMessage - error description
 * @param bool $terminate - true/false: if true - PHP-script will be stopped immediately
 */
function operationFailed(int $code, string $errorMessage = '', $terminate = true): void
{
    http_response_code($code);
    echo $errorMessage;
    if ($terminate) {
        exit();
    }
}