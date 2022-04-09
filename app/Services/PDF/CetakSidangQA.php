<?php

namespace App\Services\PDF;


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

        // PDF CONFIG
        $pdf->SetAutoPageBreak(true);
        $pdf->setData($data);
        $pdf->AliasNbPages();
        $pdf->SetFillColor(200, 200, 200);
        $rowHeight = 5.5;
        $dataCount = count($data['sidang_detail']);
        $pdf->Header();
        $pdf->Footer();


        for ($n = 0; $n < $numberOfPages; $n++) {
            $pdf->AddPage();
            /*Upper Section*/
            $pdf->setXY(10, 30);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(57, $rowHeight, '', 1, 0, 'L', true);
            $pdf->Cell(55, $rowHeight, 'Perangkat ' . (($n * 4) + 1), 1, 0, 'C', true);
            $pdf->Cell(55, $rowHeight, 'Perangkat ' . (($n * 4) + 2), 1, 0, 'C', true);
            $pdf->Cell(55, $rowHeight, 'Perangkat ' . (($n * 4) + 3), 1, 0, 'C', true);
            $pdf->Cell(55, $rowHeight, 'Perangkat ' . (($n * 4) + 4), 1, 1, 'C', true);


            /*TABLE BODY LEFT*/
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(57, $rowHeight, 'No. SPK', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight, 'No. Laporan', 1, 1, 'R', false);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(57, $rowHeight, 'No. Sertifikat', 1, 1, 'R', false);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(57, $rowHeight * 2, 'PEMOHON/Company', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight * 2, 'PERANGKAT/Equipment', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight * 2, 'MEREK/Brand', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight * 2, 'TIPE/type', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight * 2, 'KAPASITAS/capacity', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight * 2, 'NOMOR SERIAL/Serial Number', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight * 2, 'REFERENSI UJI/Test Reference', 1, 1, 'R', false);
            $pdf->Cell(57, $rowHeight * 2, 'BUATAN/Made In', 1, 1, 'R', false);
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


            /*TABLE BODY CONTENT*/
            $pdf->SetFont('helvetica', '', 8);
            for ($i = 0 + ($n * 4); $i < (($n + 1) * 4); $i++) {
                $dataNumber = $dataDummy ? $i % 2 : $i;

                $pdf->setXY(67 + (($i % 4) * 55), 30 + $rowHeight);
                $pdf->Cell(55, $rowHeight, $data['sidang_detail'][$dataNumber]->examination->spk_code ?? '', 1, 2, 'C', false);
                $pdf->Cell(55, $rowHeight, $i < $dataCount || $dataDummy ? ($data['sidang_detail'][$dataNumber]->examination->media->where('name', 'Laporan Uji')->first()->no ?? '') : '', 1, 2, 'C', false);
                $pdf->Cell(55, $rowHeight, $data['certificateNumber'][$dataNumber] ?? '', 1, 2, 'C', false);
                $pdf->Cell(55, $rowHeight * 2, '', 1, 2, '', false); //company_name multiline (handle diferently)
                $pdf->Cell(55, $rowHeight * 2, '', 1, 2, '', false); //device_name multiline (handle diferently)
                $pdf->Cell(55, $rowHeight * 2, '', 1, 2, '', false); //device_mark multiline (handle diferently)
                $pdf->Cell(55, $rowHeight * 2, '', 1, 2, '', false); //device_type multiline (handle diferently)
                $pdf->Cell(55, $rowHeight * 2, '', 1, 2, '', false); //device_capacity multiline (handle diferently)
                $pdf->Cell(55, $rowHeight * 2, '', 1, 2, '', false); //device_serial_number multiline (handle diferently)
                $pdf->Cell(55, $rowHeight * 2, '', 1, 2, '', false); //device_test_reference multiline (handle diferently)
                $pdf->Cell(55, $rowHeight * 2, '', 1, 2, '', false); //device_manufactured_by multiline (handle diferently)
                $pdf->Cell(55, $rowHeight, $i < $dataCount || $dataDummy ? \App\Services\MyHelper::tanggalIndonesia($data['sidang_detail'][$dataNumber]->examination->equipmentHistory->where('location', 2)->first()->action_date ?? '') ?? '' : '', 1, 2, 'C', false);
                $pdf->Cell(55, $rowHeight, $dataDummy ? \App\Services\MyHelper::tanggalIndonesia($dummy[$dataNumber]['started']) : \App\Services\MyHelper::tanggalIndonesia($data['sidang_detail'][$dataNumber]->startDate ?? '') ?? '', 1, 2, 'C', false);
                $pdf->Cell(55, $rowHeight, $dataDummy ? \App\Services\MyHelper::tanggalIndonesia($dummy[$dataNumber]['finished']) : \App\Services\MyHelper::tanggalIndonesia($data['sidang_detail'][$dataNumber]->endDate ?? '') ?? '', 1, 2, 'C', false);
                $pdf->Cell(55, $rowHeight, $i < $dataCount || $dataDummy ? $data['sidang_detail'][$dataNumber]->examination->examinationLab->name ?? '' : '', 1, 2, 'C', false);
                $pdf->Cell(55, $rowHeight, $dataDummy ? \App\Services\MyHelper::tanggalIndonesia($dummy[$dataNumber]['target']) : \App\Services\MyHelper::tanggalIndonesia($data['sidang_detail'][$dataNumber]->targetDate ?? '') ?? '', 1, 2, 'C', false);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->Cell(55, $rowHeight, $dataDummy ? $dummy[$dataNumber]['hasil'] : $data['sidang_detail'][$dataNumber]->finalResult ?? '', 1, 2, 'C', false);
                $pdf->SetFont('helvetica', '', 8);
                $pdf->Cell(55, $rowHeight * 5, $data['sidang_detail'][$dataNumber]->catatan ?? '', 1, 2, 'C', false);
                $pdf->SetFont('helvetica', 'B', 8);
                $pdf->Cell(55, $rowHeight, $i < $dataCount || $dataDummy ? $listKeputusanSidangQA[($dataDummy ? $dummy[$dataNumber]['result'] : $data['sidang_detail'][$dataNumber]->result) ?? ''] : '', 1, 2, 'C', false);
                $pdf->SetFont('helvetica', '', 8);

                // Multiline Handling
                if ($i < $dataCount || true) {
                    $pdf->setXY(67 + (($i % 4) * 55), 30 + ($rowHeight * 4));
                    $pdf->drawTextBox($data['sidang_detail'][$dataNumber]->examination->company->name ?? '', 55, 11, 'C', 'M');
                    $pdf->setXY(67 + (($i % 4) * 55), 30 + ($rowHeight * 6));
                    $pdf->drawTextBox($data['devices'][$dataNumber]->name ?? '', 55, 11, 'C', 'M');
                    $pdf->setXY(67 + (($i % 4) * 55), 30 + ($rowHeight * 8));
                    $pdf->drawTextBox($data['devices'][$dataNumber]->mark ?? '', 55, 11, 'C', 'M');
                    $pdf->setXY(67 + (($i % 4) * 55), 30 + ($rowHeight * 10)); // type
                    $pdf->drawTextBox($data['devices'][$dataNumber]->model ?? '', 55, 11, 'C', 'M');
                    $pdf->setXY(67 + (($i % 4) * 55), 30 + ($rowHeight * 12)); // capacity
                    $pdf->drawTextBox($data['devices'][$dataNumber]->capacity ?? '', 55, 11, 'C', 'M');
                    $pdf->setXY(67 + (($i % 4) * 55), 30 + ($rowHeight * 14)); // serial_number
                    $pdf->drawTextBox($data['devices'][$dataNumber]->serial_number ?? '', 55, 11, 'C', 'M');
                    $pdf->setXY(67 + (($i % 4) * 55), 30 + ($rowHeight * 16)); // test_reference
                    $pdf->drawTextBox($data['devices'][$dataNumber]->test_reference ?? '', 55, 11, 'C', 'M');
                    $pdf->setXY(67 + (($i % 4) * 55), 30 + ($rowHeight * 16)); // manufactured_by
                    $pdf->drawTextBox($data['devices'][$dataNumber]->manufactured_by ?? '', 55, 11, 'C', 'M');
                    $pdf->setXY(67 + (($i % 4) * 55), 30 + ($rowHeight * 21));
                    $pdf->drawTextBox($dataDummy ? $dummy[$dataNumber]['note'] : $data['sidang_detail'][$dataNumber]->catatan ?? '', 55, 27.5, 'C', 'M');
                }
            }
        }

        //PDF-OUTPUT
        if ($method == 'getStream') {
            return $pdf->Output('', 'S');
        }
        $pdf->Output();
        exit;
    }
}
