<?php
namespace App\Services\PDF;


class CetakBuktiPenerimaanPerangkat{

	private function doubledecode($var){
        return urldecode(urldecode($var));
    }

    public function makePDF($data, $pdf)
    {
        $request  = $data['request'];
        $kode_barang = $data['kode_barang'];
        $company_name = $data['company_name'];
        $company_address = $data['company_address'];
        $company_phone = $data['company_phone'];
        $company_fax = $data['company_fax'];
        $user_phone = $data['user_phone'];
        $user_fax = $data['user_fax'];
        $device_name = $data['device_name'];
        $device_mark = $data['device_mark'];
        $device_manufactured_by = $data['device_manufactured_by'];
        $device_model = $data['device_model'];
        $device_serial_number = $data['device_serial_number'];
        $exam_type = $data['exam_type'];
        $exam_type_desc = $data['exam_type_desc'];
        $contract_date = $data['contract_date'];
        $equipment = $data['equipment'];
        $currentUser = $data['currentUser'];


		if($currentUser){
			$pic_urel = $currentUser->name;
		}else{
			$pic_urel = '...............................';
		}
        
		$pdf->judul_kop('BUKTI PENERIMAAN & PENGELUARAN PERANGKAT UJI','Nomor: '.$this->doubledecode($kode_barang));
		$pdf->setData(['kodeForm' => 'TLKM02/F/009 Versi 01']);
		$pdf->AliasNbPages();
		$pdf->AddPage();
		 
		$y = $pdf->getY();
	 
		$pdf->SetFont('helvetica','',10);
		$pdf->setXY(10.00125,$y + 3);
		$pdf->Cell(10,5,"Nama Perangkat",0,0,'L');
		$pdf->SetWidths(array(0.00125,50,55,110));
		$pdf->Row(array("","",":",$this->doubledecode($device_name))); 
		/*Pemilik Perangkat*/
		$y = $pdf->getY(); 
		$pdf->setXY(10.00125,$y + 1);
		$pdf->Cell(10,5,"Nama Perusahaan",0,0,'L');
		$pdf->SetWidths(array(0.00125,50,55,110));
		$pdf->Row(array("","",":",$this->doubledecode($company_name)));
		/*Alamat*/ 
		$y = $pdf->getY(); 
		$pdf->setXY(10.00125,$y + 1);
		$pdf->Cell(10,5,"Alamat",0,0,'L');
		$pdf->SetWidths(array(0.00125,50,55,110));
		$pdf->Row(array("","",":",$this->doubledecode($company_address)));
		
		/*Phone & Fax*/
		$y = $pdf->getY(); 
		$pdf->setXY(10.00125,$y + 1);
		$pdf->Cell(10,5,"Nomor Telepon",0,0,'L');
		$pdf->SetWidths(array(0.00125,50,55,110));
		$pdf->Row(array("","",":",$this->doubledecode($user_phone))); 

		/*Jenis Pengujian*/ 
		$y = $pdf->getY(); 
		$pdf->setXY(10.00125,$y + 1);
		$pdf->Cell(10,5,"Jenis Pengujian",0,0,'L');
		$pdf->SetWidths(array(0.00125,50,55,110));
		$pdf->Row(array("","",":",$this->doubledecode($exam_type).'/'.$this->doubledecode($exam_type_desc))); 

	 	//LIST Perangkat
		$pdf->Ln(2); 
		$pdf->SetWidths(array(0.00125,20,30,30,50,60));
		$pdf->SetAligns(array('L','C','C','C','C','C')); 
		$pdf->SetFont('helvetica','B',10);
 		$pdf->RowRect(array('','No.','Jumlah','Satuan','Uraian Perangkat','Keterangan'));
		$pdf->SetFont('helvetica','',10);
        
        
        if(count($equipment)){
			$pic = $equipment[0]->pic;
			$no = 1;
			foreach($equipment as $data){
				$pdf->RowRect(array('',$no,$data->qty,$data->unit,$data->description,$data->remarks));
				$no++;
			}
			for ($i=count($equipment); $i <24 ; $i++) { 
				$pdf->RowRect(array('','','','','',''));
			}
		}else{
			$pic = '...............................';
			for ($i=0; $i <24 ; $i++) { 
				$pdf->RowRect(array('','','','','',''));
			}	  			
		}

		$pdf->Ln(2);  
	 	$pdf->SetFont('helvetica','',10); 
	 	$pdf->SetFillColor(976,245,458);
		$pdf->setX(10.00125);
		$pdf->Cell(80, 4, 'Penerimaan Perangkat', 1, 0, 'C',true); 
		$pdf->setX(120);
		$pdf->Cell(80, 4, 'Pengambilan Perangkat', 1, 0, 'C',true); 

		//TTD PENERIMAAN PERANGKAT

		$pdf->Ln(6); 
	 	$pdf->SetFont('helvetica','',10); 
		$pdf->setX(10.00125);
		$pdf->Cell(40, 4, 'Pelanggan', 1, 0, 'C');
		$pdf->Cell(40, 4, 'Telkom Test House', 1, 0, 'C');
		 
		$pdf->setX(10.00125);
		$pdf->drawTextBox('('.$pic.')', 40, 25, 'C', 'B', 1);
		$pdf->setXY(50,$pdf->getY()-25);
		$pdf->drawTextBox('('.$pic_urel.')', 40, 25, 'C', 'B', 1); 
		 
		//TTD PENGAMBILAN PERANGKAT 
	  	$pdf->SetFont('helvetica','',10); 
	 	$pdf->setXY(120,$pdf->getY()-25);
		$pdf->Cell(40, 4, 'Pelanggan', 1, 0, 'C');
		$pdf->Cell(40, 4, 'Telkom Test House', 1, 0, 'C'); 
		$pdf->setX(120);
		$pdf->drawTextBox('(...............................)', 40, 25, 'C', 'B', 1);
		$pdf->setXY(160,$pdf->getY() -25);
		$pdf->drawTextBox('(...............................)', 40, 25, 'C', 'B', 1);  

		//TANGGAL PENERIMAAN & PENGEMBALIAN
		$pdf->setXY(10,$pdf->getY() - 22);
		$pdf->Cell(40,10,'TGL '.$this->doubledecode($contract_date),0,0,'C');  
		$pdf->Cell(40,10,'TGL '.$this->doubledecode($contract_date),0,0,'C'); 
		$pdf->setX(127);
		$pdf->Cell(40,10,'TGL ..................',0,0,'L');
		$pdf->Cell(40,10,'TGL ..................',0,0,'L'); 

		$pdf->Output();
		exit;
    }
}