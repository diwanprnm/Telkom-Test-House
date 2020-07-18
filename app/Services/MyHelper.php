<?php
namespace App\Services;

class MyHelper
{
    public static function filterDefault($string, $is_number = false, $is_strip = false)
    {
        $is_strip ? $default_string = '-' : $default_string = '';
        $is_number? $defaultValue = '0' : $defaultValue = $default_string;
        return isset($string)? $string : $defaultValue;
    }

}