<?php
/**
 * @var $postsExist
 * @var $postsDataArray
 * @var $currentDirectory
 */
?>
<div id="content">
    <?php if (isset($_SESSION['success'])): ?>
        <h4 class="success">Операцію додавання даних проведено успішно!</h4>
    <?php endif; ?>
    <?php if (isset($_SESSION['successDelete'])): ?>
        <h4 class="success">Операцію видалення даних проведено успішно!</h4>
    <?php endif; ?>
    <?php if ($postsExist): ?>
        <a href="#pageBottom" id="go_bottom">
            <button class="btn btn-success btn-sm">Перейти до кінця сторінки</button>
        </a>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID допису</th>
                <th>Батьківський ID</th>
                <th>Автор допису</th>
                <th>Текст допису</th>
                <th>Дата створення</th>
                <th>Відповісти на допис</th>
                <th>Видалити допис</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($postsDataArray as $postId => $postInfo): ?>
                <tr id="<?= $postId ?>">
                    <td><?= $postId ?></td>
                    <?php if ($postInfo['parentPostId'] === null): ?>
                        <td></td>
                    <?php else: ?>
                        <td>ID: <?= $postInfo['parentPostId'] ?>&nbsp;&nbsp;&nbsp;
                            <a href="#<?= $postInfo['parentPostId'] ?>">
                                <button class="btn btn-secondary btn-sm">Перейти</button>
                            </a>
                        </td>
                    <?php endif; ?>
                    <td><?= $postInfo['username'] ?></td>
                    <td><?= $postInfo['message'] ?></td>
                    <td><?= date('d.m.Y H:i:s', $postInfo['unixTimeCreated']) ?></td>
                    <td>
                        <a href="#">
                            <button class="btn btn-secondary btn-sm" onclick="setValues(<?= $postId ?>)">
                                Відповісти</button>
                        </a>
                    </td>
                    <td>
                        <a href="<?= $currentDirectory ?>/post-delete.php?deletePostID=<?= $postId ?>">
                            <button class="btn btn-secondary btn-sm">
                                Видалити допис і всі відповіді</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <a href="#" id="pageBottom">
            <button class="btn btn-success btn-sm">Перейти до початку сторінки</button>
        </a>
    <?php endif; ?>
</div>