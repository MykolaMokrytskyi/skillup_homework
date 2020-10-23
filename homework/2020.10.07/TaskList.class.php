<?php
class TaskList {
    protected array $taskList;/* Масив, який буде заповнюватися завданнями */
    protected string $taskListName;/* Назва переліку завдань */
    protected int $taskId = 1;
    protected array $statuses =/* Статуси завдань в читабельному вигляді */
        [
            'OK' => 'Не взяте в роботу',
            'AC' => 'В роботі',
            'FI' => 'Завершене',
            '--' => 'Анульоване',
        ];
    /* При створенні об'єкту виводжу користувачу повідомлення про те, що пустий перелік створено */
    public function __construct(string $taskListName)
    {
        $this->taskList = [];
        $this->taskListName = $taskListName ?: 'Назва за замовчуванням';
        echo "<p>Об'єкт \"{$this->taskListName}\" класу \"".__CLASS__."\" успішно створено.</p>";
    }
    /* При знищенні об'єкту виводжу користувачу повідомлення про те, що роботу з даним об'єктом завершено */
    public function __destruct()
    {
        echo "<p>Об'єкт \"{$this->taskListName}\" класу \"".__CLASS__."\" успішно зруйновано - посилань на даний об'єкт не залишилося.</p>";
    }
    /* Додавання нового завдання до переліку */
    public function setNewTask(string $title, string $description, string $owner, string $status): void
    {
        $this->taskList[$title] =
            [
                'id' => $this->taskId,
                'description' => $description,
                'owner' => $owner,
                'status' => $status,
            ];
        $this->taskId++;
    }
    /* Додавання нового підзавдання до головного завдання */
    public function setSubTask(string $mainTitle, string $title, string $description, string $owner, string $status): void
    {
        if (array_key_exists($mainTitle, $this->taskList)) {
            $this->taskList[$mainTitle][$title] =
                [
                    'id' => count($this->taskList[$mainTitle]) - 3,
                    'description' => $description,
                    'owner' => $owner,
                    'status' => $status,
                ];
        } else {
            echo "<p class=\"warning\">Не можна встановити підзвадання для неіснуючого завдання \"{$mainTitle}\"!</p>";
        }
    }
    /* Неформатоване виведення інформації по поточному переліку завдань */
    public function dumpTaskList(): void
    {
        echo '<p>Неформатоване виведення інформації по поточному переліку завдань:</p><div class="dump_div"><pre>';
        var_dump($this->taskList);
        echo '</pre></div>';
    }
    /* Виведення інформації по поточному переліку завдань у вигляді HTML-таблиці */
    public function htmlTaskList(): void
    {
        $htmlTable = <<<HTML
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th colspan="5">{$this->taskListName}</th>
                    </tr>
                    <tr>
                        <th>ID завдання</th>
                        <th>Назва завдання</th>
                        <th>Короткий опис</th>
                        <th>Відповідальна особа</th>
                        <th>Статус виконання</th>
                    </tr>
                </thead>
               <tbody>
        HTML;
        foreach ($this->taskList as $task => $taskInfo) {
            $htmlTable .= <<<HTML
                <tr>
                    <td>{$taskInfo['id']}</td>
                    <td>"{$task}"</td>
                    <td>{$taskInfo['description']}</td>
                    <td>{$taskInfo['owner']}</td>
                    <td>{$this->statuses[$taskInfo['status']]}</td>
                </tr>
            HTML;
            if (count($this->taskList[$task]) > 4) {
                $keysList = array_keys($this->taskList[$task]);
                for ($a = 4; $a < count($keysList); $a++) {
                    $htmlTable .= <<<HTML
                        <tr class="sub_task">
                            <td>{$taskInfo['id']}.{$taskInfo[$keysList[$a]]['id']}</td>
                            <td>"{$keysList[$a]}": підзавдання "{$task}"</td>
                            <td>{$taskInfo[$keysList[$a]]['description']}</td>
                            <td>{$taskInfo[$keysList[$a]]['owner']}</td>
                            <td>{$this->statuses[$taskInfo[$keysList[$a]]['status']]}</td>
                        </tr>
                    HTML;
                }
            }
        }
        $htmlTable .= '</tbody></table>';
        echo $htmlTable;
    }
}