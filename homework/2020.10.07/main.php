<!DOCTYPE html>
<html lang="ua">
    <head>
        <title>Домашнє завдання: 2020.10.07</title>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" type="text/css" href="styles.css?<?=rand(1, 100)?>"/>
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
    </head>
    <body>
        <?php
            /* Автозавантаження класу TaskList: за допомогою нього буде створюватися масив (з вкладеннями, якщо необхідно) */
            spl_autoload_register('myAutoload');
            function myAutoload(string $className): void
            {
                $filePath = "{$className}.class.php";
                if (file_exists($filePath)) {
                    include_once($filePath);
                } else {
                    exit("Клас \"{$className}\" не знайдено!");
                }
            }
            $taskList = new TaskList('Перелік завдань SkillUp');/* Новий об'єкт класу TaskList */
            $description = 'Вивчення концептуальних понять пов\'язаних з мовою';
            /* Додавання нового завдання до переліку завдань */
            $taskList->setNewTask('Концепція PHP', $description, 'Всі студенти курсу', 'FI');
            $description = 'Встановлення ПЗ необхідного для роботи з PHP';
            $taskList->setNewTask('Вступ до PHP', $description, 'Всі студенти курсу', 'FI');
            $description = 'Для безпосереднього написання коду';
            /* Додавання нового підзавдання до вже існуючого завдання */
            $taskList->setSubTask('Вступ до PHP', 'PHPStorm', $description, 'Всі студенти курсу', 'FI');
            /* Додавання нового підзавдання до неіснуючого завдання - видасть попередження */
            $taskList->setSubTask('Вступ до JAVA', 'IDEA', $description, 'Всі студенти курсу', 'FI');
            $description = 'Для створення локального середовища розробки';
            $taskList->setSubTask('Вступ до PHP', 'Docker', $description, 'Всі студенти курсу', 'FI');
            $taskList->setSubTask('Вступ до PHP', 'GIT', 'Для роботи в команді', 'Всі студенти курсу', 'FI');
            $description = 'Прийти на вступне заняття, ознайомитися з синтаксисом, повернутися до мов з C-подібним синтаксисом';
            $taskList->setNewTask('Запис на курс Python', $description, 'Всі студенти курсу', '--');
            $title = 'Ознайомлення з базовими поняттями PHP';
            $description = 'Типи даних, змінні, масиви, цикли...';
            $taskList->setNewTask($title, $description, 'Всі студенти курсу', 'AC');
            $description = 'Навчитися робити так, як робити не треба';
            $taskList->setSubTask($title, 'Зробити огидний тернарник', $description, 'Всі студенти курсу', 'FI');
            $description = 'Увімкнути фантазію і уявити, що масив - база даних';
            $taskList->setSubTask($title, 'Зробити масив з завданнями', $description, 'Всі студенти курсу', 'FI');
            $description = 'Поглиблені знання для більш широкого розуміння не лише PHP, а програмування взагалі';
            $taskList->setNewTask('Вивчення ООП', $description, 'Всі студенти курсу', 'OK');
            /* Виведення отриманого масиву простою HTML-таблицею */
            $taskList->htmlTaskList();
            /* Звичайний var_dump() створеного масиву */
            $taskList->dumpTaskList();
        ?>
    </body>
</html>