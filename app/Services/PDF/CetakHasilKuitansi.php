<?php

namespace App\Services\PDF;

class CetakHasilKuitansi
{
    public function makePDF($data, $pdf)
    {
        $nomor = $data['nomor'];
        $dari = $data['dari'];
        $jumlah = $data['jumlah'];
        $untuk = $data['untuk'];
        $manager_urel = $data['manager_urel'];
        $tanggal = $data['tanggal'];
        $is_poh = $data['is_poh'];
        
        $terbilang = $pdf->terbilang(urldecode($jumlah), 3);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        
        $pdf->SetFont('helvetica','B',11);
        
        $y = $pdf->getY()-20;
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','',10);
        $pdf->setXY(45.00125,$y + 6);
        $pdf->SetFont('','U');
        $pdf->Cell(45,5,"Nomor",0,0,'L');
        $pdf->SetWidths(array(45.00125,25,5,95));
        $pdf->SetFont('','');
        $pdf->Row(array("","",":",urldecode($nomor)));
        $y2 = $pdf->getY();
        $pdf->setXY(45.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(45,5,"Nr.",0,0,'L');
            if(($y2 - $y) > 11){
                $yNow = $y2 - 6;
            }else{
                $yNow = $y2;
            }
        $pdf->setXY(45.00125,$yNow);
        
        $pdf->Ln(2);
        $y = $pdf->getY();
        $pdf->SetFont('helvetica','',10);
        $pdf->setXY(45.00125,$y + 6);
        $pdf->SetFont('','U');
        $pdf->Cell(45,5,"Sudah diterima dari",0,0,'L');
        $pdf->SetWidths(array(45.00125,25,5,95));
        $pdf->SetFont('','');
        $pdf->Row(array("","",":",urldecode($dari)));
        $y2 = $pdf->getY();
        $pdf->setXY(45.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(45,5,"Receipt From",0,0,'L');
            if(($y2 - $y) > 11){
                $yNow = $y2 - 6;
            }else{
                $yNow = $y2;
            }
        $pdf->setXY(45.00125,$yNow);
        
        $pdf->Ln(2);
        $y = $pdf->getY();
        $pdf->SetFont('helvetica','',10);
        $pdf->setXY(45.00125,$y + 6);
        $pdf->SetFont('','U');
        $pdf->Cell(45,5,"Banyak Uang",0,0,'L');
        $pdf->SetWidths(array(45.00125,25,5,95));
        $pdf->SetFont('','');
        $pdf->Row(array("","",":",$terbilang.' Rupiah'));
        $y2 = $pdf->getY();
        $pdf->setXY(45.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(45,5,"Amount",0,0,'L');
            if(($y2 - $y) > 11){
                $yNow = $y2 - 6;
            }else{
                $yNow = $y2;
            }
        $pdf->setXY(45.00125,$yNow);
        
        $pdf->Ln(2);
        $y = $pdf->getY();
        $pdf->SetFont('helvetica','',10);
        $pdf->setXY(45.00125,$y + 6);
        $pdf->SetFont('','U');
        $pdf->Cell(45,5,"Untuk Pembayaran",0,0,'L');
        $pdf->SetWidths(array(45.00125,25,5,95));
        $pdf->SetFont('','');
        $pdf->Row(array("","",":",urldecode($untuk)));
        $y2 = $pdf->getY();
        $pdf->setXY(45.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(45,5,"For Payment",0,0,'L');
            if(($y2 - $y) > 11){
                $yNow = $y2 - 6;
            }else{
                $yNow = $y2;
            }
        $pdf->setXY(45.00125,$yNow);
    
    /*Footer Manual*/
        $pdf->Ln(18);
        $pdf->SetFont('helvetica','',10);
        $now = date('Y-m-d');
        setlocale(LC_ALL, 'IND');
        $date = date('j', strtotime($tanggal))." ".strftime('%B %Y', strtotime($tanggal));
        $pdf->Cell(280,5,"Bandung, ".$date,0,0,'C');
        $pdf->Ln();
        $pdf->Cell(280,5,"DIVISI DIGITAL SERVICE",0,0,'C');
        $pdf->Ln(20);
        $pdf->Cell(280,5,"                                        ",0,0,'C');
        $pdf->Ln();
        $pdf->SetFont('','BU');
        $pdf->Cell(280,5,urldecode($manager_urel),0,0,'C');
        $pdf->Ln();
        $pdf->SetFont('helvetica','BU',12);
        $pdf->Cell(30);
        $pdf->Cell(0,5,"Rp. ".number_format($jumlah, 0, '.', ','),0,0,'L');
        $pdf->SetFont('helvetica','',9);
        $pdf->setXY(110,$pdf->getY());
        if($is_poh == '1'){
            $pdf->Cell(0,5,"POH. MANAGER USER RELATION",0,0,'C');
        }else{
            $pdf->Cell(0,5,"MANAGER USER RELATION",0,0,'C');
        }
        // $pdf->SetFont('','U');
        // $pdf->Cell(185,5,"NAMA PEMOHON & CAP PERUSAHAAN",0,0,'R');
        // $pdf->Ln(4);
        // $pdf->SetFont('','I');
        // $pdf->Cell(185,5,"APPLICANT'S NAME & COMPANY STAMP",0,0,'R');
        // $pdf->Ln(6);
        // $pdf->SetFont('','U');
        // $pdf->Cell(10,5,"User Relation, Divisi Digital Business, Telp. 62-22-4571050, 4571101 Fax. 62-22-2012255",0,0,'L');
        // $pdf->Ln(4);
        // $pdf->SetFont('','I');
        // $pdf->Cell(10,5,"Divisi Digital Business, User Relation, Phone. 62-22-4571050, 4571101 Fax. 62-22-2012255",0,0,'L');
        // $pdf->Ln(6);
        // $pdf->SetFont('helvetica','',8);
        // $pdf->Cell(185,5,"IASO2/F/002 Versi 01",0,0,'R');
    /*End Footer Manual*/
        $pdf->Output();
        exit;
    }
}