<?php

class Controller
{
    public string $model;
    public string $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function actionIndex(): string
    {

    }
}