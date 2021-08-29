<?php

namespace App\Services\PDF;


class CetakSertifikatQA
{


    public function makePDF($data, $pdf)
    {
        $title = $data['title'];
        $documentNumber = $data['documentNumber'];
        $companyName = $data['companyName'];
        $brand = $data['brand'];
        $deviceName = $data['deviceName'];
        $deviceType = $data['deviceType'];
        $deviceCapacity = $data['deviceCapacity'];
        $deviceSerialNumber = $data['deviceSerialNumber'];
        $examinationNumber = $data['examinationNumber'];
        $examinationReference = $data['examinationReference'];
        $signDate = $data['signDate'];
        $signee = $data['signee'];
        $timeAndLocationSign = "Bandung, $signDate";

        $pdf->AliasNbPages();
        $pdf->AddPage();

        /*Header*/
        $pdf->SetY(37);$pdf->SetFont('helvetica','UB',22);
		$pdf->Cell(0,5,$title,0,0,'C');$pdf->Ln(8);
        $pdf->SetFont('helvetica','B',12);
		$pdf->Cell(0,5,$documentNumber,0,0,'C');$pdf->Ln(4);

        /*Upper Section*/
		$pdf->setXY(27,62);
        $pdf->SetFont('helvetica','',11);$pdf->Cell(78,0,"Surat keterangan ini dikeluarkan untuk",0,0,'L');
        $pdf->Cell(5,4,":",0,0,'L');
        $pdf->SetFont('helvetica','B',11);$pdf->Cell(72,4,$companyName,0,0,'L');$pdf->Ln(4);$pdf->setX(27);
        $pdf->SetFont('helvetica','I',8);$pdf->Cell(78,0,"This declaration letter is issued to",0,0,'L');$pdf->Ln(6);$pdf->setX(27);
        $pdf->SetFont('helvetica','',11);$pdf->Cell(78,0,"Sebagai Pabrikan/Agen/Perwakilan dari",0,0,'L');
        $pdf->Cell(5,4,":",0,0,'L');
        $pdf->SetFont('helvetica','B',11);$pdf->Cell(72,4,$brand,0,0,'L');$pdf->Ln(4);$pdf->setX(27);
        $pdf->SetFont('helvetica','I',8);$pdf->Cell(78,0,"As a (or) an Manufacture/Agent/Representative of",0,0,'L');

        /*Pernyataan*/
        $pdf->setXY(42,92);
        $pdf->SetFont('helvetica','',11);$pdf->Cell(20,0,"Dengan ini",0,0,'L');
        $pdf->SetFont('helvetica','B',11);$pdf->Cell(66,0,"PT Telkom Indonesia (Persero) Tbk ",0,0,'L');
        $pdf->SetFont('helvetica','',11);$pdf->Cell(35,0,"menyatakan bahwa:",0,0,'L');$pdf->Ln(4);
        $pdf->SetFont('helvetica','I',8);$pdf->Cell(0,0,"Hereby, PT Telkom Indonesia (Persero) Tbk declared that:",0,0,'C');

        /*Upper Section*/
		$pdf->setXY(27,113);
        $pdf->SetFont('helvetica','',11);$pdf->Cell(78,0,"Nama perangkat",0,0,'L');
        $pdf->Cell(5,4,":",0,0,'L');
        $pdf->SetFont('helvetica','B',11);$pdf->Cell(72,4,$deviceName,0,0,'L');$pdf->Ln(4);$pdf->setX(27);
        $pdf->SetFont('helvetica','I',8);$pdf->Cell(78,0,"Equipment name",0,0,'L');$pdf->Ln(6);$pdf->setX(27);
        //
        $pdf->SetFont('helvetica','',11);$pdf->Cell(78,0,"Tipe/Model",0,0,'L');
        $pdf->Cell(5,4,":",0,0,'L');
        $pdf->SetFont('helvetica','B',11);$pdf->Cell(72,4,$deviceType,0,0,'L');$pdf->Ln(4);$pdf->setX(27);
        $pdf->SetFont('helvetica','I',8);$pdf->Cell(78,0,"Type/Model",0,0,'L');$pdf->Ln(6);$pdf->setX(27);
        //
        $pdf->SetFont('helvetica','',11);$pdf->Cell(78,0,"Kapasitas",0,0,'L');
        $pdf->Cell(5,4,":",0,0,'L');
        $pdf->SetFont('helvetica','B',11);$pdf->Cell(72,4,$deviceCapacity,0,0,'L');$pdf->Ln(4);$pdf->setX(27);
        $pdf->SetFont('helvetica','I',8);$pdf->Cell(78,0,"Capacity",0,0,'L');$pdf->Ln(6);$pdf->setX(27);
        //
        $pdf->SetFont('helvetica','',11);$pdf->Cell(78,0,"Nomor seri",0,0,'L');
        $pdf->Cell(5,4,":",0,0,'L');
        $pdf->SetFont('helvetica','B',11);$pdf->Cell(72,4,$deviceSerialNumber,0,0,'L');$pdf->Ln(4);$pdf->setX(27);
        $pdf->SetFont('helvetica','I',8);$pdf->Cell(78,0,"Serial number",0,0,'L');$pdf->Ln(6);$pdf->setX(27);
        //
        $pdf->SetFont('helvetica','',11);$pdf->Cell(78,0,"Berdasarkan nomor laporan hasil uji",0,0,'L');
        $pdf->Cell(5,4,":",0,0,'L');
        $pdf->SetFont('helvetica','B',11);$pdf->Cell(72,4,$examinationNumber,0,0,'L');$pdf->Ln(4);$pdf->setX(27);
        $pdf->SetFont('helvetica','I',8);$pdf->Cell(78,0,"Based on the test report number",0,0,'L');$pdf->Ln(6);$pdf->setX(27);
        //
        $pdf->SetFont('helvetica','',11);$pdf->Cell(78,0,"Telah memenuhi spesifikasi sebagai berikut",0,0,'L');
        $pdf->Cell(5,4,":",0,0,'L');
        $pdf->SetFont('helvetica','B',11);$pdf->Cell(72,4,$examinationReference,0,0,'L');$pdf->Ln(4);$pdf->setX(27);
        $pdf->SetFont('helvetica','I',8);$pdf->Cell(78,0,"Has been complied the following specification(s)",0,0,'L');$pdf->Ln(11);$pdf->setX(27);

        /*KETENTUAN*/
		$pdf->setXY(12.5,178);
        $pdf->Cell(185,15,"",'TB',0,'');
        $pdf->setXY(12.5,176);
        $pdf->SetFont('helvetica','',11);$pdf->Cell(185,15,"QA Test perlu dilakukan kembali dalam periode waktu 3 tahun, kecuali ditemukan kejanggalan sebelumnya.",'',0,'C');$pdf->Ln(4);
        $pdf->SetFont('helvetica','I',8);$pdf->Cell(185,15,"QA Test shall be repeated in a period of 3 years, except if there is/are nonconformity(s) found before that.",'',0,'C');$pdf->Ln(4);

        /*SIGN*/
		$pdf->setY(200);
        $pdf->SetFont('helvetica','',11);$pdf->Cell(0,0,$timeAndLocationSign,'',0,'C');
        $pdf->setY(231);
        $pdf->SetFont('helvetica','BU',11);$pdf->Cell(0,0,$signee,'',0,'C');$pdf->Ln(5);
        $pdf->SetFont('helvetica','B',11);$pdf->Cell(0,0,"Senior Manager Infrastructure Assurance",'',0,'C');

        //PDF-OUTPUT
        $pdf->Output();
        exit;
    }
}
