<?php

namespace App\Services\PDF;

class CetakKontrak
{
    public function makePDF($data, $pdf)
    { 
        //$data[0]['is_loc_test'] = 0;
        //dd($data[0]);
        $pdf->setPDFData($data[0]);
        $pdf->SetMargins(17, 0, 17);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $charHeight = 4;
        $currentY = 0;
        $signDate = date("d/m/Y", strtotime($data[0]['contract_date']));

        // DATA PEMOHON
        $pdf->setY(37);
        $pdf->SetFont('helvetica','B',9);
        $pdf->Cell(0,5,"Nomor Registrasi: ".$data[0]['no_reg'],0,1,'R');
        $pdf->Cell(0,5,"Data Pelanggan:",0,1);
        $pdf->SetFont('','', 9);
        $pdf->SetX(21.5);
        $pdf->Cell(35,5,"Nama Perusahaan",0,2);
        $pdf->Cell(35,5,"Alamat Perusahaan",0,2);
        $pdf->SetXY(56.5, 47);
        $pdf->Cell(0,5,$data[0]['nama_perusahaan'],0,2);
        $pdf->MultiCell(0,5,$data[0]['alamat_perusahaan'],0,'L');
        $pdf->Ln(5);

        // DATA PERANGKAT
        $pdf->SetFont('','B');
        $pdf->Cell(35,5,"Data Perangkat:",0,2);
        $pdf->SetFont('','');
        $pdf->SetWidths([11.5,46.5,275]);
        $pdf->Row(["","Nama Perangkat",$data[0]['nama_perangkat']]);
        $pdf->SetWidths([11.5,46.5,95.5,123,56.5]);
        $pdf->Row(["","Merek",$data[0]['merek_perangkat'],"Tipe/Model",$data[0]['model_perangkat']]);
        $pdf->Row(["","Kapasitas",$data[0]['kapasitas_perangkat'],"Nomor Seri",$data[0]['serial_number']]);
        $pdf->Row(["","Negara Pembuat",$data[0]['pembuat_perangkat'],"Referensi Uji",$data[0]['referensi_perangkat']]);
        $pdf->Ln(5);
        
        // DATA PERANGKAT
        $pdf->SetFont('','B');
        $pdf->Cell(35,5,"Pernyataan:",0,2);
        $pdf->SetFont('','',7);
        $pdf->Cell(4.5,$charHeight,"1.",0,0);
        $pdf->MultiCell(0, $charHeight,'Pelanggan memahami referensi uji, item uji, dan konfigurasi uji.',0,'L');
        if ($data[0]['is_loc_test']){
            $pdf->Cell(4.5,$charHeight,"2.",0,0);
            $pdf->Cell(38.5, $charHeight,'Kesepakatan yang tertuang dalam');$pdf->SetFont('','I');$pdf->Cell(20, $charHeight,'technical meeting');;$pdf->SetFont('','');$pdf->Cell(44, $charHeight,'uji lokasi adalah benar. Pelanggan menyatakan bahwa lingkungan milik pelanggan (laboratorium,',0,1);
            $pdf->Cell(4.5,$charHeight,"",0,0);$pdf->SetFont('','I');$pdf->Cell(14, $charHeight,'test enginer,');$pdf->SetFont('','');$pdf->Cell(72, $charHeight,'sampel uji, dan alat ukur) sudah siap.',0,1);
            $pdf->Cell(4.5,$charHeight,"3.",0,0);
            $pdf->Cell(99.5,$charHeight,'Telkom Test House (TTH) menerbitkan biaya uji (biaya uji perangkat dan tunjangan harian');$pdf->SetFont('','I');$pdf->Cell(13, $charHeight,'test enginer');;$pdf->SetFont('','');$pdf->Cell(0,$charHeight,') yang nominalnya tertera sesuai Surat Pemberitahuan',0,1);
            $pdf->Cell(4.5,$charHeight,"",0,0);$pdf->Cell(94, $charHeight,'Biaya (SPB). Di luar biaya uji ini pelanggan menanggung transportasi dan akomodasi');$pdf->SetFont('','I');$pdf->Cell(14, $charHeight,'test enginer');;$pdf->SetFont('','');$pdf->Cell(0,$charHeight,"selama melakukan uji lokasi. Apabila pelanggan tidak",0,1);
            $pdf->Cell(4.5,$charHeight,"",0,0);$pdf->MultiCell(0, $charHeight,'melakukan pembayaran sampai akhir tahun berjalan, maka web TTH secara otomatis menghapus permohonan ini.',0,'L');
            $pdf->Cell(4.5,$charHeight,"4.",0,0);
            $pdf->MultiCell(0, $charHeight,'Web TTH secara otomatis menerbitkan nomor Surat Perintah Kerja (SPK) yang menunjukkan bahwa pembayaran ini telah terverifikasi oleh TTH. Setelah nomor SPK terbit, pelanggan tidak dapat melakukan hal-hal berikut:',0,'L');
            $pdf->Cell(4.5,$charHeight,"",0,0);$pdf->Cell(4.5, $charHeight,"a. ",0,0);$pdf->MultiCell(0, $charHeight,'Perubahan/penambahan referensi uji.',0,'L');
            $pdf->Cell(4.5,$charHeight,"",0,0);$pdf->Cell(4.5, $charHeight,"b. ",0,0);$pdf->MultiCell(0, $charHeight,'Pengalihan lokasi uji dari internal ke eksternal Lab TTH dan sebaliknya.',0,'L');
            $pdf->Cell(4.5,$charHeight,"5.",0,0);
            $pdf->MultiCell(0, $charHeight,'Pelaksanaan uji ditentukan berdasarkan antrean pengujian di Lab TTH bukan dihitung sejak nomor SPK terbit.',0,'L');
            $pdf->Cell(4.5,$charHeight,"6.",0,0);
            $pdf->MultiCell(0,$charHeight,'Pelanggan dapat mengajukan pembatalan uji setelah nomor SPK terbit, jika:',0,'L');
            $pdf->Cell(4.5,$charHeight,"",0,0);$pdf->Cell(4.5, $charHeight,"a. ",0,0);$pdf->MultiCell(0, $charHeight,'Pengujian belum dilakukan sama sekali.',0,'L');
            $pdf->Cell(4.5,$charHeight,"",0,0);$pdf->Cell(4.5, $charHeight,"b. ",0,0);$pdf->MultiCell(0, $charHeight,'Terdapat perubahan referensi uji pada periode nomor SPK telah terbit.',0,'L');
            $pdf->Cell(4.5,$charHeight,"",0,0);$pdf->Cell(4.5, $charHeight,"c. ",0,0);$pdf->MultiCell(0, $charHeight,'Alat ukur mendadak rusak dan tidak dapat diperbaiki lagi.',0,'L');
            $pdf->Cell(4.5,$charHeight,"",0,0);$pdf->Cell(77,$charHeight,"Pembatalan uji ini diputuskan selesai dengan Laporan Hasil Uji (LHU)");$pdf->SetFont('','B');$pdf->Cell(20,$charHeight,"tidak memenuhi/");$pdf->SetFont('','BI');$pdf->Cell(16,$charHeight,"not comply.",0,1);$pdf->SetFont('','');
            $pdf->Cell(4.5,$charHeight,"7.",0,0);
            $pdf->MultiCell(0,$charHeight,'Jika pembatalan uji sebelum tanggal 5 bulan berikutnya, maka biaya uji yang sudah dibayarkan dapat dikembalikan utuh (biaya uji + PPN 10 %). Namun jika sudah melebihi tanggal 5 bulan berikutnya, maka biaya uji tersebut menjadi deposit biaya permohonan uji berikutnya (hanya biaya uji saja).',0,'L');
            $pdf->Cell(4.5,$charHeight,"8.",0,0);
            $pdf->MultiCell(0,$charHeight,'Pelanggan menyatakan bahwa informasi perangkat uji yang didaftarkan dalam kontrak uji ini adalah sama dengan sampel uji yang diuji di lokasi.',0,'L');
            $pdf->Cell(4.5, $charHeight,"9.",0,0);
            $pdf->Cell(54,$charHeight,'Pelanggan menerima LHU dan/atau Sertifikat Uji ',0,'L');$pdf->SetFont('','I');$pdf->Cell(21,$charHeight,'Quality Assurance',0,'L');$pdf->SetFont('','');$pdf->Cell(0,$charHeight,'(QA) setelah pengujian perangkat selesai.',0,1);
            $pdf->Cell(4.5, $charHeight,"10.",0,0);
            $pdf->MultiCell(0, $charHeight,'Sebagai prasyarat mengunduh LHU dan/atau Sertifikat Uji QA, pelanggan harus mengisi survei kepuasan pelanggan yang ada di web TTH pada saat pelanggan mengunduh dokumen tersebut.',0,'L');
            $pdf->Cell(4.5, $charHeight,"11.",0,0);
            $pdf->MultiCell(0, $charHeight,'Pelanggan bersedia jika informasi pelanggan dan perangkat uji yang telah lulus uji QA dipublikasikan melalui web TTH.',0,'L');
            $pdf->Cell(4.5, $charHeight,"12.",0,0);
            $pdf->MultiCell(0, $charHeight,'Khusus untuk permohonan uji QA, pelanggan harus memperhatikan hal-hal berikut:',0,'L');
            $pdf->Cell(4.5, $charHeight,"",0,0);$pdf->Cell(4.5, $charHeight,"a. ",0,0);$pdf->MultiCell(0, $charHeight,'Pelanggan harus memastikan bahwa sertifikat ISO maupun CIQS valid sebagai prasyarat utama untuk mengeluarkan keputusan hasil uji QA.',0,'L');
            $pdf->Cell(4.5, $charHeight,"",0,0);$pdf->Cell(4.5, $charHeight,"b. ",0,0);$pdf->Cell(30.5, $charHeight,'Jika keputusan hasil uji QA',0,0); ;$pdf->SetFont('','B');$pdf->Cell(20,$charHeight,"tidak memenuhi/");$pdf->SetFont('','BI');$pdf->Cell(14,$charHeight,"not comply",0,0);$pdf->SetFont('','');$pdf->Cell(16,$charHeight,"maka pelanggan dapat mengajukan kembali permohonan uji QA ini terhitung 2 (dua) bulan",0,1);
            $pdf->Cell(4.5, $charHeight,"",0,0);$pdf->Cell(4.5, $charHeight,"",0,0);$pdf->Cell(111, $charHeight,'untuk produk Tingkat Komponen Dalam Negeri (TKDN) dan 6 (enam) bulan untuk produk non TKDN setelah keputusan tersebut keluar.',0,1);
            $pdf->Cell(4.5, $charHeight,"13.",0,0);
            $pdf->Cell(35, $charHeight,"Pelanggan dapat menghubungi");$pdf->SetFont('','I');$pdf->Cell(20,$charHeight,"customer service");$pdf->SetFont('','');$pdf->Cell(42,$charHeight,"TTH (WA: 081224837500 atau email:");$pdf->SetFont('','U');$pdf->SetTextColor(0,0,255);$pdf->Cell(21,$charHeight,"cstth@telkom.co.id");$pdf->SetTextColor(0,0,0);$pdf->SetFont('','');$pdf->Cell(10,$charHeight,") jika ada perbedaan informasi pelanggan maupun",0,1);
            $pdf->Cell(4.5, $charHeight,"",0,0);$pdf->MultiCell(0, $charHeight,'perangkat uji yang tidak terkait item pengujian antara informasi saat pendaftaran dengan informasi setelah laporan.',0,'L');
            $pdf->Cell(4.5, $charHeight,"14.",0,0);
            $pdf->MultiCell(0, $charHeight,'Pelanggan dilarang memberikan gratifikasi dalam bentuk apapun di luar biaya uji, transportasi, dan akomodasi. Jika ditemukan pelanggaran dalam hal ini',0,'L');
            $pdf->Cell(4.5, $charHeight,"",0,0);$pdf->Cell(60, $charHeight,'maka permohonan uji diputuskan selesai dengan LHU',0,0);$pdf->SetFont('','B');$pdf->Cell(20,$charHeight,"tidak memenuhi/");$pdf->SetFont('','BI');$pdf->Cell(16,$charHeight,"not comply.",0,1);$pdf->SetFont('','');
        }
        else{
            $pdf->Cell(4.5,$charHeight,"2.",0,0);
            $pdf->MultiCell(0, $charHeight,'Telkom Test House (TTH) menerbitkan biaya uji/kalibrasi yang nominalnya tertera sesuai Surat Pemberitahuan Biaya (SPB). Apabila pelanggan tidak melakukan pembayaran sampai akhir tahun berjalan, maka web TTH secara otomatis menghapus permohonan ini.',0,'L');
            $pdf->Cell(4.5,$charHeight,"3.",0,0);
            $pdf->MultiCell(0,$charHeight,'Web TTH secara otomatis menerbitkan nomor Surat Perintah Kerja (SPK) yang menunjukkan bahwa pembayaran ini telah terverifikasi oleh TTH. Setelah nomor SPK terbit, pelanggan tidak dapat melakukan hal-hal berikut:',0,'L');
            $pdf->Cell(4.5,$charHeight,"",0,0);$pdf->Cell(4.5, $charHeight,"a. ",0,0);$pdf->MultiCell(0, $charHeight,'Perubahan/penambahan referensi uji.',0,'L');
            $pdf->Cell(4.5,$charHeight,"",0,0);$pdf->Cell(4.5, $charHeight,"b. ",0,0);$pdf->MultiCell(0, $charHeight,'Pengalihan lokasi uji dari internal ke eksternal Lab TTH dan sebaliknya.',0,'L');
            $pdf->Cell(4.5,$charHeight,"4.",0,0);
            $pdf->MultiCell(0, $charHeight,'Pelaksanaan uji/kalibrasi ditentukan berdasarkan antrean pengujian/kalibrasi di Lab TTH bukan dihitung sejak nomor SPK terbit.',0,'L');
            $pdf->Cell(4.5,$charHeight,"5.",0,0);
            $pdf->MultiCell(0, $charHeight,'Pelanggan dapat mengajukan pembatalan uji/kalibrasi setelah nomor SPK terbit, jika:',0,'L');
            $pdf->Cell(4.5,$charHeight,"",0,0);$pdf->Cell(4.5, $charHeight,"a. ",0,0);$pdf->MultiCell(0, $charHeight,'Pengujian/kalibrasi belum dilakukan sama sekali.',0,'L');
            $pdf->Cell(4.5,$charHeight,"",0,0);$pdf->Cell(4.5, $charHeight,"b. ",0,0);$pdf->MultiCell(0, $charHeight,'Terdapat perubahan referensi uji pada periode nomor SPK telah terbit.',0,'L');
            $pdf->Cell(4.5,$charHeight,"",0,0);$pdf->Cell(4.5, $charHeight,"c. ",0,0);$pdf->MultiCell(0, $charHeight,'Alat ukur mendadak rusak dan tidak dapat diperbaiki lagi.',0,'L');
            $pdf->Cell(4.5,$charHeight,"",0,0);$pdf->Cell(74,$charHeight,"Pembatalan uji diputuskan selesai dengan Laporan Hasil Uji (LHU)");$pdf->SetFont('','B');$pdf->Cell(19.5,$charHeight,"tidak memenuhi/");$pdf->SetFont('','BI');$pdf->Cell(14,$charHeight,"not comply ",0,0);$pdf->SetFont('','');$pdf->Cell(22.5,$charHeight,"sedangkan pembatalan kalibrasi diputuskan selesai dengan",0,1);
            $pdf->Cell(4.5, $charHeight,'',0,0);$pdf->SetFont('','BI');$pdf->Cell(22.5,$charHeight,"Uncalibrated Report.",0,1);$pdf->SetFont('','');
            $pdf->Cell(4.5,$charHeight,"6.",0,0);
            $pdf->MultiCell(0,$charHeight,'Jika pembatalan uji/kalibrasi sebelum tanggal 5 bulan berikutnya, maka biaya uji/kalibrasi yang sudah dibayarkan dapat dikembalikan utuh (biaya uji/kalibrasi + PPN 10 %). Namun jika sudah melebihi tanggal 5 bulan berikutnya, maka biaya uji/kalibrasi tersebut menjadi deposit biaya permohonan berikutnya (hanya biaya uji/kalibrasi saja).',0,'L');
            $pdf->Cell(4.5,$charHeight,"7.",0,0);
            $pdf->MultiCell(0,$charHeight,'Pelanggan menyatakan bahwa informasi perangkat uji/alat ukur yang didaftarkan dalam kontrak uji ini adalah sama dengan perangkat uji/alat ukur yang diserahkan ke TTH.',0,'L');
            $pdf->Cell(4.5,$charHeight,"8.",0,0);
            $pdf->Cell(0, $charHeight,'Dalam hal ada pengecekan/perubahan/peminjaman/penggantian/pengambilan perangkat uji oleh pelanggan setelah terbit nomor SPK, maka permohonan ini',0,1);//$pdf->setXY(101,192);$pdf->SetFont('','B');$pdf->Cell(22.5,5,"tidak memenuhi/");$pdf->SetFont('','BI');$pdf->Cell(0,5,"not comply",0,1);$pdf->SetFont('','');
            $pdf->Cell(4.5,$charHeight,"",0,0);$pdf->Cell(42, $charHeight,'akan diputuskan selesai dengan LHU ',0,0);$pdf->SetFont('','B');$pdf->Cell(20,$charHeight,"tidak memenuhi/");$pdf->SetFont('','BI');$pdf->Cell(16,$charHeight,"not comply.",0,1);$pdf->SetFont('','');
            $pdf->Cell(4.5, $charHeight,"9.",0,0);
            $pdf->Cell(54,$charHeight,'Pelanggan menerima LHU dan/atau Sertifikat Uji',0,'L');$pdf->SetFont('','I');$pdf->Cell(21,$charHeight,'Quality Assurance',0,'L');$pdf->SetFont('','');$pdf->Cell(75,$charHeight,'(QA) setelah pengujian perangkat selesai atau pelanggan menerima',0,0);$pdf->SetFont('','I');$pdf->Cell(28, $charHeight,'Calibration Certificate',0,1);
            $pdf->Cell(4.5, $charHeight,'',0,0);$pdf->SetFont('','');$pdf->Cell(22.5,$charHeight,"setelah kalibrasi alat ukur selesai.",0,1);
            $pdf->Cell(4.5, $charHeight,"10.",0,0);
            $pdf->Cell(74, $charHeight,'Sebagai prasyarat mengunduh LHU dan/atau Sertifikat Uji QA atau',0,0);$pdf->SetFont('','I');$pdf->Cell(23, $charHeight,'Calibration Certificate',0,0);$pdf->SetFont('','');$pdf->Cell(62, $charHeight,', pelanggan harus mengambil perangkat uji/alat ukur dan mengisi',0,1);
            $pdf->Cell(4.5, $charHeight,'',0,0);$pdf->Cell(22.5,$charHeight,"survei kepuasan pelanggan yang ada di web TTH pada saat pelanggan mengunduh dokumen tersebut.",0,1);
            $pdf->Cell(4.5, $charHeight,"11.",0,0);
            $pdf->MultiCell(0, $charHeight,'Pelanggan bersedia jika informasi pelanggan dan perangkat uji yang telah lulus uji QA dipublikasikan melalui web TTH.',0,'L');
            $pdf->Cell(4.5, $charHeight,"12.",0,0);
            $pdf->MultiCell(0, $charHeight,'Khusus untuk permohonan uji QA, pelanggan harus memperhatikan hal-hal berikut:',0,'L');
            $pdf->Cell(4.5, $charHeight,"",0,0);$pdf->Cell(4.5, $charHeight,"a. ",0,0);$pdf->MultiCell(0, $charHeight,'Pelanggan harus memastikan bahwa sertifikat ISO maupun CIQS valid sebagai prasyarat utama untuk mengeluarkan keputusan hasil uji QA.',0,'L');
            $pdf->Cell(4.5, $charHeight,"",0,0);$pdf->Cell(4.5, $charHeight,"b. ",0,0);$pdf->Cell(30.5, $charHeight,'Jika keputusan hasil uji QA',0,0); ;$pdf->SetFont('','B');$pdf->Cell(19.5,$charHeight,"tidak memenuhi/");$pdf->SetFont('','BI');$pdf->Cell(14,$charHeight,"not comply",0,0);$pdf->SetFont('','');$pdf->Cell(16,$charHeight,"maka pelanggan dapat mengajukan kembali permohonan uji QA ini terhitung 2 (dua) bulan untuk",0,1);
            $pdf->Cell(4.5, $charHeight,"",0,0);$pdf->Cell(4.5, $charHeight,"",0,0);$pdf->Cell(111, $charHeight,'produk Tingkat Komponen Dalam Negeri (TKDN) dan 6 (enam) bulan untuk produk non TKDN setelah keputusan tersebut keluar.',0,1);
            $pdf->Cell(4.5, $charHeight,"13.",0,0);
            $pdf->Cell(35, $charHeight,"Pelanggan dapat menghubungi");$pdf->SetFont('','I');$pdf->Cell(19,$charHeight,"customer service");$pdf->SetFont('','');$pdf->Cell(41.5,$charHeight,"TTH (WA: 081224837500 atau email:");$pdf->SetFont('','U');$pdf->SetTextColor(0,0,255);$pdf->Cell(21,$charHeight,"cstth@telkom.co.id");$pdf->SetTextColor(0,0,0);$pdf->SetFont('','');$pdf->Cell(10,$charHeight,") jika ada perbedaan informasi pelanggan maupun",0,1);
            $pdf->Cell(4.5, $charHeight,"",0,0);$pdf->MultiCell(0, $charHeight,'perangkat uji/alat ukur yang tidak terkait item uji antara informasi saat pendaftaran dengan informasi setelah laporan.',0,'L');
            $pdf->Cell(4.5, $charHeight,"14.",0,0);
            $pdf->Cell(111, $charHeight,'Pelanggan dilarang memberikan gratifikasi dalam bentuk apapun di luar biaya uji/kalibrasi. Jika ditemukan pelanggaran dalam hal ini maka permohonan ini',0,1);
            $pdf->Cell(4.5, $charHeight,"",0,0);$pdf->Cell(36, $charHeight,'diputuskan selesai dengan LHU',0,0);$pdf->SetFont('','B');$pdf->Cell(19.5,$charHeight,"tidak memenuhi/");$pdf->SetFont('','BI');$pdf->Cell(14,$charHeight,"not comply ",0,0);$pdf->SetFont('','');$pdf->Cell(6, $charHeight,'atau',0,0);$pdf->SetFont('','BI');$pdf->Cell(16,$charHeight,"Uncalibrated Report. ",0,1);$pdf->SetFont('','');
        }
        
        // SIGN
        if($pdf->getY() > 242){
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->Ln(15);
            $pdf->setX(134);
            $pdf->Cell(59,$charHeight,"Bandung, $signDate",0,1,'C');
            $pdf->Cell(59,$charHeight, 'Pelanggan', 0, 0, 'C');
            $pdf->Cell(58,$charHeight, 'Mgr. Lab', 0, 0, 'C');
            $pdf->Cell(59,$charHeight, 'Mgr. UREL', 0, 1, 'C');
            $pdf->Ln(15);
            $pdf->Cell(59,$charHeight, '('.$data[0]['pic'].')', 0, 0, 'C');
            $pdf->Cell(58,$charHeight, '('.$data[0]['manager_lab'].')', 0, 0, 'C');
            $data[0]['is_poh'] ? $pdf->Cell(59,$charHeight,'(POH '.$data[0]['manager_urel'].')', 0, 1, 'C') :  $pdf->Cell(59,$charHeight,'('.$data[0]['manager_urel'].')', 0, 1, 'C');
        }else{
            $pdf->Ln(1);
            $pdf->setX(134);
            $pdf->Cell(59,$charHeight,"Bandung, $signDate",0,1,'C');
            $pdf->Cell(59,$charHeight, 'Pelanggan', 0, 0, 'C');
            $pdf->Cell(58,$charHeight, 'Mgr. Lab', 0, 0, 'C');
            $pdf->Cell(59,$charHeight, 'Mgr. UREL', 0, 1, 'C');
            $pdf->Ln(15);
            $pdf->Cell(59,$charHeight, '('.$data[0]['pic'].')', 0, 0, 'C');
            $pdf->Cell(58,$charHeight, '('.$data[0]['manager_lab'].')', 0, 0, 'C');
            $data[0]['is_poh'] ? $pdf->Cell(59,$charHeight,'(POH '.$data[0]['manager_urel'].')', 0, 1, 'C') :  $pdf->Cell(59,$charHeight,'('.$data[0]['manager_urel'].')', 0, 1, 'C');
        }
        
        $pdf->Output();
        exit;
    }

}
