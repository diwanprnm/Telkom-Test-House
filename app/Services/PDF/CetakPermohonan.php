<?php

namespace App\Services\PDF;

class CetakPermohonan
{

    public function makePDF($data, $pdf)
    {
        $pdf->setPDFData($data[0]);
        $pdf->SetMargins(17, 0, 17);
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // DATA PEMOHON
        $pdf->setY(37);
        $pdf->SetFont('helvetica','B',9);
        $pdf->Cell(0,5,"Nomor Registrasi: ".$data[0]['no_reg'],0,1,'R');
        $pdf->Cell(0,5,"Data Pemohon:",0,1);
        $pdf->Ln(2);
        $pdf->SetFont('','', 9);
        $pdf->SetX(21.5);
        $pdf->Cell(35,5,"Nama Pemohon",0,2);
        $pdf->Cell(35,5,"Alamat Pemohon",0,2);
        $pdf->Cell(35,5,"Nomor HP Pemohon",0,2);
        $pdf->SetXY(56.5, 49);
        $pdf->Cell(0,5,$data[0]['nama_pemohon'],0,2);
        $pdf->Cell(0,5,$data[0]['alamat_pemohon'],0,2);
        $pdf->Cell(45,5,$data[0]['telepon_pemohon'],0,0);
        $pdf->Cell(3,5);
        $pdf->Cell(37,5,"Alamat E-Mail Pemohon",0,0);
        $pdf->Cell(0,5,$data[0]['email_pemohon'],0,0);
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
        $pdf->Ln(10);
        $checkPossition = [
            'Pabrikan' => 31.5,
            'Perwakilan' => 86.5,
            'Agen' => 141.5,
        ];
        $pdf->SetXY($checkPossition[$data[0]['jns_perusahaan']]??40, 77);
        $pdf->SetFont('ZapfDingbats','', 14);
        $pdf->Cell(7, 5, "4", 0, 1, 'C');
        $pdf->SetFont('helvetica','',9);
        $pdf->Ln(4);
        $pdf->Cell(3,5);
        $pdf->Cell(35,5,"Nama Perusahaan",0,2);
        $pdf->Cell(35,5,"Alamat Perusahaan",0,2);
        $pdf->SetXY(56.5, 85);
        $pdf->Cell(0,5,$data[0]['nama_perusahaan'],0,2);
        $pdf->Cell(0,5,$data[0]['alamat_perusahaan'],0,2);
        $pdf->Ln(10);

        // DATA PERANGKAT
        $pdf->SetFont('','B');
        $pdf->Cell(35,5,"Data Perangkat:",0,2);
        $pdf->Ln(3);
        $pdf->SetFont('','');
        $pdf->SetX(21.5);
        $pdf->Cell(35,5,"Nama Perangkat",0,2);
        $pdf->Cell(35,5,"Merek",0,2);
        $pdf->Cell(35,5,"Kapasitas",0,2);
        $pdf->Cell(35,5,"Negara Pembuat",0,2);
        $pdf->SetXY(105, 118);
        $pdf->Cell(37,5,"Tipe/Model",0,2);
        $pdf->Cell(37,5,"Nomor Seri",0,2);
        $pdf->Cell(37,5,"Referensi Uji",0,2);
        $pdf->SetXY(56.5, 113);
        $pdf->Cell(0,5,$data[0]['nama_perangkat'],0,2);
        $pdf->Cell(45,5,$data[0]['merek_perangkat'],0,2);
        $pdf->Cell(45,5,$data[0]['kapasitas_perangkat'],0,2);
        $pdf->Cell(45,5,$data[0]['pembuat_perangkat'],0,2);
        $pdf->SetXY(132.5, 118);
        $pdf->Cell(0,5,$data[0]['model_perangkat'],0,2);//daniel
        $pdf->Cell(0,5,$data[0]['serial_number'],0,2);
        $pdf->Cell(0,5,$data[0]['referensi_perangkat'],0,2);
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

