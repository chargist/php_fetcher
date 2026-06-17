<?php

namespace Chargist\Fetcher;

class Parser
{
    public static function Price(string|int|float|null $value): float
    {
        if (null == $value) {
            return 0;
        }

        $string = str_replace([",", " ", " "], [".", "", ""], $value);
        preg_match('/^[^-\.\d]*(\-?\d+(?:\.\d+)?)[^\d]*$/', $string, $matches);
        if (!isset($matches[1])) {
            return 0;
        }
        return floatval($matches[1]);
    }
}
