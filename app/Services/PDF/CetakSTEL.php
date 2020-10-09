<?php

namespace App\Services\PDF;
use Storage;

class CetakStel
{
	public function makePDF($data, $pdf)
	{
		$request = $data;
		$attach = $request->attach;
		$invoice_id = $request->invoice_id;
		$company_name = $request->company_name;
		// $pdf = new Fpdf('P','in',array(8.5,11)); *-
		
		$minioPath = "stel/$attach";
		//$minioPath = "usman/User Manual Situs Jasa Layanan Pelanggan Lab Pengujian [Admin].pdf";
		$fileFromMinio = Storage::disk('minio')->get($minioPath);
		Storage::disk('tmp')->put("$attach-watermark-stel.pdf",$fileFromMinio);
		$tempPath = storage_path("tmp/$attach-watermark-stel.pdf");

		$pagecount = $pdf->setSourceFile($tempPath);
		for ($i=1; $i <= $pagecount ; $i++) { 
			$pdf->AddPage();
			//Import the first page of the file
			$tppl = $pdf->importPage($i); 
			// use the imported page and place it at point 20,30 with a width of 170 mm
			$pdf->useTemplate($tppl, 0, 0); 
			$pdf->SetAlpha(0.4);
			$image_path = public_path('assets/images/Telkom-Indonesia-Corporate-Logo1.jpg');
			$pdf->Image($image_path,170,3,27);   
			$pdf->SetY(260); 
			$pdf->SetTextColor(255,0,0);
			$pdf->SetFont('helvetica','I',18);
			//Page number
			$pdf->Cell(0,0.1,$company_name,0,0,'C'); 
			$pdf->SetY(266);
			$pdf->SetTextColor(255,0,0);
			//Arial italic 8
			$pdf->SetFont('helvetica','I',18);
			//Page number
			$pdf->Cell(0,0.1,'STEL '.$invoice_id,0,0,'C');	
			$pdf->SetFillColor(217,217,217);
			$pdf->Rect(70, 0, 75, 297, 'F');
		}
		$pdf->Output();
		File::delete(storage_path("tmp/$attach-watermark-stel.pdf"));
		exit;
	}
}