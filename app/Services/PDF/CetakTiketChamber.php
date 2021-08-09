<?php

namespace App\Services\PDF;


class CetakTiketChamber
{
    public function makePDF($data, $pdf)
    {
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(false);
        $pdf->SetMargins(0.5, 0.5, 0.5, 0,5);

        //LAYOUT
        $pdf->SetFillColor(255,127,0);
        $pdf->Rect( 0,  0,  25,  100, 'F');
        $pdf->SetFillColor(0,0,0);

        //IMAGES
        $pdf->Image(public_path().'/assets/images/Telkom-Indonesia-Corporate-Logo1.jpg',170,3,27);
        $pdf->Image(public_path().'/assets/images/tth-logo-oppacity-15.jpg',60,30,100);

        //TEXT
        //vertical orange text
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','',30);
        $pdf->TextWithDirection(16,83,'Tiket Chamber','U');
        //body text
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->setX(30); $pdf->Cell(25,5,"INVOICE ",0,0,'L');$pdf->Cell(30,5,': '.$data->invoice,0,0,'L');$pdf->ln(4);
        $pdf->setX(30); $pdf->Cell(25,5,"Perusahaan",0,0,'L');$pdf->Cell(30,5,': '.$data->companyName,0,0,'L');$pdf->ln(4);
        $pdf->setX(30); $pdf->Cell(25,5,"Email",0,0,'L');$pdf->Cell(30,5,': '.$data->email,0,0,'L');$pdf->ln(30);

        $pdf->SetFont('Arial','',16);
        $pdf->setX(30); $pdf->Cell(80,5,"Melakukan penyewaan pada",0,0,'L');
        $pdf->Cell(32,5,': '.$data->start_date,0,0,'L');
        if ($data->end_date){
            $pdf->Cell(30,5,' &',0,0,'L');$pdf->ln(5);
            $pdf->setX(113);$pdf->Cell(30,5,$data->end_date,0,0,'L');
        }

        $pdf->SetFont('Arial','',10);
        $pdf->setXY(120, 90); $pdf->Cell(25,5,"Harga (+ppn): ",0,0,'L');
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(50,5,"Rp " . number_format($data->total,0,',','.').',-',0,0,'L');


    /*Footer Manual*/
        
    /*End Footer Manual*/
        //$pdf->Output(  storage_path('tmp/cetakTiketChamber.pdf') , 'F');
        $pdf->Output();
        exit;
    }
}
