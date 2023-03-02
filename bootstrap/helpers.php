<?php

// my custom functions

if (!function_exists('thousands_currency_format')) {
    function thousands_currency_format($num)
    {
        if ($num > 1000) {
            $x = round($num);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $x_display = $x;
            $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $x_display .= $x_parts[$x_count_parts - 1];

            return $x_display;
        }

        return $num;
    }
}

if (!function_exists('parse_float')) {
    function parse_float($string)
    {
        return preg_replace('/[^0-9\.-]/', '', $string);
    }
}

if (!function_exists('crypto_number_format')) {
    function crypto_number_format($value, $thousands_separator = ' ', $trim_last_zero = false)
    {
        $value = number_format($value, (preg_replace('/^-/', '', $value) > 1 ? 2 : 6), '.', $thousands_separator);

        if ($trim_last_zero) {
            $value = preg_replace('/\.00$/', '', $value);
            $value = preg_replace('/0*$/', '', $value);
        }

        return $value;
    }
}
