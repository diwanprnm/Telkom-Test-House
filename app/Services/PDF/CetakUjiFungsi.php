<?php

namespace App\Services\PDF;


class CetakUjiFungsi
{
    private const UNDERSCORES = '(___________________________)';

    private function doubledecode($var){
        return urldecode(urldecode($var));
    }

    public function makePDF($data, $pdf)
    {
        $pdf->SetMargins(17, 0, 17);


        $status = $data['status'];
        $tgl_uji_fungsi = $data['tgl_uji_fungsi'];
        $name_te = $data['name_te'];
        $pic = $data['pic'];
        $currentUser = $data['currentUser'];

		if($currentUser){
			$pic_urel = $currentUser->name;
		}else{
			$pic_urel = '-';
		}
        
        $pdf->judul_kop('LAPORAN UJI FUNGSI','Function Test Report');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        
        $pdf->Ln(10);
        $pdf->SetFont('helvetica','',10);

        $upperDatas = [
            'Nomor Registrasi' => $this->doubledecode($data['no_reg']),
            'Nama Perusahaan' => $data['company_name'],
            'Alamat Perusahaan' => $data['company_address'],
            'Nama Perangkat' => $data['device_name'],
            'Merek' => $data['device_mark'],
            'Tipe/Model' => $data['device_model'],
            'Kapasitas' => $data['device_capacity'],
            'Nomor Seri' => $data['device_serial_number'],
            'Negara Pembuat' => $data['device_manufactured_by'],
            'Referensi Uji' => $data['device_test_reference'],
        ];

        $keyWidth = 55;
        $startY = 17;
        $i = 0;
        foreach($upperDatas as $key => $val ){
            $i++;
            $pdf->setXY(17,$startY+(($i+1)*10));
            $pdf->drawTextBox($key, $keyWidth, 10, 'L', 'M');
            $pdf->setXY(17+$keyWidth,$startY+(($i+1)*10));
            $pdf->drawTextBox($val, 121, 10, 'L', 'M');
        }

        //HASIL UJI FUNGSI
        $pdf->Ln(7);
        $pdf->Rect(17,$pdf->getY(),176,30);	
        $pdf->SetFont('','B');
        $pdf->Cell(0,5,'Hasil Uji Fungsi',0,1,'C');
        $pdf->Ln(20);
        $pdf->SetFont('ZapfDingbats','', 15);
        $status = 2;
        if($status == 1){
            $pdf->Cell(28, 5, "4", 0, 0, 'R');
            $pdf->Cell(88, 5, "m", 0, 1, 'R');
        }
        else if($status == 2){
            $pdf->Cell(28, 5, "m", 0, 0, 'R');
            $pdf->Cell(88, 5, "4", 0, 1, 'R');
        }else{
            $pdf->Cell(28, 5, "m", 0, 0, 'R');
            $pdf->Cell(88, 5, "m", 0, 1, 'R');
        }
        $pdf->SetY($pdf->GetY()-5);
        $pdf->SetFont('helvetica','', 10);
        $pdf->Cell(28, 5);
        $pdf->Cell(88, 5, "Memenuhi");
        $pdf->Cell(0, 5, "Tidak Memenuhi");

        //HASIL UJI FUNGSI
        $pdf->Ln(9);
        $pdf->Rect(17,$pdf->GetY(),176,35);
        $pdf->Cell(0,5,'Catatan:',0,1);
        $y = $pdf->GetY();
        $pdf->MultiCell(0, 5,$data['catatan'],0,'L');
        $pdf->SetY($y+(5*5));
        $pdf->Cell(18,5,'Beritanda:');
        $pdf->SetFont('ZapfDingbats','B');
        $pdf->Cell(5, 5, "4");
        $pdf->SetFont('helvetica','');
        $pdf->Cell(20,5,'pada kolom:');
        $pdf->SetFont('','B');
        $pdf->Cell(10,5,'Hasil Uji Fungsi');
        $pdf->SetFont('','');


        /* HANDSIGN SECTION */ 
        $pdf->Ln(9);
        $pdf->Rect(17,$pdf->GetY(),176,35);
        $pdf->Cell(117); $pdf->Cell(58,5, 'Bandung, '.$this->doubledecode($tgl_uji_fungsi) ,0,1,'C');
        $pdf->Cell(58,5,'Pelanggan',0,0,'C');
        $pdf->Cell(59,5,'Test Engineer TTH',0,0,'C');
        $pdf->Cell(58,5,'Officer TTH',0,1,'C');
        $pdf->Ln(19);
        $pdf->Cell(58,5,$pic,0,0,'C');
        $pdf->Cell(59,5,$name_te,0,0,'C');
        $pdf->Cell(58,5,$pic_urel,0,0,'C');
        $pdf->Ln(1);
        $pdf->Cell(58,5,self::UNDERSCORES,0,0,'C');
        $pdf->Cell(59,5,self::UNDERSCORES,0,0,'C');
        $pdf->Cell(58,5,self::UNDERSCORES,0,0,'C');

        $pdf->Output();
        exit;
    }
}
