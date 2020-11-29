<?php

namespace app\controllers;

class ProductController
{
    /**
     * Default method
     */
    public function actionIndex(): void
    {
        echo self::class . ' index method...';
    }

    /**
     * Returns request parameters info
     * @param array $parameters
     */
    public function actionGetProductInfo(array $parameters): void
    {
        echo '<pre>';
        echo var_export($parameters, true);
        echo '</pre>';
    }
}