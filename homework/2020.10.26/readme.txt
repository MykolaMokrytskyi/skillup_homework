File manager v.2

File manager's maximum level is a root directory of index.php file.
Every new content (directory content info, content itself) loads within ajax.

Every entity of current level will be excluded if it exists in file entities-filter.inc.php.
This file includes an array with info for every level, which has any restrictions.

You can load subdirectory info by clicking on parent directory's icon or name.
You can also do the same to hide subdirectories.

To get some file's content just click on it's name (you'll get file's content only for supported mime types).

If you want to add new entity (directory/file) press plus near name of the existing directory.
If you want to remove some entity - press minus.