<?php

namespace App\Services;

use Storage;
use Excel;
use PHPExcel_Worksheet_Drawing;

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

        // echo '<pre>';
        // print_r($transposedData);
        // echo '</pre>';
        // die;

        $signeeData = \App\GeneralSetting::whereIn('code', ['sm_urel', 'poh_sm_urel'])->where('is_active', '=', 1)->first();

        $signeeDataManager = \App\GeneralSetting::whereIn('code', ['poh_manager_urel', 'manager_urel'])->where('is_active', '=', 1)->first();

        $signeeDataArray = array(
            'signee' => $signeeData->value,
            'isSigneePoh' => $signeeData->code !== 'sm_urel',
            'signImagePath' => Storage::disk('minio')->url("generalsettings/$signeeData->id/$signeeData->attachment")
        );

        $signeeDataManagerArray = array(
            'signee' => $signeeDataManager->value,
            'isSigneePoh' => $signeeDataManager->code !== 'sm_urel',
            'signImagePath' => Storage::disk('minio')->url("generalsettings/$signeeDataManager->id/$signeeDataManager->attachment")
        );

        $lastDataColumn = chr(ord('B') + (count($data) - 1));
        $lastDataIndex = "{$lastDataColumn}23";
        $mainDataTableRange = "B5:{$lastDataIndex}";

        $lastHeaderColumn =
            chr(ord('A') + (count($data) + 1));
        $lastDHeaderIndex = $lastHeaderColumn;
        $mainDHeaderRange = "A4:{$lastDHeaderIndex}4";

        // Generate and return the spreadsheet
        Excel::create($fileName, function ($excel) use ($lastDHeaderIndex, $transposedData, $signeeDataArray, $mainDataTableRange, $mainDHeaderRange) {
            $excel->sheet('sheet1', function ($sheet) use ($lastDHeaderIndex, $transposedData, $signeeDataArray, $mainDataTableRange, $mainDHeaderRange) {

                // echo '<pre>';
                // print_r($mainDHeaderRange);
                // echo '</pre>';
                // die;

                $mainHeaderFont = array(
                    'size'       => '10',
                    'bold'       => true
                );


                for ($x = 'B', $index = 1; $x != $lastDHeaderIndex; $x++, $index++) {
                    $sheet->cell("{$x}4", "Perangkat {$index}")->setFontSize('12');
                }

                $sheet->fromArray($transposedData, null, 'B5', false, false)->setAllBorders('thin')->setFontFamily('Tahoma')->setFontSize('9');

                $sheet->cells($mainDHeaderRange, function ($cells) {
                    // manipulate the range of cells
                    $cells->setBorder('thin', 'thin', 'thin')->setFont(array(
                        'size'       => '12',
                        'bold'       => true
                    ));
                });

                // Set black background
                $sheet->row(4, function ($row) {
                    // call cell manipulation methods
                    $row->setBackground('#C0C0C0')->setFontFamily('Tahoma')->setFontSize('12');
                });


                $sheet->cell("B1", function ($cell) use ($mainHeaderFont) {
                    $cell->setValue('RISALAH KEPUTUSAN SIDANG KOMITE VALIDASI QA DDB - 2021')->setFont($mainHeaderFont)->setFontFamily('Verdana');
                });

                $sheet->setBorder($mainDataTableRange, 'double');

                $sheet->cell('B2', function ($cell) use ($mainHeaderFont) {
                    $cell->setValue('PERIODE : 15 Juni 2021')->setFont($mainHeaderFont)->setFontFamily('Verdana');
                });

                $sheet->cell('A4', function ($cell) {
                    $cell->setValue(' ')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A5', function ($cell) {
                    $cell->setValue('No. SPK')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A6', function ($cell) {
                    $cell->setValue('No. Laporan')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A7', function ($cell) {
                    $cell->setValue('No. Sertifikat')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A8', function ($cell) {
                    $cell->setValue('PEMOHON/Company')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A9', function ($cell) {
                    $cell->setValue('PERANGKAT/Equipment')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A10', function ($cell) {
                    $cell->setValue('MEREK/Brand');
                });
                $sheet->cell('A11', function ($cell) {
                    $cell->setValue('TIPE/Type')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A12', function ($cell) {
                    $cell->setValue('KAPASITAS/Capacity')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A13', function ($cell) {
                    $cell->setValue('NOMOR SERI/Serial Number')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A14', function ($cell) {
                    $cell->setValue('REFERENSI UJI/Test Reference')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A15', function ($cell) {
                    $cell->setValue('BUATAN/Made In')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A16', function ($cell) {
                    $cell->setValue('TANGGAL PENERIMAAN/Received')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A17', function ($cell) {
                    $cell->setValue('TANGGAL MULAI UJI/Started')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A18', function ($cell) {
                    $cell->setValue('TANGGAL SELESAI UJI/Finished')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A19', function ($cell) {
                    $cell->setValue('DIUJI OLEH/Tested By')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A20', function ($cell) {
                    $cell->setValue('Target Penyelesaian')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A21', function ($cell) {
                    $cell->setValue('Hasil Pengujian')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A22', function ($cell) {
                    $cell->setValue('Catatan')->setBorder('thin', 'thin', 'thin');
                });
                $sheet->cell('A23', function ($cell) {
                    $cell->setValue('Keputusan Sidang')->setBorder('thin', 'thin', 'thin');
                });

                $sheet->setBorder('A4:A23', 'double');

                $sheet->cell('B25', function ($cell) {
                    $cell->setValue('Bandung, 15 Juni 2021');
                });
                $sheet->cell('B26', function ($cell) {
                    $cell->setValue('Komite Validasi QA');
                });
                $signeeImage = file_put_contents("Tmpfile.jpg", fopen($signeeDataArray['signImagePath'], 'r'));

                // echo '<pre>';
                // print_r($signeeImage);
                // echo '</pre>';
                // die;

                // $getSigneeImage = (function () use ($signeeDataArray) {
                //     return $signeeDataArray['signImagePath'];
                // });

                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('Tmpfile.jpg')); //your image path
                $objDrawing->setCoordinates('B28');
                $objDrawing->setHeight(50);
                $objDrawing->setWorksheet($sheet);
                $sheet->cell('B32', function ($cell) use ($signeeDataArray) {
                    $cell->setValue($signeeDataArray['signee']);
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
