<?php

namespace App\Services\PDF;

use App\Role;

function calculateRowHeightMultiplier($string, $data_category){
    if (strlen($string) > 32){        
        switch ($data_category){
             case 'company_name': 
                $company_nameRowHeightMultiplier = 2;
                break;
            case 'device_name': 
                $device_nameRowHeightMultiplier = 2;
                break;
            case 'device_mark': 
                $device_markRowHeightMultiplier = 2;
                break;
            case 'device_model': 
                $device_modelRowHeightMultiplier = 2;
                break;
            case 'device_capacity': 
                $device_capacityRowHeightMultiplier = 2;
                break;
            case 'device_serial_number': 
                $device_serial_numberRowHeightMultiplier = 2;
                break;
            case 'device_test_reference': 
                $device_test_referenceRowHeightMultiplier = 2;
                break;
            default: break;
        }
    }
}

class CetakSidangQA
{    


    public function makePDF($data, $pdf)
    {
        // BUSSINESS PROCESS, LOGIC, & CONFIG
        $numberOfPages = (int)ceil(count($data["sidang_detail"]) / 4);
        $year = substr($data["sidang_detail"][0]->valid_from, 0, 4);
        $data['date'] = \App\Services\MyHelper::tanggalIndonesia($data["sidang"]->date);
        $method = $data['method'] ?? '';
        $data['title'] = $data['title'] ?? "RISALAH KEPUTUSAN SIDANG KOMITE VALIDASI QA DDB - $year";
        $data['subTitle'] = $data['subTitle'] ?? "Periode - " . $data['date'];
        $listKeputusanSidangQA = [
            '' => '',
            -1 => 'Tidak Lulus',
            0 => 'Belum',
            1 => 'Lulus',
            2 => 'Pending',
        ];
        $dataDummy = false;
        $dummy = [
            [
                'started' => '2021-06-15',
                'finished' => '2021-07-22',
                'target' => '2021-07-23',
                'hasil' => 'COMPLY',
                'result' => 1,
                'note' => 'Suspendisse diam nunc, molestie non cursus a, dignissim at lectus. Etiam in erat urna. In condimentum felis ac metus interdum, ut sollicitudin ex blandit. Nullam vulputate et neque at facilisis'
            ],
            [
                'started' => '2021-06-28',
                'finished' => '2021-07-23',
                'target' => '2021-08-10',
                'hasil' => 'NOT COMPLY',
                'result' => 2,
                'note' => 'Aliquam tristique felis sit amet sem placerat tristique. Donec sodales neque a vestibulum dapibus. Suspendisse convallis ligula quis ipsum condimentum aliquam. Nulla mattis velit non mi vestibulum, at finibus tellus pharetra'
            ]
        ];

        // Row height multiplier
        $company_nameRowHeightMultiplier = 1;
        $device_nameRowHeightMultiplier = 1;
        $device_markRowHeightMultiplier = 1;
        $device_modelRowHeightMultiplier = 1;
        $device_capacityRowHeightMultiplier = 1;
        $device_serial_numberRowHeightMultiplier = 1;
        $device_test_referenceRowHeightMultiplier = 1;

        // PDF CONFIG
        $pdf->SetAutoPageBreak(false);
        $pdf->setData($data);
        $pdf->AliasNbPages();
        $pdf->SetFillColor(200, 200, 200);
        $rowHeight = 5.5;
        $dataCount = count($data['sidang_detail']);
        $pdf->Header();
        $pdf->Footer();

        for ($n = 0; $n < $numberOfPages; $n++) {
            $pdf->AddPage('L', array(240, 300));
            /*Upper Section*/
            $pdf->setXY(10, 30);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(57, $rowHeight, '', 1, 0, 'L', true);
            $pdf->Cell(55, $rowHeight, 'Perangkat ' . (($n * 4) + 1), 1, 0, 'C', true);
            $pdf->Cell(55, $rowHeight, 'Perangkat ' . (($n * 4) + 2), 1, 0, 'C', true);
            $pdf->Cell(55, $rowHeight, 'Perangkat ' . (($n * 4) + 3), 1, 0, 'C', true);
            $pdf->Cell(55, $rowHeight, 'Perangkat ' . (($n * 4) + 4), 1, 1, 'C', true);


            
            /*TABLE BODY CONTENT*/
            $pdf->SetFont('helvetica', '', 8);
            for ($i = 0 + ($n * 4); $i < (($n + 1) * 4); $i++) {
                $dataNumber = $dataDummy ? $i % 2 : $i;

                $spk_code = $data['sidang_detail'][$dataNumber]->examination->spk_code ?? '';
                $no_lap_uji = $i < $dataCount || $dataDummy ? ($data['sidang_detail'][$dataNumber]->examination->media->where('name', 'Laporan Uji')->first()->no ?? '') : '';
                $certificateNumber = $data['certificateNumber'][$dataNumber] ?? '';
                $action_date = $i < $dataCount || $dataDummy ? \App\Services\MyHelper::tanggalIndonesia($data['sidang_detail'][$dataNumber]->examination->equipmentHistory->where('location', 2)->first()->action_date ?? '') ?? '' : '';
                $start_date = $dataDummy ? \App\Services\MyHelper::tanggalIndonesia($dummy[$dataNumber]['started']) : \App\Services\MyHelper::tanggalIndonesia($data['sidang_detail'][$dataNumber]->startDate ?? '') ?? '';
                $finish_date = $dataDummy ? \App\Services\MyHelper::tanggalIndonesia($dummy[$dataNumber]['finished']) : \App\Services\MyHelper::tanggalIndonesia($data['sidang_detail'][$dataNumber]->endDate ?? '') ?? '';
                $lab_name = $i < $dataCount || $dataDummy ? $data['sidang_detail'][$dataNumber]->examination->examinationLab->name ?? '' : '';
                $target_date = $dataDummy ? \App\Services\MyHelper::tanggalIndonesia($dummy[$dataNumber]['target']) : \App\Services\MyHelper::tanggalIndonesia($data['sidang_detail'][$dataNumber]->targetDate ?? '') ?? '';
                $final_result = $dataDummy ? $dummy[$dataNumber]['hasil'] : $data['sidang_detail'][$dataNumber]->finalResult ?? '';
                $catatan = $data['sidang_detail'][$dataNumber]->catatan ?? '';
                $result = $i < $dataCount || $dataDummy ? $listKeputusanSidangQA[($dataDummy ? $dummy[$dataNumber]['result'] : $data['sidang_detail'][$dataNumber]->result) ?? ''] : '';
                $company_name = $data['sidang_detail'][$dataNumber]->examination->company->name ?? '';
                $device_name = $data['devices'][$dataNumber]->name ?? '';
                $device_mark = $data['devices'][$dataNumber]->mark ?? '';
                $device_model = $data['devices'][$dataNumber]->model ?? '';
                $device_capacity = $data['devices'][$dataNumber]->capacity ?? '';
                $device_serial_number = $data['devices'][$dataNumber]->serial_number ?? '';
                $device_test_reference = $data['devices'][$dataNumber]->test_reference ?? '';
                $device_manufactured_by = $data['devices'][$dataNumber]->manufactured_by ?? '';
                // echo '<pre>';
                // print_r($transposedData);
                // echo '</pre>';
                // die;

                $pdf->setXY(67 + (($i % 4) * 55), 30 + $rowHeight);
                $pdf->Cell(55, $rowHeight, $spk_code, 1, 2, 'C', false);
                $pdf->Cell(55, $rowHeight, $no_lap_uji, 1, 2, 'C', false);
                $pdf->Cell(55, $rowHeight,  $certificateNumber, 1, 2, 'C', false);

                $company_nameRowHeightMultiplier = strlen($company_name) > 32 ? 2 : $company_nameRowHeightMultiplier;
                $pdf->Cell(55, $rowHeight * $company_nameRowHeightMultiplier, '', 1, 2, '', false); //company_name multiline (handle diferently)

                $device_nameRowHeightMultiplier = strlen($device_name) > 32 ? 2 : $device_nameRowHeightMultiplier;
                $pdf->Cell(55, $rowHeight * $device_nameRowHeightMultiplier, '', 1, 2, '', false); //device_name multiline (handle diferently)

                $device_markRowHeightMultiplier = strlen($device_mark) > 32 ? 2 : $device_markRowHeightMultiplier;
                $pdf->Cell(55, $rowHeight * $device_markRowHeightMultiplier, '', 1, 2, '', false); //device_mark multiline (handle diferently)

                $device_modelRowHeightMultiplier = strlen($device_model) > 32 ? 2 : $device_modelRowHeightMultiplier;
                $pdf->Cell(55, $rowHeight * $device_modelRowHeightMultiplier, '', 1, 2, '', false); //device_type multiline (handle diferently)

                $device_capacityRowHeightMultiplier = strlen($device_capacity) > 32 ? 2 : $device_capacityRowHeightMultiplier;
                $pdf->Cell(55, $rowHeight * $device_capacityRowHeightMultiplier, '', 1, 2, '', false); //device_capacity multiline (handle diferently)

                $device_serial_numberRowHeightMultiplier = strlen($device_serial_number) > 32 ? 2 : $device_serial_numberRowHeightMultiplier;
                $pdf->Cell(55, $rowHeight * $device_serial_numberRowHeightMultiplier, '', 1, 2, '', false); //device_serial_number multiline (handle diferently)

                $device_test_referenceRowHeightMultiplier = strlen($device_test_reference) > 32 ? 2 : $device_test_referenceRowHeightMultiplier;
                $pdf->Cell(55, $rowHeight * $device_test_referenceRowHeightMultiplier, '', 1, 2, '', false); //device_test_reference multiline (handle diferently)

                $pdf->Cell(55, $rowHeight, $device_manufactured_by, 1, 2, 'C', false); //device_manufactured_by multiline (handle diferently)
                $pdf->Cell(55, $rowHeight, $action_date, 1, 2, 'C', false);
                $pdf->Cell(55, $rowHeight, $start_date, 1, 2, 'C', false);
                $pdf->Cell(55, $rowHeight, $finish_date, 1, 2, 'C', false);
                $pdf->Cell(55, $rowHeight, $lab_name, 1, 2, 'C', false);
                $pdf->Cell(55, $rowHeight, $target_date, 1, 2, 'C', false);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->Cell(55, $rowHeight, $final_result, 1, 2, 'C', false);
                $pdf->SetFont('helvetica', '', 8);
                $pdf->Cell(55, $rowHeight * 5, $catatan, 1, 2, 'C', false);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->Cell(55, $rowHeight, $result, 1, 2, 'C', false);
                $pdf->SetFont('helvetica', '', 8);

                // Multiline Handling
                if ($i < $dataCount || true) {
                    $pdf->setXY(67 + (($i % 4) * 55), 30 + ($rowHeight * 4));
                    $pdf->drawTextBox($company_name, 55, 11, 'C', 'M');
                    $pdf->setXY(67 + (($i % 4) * 55), 30 + ($rowHeight * 6));
                    $pdf->drawTextBox($device_name, 55, 11, 'C', 'M');
                    $pdf->setXY(67 + (($i % 4) * 55), 30 + ($rowHeight * 8));
                    $pdf->drawTextBox($device_mark, 55, 11, 'C', 'M');
                    $pdf->setXY(67 + (($i % 4) * 55), 30 + ($rowHeight * 10)); // type
                    $pdf->drawTextBox( $device_model, 55, 11, 'C', 'M');
                    $pdf->setXY(67 + (($i % 4) * 55), 30 + ($rowHeight * 12)); // capacity
                    $pdf->drawTextBox($device_capacity, 55, 11, 'C', 'M');
                    $pdf->setXY(67 + (($i % 4) * 55), 30 + ($rowHeight * 14)); // serial_number
                    $pdf->drawTextBox($device_serial_number, 55, 11, 'C', 'M');
                    $pdf->setXY(67 + (($i % 4) * 55), 30 + ($rowHeight * 16)); // test_reference
                    $pdf->drawTextBox($device_test_reference, 55, 11, 'C', 'M');
                    
                } 
            }
            // LEFT GOES HERE
            /*TABLE BODY LEFT*/
            $pdf->setXY(10, 30 + $rowHeight);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(57, $rowHeight, 'No. SPK', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight, 'No. Laporan', 1, 1, 'R', false);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(57, $rowHeight, 'No. Sertifikat', 1, 1, 'R', false);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(57, $rowHeight * $company_nameRowHeightMultiplier, 'PEMOHON/Company', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight * $device_nameRowHeightMultiplier, 'PERANGKAT/Equipment', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight * $device_markRowHeightMultiplier, 'MEREK/Brand', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight * $device_modelRowHeightMultiplier, 'TIPE/type', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight * $device_capacityRowHeightMultiplier, 'KAPASITAS/capacity', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight * $device_serial_numberRowHeightMultiplier, 'NOMOR SERIAL/Serial Number', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight * $device_test_referenceRowHeightMultiplier, 'REFERENSI UJI/Test Reference', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight, 'BUATAN/Made In', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight, 'TANGGAL PENERIMAAN/Recieved', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight, 'TANGGAL MULAI UJI/Started', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight, 'TANGGAL SELESAI UJI/Finished', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight, 'DIUJI OLEH/Tested by', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight, 'Target Penyelesaian', 1, 1, 'R', false);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(57, $rowHeight, 'Hasil Pengujian', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight * 5, 'Catatan', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight, 'KEPUTUSAN SIDANG', 1, 1, 'R', false);
            $pdf->SetFont('helvetica', '', 9);
        }

        //PDF-OUTPUT
        if ($method == 'getStream') {
            return $pdf->Output('', 'S');
        }
        $pdf->Output();
        exit;
    }
}
