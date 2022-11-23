<?php

namespace App\Services\PDF;
use Storage;

class CetakUjiFungsi
{
    private const UNDERSCORES = '(___________________________)';

    private function doubledecode($var){
        return urldecode(urldecode($var));
    }

    private $allowedFile = ['pdf','mp4','3gp'];

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
        
        $pdf->judul_kop('LAPORAN UJI FUNGSI (UF)','Function Test Report');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->Cell(0,5,'Laporan Uji Fungsi (UF)',0,0,'C');
        
        $pdf->Ln(10);
        $pdf->SetFont('helvetica','',10);

        $upperDatas = [
            'Nomor Registrasi' => $this->doubledecode($data['no_reg']),
            'Nama Perusahaan' => $this->doubledecode($data['company_name']),
            // 'Alamat Perusahaan' => $this->doubledecode($data['company_address']),
            'Nama Perangkat' => $this->doubledecode($data['device_name']),
            'Merek' => $this->doubledecode($data['device_mark']),
            'Tipe/Model' => $this->doubledecode($data['device_model']),
            'Kapasitas' => $this->doubledecode($data['device_capacity']),
            'Nomor Seri' => $this->doubledecode($data['device_serial_number']),
            'Negara Pembuat' => $this->doubledecode($data['device_manufactured_by']),
            'Referensi Uji' => $this->doubledecode($data['device_test_reference']),
        ];

        $keyWidth = 35;
        $startY = 17;
        $i = 1;
        $pdf->SetFont('','B');
        $pdf->setXY(17,$startY+((2)*10));
        $pdf->drawTextBox('Keterangan', $keyWidth, 10, 'C', 'M');
        $pdf->setXY(17+$keyWidth,$startY+((2)*10));
        $pdf->drawTextBox('Data Perangkat Terdaftar', 75, 10, 'C', 'M');
        $pdf->setXY(17+$keyWidth+75,$startY+((2)*10));
        $pdf->drawTextBox('Verifikasi Data Perangkat', 66, 10, 'C', 'M');
        $pdf->SetFont('','');
        foreach($upperDatas as $key => $val ){
            $i++;
            $keyHigh = strlen($val) > 75 ? 20 : 10;
            $pdf->setXY(17,$startY+(($i+1)*10));
            $pdf->drawTextBox($key, $keyWidth, $keyHigh, 'L', 'M');
            $pdf->setXY(17+$keyWidth,$startY+(($i+1)*10));
            $pdf->drawTextBox($val, 75, $keyHigh, 'L', 'M');
            $pdf->setXY(17+$keyWidth+75,$startY+(($i+1)*10));
            $pdf->drawTextBox('', 66, $keyHigh, 'L', 'M');
            if($keyHigh == 20){$i++;}
        }

        //HASIL UJI FUNGSI
        $pdf->Ln(7);
        $pdf->Rect(17,$pdf->getY(),176,30);	
        $pdf->SetFont('','B');
        $pdf->Cell(0,5,'Hasil UF',0,1,'C');
        $pdf->Ln(8);
        $pdf->SetFont('','');
        $pdf->Cell(0,5,$status == 1 ? 'Memenuhi' : 'Tidak Memenuhi',0,1,'C');
        
        $pdf->Ln(7);$pdf->SetFont('','B');
        // $pdf->Ln(20);
        // $pdf->SetFont('ZapfDingbats','', 15);
        // if($status == 1){
        //     $pdf->Cell(28, 5, "4", 0, 0, 'R');
        //     $pdf->Cell(88, 5, "m", 0, 1, 'R');
        // }
        // else if($status == 2){
        //     $pdf->Cell(28, 5, "m", 0, 0, 'R');
        //     $pdf->Cell(88, 5, "4", 0, 1, 'R');
        // }else{
        //     $pdf->Cell(28, 5, "m", 0, 0, 'R');
        //     $pdf->Cell(88, 5, "m", 0, 1, 'R');
        // }
        // $pdf->SetY($pdf->GetY()-5);
        // $pdf->SetFont('helvetica','', 10);
        // $pdf->Cell(28, 5);
        // $pdf->Cell(88, 5, "Memenuhi");
        // $pdf->Cell(0, 5, "Tidak Memenuhi");

        //HASIL UJI FUNGSI
        $pdf->SetFont('','');
        $pdf->Ln(9);
        $pdf->Rect(17,$pdf->GetY(),176,35);
        $pdf->Cell(0,5,'Catatan:',0,1);
        $y = $pdf->GetY();
        $pdf->MultiCell(0, 5,$this->doubledecode($data['catatan']),0,'L');
        $pdf->SetY($y+(5*5));
        count($data['evidence']) ? $pdf->Cell(18,5,'Bukti: lihat lampiran (muncul jika ada bukti)') : '';
        // $pdf->SetFont('ZapfDingbats','B');
        // $pdf->Cell(5, 5, "4");
        // $pdf->SetFont('helvetica','');
        // $pdf->Cell(20,5,'pada kolom:');
        // $pdf->SetFont('','B');
        // $pdf->Cell(10,5,'Hasil Uji Fungsi');


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

        if(count($data['evidence'])){
            foreach ($data['evidence'] as $fileName) {
                $pdf->AliasNbPages();
                $pdf->AddPage();
                $pdf->SetY(28);
                $pdf->SetFont('helvetica','BU',12);
                $pdf->Cell(0,5,'Lampiran UF (muncul jika ada bukti)',0,0,'C');

                $pdf->Ln(10);
                $url = 'examination/'.$data['id'].'/'.$fileName;
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                in_array($ext, $this->allowedFile) ? $pdf->Cell(0,5,$fileName,0,0,'C') : $pdf->Image((Storage::disk('minio')->url($url)),20,50,100);
            }
        }

        $pdf->Output();
        exit;
    }
}
