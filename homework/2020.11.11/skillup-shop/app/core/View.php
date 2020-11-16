<?php

class View
{
    public function generate(string $contentView, string $templateView, string $data = null): void
    {
        include_once(__DIR__ . "/app/views/{$templateView}");
    }
}