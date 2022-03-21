<?php

namespace App\Services;

use Storage;
use Excel;

class ExcelService
{
    public static function download($data, $fileName)
    {
        if (!$data || !$fileName) {
            return false;
        }

        function flipDiagonally($arr)
        {
            $out = array();
            foreach ($arr as $key => $subarr) {
                foreach ($subarr as $subkey => $subvalue) {
                    $out[$subkey][$key] = $subvalue;
                }
            }
            return $out;
        }

        // here is the transpose part
        $transposedData = flipDiagonally($data);
        // end of the transpose part

        // Generate and return the spreadsheet
        Excel::create($fileName, function ($excel) use ($transposedData) {
            $excel->sheet('sheet1', function ($sheet) use ($transposedData) {
                $sheet->fromArray($transposedData, null, 'B4', false, false);
                $sheet->cell('B1', function ($cell) {
                    $cell->setValue('RISALAH KEPUTUSAN SIDANG KOMITE VALIDASI QA DDB - 2021');
                });
                $sheet->cell('B2', function ($cell) {
                    $cell->setValue('PERIODE : 15 Juni 2021');
                });

                $sheet->cell('A5', function ($cell) {
                    $cell->setValue('No. SPK');
                });
                $sheet->cell('A6', function ($cell) {
                    $cell->setValue('No. Laporan');
                });
                $sheet->cell('A7', function ($cell) {
                    $cell->setValue('No. Sertifikat');
                });
                $sheet->cell('A8', function ($cell) {
                    $cell->setValue('PEMOHON/Company');
                });
                $sheet->cell('A9', function ($cell) {
                    $cell->setValue('PERANGKAT/Equipment');
                });
                $sheet->cell('A10', function ($cell) {
                    $cell->setValue('MEREK/Brand');
                });
                $sheet->cell('A11', function ($cell) {
                    $cell->setValue('TIPE/Type');
                });
                $sheet->cell('A12', function ($cell) {
                    $cell->setValue('KAPASITAS/Capacity');
                });
                $sheet->cell('A13', function ($cell) {
                    $cell->setValue('NOMOR SERI/Serial Number');
                });
                $sheet->cell('A14', function ($cell) {
                    $cell->setValue('REFERENSI UJI/Test Reference');
                });
                $sheet->cell('A15', function ($cell) {
                    $cell->setValue('BUATAN/Made In');
                });
                $sheet->cell('A16', function ($cell) {
                    $cell->setValue('TANGGAL PENERIMAAN/Received');
                });
                $sheet->cell('A17', function ($cell) {
                    $cell->setValue('TANGGAL MULAI UJI/Started');
                });
                $sheet->cell('A18', function ($cell) {
                    $cell->setValue('TANGGAL SELESAI UJI/Finished');
                });
                $sheet->cell('A19', function ($cell) {
                    $cell->setValue('DIUJI OLEH/Tested By');
                });
                $sheet->cell('A20', function ($cell) {
                    $cell->setValue('Target Penyelesaian');
                });
                $sheet->cell('A21', function ($cell) {
                    $cell->setValue('Hasil Pengujian');
                });
                $sheet->cell('A22', function ($cell) {
                    $cell->setValue('Catatan');
                });
                $sheet->cell('A23', function ($cell) {
                    $cell->setValue('Keputusan Sidang');
                });
            });
        })->store('xlsx');

        $file = Storage::disk('tmp')->get($fileName . '.xlsx');

        $headers = \App\Services\MyHelper::getHeaderExcel("$fileName.xlsx");

        return array(
            'file' => $file,
            'headers' => $headers,
        );
    }
}
