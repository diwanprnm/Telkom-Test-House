<?php

namespace App\Services\PDF;


class CetakKepuasanKonsumen
{

	public function makePDF($questioner, $pdf)
	{
		$pdf->AliasNbPages();
		$pdf->AddPage();

		$y = $pdf->getY();
		$pdf->SetFont('helvetica','B',11);
		$pdf->Cell(28,5,"Survey Kepuasaan Kastamer Eksternal",0,0,'L');
		$pdf->SetFont('helvetica','',11);
		$pdf->SetFont('','I');

		/*Nama Perangkat*/
		$y = $pdf->getY();
		$pdf->Ln(6); 

		/*Merek dan Model Perangkat*/
		$y = $pdf->getY(); 
		$pdf->SetFont('helvetica','',8);
		$pdf->setXY(10.00125,$y + 1);
		$pdf->SetWidths(array(0.00125,40,3,50));
		$pdf->Row(array("","Nama Responden",":",urldecode($questioner[0]->user->name)));
		$y2 = $pdf->getY();
		$pdf->setY($y + 1);
		$pdf->SetWidths(array(100.00125,35,3,50));
		$pdf->Row(array("","Jenis Pengujian",":",urldecode($questioner[0]->examinationType->name)." (".urldecode($questioner[0]->examinationType->description).")"));
		$y3 = $pdf->getY();
		$pdf->setXY(10.00125,$y + 11);

		$yNow = max($y,$y2,$y3);
		if($y2 == $y3){
			/* // $yNow;-* */ 
		}else{
			$yNow = $yNow - 1;
		}
		$pdf->setXY(10.00125,$yNow);
		/*Kapasitas dan Referensi Uji Perangkat*/
		$y = $pdf->getY(); 
		$pdf->SetFont('helvetica','',8);
		$pdf->setXY(10.00125,$y + 1);
		$pdf->SetWidths(array(0.00125,40,3,50));
		$pdf->Row(array("","Nama Perusahaan",":",urldecode($questioner[0]->company->name)));

		$pdf->setY($y + 1);
		$pdf->SetWidths(array(100.00125,35,3,50));
		$pdf->Row(array("","Nama Perangkat",":",urldecode($questioner[0]->device->name)));
		$y3 = $pdf->getY();
		$pdf->setXY(10.00125,$y + 1);


		/*Negara Pembuat Perangkat*/
		$y = $pdf->getY(); 
		$pdf->SetFont('helvetica','',8);
		$pdf->setXY(10.00125,$y + 6);
		$pdf->SetWidths(array(0.00125,40,3,50));
		$pdf->Row(array("","No. Telp/HP",":",urldecode($questioner[0]->user->phone_number)));

		$pdf->setXY(110.00125,$y + 6);
		$pdf->SetWidths(array(100.00125,35,3,50));
		$pdf->Row(array("","Tanggal",":",urldecode($questioner[0]->questioner[0]->questioner_date)));

		// $pdf->SetFont('helvetica','B',14); -*
		// $pdf->setXY(10.00125, $pdf->getY() + 4);  -*
		// $pdf->Cell(10,4,"Harap Diisikan penilaian anda terhadap layanan QT/TA/VT - Telkom DDB.",0,0,'L'); -*


		$pdf->SetFont('helvetica','',8);
		$pdf->setXY(10.00125, $pdf->getY() + 6); 
		$pdf->Cell(10,4,"Survey ini terdiri dari dua bagian, yaitu tingkat kepentingan dan tingkat kepuasan Anda. Tingkat kepentingan menunjukan seberapa penting ",0,0,'L');

		$pdf->setXY(10.00125, $pdf->getY() + 4); 
		$pdf->Cell(10,4,"sebuah pernyataan bagi Anda. Sedangkan, tingkat kepuasan menunjukkan seberapa puas pengalaman Anda setelah melakukan pengujian di ",0,0,'L');

		$pdf->setXY(10.00125, $pdf->getY() + 4); 
		$pdf->Cell(10,4,"Infrasutructure Assurance (IAS) Divisi Digital Business (DDB) PT. Telekomuniasi Indonesia, Tbk.",0,0,'L');

		$pdf->setXY(10.00125, $pdf->getY() + 6); 
		$pdf->Cell(10,4,"Besar pengharapan kami agar pengisian survey ini dapat dikerjakan dengan sebaik-baiknya. Atas kerja samanya, kami ucapkan terimakasih. ",0,0,'L');

		$pdf->setXY(10.00125, $pdf->getY() + 6); 
		$pdf->Cell(10,4,"Skala pemberian nilai adalah 1 - 10 dengan nilai 1 adalah penilaian Sangat Tidak Baik atau Sangat Tidak Setuju. Kastemer diharapkan dapat ",0,0,'L');

		$pdf->setXY(10.00125, $pdf->getY() + 4); 
		$pdf->Cell(10,4,"memberikan dengan angka bulat.",0,0,'L');



		$pdf->Ln(8); 
		$pdf->SetWidths(array(0.00125,10,100,30,30));
		$pdf->SetAligns(array('L','C','L','C','C')); 
		$pdf->SetFont('helvetica','',8);
		$pdf->RowRect(array('','NO','PERTANYAAN','TINGKAT KEPENTINGAN','TINGKAT KEPUASAN')); 
		$no_k = 0;

		foreach($questioner[0]->QuestionerDynamic as $row){
				$no_k++;
			if($row->is_essay){
				$pdf->SetWidths(array(0.00125,110,60));
				$pdf->SetAligns(array('L','L','L')); 
				$pdf->RowRect(array('',$row->qq->question,$row->eks_answer));
			}else{
				$pdf->SetWidths(array(0.00125,10,100,30,30));
				$pdf->SetAligns(array('L','C','L','C','C')); 
				$pdf->RowRect(array('',$no_k,$row->qq->question,$row->eks_answer,$row->perf_answer));
				}
		}

		// $pdf->setXY(10.00125, $pdf->getY() + 4); -*
		// $pdf->Cell(10,4,"Kritik dan Saran Anda untuk meningkatkan kualitas pelayanan kami:",0,0,'L'); -*
		// $pdf->Ln(6); -*
		// $pdf->setX(10.00125); -*
		// $pdf->SetWidths(array(0.00125,170)); -*
		// $pdf->SetAligns(array('L','L')); -*
		// $pdf->RowRect(array('',$questioner[0]->questioner[0]->quest6)); -*
		$pdf->Ln(6);
		$pdf->SetFont('helvetica','',8);
		$pdf->setX(10.00125);
		$pdf->Cell(185,5,"TLKM05/F/002 Versi 01",0,0,'L');
		$pdf->Output();
		exit;
	}

}