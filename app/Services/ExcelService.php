<?php

namespace App\Services;

use Storage;
use Excel;

class ExcelService
{
    public static function download($data, $fileName)
    {
        if (!$data || !$fileName){return false;}

        // Generate and return the spreadsheet
        Excel::create($fileName , function($excel) use ($data) {
            $excel->sheet('sheet1', function($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', false, false);
            });
        })->store('xlsx');

        $file = Storage::disk('tmp')->get($fileName.'.xlsx');

        $headers = [
            'Content-Type' => 'Application/Spreadsheet',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename=$fileName.xlsx",
            'filename'=> $fileName.'.xlsx'
        ];

        return array(
            'file' => $file,
            'headers' => $headers,
        );
    }
}