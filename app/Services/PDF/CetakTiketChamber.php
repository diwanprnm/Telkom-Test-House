<?php

namespace App\Services\PDF;


class CetakTiketChamber
{
    public function makePDF($data, $pdf)
    {
        //SETUP PDF
        $pdf->AddFont('tgfm','','tgfm.php');
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(false);
        $pdf->SetMargins(0.5, 0.5, 0.5, 0,5);

        //IMAGES
        $pdf->Image(public_path().'/assets/images/format-tiket-chamber.jpg',0, 0,210);
        //TEXT

        //body text
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('tgfm','',6);
        $pdf->setXY(26, 15); $pdf->Cell(50,5,"No. pesanan : $data->invoice",0,0,'L');
        $pdf->setXY(26, 18); $pdf->Cell(50,5,"Tanggal buat tiket : $data->printDate",0,0,'L');
        $pdf->SetFont('tgfm','',8);
        $pdf->setXY(144, 15); $pdf->Cell(50,5,"ID : $data->id",0,0,'R');
        $pdf->SetFont('Arial','B',14);
        $pdf->setXY(26, 28.5); $pdf->Multicell(85,5,$data->companyName,0,'L','');
        $pdf->SetFont('Arial','B',10);
        $pdf->setXY(26, 47); $pdf->Multicell(85,5,$data->userName,0,'L','');
        $pdf->SetFont('tgfm','',10);
        $pdf->setXY(113, 32); $pdf->Cell(50,5,"$data->startDate",0,0,'L');
        if ($data->endDate){
            $pdf->setXY(113, 37); $pdf->Cell(50,5,"$data->endDate",0,0,'L');
        }
        $pdf->setXY(113, 47); $pdf->Cell(50,5,"Selesai",0,0,'L');
        
    /*Footer Manual*/
        
    /*End Footer Manual*/
        if ($data->method == 'getStream'){
            return $pdf->Output('', 'S');
        }
        
        $pdf->Output();
        exit;
    }
}
