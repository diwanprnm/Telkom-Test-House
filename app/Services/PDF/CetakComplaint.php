<?php

namespace App\Services\PDF;

class CetakComplaint
{

    public function makePDF($questioner, $pdf)
    {
        
        $pdf->AddPage();
        $pdf->AliasNbPages();
     
        $pdf->Ln(6); 
        $pdf->SetFont('helvetica','',10); 
        $pdf->setX(10.00125);
        $pdf->Cell(140, 15, 'CUSTOMER COMPLAINT', 1, 0, 'C');
        $pdf->Cell(40, 15, '', 1, 0, 'C'); 
    
        $y2= $pdf->getY();
        $pdf->setXY(10.00125,$y2);
        $pdf->SetWidths(array(0.00125,140));
        $pdf->SetAligns(array('L','L')); 
        $pdf->Cell(140, 40, '', 1, 0, 'L');
        $pdf->Cell(40, 40, '', 1, 0, 'C'); 
        $pdf->setY($pdf->getY()+15);
        $pdf->Row(array('','Customer Name and Address : '. $questioner[0]->user->name.' - '.$questioner[0]->user->address)); 
    
        $y3 = $pdf->getY();
        $pdf->setXY(10.00125,$y3+20);
        $pdf->SetFont('helvetica','',10); 
        $pdf->setX(10.00125);
        $pdf->Cell(140, 10, 'Customer Contact : '. $questioner[0]->user->phone_number, 1, 0, 'L');
        $pdf->Cell(40, 10, 'Date : '. $questioner[0]->questioner[0]->complaint_date, 1, 0, 'L'); 
    
        $y = $pdf->getY();
        $pdf->setXY(10.00125,$y+10);
        $pdf->Cell(180, 50, '', 1, 0, 'L'); 
        $pdf->SetWidths(array(0.00125,140));
        $pdf->SetAligns(array('L','L')); 
        $pdf->Row(array('','Complaint : '. $questioner[0]->questioner[0]->complaint)); 
    
        $y = $pdf->getY();
        $pdf->setXY(10.00125,$y+45);
        $pdf->SetMargins(0,0,0);
        $pdf->Cell(90, 40, 'Signature Of Receipt :', 1, 0, 'L');
        $pdf->Cell(90, 40, 'Name Of Receipt', 1, 0, 'LT'); 
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','',8);
        $pdf->Cell(185,5,"IASO4/F/001 Versi 01",0,0,'R');
    
        $pdf->Output();
        exit;
    }
}