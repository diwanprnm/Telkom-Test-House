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
        $no_reg = $data['no_reg'];
        $company_name = $data['company_name'];
        $company_address = $data['company_address'];
        $company_phone = $data['company_phone'];
        $device_name = $data['device_name'];
        $device_mark = $data['device_mark'];
        $device_manufactured_by = $data['device_manufactured_by'];
        $device_model = $data['device_model'];
        $device_serial_number = $data['device_serial_number'];
        $status = $data['status'];
        $catatan = $data['catatan'];
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
        $pdf->SetFont('helvetica','',11);
        $pdf->SetWidths(array(0.00125,50,140));
        $pdf->SetAligns(array('L','L','L'));
        $pdf->RowRect(array('','No. Reg.',$this->doubledecode($no_reg)));	
        $pdf->RowRect(array('','Nama Perusahaan',$this->doubledecode($company_name)));	
        $pdf->RowRect(array('','Alamat',$this->doubledecode($company_address)));	
        $pdf->RowRect(array('','Nomor Telepon',$this->doubledecode($company_phone)));	
        $pdf->RowRect(array('','Nama Perangkat',$this->doubledecode($device_name)));	
        $pdf->RowRect(array('','Merek/Pabrik',$this->doubledecode($device_mark)));	
        $pdf->RowRect(array('','Model/Tipe',$this->doubledecode($device_model)));
        $pdf->RowRect(array('','Nomor Seri',$this->doubledecode($device_serial_number)));
        $pdf->RowRect(array('','Negara Pembuat',$this->doubledecode($device_manufactured_by)));
        $pdf->Ln(1);
        $pdf->Rect(10,$pdf->getY(),190,40);	
        $pdf->SetFont('','B');
        $pdf->Cell(180,10,'Hasil Uji Fungsi',0,0,'C');
        $pdf->Ln(-14);
        if($status == 1){
            $pdf->SetFont('ZapfDingbats','', 10);
            $pdf->Cell(20);
            $pdf->Cell(4, 100, "4", 0, 0);
            $pdf->SetFont('helvetica','',10);
            $pdf->Cell(18,100,'Memenuhi',0,0,'C');
            $pdf->SetFont('ZapfDingbats','', 10);
            $pdf->Cell(70);
            $pdf->Cell(4, 100, "m", 0, 0);
            $pdf->SetFont('helvetica','',10);
            $pdf->Cell(8);
            $pdf->Cell(18,100,'Tidak Memenuhi',0,0,'C');
        }
        else if($status == 2){
            $pdf->SetFont('ZapfDingbats','', 10);
            $pdf->Cell(20);
            $pdf->Cell(4, 100, "m", 0, 0);
            $pdf->SetFont('helvetica','',10);
            $pdf->Cell(18,100,'Memenuhi',0,0,'C');
            $pdf->SetFont('ZapfDingbats','', 10);
            $pdf->Cell(70);
            $pdf->Cell(4, 100, "4", 0, 0);
            $pdf->SetFont('helvetica','',10);
            $pdf->Cell(8);
            $pdf->Cell(18,100,'Tidak Memenuhi',0,0,'C');
        }
        
        $pdf->Rect(10,$pdf->getY()+55,190,40);	
        $pdf->Ln(1);
        $pdf->Rect(10,$pdf->getY()+55+40,190,50);
        $pdf->setY($pdf->getY()+58);
        $pdf->SetWidths(array(5.00125,20,160));
        $pdf->SetAligns(array('L','L','L'));
        $pdf->Row(array('','Catatan:',$this->doubledecode($catatan)));	
        $pdf->Cell(18,50,'Beri tanda',0,0,'L');
        $pdf->SetFont('ZapfDingbats','', 10);
        $pdf->Cell(4, 50, "4", 0, 0);
        $pdf->SetFont('helvetica','',10);
        $pdf->Cell(20,50,'pada kolom',0,0,'L');
        $pdf->SetFont('','B');
        $pdf->Cell(35,50,'HASIL UJI FUNGSI',0,0,'L');


        /**
         * HANDSIGN SECTION
         */ 
        $pdf->Ln(15);

        $pdf->Ln(-20);
        $pdf->SetFont('','');
        $pdf->Cell(144); $pdf->Cell(18,100-5, 'Bandung, '.$this->doubledecode($tgl_uji_fungsi) ,0,0,'C');

        $pdf->Ln(-15);
        $pdf->Cell(16); $pdf->Cell(18,110+25,'Pelanggan',0,0,'C');
        $pdf->Cell(47); $pdf->Cell(18,110+25,'Test Engineer Laboratorium QA',0,0,'C');
        $pdf->Cell(45); $pdf->Cell(18,110+25,'Officer UREL',0,0,'C');

        $pdf->Ln(45);
        $pdf->Cell(16); $pdf->Cell(18,100-5,$pic,0,0,'C');
        $pdf->Cell(47); $pdf->Cell(18,100-5,$name_te,0,0,'C');
        $pdf->Cell(45); $pdf->Cell(18,100-5,$pic_urel,0,0,'C');
        
        $pdf->Ln(1);
        $pdf->SetFont('','');
        $pdf->Cell(16); $pdf->Cell(18,100-5, self::UNDERSCORES ,0,0,'C');
        $pdf->Cell(47); $pdf->Cell(18,100-5, self::UNDERSCORES ,0,0,'C');
        $pdf->Cell(45); $pdf->Cell(18,100-5, self::UNDERSCORES ,0,0,'C');
        
        $pdf->setData(['kodeForm' => 'TLKM02/F/005 Versi 03']);

    /*Footer Manual*/
        
    /*End Footer Manual*/
        $pdf->Output();
        exit;
    }
}
