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

		$pdf->AliasNbPages();
		$pdf->AddPage();
        $pdf->SetMargins(17, 0, 17);


		$pdf->SetFont('helvetica','',10);
		$pdf->SetWidths([50,55,110]);
        $pdf->Ln(5);
		$pdf->SetX(0);
		$pdf->Row(array("Nomor Barang",":",$this->doubledecode($kode_barang))); 
		$pdf->Row(array("Nama Perangkat",":",$this->doubledecode($device_name))); 
		$pdf->Row(array("Nama Perusahaan",":",$this->doubledecode($company_name))); 
		$pdf->Row(array("Alamat Perusahaan",":",$this->doubledecode($company_address))); 
		$pdf->Row(array("Nomor HP Pemohon",":",$this->doubledecode($user_phone))); 		

	 	//LIST Perangkat
		$pdf->Ln(5); 
		$pdf->SetX(0);
		$pdf->SetWidths([10,35,85,50]);
		$pdf->SetAligns(['L','C','C','C']); 
		$pdf->SetFont('helvetica','B',10);
 		$pdf->RowRect(['data'=>['No.','Jumlah (Satuan)','Merek, Tipe/Model, Kapasitas, Nomor Seri, dan Negara Pembuat','Keterangan']]);
		$pdf->SetFont('helvetica','',10);
        
        $pic = '...............................';
		if(count($equipment)){
			$pic = $equipment[0]->pic;
			$no = 1;
			foreach($equipment as $data){
				$pdf->RowRect(['data'=>[$no,"$data->qty ($data->unit)",$data->description,$data->remarks]]);
				$no++;
			}
		}
		$pdf->setY(223);
		
		$pdf->Ln(5);  
	 	$pdf->SetFont('helvetica','',10); 
		$pdf->Cell(80, 4, 'Penerimaan Perangkat', 1, 0, 'C'); 
		$pdf->setX(115);
		$pdf->Cell(80, 4, 'Pengeluaran Perangkat', 1, 1, 'C'); 

		//TTD PENERIMAAN PERANGKAT
		$pdf->Ln(2); 
		$pdf->Cell(40, 4, 'Pelanggan', 1, 0, 'C');
		$pdf->Cell(40, 4, 'Officer TTH', 1, 0, 'C');
		$pdf->setX(115);
		$pdf->Cell(40, 4, 'Pelanggan', 1, 0, 'C');
		$pdf->Cell(40, 4, 'Officer TTH', 1, 1, 'C'); 
		 

		$pdf->drawTextBox('('.$pic.')', 40, 20, 'C', 'B', 1);
		$pdf->setXY(55,$pdf->getY()-20);
		$pdf->drawTextBox('('.$pic_urel.')', 40, 20, 'C', 'B', 1); 
	 	$pdf->setXY(115,$pdf->getY()-20);
		$pdf->drawTextBox('('.$pic.')', 40, 20, 'C', 'B', 1);
		$pdf->setXY(155,$pdf->getY()-20);
		$pdf->drawTextBox('('.$pic_urel.')', 40, 20, 'C', 'B', 1);  

		//TANGGAL PENERIMAAN & PENGEMBALIAN
		$pdf->setXY(17,$pdf->getY() - 22);
		$pdf->Cell(40,10,'Tgl '.$this->doubledecode($contract_date),0,0,'C');  
		$pdf->Cell(40,10,'Tgl '.$this->doubledecode($contract_date),0,0,'C'); 
		$pdf->setX(115);
		$pdf->Cell(40,10,'Tgl ..................',0,0,'C');
		$pdf->Cell(40,10,'Tgl ..................',0,0,'C'); 

		$pdf->Output();
		exit;
    }
}