<?php
namespace App\Services;

class MyHelper
{
    public static function filterDefault($string, $is_number = false, $is_strip = false)
    {
        $is_strip ? $default_string = '-' : '';
        $is_number? $defaultValue = $default_string : $defaultValue = '0';
        return isset($string)? $string : $defaultValue ;
    }

}