<?php

declare(strict_types=1);
error_reporting(E_ALL);

include_once(__DIR__ . '/inc/php/functions.inc.php');

$autoloaderFunction = 'classesAutoloader';

if (!function_exists($autoloaderFunction)) {
    die('Autoloader error!');
}

spl_autoload_register($autoloaderFunction);

$mentor = new Mentor('Dmytro', 'PHP', true);
$studentMykola = new Student('Mykola', $mentor->getLanguage(), false);
$studentAndriy = new Student('Andriy', $mentor->getLanguage(), false);

$homework = new Homework();

$homework->addHomework('2020-11-16', 'Write simple classes', $mentor);
$homework->addHomework('2020-11-18', 'Try to understand MVC concept', $mentor);
$homework->addHomework('2020-11-23', 'Write first framework', $mentor);

$studentMykola->setHomework($homework->getHomework());
$studentMykola->makeHomework();

$studentAndriy->setHomework($homework->getHomework());
$studentAndriy->makeHomework();

$studentMykola->checkHomework($mentor);

$studentAndriy->checkHomework($mentor);


