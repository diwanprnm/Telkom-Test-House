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

        //setup pdf
        $pdf->AddPage();
        $pdf->AliasNbPages();
        $pdf->SetMargins(17, 0, 17);
        $isNextPage = 0;
        $yToNextPage = 240;

        // DATA PERANGKAT UJI - SECTION 
        $pdf->ln(2);
        $pdf->SetFont('helvetica','B',14);
        $pdf->Cell(0,5,'Technical Meeting(TM) Uji Lokasi',0,1,'C');
        $pdf->ln(2);
		$pdf->Cell(0,5,$data['companyName'],0,1,'C');
        $pdf->ln(3);
        $pdf->SetFont('helvetica','',8);
        $pdf->Cell(0,5,'A.    Data Perangkat',0,1);
        $pdf->SetWidths([10,25,41,25,25,25,25]);
        $pdf->SetFont('','B');
        $pdf->SetAligns(['C','C','C','C','C','C','C']);
        $pdf->Row(['No.', 'No. Registrasi', 'Nama Perangkat', 'Merek', 'Tipe/Model','Kapasitas', 'Referensi Uji']);
        $pdf->SetFont('','');
        $pdf->SetAligns(['C','C','C','C','C','C','L']);
        $pdf->Row(array('1', $data['examinationFunctionTestNO'], $data['deviceName'], $data['deviceMark'],$data['deviceModel'], $data['deviceCapacity'], $data['deviceTestReference']  ));
        $pdf->ln(3);

        // DATA SHEET FORM UJI - SECTION
        // $pdf->Cell(0,5,'B.    Data Sheet Sesuai dengan Data Perangkat',0,1);
        // $dataMatch = $data['examinationFunctionTestTE'] == 1 ? '4' : '';
        // $dataNotMatch = $data['examinationFunctionTestTE'] == 2 ? '4' : '';
        // $pdf->SetFont('ZapfDingbats','',20); $pdf->Cell(10,5,'',0); $pdf->Cell(10,5,$dataMatch,1,0,'C'); $pdf->SetFont('helvetica','',8); $pdf->Cell(20,5,'Sesuai');
        // $pdf->SetFont('ZapfDingbats','',20); $pdf->Cell(10,5,'',0); $pdf->Cell(10,5,$dataNotMatch,1,0,'C'); $pdf->SetFont('helvetica','',8); $pdf->Cell(20,5,'Tidak Sesuai/Lengkap',0,1);
        // $pdf->ln(2);

        // KESEPAKATAN TEST ENGINER - SECTION
        $pdf->Cell(0,5,'B.    Kesepakatan Jumlah Test Engineer (TE), Lokasi Uji/Kalibrasi, Durasi Pengujian/Kalibrasi serta Transportasi dan Akomodasi',0,1);
        $pdf->SetAligns(['C','C','C','C']);
        $pdf->SetWidths([30,35,41,70]);
		$pdf->SetFont('','B');
        $pdf->Row(['Jumlah TE','Lokasi Uji/Kalibrasi', 'Durasi Pengujian/Kalibrasi', 'Transportasi dan Akomodasi']);
		$pdf->SetFont('', '');
        $pdf->SetAligns(['C','C','C','L']);
        $row = $pdf->Row(['data' => ['', '', $data['exaimationDuration']?? '' ], 'minHeight' => 20] );
        $maxHeight = max($row['height'],20);
        $pdf->Rect(123, $pdf->GetY()-$row['height'] ,70,$maxHeight);
        $pdf->SetXY(123, $pdf->GetY()-($row['height']));
        $pdf->Cell(0,5,'- Transportasi darat dari dan ke Lab TTH (non SPB)*',0,2);
        $pdf->Cell(0,5,'- Akomodasi (non SPB)',0,2);
        $pdf->Cell(0,5,'- Transportasi udara antar kota/negara (SPB)**',0,2);
        $pdf->Cell(0,5,'- Tunjangan harian TE (SPB)',0,1);
        $pdf->ln(1);
        $pdf->Cell(0,4,'* Transportasi darat dari dan ke Lab TelkomTestHouse (TTH) berlaku untuk lokasi uji/kalibrasi di dalam Pulau Jawa.',0,1);
        $pdf->Cell(0,4,'** Transportasi udara antar kota/negara berlaku untuk lokasi uji/kalibrasi di luar Pulau Jawa atau luar negeri.',0,1);
        $pdf->Ln(2);

        //D.	Kelengkapan Alat Ukur
        $pdf->Cell(0,5,'C.    Kelengkapan Uji Lokasi',0,1);
        $pdf->SetWidths([10,85,41,40]);
        $pdf->SetFont('','B');
        $pdf->SetAligns(['C','C','C','C']);
        $pdf->Row(['No.', 'Kelengkapan yang Harus Disediakan Pelanggan', 'Penyedia', 'Keterangan']);
        $pdf->SetFont('','');
        $pdf->SetAligns(['C','C','C','C']);
        $pdf->Row([ 'data' => ['', $data['alatUkur'] ?? '', $data['penyediaAlatUkur'] ?? '', $data['keteranganAlatUkur'] ?? ''], 'minHeight' => 10 ]);
        $pdf->ln(2);

        //E.	Parsial Test
        $pdf->Cell(0,5,'D.    Parsial Test',0,1);
        $pdf->SetWidths([10,91,75]);
        $pdf->SetFont('','B');
        $pdf->SetAligns(['C','C','C']);
        $pdf->Row(['No.', 'Item Uji', 'Keterangan']);
        $pdf->SetFont('','');
        $pdf->SetAligns(['C','C','C']);
        $pdf->Row([ 'data' => ['', $data['itemTest'] ?? '', $data['keteranganParsialTest'] ?? ''], 'minHeight' => 10 ]);
        $pdf->ln(2);

        // DISCLAIMER - SECTION
        $pdf->SetFont('','B');
        $pdf->Cell(0,4,'Pernyataan:',0,1); $pdf->SetFont('','');
        $pdf->Cell(4.5,4,'1.');
        $pdf->MultiCell(0, 4,'Data sheet perangkat harus menggunakan Bahasa Indonesia atau Bahasa Inggris.');
        $pdf->Cell(4.5,4,'2.');
        $pdf->MultiCell(0, 4,'Penentuan jadwal uji lokasi dilakukan maksimal H-7 sebelum pelaksanaan uji lokasi.');
        $pdf->Cell(4.5,4,'3.');
        $pdf->MultiCell(0, 4,'Jika pengujian perangkat uji menggunakan alat ukur yang tidak disediakan oleh pelanggan atau TTH, maka sewa alat ukur menjadi tanggung jawab pelanggan. Apabila alat ukur disediakan oleh TTH dan harus dikirim melalui jasa pengiriman maka TTH akan mengirimkan maksimal H-7 pengujian serta biaya pengiriman dan asuransi alat ukur menjadi tanggung jawab pelanggan.');
        $pdf->Cell(4.5,4,'4.');
        $pdf->MultiCell(0, 4,'Jika ada item uji yang tidak dapat dilakukan di lokasi eksternal Lab TTH maka item uji harus diuji di Lab TTH dan dicantumkan di bagian D. Parsial Test.');
        $pdf->Cell(4.5,4,'5.');
        $pdf->MultiCell(0, 4,'Jika kondisi yang ditemukan di lokasi eksternal Lab TTH tidak sesuai dengan kesepakatan dalam TM uji lokasi ini, maka uji lokasi ini ditunda sampai dengan kondisi yang disepakati bersama antara TTH dengan pelanggan dan biaya yang muncul akibat penundaan ini menjadi tanggung jawab pelanggan.');
        $pdf->Cell(4.5,4,'6.');
        $pdf->MultiCell(0, 4,'Pengujian/kalibrasi di luar Lab TTH/dalam negeri adalah 150% dari biaya uji/kalibrasi ditambah tunjangan harian sesuai dengan tarif perjalanan dinas dalam negeri di Telkom, sedangkan pengujian/kalibrasi di luar negeri adalah 200% dari biaya uji/kalibrasi ditambah tunjangan harian sesuai dengan tarif perjalanan dinas luar negeri di Telkom.');
        $pdf->Cell(4.5,4,'7.');
        $pdf->MultiCell(0, 4,'Biaya transportasi dan akomodasi diatur sebagai berikut:');
        if($pdf->getY() > $yToNextPage  && $isNextPage == 0){$pdf->SetMargins(17, 0, 17);$pdf->AliasNbPages();$pdf->AddPage();$pdf->setY(28);$isNextPage = 1;}
        $pdf->SetX(21.5);
        $pdf->Cell(4.5,4,'a.');
        $pdf->MultiCell(0, 4,'Biaya transportasi meliputi:');
        $pdf->SetX(26);
        $pdf->Cell(4.5,4,'-');
        $pdf->MultiCell(0, 4,'Jika menggunakan transportasi darat maka pelanggan wajib menyediakan fasilitas antar/jemput dari Lab TTH ke lokasi uji termasuk fasilitas antar/jemput dari penginapan ke lokasi uji.');
        $pdf->SetX(26);
        $pdf->Cell(4.5,4,'-');
        $pdf->MultiCell(0, 4,'Jika menggunakan trasportasi udara maka pelanggan wajib memberikan dalam bentuk tiket pesawat atau kode booking pesawat');
        if($pdf->getY() > $yToNextPage  && $isNextPage == 0){$pdf->SetMargins(17, 0, 17);$pdf->AliasNbPages();$pdf->AddPage();$pdf->setY(28);$isNextPage = 1;}
        $pdf->SetX(21.5);
        $pdf->Cell(4.5,4,'b.');
        $pdf->MultiCell(0, 4,'Biaya akomodasi meliputi:');
        $pdf->SetX(26);
        $pdf->Cell(4.5,4,'-');
        $pdf->MultiCell(0, 4,'Penginapan: pelanggan wajib menyediakan minimal hotel bintang 3 atau yang terbaik di daerahnya dan masing-masing 1 (satu) kamar untuk 1 (satu) orang pada hotel yang sama.');
        $pdf->SetX(26);
        $pdf->Cell(4.5,4,'-');
        $pdf->MultiCell(0, 4,'Konsumsi: pelanggan wajib menyediakan konsumsi selama uji lokasi berlangsung.');
        if($pdf->getY() > $yToNextPage  && $isNextPage == 0){$pdf->SetMargins(17, 0, 17);$pdf->AliasNbPages();$pdf->AddPage();$pdf->setY(28);$isNextPage = 1;}
        // $pdf->Cell(4.5,4,'6.');
        // $pdf->Write(4,'Jika kondisi yang ditemukan di lokasi tidak sesuai dengan kesepakatan dalam ');$pdf->SetFont('','I'); $pdf->Write(4,'technical meeting '); $pdf->SetFont('','');$pdf->Write(4,'uji lokasi ini, maka uji lokasi ini'); $pdf->ln();
        // $pdf->Cell(4.5,4,''); $pdf->Write(4,'ditunda sampai dengan kondisi yang disepakati bersama antara TTH dengan pelanggan dan biaya yang muncul akibat penundaan ini ');$pdf->ln();
        // $pdf->Cell(4.5,4,''); $pdf->Write(4,'menjadi tanggung jawab pelanggan.');
        $pdf->ln();

        // SIGN - SECTION
        $pdf->Cell(117,4,''); $pdf->Cell(59,4,'Bandung, '.$data['examinationFunctionDate'],0,1,'C');
        $pdf->Cell(58,4,'Pelanggan',0,0,'C');
        $pdf->Cell(59,4,'Test Engineer TTH',0,0,'C');
        $pdf->Cell(58,4,'Officer TTH',0,0,'C');
        $pdf->Ln(15);
        $pdf->SetFont('','U');
        $pdf->Cell(58,4,'( '.$data['userName'].' )',0,0,'C');
        $pdf->Cell(59,4,'( '.$data['examinationFunctionTestPIC'].' )',0,0,'C');
        $pdf->Cell(58,4,'( '.$data['adminName'].' )',0,1,'C');
        $pdf->SetFont('','');
        $pdf->Ln(4);
        $pdf->Cell(0,4,'Mengetahui,',0,1,'C');
        $pdf->Cell(0,4,'Mgr. Lab',0,1,'C');
        $pdf->Ln(15);
        $pdf->SetFont('','U');
        $pdf->Cell(0,4,'( '.$data['managerLab'].' )',0,1,'C');
        $pdf->SetFont('','');


        $pdf->Output();
        exit;
    }
}
