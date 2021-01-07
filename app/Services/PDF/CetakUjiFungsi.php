<?php

namespace App\Services\PDF;


class CetakUjiFungsi
{
    private const UNDERSCORES = '____________________________';

    private function doubledecode($var){
        return urldecode(urldecode($var));
    }

    public function makePDF($data, $pdf)
    {
        $no_reg = $data['no_reg'];
        $company_name = $data['company_name'];
        $company_address = $data['company_address'];
        $company_phone = $data['company_phone'];
        $company_fax = $data['company_fax'];
        $device_name = $data['device_name'];
        $device_mark = $data['device_mark'];
        $device_manufactured_by = $data['device_manufactured_by'];
        $device_model = $data['device_model'];
        $device_serial_number = $data['device_serial_number'];
        $status = $data['status'];
        $catatan = $data['catatan'];
        $tgl_uji_fungsi = $data['tgl_uji_fungsi'];
        $nik_te = $data['nik_te'];
        $name_te = $data['name_te'];
        $pic = $data['pic'];
        $currentUser = $data['currentUser'];

		if($currentUser){
			$pic_urel = $currentUser->name;
		}else{
			$pic_urel = '-';
		}
        
        $pdf->judul_kop('LAPORAN UJI FUNGSI','');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        
        $pdf->Ln(10);
        $pdf->SetFont('helvetica','',11);
        $pdf->SetWidths(array(0.00125,50,140));
        $pdf->SetAligns(array('L','R','L'));
        // $pdf->SetFont('','BI'); -*
        $pdf->RowRect(array('','No. Registrasi',$this->doubledecode($no_reg)));	
        $pdf->RowRect(array('','Nama Perusahaan',$this->doubledecode($company_name)));	
        $pdf->RowRect(array('','Alamat',$this->doubledecode($company_address)));	
        $pdf->RowRect(array('','Telepon / Fax',$this->doubledecode($company_phone).' / '.$this->doubledecode($company_fax)));	
        $pdf->RowRect(array('','Nama Perangkat',$this->doubledecode($device_name)));	
        $pdf->RowRect(array('','Merek / Buatan',$this->doubledecode($device_mark).' / '.$this->doubledecode($device_manufactured_by)));	
        $pdf->RowRect(array('','Tipe / Serial Number',$this->doubledecode($device_model).' / '.$this->doubledecode($device_serial_number)));	
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
        $pdf->SetFont('','');
        $pdf->Cell(-78);
        $pdf->Cell(180,73,'Bandung, '.$this->doubledecode($tgl_uji_fungsi),0,0,'C');
        $pdf->Ln(-13);
        $pdf->Cell(180,110,'Diketahui oleh:',0,0,'C');
        $pdf->Ln(-7);
        $pdf->Cell(15);
        $pdf->Cell(18,110+25,'Officer Customer Relationship',0,0,'C');
        $pdf->Cell(50);
        $pdf->Cell(18,110+25,'Test Engineer Laboratorium',0,0,'C');
        $pdf->Cell(45);
        $pdf->Cell(18,110+25,'Pelanggan',0,0,'C');

        $pdf->Ln(40);
        $pdf->Cell(16);
        $pdf->Cell(18,100-5,$pic_urel,0,0,'C');
        $pdf->Cell(47);
        $pdf->Cell(18,100-5,$name_te,0,0,'C');
        $pdf->Cell(45);
        $pdf->Cell(18,100-5,$pic,0,0,'C');
        $pdf->SetFont('','');
        $pdf->Ln(1);
        $pdf->Cell(16);
        $pdf->Cell(18,100-5, self::UNDERSCORES ,0,0,'C');
        $pdf->Cell(47);
        $pdf->Cell(18,100-5, self::UNDERSCORES ,0,0,'C');
        $pdf->Cell(45);
        $pdf->Cell(18,100-5, self::UNDERSCORES ,0,0,'C');
        $pdf->Ln(1);
        $pdf->Cell(18,100+4.9,'NIK.',0,0,'L');
        $pdf->Cell(50);
        $pdf->Cell(18,100+4.9,'NIK. '.$nik_te,0,0,'L');
        
        $pdf->Ln(70);
        $pdf->SetFont('helvetica','',8);
        $pdf->Cell(185,5,"TLKM02/F/005 Versi 02",0,0,'L');

    /*Footer Manual*/
        
    /*End Footer Manual*/
        $pdf->Output();
        exit;
    }
}


// Route::get('/cetakHasilUjiFungsi/{no_reg}/{company_name}/{company_address}/{company_phone}/{company_fax}/{device_name}/{device_mark}/{device_manufactured_by}/{device_model}/{device_serial_number}/{status}/{catatan}/{tgl_uji_fungsi}/{nik_te}/{name_te}/{pic}', 
// array('as' => 'cetakHasilUjiFungsi', 


// function(
// 	$no_reg = null, $company_name = null, $company_address = null, $company_phone = null, $company_fax = null, 
// 	$device_name = null, $device_mark = null, $device_manufactured_by = null, $device_model = null , $device_serial_number = null, 
//     $status = null, $catatan = null, $tgl_uji_fungsi = null, $nik_te = null, $name_te = null , $pic = null ) 
