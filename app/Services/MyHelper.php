<?php
namespace App\Services;

class MyHelper
{
    private const CONTENT_TYPE = 'Content-Type';

    public static function filterDefault($string, $is_number = false, $is_strip = false)
    {
        $is_strip ? $default_string = '-' : $default_string = '';
        $is_number? $defaultValue = '0' : $defaultValue = $default_string;
        return isset($string)? $string : $defaultValue;
    }

    private static function getHeader($fileName = null)
    {
        return array(
            self::CONTENT_TYPE => '',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'filename'=> "\"$fileName\""
        );
    }

    public static function getHeaderExcel($fileName = null)
    {
        $header = self::getHeader($fileName);
        $header[self::CONTENT_TYPE] = 'application/vnd.ms-excel';
        return $header;
    }

    public static function getHeaderImage($fileName = null)
    {
        $header = self::getHeader($fileName);
        $header[self::CONTENT_TYPE] = 'image/jpeg';
        return $header;
    }
}