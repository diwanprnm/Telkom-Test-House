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
        $pdf->Cell(0,5,"Data Pelanggan:",0,1);
        $pdf->Ln(2);
        
        $pdf->SetFont('','');
        $pdf->SetWidths([6,35,136.5]);
        $pdf->Row(["","Nama Perusahaan",$data[0]['nama_perusahaan']]);
        $pdf->Row(["","Alamat Perusahaan",$data[0]['alamat_perusahaan'].'']);
        $pdf->Row(["","Nama PIC",$data[0]['nama_pemohon']]);
        $pdf->Row(["","WhatsApp",$data[0]['telepon_pemohon']]);
        $pdf->Row(["","E-Mail",$data[0]['email_pemohon']]);
        $pdf->Ln(10);

        // DATA PERUSAHAAN
        $pdf->SetFont('','B');
        $pdf->Cell(40.5,5,"Status Perusahaan:",0,0);
        $pdf->SetFont('','');
        switch ($data[0]['jns_perusahaan']) {
            case 'Agen':
                $jns_perusahaan = 'Agen/Distributor';
                break;
            case 'Pemilik':
                $jns_perusahaan = 'Pemilik Alat Ukur';
                break;
                
            default:
                $jns_perusahaan = $data[0]['jns_perusahaan'];
                break;
        }
        // $pdf->Cell(45,5,$jns_perusahaan,0,0);
        $pdf->Ln(10);
        // $pdf->Rect(16, 90, 4, 4, 'D');
        // $pdf->SetFont('ZapfDingbats',0);
        $pdf->SetFont('','');
       
        $pdf->Cell(4, 4, '', 0,0);
        $pdf->Cell(3.5,4.5,'',0,0);

        $pdf->Cell(4, 4, '', 1,0);
        $pdf->Cell(35,4.5,' Pabrikan',0,0);

        $pdf->Cell(4, 4, '', 1,0);
        $pdf->Cell(35,4.5,' Perwakilan',0,0);

        $pdf->Cell(4, 4, '', 1,0);
        $pdf->Cell(35,4.5,' Agen/Distributor',0,0);

        $pdf->Cell(4, 4, '', 1,0);
        $pdf->Cell(35,4.5,' Pemilik Alat Ukur',0,0);
        

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
        $pdf->Ln(6);

        // PERNYATAAN
        $termOfServices = [
            'Kami menyatakan bahwa permohonan ini telah diisi dengan keadaan yang sebenarnya.',
            'Kami menjamin bahwa merek, tipe/model, kapasitas, dan negara pembuat perangkat uji/alat ukur yang kami ajukan sama dengan yang diserahkan ke Telkom Test House (TTH).',
            'Kami mengetahui dan menyetujui referensi uji yang tertera di permohonan ini.',
            'Apabila perangkat uji/alat ukur terbukti tidak sesuai dengan permohonan ini, maka permohonan ini dinyatakan batal.',
            'Kami bertanggung jawab atas pengiriman, pembongkaran, dan pengambilan perangkat uji, sehingga biaya tak terduga yang muncul pada proses itu bukan menjadi tanggung jawab TTH.',
            'Kami bersedia mengikuti Technical Meeting (TM) Pra Uji Fungsi (UF) sebelum pelaksanaan UF, dengan mengajukan jadwal TM Pra UF ke nomor kontak CSTTH.',
            'Kami mengetahui bahwa kesempatan UF pertama tidak dikenakan biaya, sedangkan biaya UF berikutnya mengikuti aturan TTH',
            'Kami menyetujui pelaksanaan uji/kalibrasi mengikuti antrean pengujian/kalibrasi berdasarkan aturan TTH.',
            'Kami bersedia mengambil perangkat uji/alat ukur terlebih dahulu sebelum mengunduh Laporan Hasil Uji (LHU) dan/atau Sertifikat Quality Assurance (QA) atau Calibration Certificate.',
            'Kami bersedia mengisi survei kepuasan pelanggan setelah mengunduh LHU dan/atau Sertifikat QA atau Calibration Certificate.',
            'Kami bersedia mengambil perangkat uji/alat ukur sebelum 30 (tiga puluh) hari kalender setelah Surat Pemberitahuan Biaya (SPB) terbit.',
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
        $pdf->Ln(4);

        // TANDA TANGAN
        $signDate = date("d/m/Y", strtotime($data[0]['date']));
		$pdf->SetFont('helvetica','',10);
		$pdf->Cell(0,5,$data[0]['kotaPerusahaan'].", ".$signDate,0,0,'L');
        $pdf->Ln(17);
		$pdf->SetFont('','UB');
		$pdf->Cell(0,5,$data[0]['nama_pemohon'],0,0,'L');
        /*End Data Pemohon*/
        $pdf->Output();
        exit;
    }
}

