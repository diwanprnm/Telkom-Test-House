<?php

namespace App\Services;

use Storage;
use Excel;
use PHPExcel_Worksheet_Drawing;

class ExcelService
{
    public static function download($data, $sidangData, $fileName)
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

        $officer = \App\Services\MyHelper::getOfficer();

        $signees = [
            [
                'name' => strtoupper($officer['manager']),
                'title' => $officer['isManagerPOH'] ? "POH SEKRETARIS" : "SEKRETARIS",
                'tandaTanganManager' => $officer['tandaTanganManager']
            ],
            [
                'name' => strtoupper($officer['seniorManager']),
                'title' => $officer['isSeniorManagerPOH'] ? "POH SM INFRASTRUCTURE ASSURANCE" : "SM INFRASTRUCTURE ASSURANCE",
                'tandaTanganSeniorManager' => $officer['tandaTanganSeniorManager']
            ]
        ];

        $lastDataColumn = chr(ord('B') + (count($data) - 1));
        $lastDataIndex = "{$lastDataColumn}23";
        $mainDataTableRange = "B5:{$lastDataIndex}";

        $lastHeaderColumn =
            chr(ord('A') + (count($data) + 1));
        $lastDHeaderIndex = $lastHeaderColumn;
        $mainDHeaderRange = "A4:{$lastDHeaderIndex}4";

        // Generate and return the spreadsheet
        Excel::create($fileName, function ($excel) use ($lastDHeaderIndex, $transposedData, $signees, $mainDataTableRange, $mainDHeaderRange, $sidangData) {
            $excel->sheet('sheet1', function ($sheet) use ($lastDHeaderIndex, $transposedData, $signees, $mainDataTableRange, $mainDHeaderRange, $sidangData) {

                // echo '<pre>';
                // print_r($signees);
                // echo '</pre>';
                // die;

                $tanggal_sidang = \App\Services\MyHelper::tanggalIndonesia(
                    $sidangData['sidang_date']
                );

                $tahun_sidang = substr($sidangData['sidang_date'], 0, 4);

                $mainHeaderFont = array(
                    'size'       => '10',
                    'bold'       => true
                );


                for ($x = 'B', $index = 1; $x != $lastDHeaderIndex; $x++, $index++) {
                    $sheet->cell("{$x}4", "Perangkat {$index}")->setFontSize('12');
                }

                $sheet->fromArray($transposedData, null, 'B5', false, false)->setAllBorders('thin')->setFontFamily('Tahoma')->setFontSize('9');

                $sheet->cells($mainDHeaderRange, function ($cells) {
                    // manipulate main data header (Perangkat #)
                    $cells->setBorder('thin', 'thin', 'thin')->setFont(array(
                        'size'       => '12',
                        'bold'       => true
                    ))->setAlignment('center')->setValignment('center');
                });

                // Set grey background for main data header
                $sheet->row(4, function ($row) {
                    // row manipulation methods
                    $row->setFont(array(
                        'size'       => '12',
                        'bold'       => true
                    ))->setBackground('#C0C0C0')->setFontFamily('Tahoma');
                });

                $boldRowArray = [7, 20, 21, 23, 25, 26, 33];

                foreach ($boldRowArray as $boldRow) {
                    $sheet->row($boldRow, function ($row) {
                        // embolden no. sertifikat row
                        $row->setFont(array(
                            'size'       => '9',
                            'bold'       => true
                        ))->setFontFamily('Tahoma');
                    });
                }

                $sheet->cell("B1", function ($cell) use ($mainHeaderFont, $tahun_sidang) {
                    $cell->setValue("RISALAH KEPUTUSAN SIDANG KOMITE VALIDASI QA DDB - {$tahun_sidang}")->setFont($mainHeaderFont)->setFontFamily('Verdana');
                });

                $sheet->mergeCells('B1:C1'); // merge Top most title

                $sheet->freezePane('B1'); // Freeze A column

                $sheet->setBorder($mainDataTableRange, 'double');

                $sheet->cells($mainDataTableRange, function ($cells) {
                    // manipulate main table data cells
                    $cells->setAlignment('center')->setValignment('center');
                });

                $sheet->cell('B2', function ($cell) use ($mainHeaderFont, $tanggal_sidang) {
                    $cell->setValue("Periode : {$tanggal_sidang}")->setFont($mainHeaderFont)->setFontFamily('Verdana');
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

                $sheet->cells('A4:A23', function ($cells) {
                    // manipulate data attributes/properies (Leftmost column)
                    $cells->setAlignment('right')->setValignment('center')->setFont(array('color' => array('rgb' => '000080')));
                });

                $sheet->cell('B25', function ($cell) use ($tanggal_sidang) {
                    $cell->setValue("Bandung, {$tanggal_sidang}")->setAlignment('center')->setValignment('center');
                });
                $sheet->cell('B26', function ($cell) {
                    $cell->setValue('Komite Validasi QA,')->setAlignment('center')->setValignment('center');
                });

                // TTD Image
                $signeeImage = file_put_contents("Tmpfile.jpg", fopen($signees[0]['tandaTanganManager'], 'r'));
                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('Tmpfile.jpg')); //your image path
                $objDrawing->setCoordinates('B28');
                $objDrawing->setHeight(50);
                $objDrawing->setOffsetX(80);
                $objDrawing->setWorksheet($sheet);
                $sheet->cell('B32', function ($cell) use ($signees) {
                    $cell->setValue($signees[0]['name'])->setFontFamily('Arial')->setFont(array(
                        'size'       => '10',
                        'bold'  => true,
                        'underline'       => true
                    ))->setAlignment('center')->setValignment('center');
                });
                $sheet->cell('B33', function ($cell) use ($signees) {
                    $cell->setValue($signees[0]['title'])->setAlignment('center')->setValignment('center');
                });


                // TTD Image SM
                $signeeImageSm = file_put_contents("TmpfileSM.jpg", fopen($signees[1]['tandaTanganSeniorManager'], 'r'));
                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('TmpfileSM.jpg')); //your image path
                $objDrawing->setCoordinates('E28');
                $objDrawing->setHeight(50);
                $objDrawing->setOffsetX(65);
                $objDrawing->setWorksheet($sheet);
                $sheet->cell('E26', function ($cell) {
                    $cell->setValue('Menyetujui')->setAlignment('center')->setValignment('center');
                });
                $sheet->cell('E32', function ($cell) use ($signees) {
                    $cell->setValue($signees[1]['name'])->setFontFamily('Arial')->setFont(array(
                        'size'       => '10',
                        'bold'  => true,
                        'underline'       => true
                    ))->setAlignment('center')->setValignment('center');
                });
                $sheet->cell('E33', function ($cell) use ($signees) {
                    $cell->setValue($signees[1]['title'])->setAlignment('center')->setValignment('center');
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
