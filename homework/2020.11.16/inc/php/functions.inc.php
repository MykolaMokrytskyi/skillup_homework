<?php

function classesAutoloader(string $className): void
{
    $classesBasePath = __DIR__ . '/../../classes/';
    $currentClassPath = "{$classesBasePath}{$className}.php";

    if (file_exists($currentClassPath)) {
        include_once($currentClassPath);
    } else {
        die("Class {$className} not found!");
    }
}