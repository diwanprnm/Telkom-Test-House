<?php

namespace App\Services\PDF;

class CetakPermohonan
{

    public function makePDF($data, $pdf)
    {
        $pdf->setPDFData($data[0]);
        $pdf->SetMargins(15, 0, 17);
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // DATA PEMOHON
        $pdf->setY(37);
        $pdf->SetFont('helvetica','B',9);
        $pdf->Cell(0,5,"Nomor Registrasi: ".$data[0]['no_reg'],0,1,'R');
        $pdf->Cell(0,5,"Data Pemohon:",0,1);
        $pdf->Ln(2);
        
        $pdf->SetFont('','');
        $pdf->SetWidths([6,35,136.5]);
        $pdf->Row(["","Nama Pemohon",$data[0]['nama_pemohon']]);
        $pdf->Row(["","Alamat Pemohon",$data[0]['alamat_pemohon']]);
        $pdf->SetWidths([6,35,45.5,37.5,53.5]);
        $pdf->Row(["","Nomor HP Pemohon",$data[0]['telepon_pemohon'],"Alamat E-Mail Pemohon",$data[0]['email_pemohon']]);
        $pdf->Ln(10);

        // DATA PERUSAHAAN
        $pdf->SetFont('','B');
        $pdf->Cell(35,5,"Data Perusahaan:",0,2);
        $pdf->Ln(3);
        $pdf->SetFont('','');
        $pdf->SetX(31.5);
        $pdf->Cell(7,5,"",1,0);$pdf->Cell(3,5,"",0,0);$pdf->Cell(45,5,"Pabrikan",0,0);
        $pdf->Cell(7,5,"",1,0);$pdf->Cell(3,5,"",0,0);$pdf->Cell(45,5,"Perwakilan",0,0);
        $pdf->Cell(7,5,"",1,0);$pdf->Cell(3,5,"",0,0);$pdf->Cell(45,5,"Agen/Distributor",0,0);
        $checkPossition = [
            'Pabrikan' => 31.5,
            'Perwakilan' => 86.5,
            'Agen' => 141.5,
        ];
        $pdf->SetXY($checkPossition[$data[0]['jns_perusahaan']]??86.5, $pdf->GetY());
        $pdf->SetFont('ZapfDingbats','', 14);
        $pdf->Cell(7, 5, "4", 0, 1, 'C');
        $pdf->SetFont('helvetica','',9);
        $pdf->Ln(4);
        $pdf->SetWidths([6,35,136.5]);
        $pdf->Row(["","Nama Perusahaan",$data[0]['nama_perusahaan']]);
        $pdf->Row(["","Alamat Perusahaan",$data[0]['alamat_perusahaan']]);
        $pdf->Ln(10);

        // DATA PERANGKAT
        $pdf->SetFont('','B');
        $pdf->Cell(35,5,"Data Perangkat:",0,2);
        $pdf->Ln(3);
        $pdf->SetFont('','');
        $pdf->SetWidths([6,35,136.5]);
        $pdf->Row(["","Nama Perangkat",$data[0]['nama_perangkat']]);
        $pdf->SetWidths([6,35,45.5,28,63]);
        $pdf->Row(["","Merek",$data[0]['merek_perangkat'],"Tipe/Model",$data[0]['model_perangkat']]);
        $pdf->Row(["","Kapasitas",$data[0]['kapasitas_perangkat'],"Nomor Seri",$data[0]['serial_number']]);
        $pdf->Row(["","Negara Pembuat",$data[0]['pembuat_perangkat'],"Referensi Uji",$data[0]['referensi_perangkat']]);
        $pdf->Ln(10);

        // PERNYATAAN
        $termOfServices = [
            'Kami menyatakan bahwa permohonan uji ini telah diisi dengan keadaan yang sebenarnya.',
            'Kami menjamin bahwa merek, tipe/model, kapasitas, dan negara pembuat perangkat uji yang kami produksi sama dengan yang diujikan.',
            'Kami telah mengetahui dan menyetujui referensi uji yang tertera di permohonan uji ini.',
            'Apabila perangkat uji terbukti tidak sesuai dengan permohonan uji ini, maka permohonan uji ini dinyatakan batal.',
            'Kami bertanggung jawab atas pengiriman, pembongkaran, dan pengambilan perangkat uji, sehingga biaya tak terduga yang muncul pada proses itu bukan menjadi tanggung jawab Telkom Test House (TTH).',
        ];
        $pdf->SetFont('','B');
        $pdf->Cell(35,5,"Pernyataan:",0,2);
        $pdf->Ln(3);
        $pdf->SetFont('','');
        $i = 0;
        foreach ($termOfServices as $tos){
            $i++;
            $pdf->Cell(4.5,5, "$i.");
            $pdf->MultiCell(0,5,$tos,0,'L');
        }
        $pdf->Ln(10);

        // TANDA TANGAN
        $signDate = date("d/m/Y", strtotime($data[0]['date']));
		$pdf->SetFont('helvetica','',10);
		$pdf->Cell(0,5,$data[0]['kotaPerusahaan'].", ".$signDate,0,0,'L');
        $pdf->Ln(10);
		$pdf->SetFont('','UB');
		$pdf->Cell(0,5,$data[0]['nama_pemohon'],0,0,'L');
        /*End Data Pemohon*/
        $pdf->Output();
        exit;
    }
}

