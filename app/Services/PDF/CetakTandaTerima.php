<?php

namespace App\Services\PDF;

class CetakTandaTerima
{
    public function makePDF($data, $pdf)
    {
        $pdf->AliasNbPages();
        $pdf->AddPage('landscape');
        $pdf->setData([
            'kodeForm' => 'TLKM02/F/010 Versi 01'
        ]);
        $pageWidth = 210;
        $pageHeight = 295;
        $lineWidth = 10;
        $lineHeight = 10;
        $margin = 10;
        $pdf->Rect( $margin, $margin , ($pageHeight - $lineHeight) - $margin , ($pageWidth - $lineWidth) - $margin);
    
        $pdf->SetFont('helvetica','B',12);
        $pdf->Cell(10);
        $pdf->Cell(0,15,'DDB - PT. TELKOM',0,0,'L');
        $pdf->Ln();
        $pdf->SetFont('helvetica','',12);
        $pdf->Cell(10);
        $pdf->Cell(0,0,'Jl. Gegerkalong Hilir No. 47 - Bandung',0,0,'L');
    
        $pdf->Ln(15);
        $pdf->SetFont('helvetica','B',14);
        $pdf->Cell(0,10,'TANDA TERIMA HASIL PENGUJIAN',0,0,'C');
    
        $pdf->Ln(15); 
        $pdf->SetWidths(array(10,10,70,45,30,50,50));
        $pdf->SetAligns(array('L','C','C','C','C','C','C')); 
        $pdf->SetFont('helvetica','B',10);
            $pdf->RowRect(array('','No','Nama Perangkat','Merek/Tipe','No Laporan','No Sertifikat','Keterangan/Tanggapan'));
        $pdf->SetFont('helvetica','',10);
            $pdf->RowRect(array('',
                '1',
                $data[0]['nama_perangkat'],
                $data[0]['merek_perangkat'].'/'.$data[0]['model_perangkat'],
                $data[0]['no_laporan'],
                $data[0]['cert_number'],
                '
    
    
    
    
                '));
    
        $pdf->setY($pdf->getY()+8); 
        $pdf->Cell(4);
        $pdf->Cell(0,0,'Dengan ini menyatakan bahwa:',0,0,'L');
        $pdf->setY($pdf->getY()+8); 
        $pdf->Cell(14);
        $pdf->SetFont('helvetica','B',10);
        $pdf->Cell(0,0,'Pengujian ini telah dilaksanakan sesuai dengan kesepakatan dan hasil pengujian telah diterima dalam keadaan baik.',0,0,'L');
        $pdf->setY($pdf->getY()+8); 
        $pdf->Cell(4);
        $pdf->SetFont('helvetica','',10);
        $pdf->Cell(0,0,'Demikian Tanda Terima Sertifikat/Laporan Hasil Pengujian ini dibuat untuk dipergunakan sebagaimana mestinya.',0,0,'L');
    
        $pdf->setY($pdf->getY()+8); 
        $pdf->Cell(190); $pdf->Cell(50,5,"Bandung, ".date("d-m-Y"),0,0,'C');

        $pdf->setY($pdf->getY()+5); 
        $pdf->Cell(20); $pdf->Cell(50,5,"DDB - PT. TELKOM",0,0,'C');
        $pdf->Cell(120); $pdf->Cell(50,5,"Penerima",0,0,'C');

        $pdf->setY($pdf->getY()+28); 
        $pdf->Cell(20); $pdf->Cell(50,5,"(____________________________)",0,0,'C');
        $pdf->Cell(120);$pdf->Cell(50,5,"(____________________________)",0,0,'C');
        
        $pdf->Ln(10);
        $pdf->SetFont('','U');
        $pdf->Cell(5);
        $pdf->Cell(10,5,"Telkom Test House, Telp. (+62) 812-2483-7500",0,0,'L');
        $pdf->Ln(4);
        $pdf->SetFont('','I');
        $pdf->Cell(5);
        $pdf->Cell(10,5,"Telkom Test House, Phone. (+62) 812-2483-7500",0,0,'L');
    
    /*Footer Manual*/
        
    /*End Footer Manual*/
        $pdf->Output();
        exit;
    }
}