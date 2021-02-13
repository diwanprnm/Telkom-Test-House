<?php

namespace App\Services\PDF;


class CetakTechnicalMeetingUjiLokasi
{
    public function makePDF($data, $pdf)
    {
        /**
         * SETUP BASIC
         */
        //set data
        $pdf->setData([
            'title' => 'TECHNICAL MEETING UJI LOKASI DALAM NEGERI',
            'subTitle' => 'Technical Meeting On-Site Testing Contract',
            'kodeForm' => 'TLKM02/F/008 Versi 02',
        ]);

        //setup pdf
        $pdf->AddPage();
        $pdf->AliasNbPages();
		$pdf->SetFont('helvetica','',10);


        /**
         * DATA SECTION
         */
		$pdf->SetFont('helvetica','B',12);
        $pdf->Cell(190,10,'Pembahasan Technical Meeting:'); $pdf->ln(6);

        // DATA PERANGKAT UJI - SECTION 
		$pdf->SetFont('helvetica','',10);
        $pdf->Cell(190,10,'A. Daftar Perangkat Uji'); $pdf->ln(8);
        //Table Header
		$pdf->SetFont('helvetica','B',10);
        $pdf->Cell(20,7,'No.',1,0,'C');
        $pdf->Cell(50,7,'Nama Perangkat',1,0,'C');
        $pdf->Cell(40,7,'Merek/Tipe',1,0,'C');
        $pdf->Cell(40,7,'Kapasitas',1,0,'C');
        $pdf->Cell(40,7,'Referensi Uji',1,0,'C');
        $pdf->Ln(7);
        // Table body
        $pdf->SetFont('helvetica','',10);
        $pdf->SetWidths(array(20,50,40,40,40));
        $pdf->Row(array('1', $data['deviceName'], $data['deviceMark'].'/'.$data['deviceModel'], $data['deviceCapacity'], $data['deviceTestReference']  ));


        // DATA SHEET FORM UJI - SECTION
        $pdf->Cell(190,10,'B. Data Sheet sesuai dengan Form Uji'); $pdf->ln(10);
        $dataMatch = $dataNotMatch = '';
        if($data['examinationFunctionTestTE']){$dataMatch = 4;} else {$dataNotMatch = 4;}
        $pdf->SetFont('ZapfDingbats','',20); $pdf->Cell(20,7,'',0); $pdf->Cell(10,7,$dataMatch,1,0,'C'); $pdf->SetFont('helvetica','',10); $pdf->Cell(20,7,'Sesuai');
        $pdf->SetFont('ZapfDingbats','',20); $pdf->Cell(20,7,'',0); $pdf->Cell(10,7,$dataNotMatch,1,0,'C'); $pdf->SetFont('helvetica','',10); $pdf->Cell(20,7,'Tidak Sesuai/Lengkap');
        $pdf->ln(9);


        // KESEPAKATAN TEST ENGINER - SECTION
        $pdf->Cell(190,10,'C. Kesepakatan Test Engineer dan Lokasi Uji'); $pdf->ln(8);
        //table head
		$pdf->SetFont('helvetica','B',10);
        $pdf->Cell(55,7,'Test Engineer',1,0,'C');
        $pdf->Cell(55,7,'Uji Lokasi',1,0,'C');
        $pdf->Ln(7);
        //table body
		$pdf->SetFont('helvetica','',10);
        $pdf->SetWidths(array(55,55));
        $pdf->Row(array($data['examinationFunctionTestPIC'], $data['companyAddress'].', '.$data['companyCity']  ));
        $pdf->Ln(7);


        // DISCLAIMER - SECTION
        $pdf->Write(10,'Catatan:');$pdf->Ln(8);
		$pdf->SetFont('helvetica','BI',10); $pdf->Write(5,'Disclaimer: '); $pdf->SetFont('helvetica','',10);
        $pdf->Write(5,'jika kondisi yang ditemukan di lokasi tidak sesuai dengan kesepakatan dalam ');
		$pdf->SetFont('helvetica','I',10); $pdf->Write(5,'Technical Meeting'); $pdf->SetFont('helvetica','',10);
        $pdf->Write(5,' Uji Lokasi Dalam Negeri, maka pengujian ditunda sampai dengan kondisi yang disepakati bersama antara pelanggan dengan Telkom Test House dan biaya seluruhnya ditanggung oleh pelanggan.'); $pdf->ln(5);
        $pdf->Write(5,'Demikian ');
        $pdf->SetFont('helvetica','I',10); $pdf->Write(5,'Technical Meeting'); $pdf->SetFont('helvetica','',10);
        $pdf->Write(5,' Uji Lokasi Dalam Negeri ini dilaksanakan dan dapat digunakan sebagaimana mestinya.');
        $pdf->Ln(15);


        // SIGN - SECTION
        $pdf->Cell(125,7,''); $pdf->Cell(60,7,'Bandung, '.$data['examinationFunctionDate'],0,0,'C'); $pdf->Ln(5);
        $pdf->Cell(5,7,''); $pdf->Cell(60,7,'Pelanggan',0,0,'C'); $pdf->Cell(60,7,'Test Engineer Laboratorium QA',0,0,'C'); $pdf->Cell(60,7,'Officer UREL',0,0,'C');
        $pdf->Ln(25); 
        $pdf->Cell(5,7,''); $pdf->Cell(60,7,'( '.$data['userName'].' )',0,0,'C'); $pdf->Cell(60,7,'( '.$data['examinationFunctionTestPIC'].' )',0,0,'C'); $pdf->Cell(60,7,'( '.$data['adminName'].' )',0,0,'C');
        $pdf->Ln(9);
        $pdf->Cell(65,7,''); $pdf->Cell(120,7,'Mengetahui,',0,0,'C'); $pdf->Ln(5);
        $pdf->Cell(65,7,''); $pdf->Cell(60,7,'Manager Laboratorium QA',0,0,'C'); $pdf->Cell(60,7,'Manager UREL',0,0,'C');
        $pdf->Ln(25);
        $pdf->Cell(65,7,''); $pdf->Cell(60,7,'( '.$data['managerLab'].' )',0,0,'C'); $pdf->Cell(60,7,'( '.$data['managerUrel'].' )',0,0,'C'); $pdf->Ln(7);
        
        $pdf->Output();
        exit;
    }
}
