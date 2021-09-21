<?php

namespace App\Services\PDF;


class CetakSidangQA
{


    public function makePDF($data, $pdf)
    {

        //bussiners process - logic
        $numberOfPages = 2;//(int)ceil(count($data["sidang_detail"])/4);
        $year = substr($data["sidang_detail"][0]->valid_from,0,4);
        $data['date'] = \App\Services\MyHelper::tanggalIndonesia($data["sidang"]->date);
        $method = $data['method'] ?? '';
        $data['title'] = $data['title'] ?? "RISALAH KEPUTUSAN SIDANG KOMITE VALIDASI QA DDB - $year";
        $data['subTitle'] = $data['subTitle'] ?? "Periode - ".$data['date'];
        $listKeputusanSidangQA = [
            '' => '',
            -1 => 'Tidak Lulus',
            0 => 'Belum',
            1 => 'Lulus',
            2 => 'Pending',
        ];
        
        // PDF CONFIG
        $pdf->SetAutoPageBreak(false);
        $pdf->setData($data);
        $pdf->AliasNbPages();
        $pdf->SetFillColor(200,200,200);
        $rowHeight = 5.5;
        $dataCount = count($data['sidang_detail']);
        $pdf->Header();
        $pdf->Footer();


        for($n = 0; $n < $numberOfPages; $n++ ){
            $pdf->AddPage();
            /*Upper Section*/
            $pdf->setXY(10,30);
            $pdf->SetFont('helvetica','B',10);
            $pdf->Cell(57,$rowHeight,'',1,0,'L',true);
            $pdf->Cell(55,$rowHeight,'Perangkat '.(($n*4)+1),1,0,'C',true);
            $pdf->Cell(55,$rowHeight,'Perangkat '.(($n*4)+2),1,0,'C',true);
            $pdf->Cell(55,$rowHeight,'Perangkat '.(($n*4)+3),1,0,'C',true);
            $pdf->Cell(55,$rowHeight,'Perangkat '.(($n*4)+4),1,1,'C',true);


            /*TABLE BODY LEFT*/
            $pdf->SetFont('helvetica','',9);
            $pdf->Cell(57,$rowHeight,'No. SPK',1,1,'R',false);
            $pdf->Cell(57,$rowHeight,'No. Laporan',1,1,'R',false);$pdf->SetFont('helvetica','B',9);
            $pdf->Cell(57,$rowHeight,'No. Sertifikat',1,1,'R',false);$pdf->SetFont('helvetica','',9);
            //$pdf->Cell(57,$rowHeight,'Expired CIQS',1,1,'R',false);
            $pdf->Cell(57,$rowHeight*2,'PEMOHON/Company',1,1,'R',false);
            $pdf->Cell(57,$rowHeight*2,'PERANGKAT/Equipment',1,1,'R',false);
            $pdf->Cell(57,$rowHeight*2,'MEREK/Brand',1,1,'R',false);
            $pdf->Cell(57,$rowHeight,'TIPE/type',1,1,'R',false);
            $pdf->Cell(57,$rowHeight,'KAPASITAS/capacity',1,1,'R',false);
            $pdf->Cell(57,$rowHeight,'NOMOR SERIAL/Serial Number',1,1,'R',false);
            $pdf->Cell(57,$rowHeight,'REFERENSI UJI/Test Reference',1,1,'R',false);
            $pdf->Cell(57,$rowHeight,'BUATAN/Made In',1,1,'R',false);
            $pdf->Cell(57,$rowHeight,'TANGGAL PENERIMAAN/Recieved',1,1,'R',false);
            $pdf->Cell(57,$rowHeight,'TANGGAL MULAI UJI/Started',1,1,'R',false);
            $pdf->Cell(57,$rowHeight,'TANGGAL SEKESAI UJI/Finished',1,1,'R',false);
            $pdf->Cell(57,$rowHeight,'DIUJI OLEH/Tested by',1,1,'R',false);
            $pdf->Cell(57,$rowHeight,'Target Penyelesaian',1,1,'R',false);$pdf->SetFont('helvetica','B',9);
            $pdf->Cell(57,$rowHeight,'Hasil Pengujian',1,1,'R',false);
            $pdf->Cell(57,$rowHeight*5,'Catatan',1,1,'R',false);
            $pdf->Cell(57,$rowHeight,'KEPUTUSAN SIDANG',1,1,'R',false);$pdf->SetFont('helvetica','',9);


            /*TABLE BODY CONTENT*/
            $pdf->SetFont('helvetica','',8);
            for ($i=0 + ($n*4) ; $i < (($n+1)*4); $i++) {
                $pdf->setXY(67+(($i%4)*55),30+$rowHeight);
                $pdf->Cell(55,$rowHeight,$data['sidang_detail'][$i]->examination->spk_code??'',1,2,'C',false);
                $pdf->Cell(55,$rowHeight,$i<$dataCount ? ($data['sidang_detail'][$i]->examination->media->where('name', 'Laporan Uji')->first()->no??'') : '',1,2,'C',false);
                $pdf->Cell(55,$rowHeight,$data['certificateNumber'][$i]??'',1,2,'C',false);
                $pdf->Cell(55,$rowHeight*2,$data['sidang_detail'][$i]->examination->company->name??'',1,2,'C',false);
                $pdf->Cell(55,$rowHeight*2,$data['devices'][$i]->name??'',1,2,'C',false);
                $pdf->Cell(55,$rowHeight*2,$data['devices'][$i]->mark??'',1,2,'C',false);
                $pdf->Cell(55,$rowHeight,$data['devices'][$i]->model??'',1,2,'C',false);
                $pdf->Cell(55,$rowHeight,$data['devices'][$i]->capacity??'',1,2,'C',false);
                $pdf->Cell(55,$rowHeight,$data['devices'][$i]->serial_number??'',1,2,'C',false);
                $pdf->Cell(55,$rowHeight,$data['devices'][$i]->test_reference??'',1,2,'C',false);
                $pdf->Cell(55,$rowHeight,$data['devices'][$i]->manufactured_by??'',1,2,'C',false);
                $pdf->Cell(55,$rowHeight, $i<$dataCount ? \App\Services\MyHelper::tanggalIndonesia( $data['sidang_detail'][$i]->examination->equipmentHistory->where('location', 2)->first()->action_date ?? '' ) ?? '' : '' ,1,2,'C',false);
                $pdf->Cell(55,$rowHeight,\App\Services\MyHelper::tanggalIndonesia($data['sidang_detail'][$i]->startDate ?? '') ?? '',1,2,'C',false);
                $pdf->Cell(55,$rowHeight,\App\Services\MyHelper::tanggalIndonesia($data['sidang_detail'][$i]->endDate ?? '') ?? '',1,2,'C',false);
                $pdf->Cell(55,$rowHeight,$i<$dataCount ? $data['sidang_detail'][$i]->examination->examinationLab->name ?? '' : '',1,2,'C',false);
                $pdf->Cell(55,$rowHeight,\App\Services\MyHelper::tanggalIndonesia($data['sidang_detail'][$i]->targetDate ?? '') ?? '',1,2,'C',false);
                $pdf->Cell(55,$rowHeight,$data['sidang_detail'][$i]->finalResult ?? '',1,2,'C',false);
                $pdf->Cell(55,$rowHeight*5,$data['sidang_detail'][$i]->catatan ?? '',1,2,'C',false);
                $pdf->Cell(55,$rowHeight,$i<$dataCount ? $listKeputusanSidangQA[$data['sidang_detail'][$i]->finalResult ?? ''] : '',1,2,'C',false);
            }
        }

        //PDF-OUTPUT
        if ($method == 'getStream'){
            return $pdf->Output('', 'S');
        }
        $pdf->Output();
        exit;
    }
}
