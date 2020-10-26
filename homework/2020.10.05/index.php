<!DOCTYPE html>
<html lang="ua">
    <head>
        <title>Домашнє завдання: 2020.10.05</title>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" type="text/css" href="styles.css?<?=rand(1, 100)?>"/>
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
    </head>
    <body>
        <form method="GET" id="example_form">
            <label for="ott_always">Вивести &quot;один два три&quot;</label>
            <select name="ott_always" id="ott_always" class="form-control">
                <option value="yes">так</option>
                <option value="no" selected>ні</option>
            </select>
            <label for="ternary_check">Тернарний оператор з вкладеннями:</label>
            <input id="ternary_check" name="ternary_check" type="number" class="form-control"
                   placeholder="лише цілі числа" pattern="^(-|)[0-9]{1,}$"
                    title="Може бути лише цілим числом..." autocomplete="off"/>
            <button type="submit" class="btn btn-secondary">Підтвердити введення даних</button>
        </form>
        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($_GET['ott_always']) && $_GET['ott_always'] === 'yes') {
                    $x = true;
                    $displayBaseVar = '';
                    $displayBaseVar .= ($x == 1 ? 1 : '');
                    $displayBaseVar .= ($x == 2 ? 2 : '');
                    $displayBaseVar .= ($x == 3 ? 3 : '');
                    if (strlen($displayBaseVar) > 0) {
                        echo "<h4>Результат: <span class=\"result\">{$displayBaseVar}</span>
                                (true і число != 0 при перевірці поверне true).</h4>";
                    }
                } else if (!isset($_GET['ternary_check'])) {
                    echo '<h4>Схоже на те, що Ви не заповнили жодне з полів, але намагаєтеся передати якісь дані методом GET...</h4>';
                }
                if (isset($_GET['ternary_check']) && (!empty($_GET['ternary_check']) || $_GET['ternary_check'] === '0')
                    && preg_match('/^(-|)[0-9]{1,}$/', $_GET['ternary_check'])) {
                    echo '<h4>Перевірка тернарного оператора: '.
                            ((int)$_GET['ternary_check'] === 0 || $_GET['ternary_check'] < 0
                                ? "введене число менше або рівне нулю ({$_GET['ternary_check']})."
                                : ((int)$_GET['ternary_check'] === 1
                                    ? "введене число рівне одиниці ({$_GET['ternary_check']})."
                                    : ((int)$_GET['ternary_check'] === 2
                                        ? "введене число рівне двом ({$_GET['ternary_check']})."
                                        : ($_GET['ternary_check']%2 === 0
                                            ? "введено парне число ({$_GET['ternary_check']})."
                                            : "введено непарне число ({$_GET['ternary_check']}).")))).'<h4/>';
                } elseif (!isset($_GET['ott_always'])) {
                    echo '<h4>Схоже на те, що Ви не заповнили жодне з полів, але намагаєтеся передати якісь дані методом GET...</h4>';
                }
            }
        ?>
    </body>
</html>
