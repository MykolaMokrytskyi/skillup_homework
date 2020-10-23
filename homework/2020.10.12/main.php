<!DOCTYPE html>
<html lang="ua">
	<head>
		<title>Домашнє завдання: 2020.10.12</title>
		<meta charset="UTF-8"/>
		<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
        <style type="text/css">
            body {
                padding: 20px 0px 40px 0px;
            }
            h4 {
                font-size: 17px;
                margin-left: 15px;
                color: green;
            }
        </style>
	</head>
	<body>
		<?php
        include_once('../2020.10.07/ArrayOnly.php');
			/*
				Функція формує контент HTML-таблиці з асоціативного масиву.
				Є два режими роботи: 1. через foreach-цикл (режим за замовчуванням); 2. через for-цикл.
				Функція опрацьовує всі рівні вкладення асоціативного масиву з структурою $key => [].
			*/
			function fillHtmlTable(array $dataArray, string $funcMode, string $parentTask = null, string $parentTaskId = null): string
			{
				$html = '';/* Змінна для послідовного формування контенту HTML-таблиці */
				/* Змінні $parentTaskInfo та $parentTaskInfoId мають значення відмінні від null лише при рекурсивному виклиці функції fillHtmlTable */
				$parentTaskInfo = ($parentTask === null ? '' : ": підзавдання \"{$parentTask}\"");
				$parentTaskInfoId = ($parentTaskId === null ? '' : "{$parentTaskId}.");
				/* Опрацювання масиву циклом foreach */
				if ($funcMode === 'foreach') {
					foreach ($dataArray as $task => $taskInfo) {
						$html .= "<tr".($parentTaskId === null ? ''
									: ' class="sub_task"')."><td class=\"task_name\">\"{$task}\"{$parentTaskInfo}</td>";
						foreach ($taskInfo as $param => $paramInfo) {
							if (is_array($paramInfo)) {
								/* Рекурсивний виклик функції, якщо елемент масиву - масив */
								$html .= fillHtmlTable([$param => $paramInfo], $funcMode, $task, "{$parentTaskInfoId}{$taskInfo['id']}");
							} else {
								$html .= ($param === 'id'
											? "<td class=\"{$param}\">{$parentTaskInfoId}{$paramInfo}</td>"
											: "<td class=\"{$param}\">{$paramInfo}</td>");
							}
						}
						$html .= '</tr>';
					}
				} else {/* Опрацювання масиву циклом for */
					$dataArrayKeys = array_keys($dataArray);
					for ($a = 0; $a < count($dataArrayKeys); $a++) {
						$html .= "<tr".($parentTaskId === null ? ''
									: ' class="sub_task"')."><td class=\"task_name\">\"{$dataArrayKeys[$a]}\"{$parentTaskInfo}</td>";
						$taskArrayKeys = array_keys($dataArray[$dataArrayKeys[$a]]);
						for ($b = 0; $b < count($taskArrayKeys); $b++) {
							if (is_array($dataArray[$dataArrayKeys[$a]][$taskArrayKeys[$b]])) {
								/* Рекурсивний виклик функції, якщо елемент масиву - масив */
								$html .= fillHtmlTable([$taskArrayKeys[$b] => $dataArray[$dataArrayKeys[$a]][$taskArrayKeys[$b]]],
														$funcMode, $dataArrayKeys[$a],
														"{$parentTaskInfoId}{$dataArray[$dataArrayKeys[$a]]['id']}");
							} else {
								$html .= ($taskArrayKeys[$b] === 'id'
											? "<td class=\"{$taskArrayKeys[$b]}\">{$parentTaskInfoId}{$dataArray[$dataArrayKeys[$a]][$taskArrayKeys[$b]]}</td>"
											: "<td class=\"{$taskArrayKeys[$b]}\">{$dataArray[$dataArrayKeys[$a]][$taskArrayKeys[$b]]}</td>");
							}
						}
						$html .= '</tr>';
					}
				}
				return $html;
			}
			/*
				Функція формує кінцеву HTML-таблицю: додає до шапки результат виклику функції fillHtmlTable.
			*/
			function printHtmlTable(array $dataArray, string $funcMode = 'foreach'): void
			{
				$tableData = fillHtmlTable($dataArray, $funcMode);
				$htmlTable = <<<HTML
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th colspan="5">Перелік завдань SkillUp</th>
                                            </tr>
                                            <tr>
                                                <th>Назва завдання</th>
                                                <th>ID завдання</th>
                                                <th>Короткий опис</th>
                                                <th>Відповідальна особа</th>
                                                <th>Статус виконання</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {$tableData}
                                        </tbody>
                                    </table>
                            HTML;
				echo $htmlTable;
			}
			echo '<h4>Таблиця сформована за допомогою foreach-циклу</h4>';
			printHtmlTable($tasksArray, 'foreach');
			echo '<h4>Таблиця сформована за допомогою for-циклу</h4>';
			printHtmlTable($tasksArray, 'for');
		?>
		<script type="text/javascript">
			/* Для кожної з таблиць замінюю місцями перший і другий стовбчики, замінюю статуси завдань на читабельні */
			Array.from(document.getElementsByTagName('table')).forEach(function (table) {
				/* Взаємозаміна першого і другого стовбчика */
				let rowsArray = table.rows, firstElemValue, secondElemValue;
				for (let a = 0; a < rowsArray.length; a++) {
					if (a > 0) {
						firstElemValue = rowsArray[a].children[0].innerHTML;
						secondElemValue = rowsArray[a].children[1].innerHTML;
						rowsArray[a].children[0].innerHTML = secondElemValue;
						rowsArray[a].children[1].innerHTML = firstElemValue;
					}
				}
				/* Приведення статусу завдання до читабельного вигляду */
				Array.from(table.getElementsByClassName('status')).forEach(function (item) {
					switch (item.innerHTML) {
						case 'OK':
							item.innerHTML = 'Не взяте в роботу';
							break;
						case 'AC':
							item.innerHTML = 'В роботі';
							break;
						case 'FI':
							item.innerHTML = 'Завершене';
							break;
						case '--':
							item.innerHTML = 'Анульоване';
							break;
					}
				});
			});
		</script>
	</body>
</html>