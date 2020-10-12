<?php

namespace App\Services\PDF;

class CetakSPB
{
    public function makePDF($data, $pdf)
    {
        $is_poh = $data[0]['is_poh'];
		$manager_urel = $data[0]['manager_urel'];
		$spb_number = $data[0]['spb_number'];
		$user_name = $data[0]['exam']['user']['name'];
		$company_name = $data[0]['exam']['company']['name'];
		$no_reg = $data[0]['exam']['function_test_NO'];
		$test_reference = $data[0]['exam']['device']['test_reference'];
		if($data[0]['exam']['company']['address'] != null){
			if($data[0]['exam']['company']['postal_code'] != null){
				$company_address = $data[0]['exam']['company']['address'].", ".$data[0]['exam']['company']['city'].", ".$data[0]['exam']['company']['postal_code'].".";
			}else{
				$company_address = $data[0]['exam']['company']['address'].", ".$data[0]['exam']['company']['city'].".";
			}
		}else{
			$company_address = "-";
		}
		if($data[0]['exam']['company']['fax'] != null){
			$company_contact = $data[0]['exam']['company']['phone_number']." - ".$data[0]['exam']['company']['fax'];
		}else{
			$company_contact = $data[0]['exam']['company']['phone_number'];
		}
		setlocale(LC_ALL, 'IND');
		$contract_date = date('j', strtotime($data[0]['exam']['contract_date']))." ".strftime('%B %Y', strtotime($data[0]['exam']['contract_date']));
		$exam_type = $data[0]['exam']['examinationType']['name'];
		$biaya = 0;
		for($i=0;$i<count($data[0]['arr_nama_perangkat']);$i++){
			$biaya = $biaya + $data[0]['arr_biaya'][$i];
		}
		$ppn = floor(0.1*$biaya);
		$total_biaya = $biaya + $ppn;
		$terbilang = $pdf->terbilang($total_biaya, 3);
		$spb_date = date('j', strtotime($data[0]['spb_date']))." ".strftime('%B %Y', strtotime($data[0]['spb_date']));
//		$payment_method = $data[0]['payment_method']->data->VA;
	// $pdf->judul_kop('FORM TINJAUAN KONTRAK','Contract Review Form');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->Ln(20);
	$pdf->SetFont('helvetica','B',9);
	// $pdf->SetFont('','BU');
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(60);
	$pdf->Cell(70,5,'DIVISI DIGITAL SERVICE - PT TELKOM',0,0,'C');
	$pdf->Ln();
	$pdf->SetFont('helvetica','B',9);
	$pdf->Cell(60);
	$pdf->SetFont('','BU');
	$pdf->Cell(70,5,'SURAT PEMBERITAHUAN BIAYA (SPB)',0,0,'C');
	$pdf->Ln();
	$pdf->SetFont('helvetica','B',9);
	$pdf->Cell(60);
	// $pdf->SetFont('','I');
	$pdf->Cell(70,5,'No. '.$spb_number,0,0,'C');
	
	$pdf->Ln(10);
	$pdf->SetFont('helvetica','',9);
	$pdf->SetWidths(array(48,30,5,82));
	$pdf->SetAligns(array('C','L','L','L'));
	$pdf->Row(array('','Nama Perusahaan',':',''));
		$pdf->SetFont('helvetica','B',9);
		$pdf->SetXY(93.00125,$pdf->GetY()-5);
		$pdf->Cell(0,5,$company_name,0,0,'L');
		$pdf->Ln();
	$pdf->SetFont('helvetica','',9);
	$pdf->Row(array('','','','Up. '.$user_name));	
	$pdf->Row(array('','Alamat',':',$company_address));	
	$pdf->Row(array('','Telepon / Fax',':',$company_contact));	
	
	$pdf->Ln(4);
	$pdf->SetFont('helvetica','',9);
	$pdf->SetWidths(array(13,7,160));
	$pdf->SetAligns(array('C','L','L'));
	$pdf->Row(array('','I. ','Merujuk Kontrak Pengujian Saudara tanggal '.$contract_date.' perihal permohonan uji mutu ('.$no_reg.'), dengan ini kami beritahukan bahwa biaya pengujian yang harus dibayar adalah :'));
	
	$pdf->Ln(1);
	$pdf->SetFont('helvetica','B',9);
	$pdf->SetWidths(array(17,8,125,27));
	$pdf->SetAligns(array('L','C','C','C'));
	$pdf->RowRect(array('','No','Nama','Biaya (Rp.)'));
	$pdf->SetFont('helvetica','',9);
	$pdf->SetAligns(array('L','L','L','R'));
	for($i=0;$i<count($data[0]['arr_nama_perangkat']);$i++){
		$item = $i == 0 ? $data[0]['arr_nama_perangkat'][$i].' ('.$test_reference.')' : $data[0]['arr_nama_perangkat'][$i];
		$no = $data[0]['arr_nama_perangkat'][$i] == 'Kode Unik' ? '' : ($i+1).'.';
		$pdf->RowRect(array('',$no,$item,number_format($data[0]['arr_biaya'][$i],0,",",".").",-"));
	}
	$pdf->RowRect(array('','','PPN 10 %',number_format($ppn,0,",",".").",-"));
	$pdf->SetFont('helvetica','B',9);
	$pdf->RowRect(array('','','Total Biaya Pengujian',number_format($total_biaya,0,",",".").",-"));	
	$pdf->SetWidths(array(17,160));
	$pdf->SetAligns(array('L','C'));
	$pdf->SetFont('','BI');
	$pdf->RowRect(array('','Terbilang : '.$terbilang.' Rupiah'));	
	
	$pdf->Ln(3);
	$pdf->SetFont('helvetica','',9);
	$pdf->SetWidths(array(13,7,160));
	$pdf->SetAligns(array('C','L','L'));
	$pdf->Row(array('','II. ','Ketentuan dan tata cara pembayaran diatur sebagai berikut :'));	
	
	$pdf->Ln(1);
	$pdf->SetFont('helvetica','',9);
	$pdf->SetWidths(array(20,7,153));
	$pdf->SetAligns(array('C','L','L'));
	$pdf->Row(array('','1. ','Pembayaran dilakukan melalui Virtual Account dengan pilihan bank sebagai berikut :'));
	for($i=0;$i<count($payment_method);$i++){
		$pdf->SetFont('ZapfDingbats','', 5);
		$pdf->SetX(40.00125);
		$pdf->Cell(5, 5, "l", 0, 0);
		$pdf->SetFont('helvetica','',9);
		$pdf->Cell(8,5,$payment_method[$i]->productName,0,0,'L');
		$pdf->Ln();
	}
		$pdf->SetX(37.00125);
		$pdf->Cell(0,5,'Saudara wajib mengikuti petunjuk yang ada di website Telkom Test House atau e-mail petunjuk',0,0,'L');
		$pdf->Ln();
		$pdf->SetX(37.00125);
		$pdf->Cell(0,5,'pembayaran.',0,0,'L');
		$pdf->Ln();
	$pdf->Row(array('','2. ','Pembayaran dilakukan'));
		$pdf->SetFont('helvetica','B',9);
		$pdf->SetXY(70.00125,$pdf->GetY()-5);
		$pdf->Cell(0,5,'paling lambat 14 (empat belas) hari kalender setelah penerbitan SPB.',0,0,'L');
		$pdf->SetFont('helvetica','',9);
		$pdf->Ln();
		$pdf->SetX(37.00125);
		$pdf->Cell(0,5,'Apabila pada tenggang waktu tersebut Saudara tidak melakukan pembayaran, SPB ini tidak berlaku.',0,0,'L');
		$pdf->Ln();
	$pdf->Row(array('','3. ','Perangkat sampel uji harus sudah diambil'));
		$pdf->SetFont('helvetica','B',9);
		$pdf->SetXY(98.00125,$pdf->GetY()-5);
		$pdf->Cell(0,5,'paling lambat 14 (empat belas) hari kalender setelah',0,0,'L');
		$pdf->Ln();
		$pdf->SetX(37.00125);
		$pdf->Cell(0,5,'pemberitahuan selesai uji,',0,0,'L');
		$pdf->SetFont('helvetica','',9);
		$pdf->SetX(78.00125);
		$pdf->Cell(0,5,'apabila sampai batas waktu yang ditetapkan perangkat uji belum diambil',0,0,'L');
		$pdf->Ln();
		$pdf->SetX(37.00125);
		$pdf->Cell(0,5,'maka penyimpanan perangkat & segala akibatnya menjadi tanggung jawab Saudara.',0,0,'L');
	
/*Footer Manual*/
	
/*End Footer Manual*/

	$pdf->Ln(20);
	$pdf->Cell(9);
	$pdf->Cell(150,5,"Bandung, ".$spb_date,0,0,'L');
	$pdf->Ln(20);
	$pdf->SetFont('helvetica','B',9);
	$pdf->SetFont('','BU');
	$pdf->Cell(9);
	$pdf->Cell(185,5,$manager_urel,0,0,'L');
	$pdf->Ln(6);
	$pdf->SetFont('','B');
	$pdf->Cell(9);
	if($is_poh == '1'){
		$pdf->Cell(185,5,"POH. MANAGER USER RELATION",0,0,'L');
	}else{
		$pdf->Cell(185,5,"MANAGER USER RELATION",0,0,'L');
	}
	$pdf->Ln(10);
	$pdf->SetFont('','BI');
	$pdf->Cell(9);
	$pdf->Cell(185,5,"Tembusan: Sdr. OM Finance Service Center",0,0,'L');
	
	$pdf->Output();
	exit;
    }
}