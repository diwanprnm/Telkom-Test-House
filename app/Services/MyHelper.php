<?php

namespace App\Services;

use Storage;

class MyHelper
{
    private const CONTENT_TYPE = 'Content-Type';
    private const LIST_BULAN_INDONESIA = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    public static function filterDefault($string, $is_number = false, $is_strip = false)
    {
        $is_strip ? $default_string = '-' : $default_string = '';
        $is_number ? $defaultValue = '0' : $defaultValue = $default_string;
        return isset($string) ? $string : $defaultValue;
    }

    public static function setDefault($string, $defaultValue)
    {
        if (isset($string) && $string) {
            return $string;
        }
        return $defaultValue;
    }

    public static function getHeaderOctet($fileName = null)
    {
        $header = self::getHeader($fileName);
        $header[self::CONTENT_TYPE] = 'application/octet-stream';
        return $header;
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
        $header[self::CONTENT_TYPE] = 'image/*';
        return $header;
    }

    public static function getHeaderPDF($fileName = null)
    {
        $header = self::getHeader($fileName);
        $header[self::CONTENT_TYPE] = 'application/pdf';
        return $header;
    }

    public function filterUrlEncode($string, $number = false)
    {
        if (!$string && $number) {
            $string = '0';
        }
        if (!$string && !$number) {
            $string = '-';
        }
        if ($string && strpos($string, "/")) {
            $string = urlencode(urlencode($string));
        }
        return $string;
    }

    private static function getHeader($fileName = null)
    {
        return array(
            self::CONTENT_TYPE => '',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'filename' => "\"$fileName\""
        );
    }

    public static function tanggalIndonesia($date = '')
    {
        $result = !$date ? '' : date('d', strtotime($date)) . ' ' . self::LIST_BULAN_INDONESIA[((int)date('m', strtotime($date))) - 1] . ' ' . date('Y', strtotime($date));
        return $result;
    }

    public static function getOfficer()
    {
        $manager = \App\GeneralSetting::where('is_active', 1)
            ->whereIn('code', ['manager_urel', 'poh_manager_urel'])
            ->first();
        $seniorManager = \App\GeneralSetting::where('is_active', 1)
            ->whereIn('code', ['sm_urel', 'poh_sm_urel'])
            ->first();

        return [
            'isSeniorManagerPOH' => $seniorManager->code == 'poh_sm_urel',
            'isManagerPOH' => $manager->code == 'poh_manager_urel',
            'seniorManager' => $seniorManager->value,
            'manager' => $manager->value,
            'tandaTanganManager' => Storage::disk('minio')->url("generalsettings/$manager->id/$manager->attachment"),
            'tandaTanganSeniorManager' => Storage::disk('minio')->url("generalsettings/$seniorManager->id/$seniorManager->attachment")
        ];
    }
}
