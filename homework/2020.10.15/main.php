<?php
error_reporting(E_ALL);
/**
 * Доопрацьована функція піднесення числа в степінь (дану функцію нагуглив на stackoverflow, інші дві - мої)
 * @param int $number - число, яке підноситься до степені
 * @param int $pow - степінь
 * @return int
 */
function powerNum(int $number, int $pow): int
{
    if ($pow === 0) {
        return 1;
    }
    if ($pow % 2 === 0) {
        $pNumber = powerNum($number, $pow/2);
        return $pNumber * $pNumber;
    } else {
        return $number * powerNum($number, $pow - 1);
    }
}
/**
 * Функція рекурсивного виводу масиву
 * @param array $dataArray - масив вхідних даних
 * @param string $whitespaces - змінна, яка накопичує пробіли на початку кожного рівня вкладення
 * @return string
 */
function arrayVarDumpRecursive(array $dataArray, string $whitespaces = ''): string
{
    $defWhitespaces = $whitespaces;
    $whitespaces .= '&nbsp;&nbsp;&nbsp;&nbsp;';
    $arrayInfoBase = "{$defWhitespaces}array(".count($dataArray).") {<br>";
    foreach ($dataArray as $elemKey => $elemVal) {
        $quotes = (is_integer($elemKey) || is_float($elemKey) ? '' : '&quot;');
        if (is_array($elemVal)) {
            $arrayInfoBase .= "{$whitespaces}[{$quotes}{$elemKey}{$quotes}]=><br>";
            /* Рекурсивний виклик функції для кожного підмасиву */
            $arrayInfoBase .= arrayVarDumpRecursive($elemVal, $whitespaces);
        } else {
            /* Використовую для виведення інформації елементів у форматі функції var_dump */
            switch (gettype($elemVal)) {
                case 'NULL':
                    $valueInfo = 'NULL';
                    break;
                case 'string':
                    $valueInfo = 'string(' . strlen($elemVal) . ') &quot;' . $elemVal . '&quot;';
                    break;
                case 'integer':
                    $valueInfo = "int({$elemVal})";
                    break;
                case 'float':
                case 'double':
                    $valueInfo = "float({$elemVal})";
                    break;
                default:
                    $valueInfo = "otherType({$elemVal})";;
            }
            $arrayInfoBase .= "{$whitespaces}[{$quotes}{$elemKey}{$quotes}]=><br>{$whitespaces}{$valueInfo}<br>";
        }
    }
    return "{$arrayInfoBase}{$defWhitespaces}}<br>";
}
/**
 * Функція рекурсивного підрахунку елементів масиву
 * @param array $dataArray - масив вхідних даних
 * @param bool $countParent - змінна, яка визначає врахування батьківських елементів підмасивів
 * @return int
 */
function arrayCountRecursive(array $dataArray, bool $countParent = true): int
{
    $elementsQty = count($dataArray);
    foreach ($dataArray as $element) {
        if (is_array($element)) {
            if (!$countParent) {
                $elementsQty--;
            }
            /* Рекурсивний виклик функції для кожного підмасиву */
            $elementsQty += arrayCountRecursive($element, $countParent);
        }
    }
    return $elementsQty;
}
/* Тестовий масив з вкладеннями, з яким будуть проводитися операції виводу та підрахунку */
$testArray =
    [
        'one' => 1,
        'two' => 2,
        'three' => 3,
        'four' => 4,
        'otherNumbers' =>
            [
                'five' => 5,
                'six' => 6,
                'subNumbers' =>
                    [
                        'seven' => 7,
                        'eight' => 8,
                        'deepSubNumbers' =>
                            [
                                9,
                                10,
                                11,
                            ],
                    ],
            ],
    ];
$recursiveVarDump = arrayVarDumpRecursive($testArray);
$countWithoutParents = arrayCountRecursive($testArray, false);
$countWithParents = arrayCountRecursive($testArray, true);
echo "1. Число в степені (доопрацьована функція): 3 ** 4 = ".powerNum(3 ,4)."<br><br>
        2. Рекурсивне виведення масиву на екран по типу функції \"var_dump\":<br><br>{$recursiveVarDump}<br>
        3. Рекурсивний підрахунок кількості елементів в масиві:<br><br>&nbsp;
        - кількість елементів в масиві без урахування елементів, які самі є масивами - {$countWithoutParents};<br>&nbsp;
        - кількість елементів в масиві з урахування елементів, які самі є масивами - {$countWithParents}.";