<?php

namespace app\helpers;

final class StringsHelper
{
    /**
     * @param string $subject
     * @param string $symbols
     * @return string
     */
    public static function customTrim(string $subject, string $symbols = ''): string
    {
        return trim($subject, " \t\n\r\0\x0B{$symbols}");
    }
}
