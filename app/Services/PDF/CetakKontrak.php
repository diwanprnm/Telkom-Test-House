<?php

namespace App\Services\PDF;

class CetakKontrak
{
    public function makePDF($data, $pdf)
    { 
        
        if($data[0]['is_loc_test'] == 1){
            $pdf->judul_kop('KONTRAK UJI LOKASI DALAM NEGERI','On-Site Testing Contract');
            $pdf->setData(['kodeForm' => 'TLKM02/F/007 Versi 02']);
        }else{
            $pdf->judul_kop('KONTRAK UJI LABORATORIUM','Laboratory Testing Contract');
            $pdf->setData(['kodeForm' => 'TLKM02/F/006 Versi 03']);
        }
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('helvetica','B',9);
        $pdf->Cell(190,1,"No. Reg ".$data[0]['no_reg'],0,0,'R');
        $pdf->Ln(1);
    /*Data Pemohon*/
        $pdf->SetFont('helvetica','B',11);
        $pdf->Cell(27.5,5,"Data Pemohon",0,0,'L');
        $pdf->SetFont('helvetica','I',11);
        $pdf->Cell(10,5,"/Applicant's Data",0,0,'L');
        /*Nama Pemohon*/
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','',10);
        $pdf->setXY(10.00125,$y + 6);
        $pdf->SetFont('','U');
        $pdf->Cell(10,5,"Nama Pemohon",0,0,'L');
        $pdf->SetWidths(array(0.00125,40,45,145));
        $pdf->Row(array("","",":",$data[0]['nama_perusahaan']));
        $y2 = $pdf->getY();
        $pdf->setXY(10.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"Applicant's Name",0,0,'L');
            if(($y2 - $y) > 11){
                $yNow = $y2 - 6;
            }else{
                $yNow = $y2;
            }
        $pdf->setXY(10.00125,$yNow);
        /*Alamat Pemohon*/
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','U',10);
        $pdf->setXY(10.00125,$y + 6);
        $pdf->Cell(10,5,"Alamat",0,0,'L');
        $pdf->SetWidths(array(0.00125,40,45,145));
        $pdf->Row(array("","",":",$data[0]['alamat_perusahaan']));
        $pdf->setXY(10.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"Address",0,0,'L');
        $pdf->setX(10.00125);

        /*Merek dan Model Perangkat*/
        $y = $pdf->getY();
        $pdf->SetFont('helvetica','U',10);
        $pdf->setXY(10.00125,$y + 6);
        $pdf->Cell(10,5,"Nomor PLG_ID",0,0,'L');
        $pdf->SetWidths(array(0.00125,40,45,50));
        $plg_id = $data[0]['jns_pengujian'] == 2 ? $data[0]['plg_id'] : '-';
        $pdf->Row(array("","",":",$plg_id));
        $y2 = $pdf->getY();
        $pdf->setXY(110.00125,$y + 6);
        $pdf->SetFont('','U');
        $pdf->Cell(10,5,"NIB",0,0,'L');
        $pdf->SetWidths(array(0.00125,135,140,50));
        $nib = $data[0]['jns_pengujian'] == 2 ? $data[0]['nib'] : '-';
        $pdf->Row(array("","",":",$nib));
        $y3 = $pdf->getY();
        $pdf->setXY(10.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(100,5,"PLG_ID Number",0,0,'L');
        $pdf->Cell(10,5,"NIB",0,0,'L');
        $yNow = max($y,$y2,$y3);
        if($y2 == $y3){ //
        }else{ $yNow = $yNow - 6; }
        
        $pdf->setXY(10.00125,$yNow);
        
        $pdf->Ln(8);
        $pdf->setX(10.00125);
    /*End Data Pemohon*/
    
    /*Data Perangkat*/
        $pdf->SetFont('helvetica','B',11);
        $pdf->Cell(29,5,"Data Perangkat ",0,0,'L');
        $pdf->SetFont('helvetica','I',11);
        $pdf->Cell(10,5,"/Equipment's Data",0,0,'L');
        /*Nama Perangkat*/
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','',10);
        $pdf->setXY(10.00125,$y + 6);
        $pdf->SetFont('','U');
        $pdf->Cell(10,5,"Nama Perangkat",0,0,'L');
        $pdf->SetWidths(array(0.00125,40,45,145));
        $pdf->Row(array("","",":",$data[0]['nama_perangkat']));
        $y2 = $pdf->getY();
        $pdf->setXY(10.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"Equipment Name",0,0,'L');
        if(($y2 - $y) > 11){ $yNow = $y2 - 6;
        }else{ $yNow = $y2; }
        $pdf->setXY(10.00125,$yNow);

        /*Merek dan Model Perangkat*/
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','U',10);
        $pdf->setXY(10.00125,$y + 6);
        $pdf->Cell(10,5,"Merk/Pabrik",0,0,'L');
        $pdf->SetWidths(array(0.00125,40,45,50));
        $pdf->Row(array("","",":",$data[0]['merek_perangkat']));
        $y2 = $pdf->getY();
        $pdf->setXY(110.00125,$y + 6);
        $pdf->SetFont('','U');
        $pdf->Cell(10,5,"Model/Type",0,0,'L');
        $pdf->SetWidths(array(0.00125,135,140,50));
        $pdf->Row(array("","",":",$data[0]['model_perangkat']));
        $y3 = $pdf->getY();
        $pdf->setXY(10.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(100,5,"Brand/Factory",0,0,'L');
        $pdf->Cell(10,5,"Model/Type",0,0,'L');
        $yNow = max($y,$y2,$y3);
        $pdf->setXY(10.00125,$yNow);
        $pdf->Ln(3);
        
        /*Kapasitas dan Referensi Uji Perangkat*/
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','',10);
        $pdf->setXY(10.00125,$y + 3);
        $pdf->SetFont('','U');
        $pdf->Cell(10,5,"Kapasitas/Kecepatan",0,0,'L');
        $pdf->SetWidths(array(0.00125,40,45,50));
        $pdf->Row(array("","",":",$data[0]['kapasitas_perangkat']));
        $y2 = $pdf->getY();
        $pdf->setXY(110.00125,$y + 3);
        $pdf->SetFont('','U');
        $pdf->Cell(10,5,"Referensi Uji",0,0,'L');
        $pdf->SetWidths(array(0.00125,135,140,50));
        $pdf->Row(array("","",":",$data[0]['referensi_perangkat']));
        $y3 = $pdf->getY();
        $pdf->setXY(10.00125,$y + 7);
        $pdf->SetFont('','I');
        $pdf->Cell(100,5,"Capacity/Speed",0,0,'L');
        $pdf->Cell(10,5,"Test Reference",0,0,'L');
        $yNow = max($y,$y2,$y3);
        if($y2 == $y3){
            /* // $yNow; */
        }else{
            $yNow = $yNow - 6;
        }
        $pdf->setXY(10.00125,$yNow);
        /*Negara Pembuat Perangkat*/
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','',10);
        $pdf->setXY(10.00125,$y + 6);
        $pdf->SetFont('','U');
        $pdf->Cell(10,5,"Negara Pembuat",0,0,'L');
        $pdf->SetWidths(array(0.00125,40,45,50));
        $pdf->Row(array("","",":",$data[0]['pembuat_perangkat']));
        $y2 = $pdf->getY();
        $pdf->setXY(110.00125,$y + 6);
        $pdf->SetFont('','U');
        $pdf->Cell(10,5,"Pilihan Item Uji *)",0,0,'L');
        $pdf->SetWidths(array(0.00125,135,140,50));
        $pdf->Row(array("","",":",'All / Partial **)'));
        $y3 = $pdf->getY();
        $pdf->setXY(10.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(100,5,"Made In",0,0,'L');
        $pdf->Cell(10,5,"Test Item Choice",0,0,'L');
        $yNow = max($y,$y2,$y3);
        if($y2 == $y3){
            /* // $yNow; */
        }else{
            $yNow = $yNow - 6;
        }
        $pdf->setXY(10.00125,$yNow);
        /*Keterangan*/
        //$pdf->Rect(54, 117, 145, 10);
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','',10);
        $pdf->setXY(10.00125,$y + 6);
        $pdf->SetFont('','U');
        $pdf->Cell(100,5,"Keterangan",0,0,'L');
        $pdf->SetWidths(array(0.00125,40,45,145));
        $pdf->Row(array("","",":",''));
        $y2 = $pdf->getY();
        $pdf->setXY(10.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"Note",0,0,'L');
        $pdf->Ln(8);
        $pdf->setX(10.00125);        

    /*End Data Perangkat*/
    
    /*Hal-hal yang disepakati*/
        $pdf->SetFont('helvetica','B',11);
        $pdf->Cell(21.5,5,"Pernyataan",0,0,'L');
        $pdf->SetFont('helvetica','',11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"/Agreements",0,0,'L');
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','',10);
        $pdf->setXY(12.00125,$y + 6);
        // $pdf->Cell(4, 4, "", 1, 0);
        if($data[0]['is_loc_test'] == 1){
            $pdf->Cell(4,4,"1.",0,0,'L');
            $pdf->Cell(10,4,"Kesepakatan yang tertuang dalam Technical Meeting Uji Lokasi Dalam Negeri adalah benar.",0,0,'L');
            $y = $pdf->getY();
            $pdf->Ln(5);
            $pdf->SetFont('helvetica','',10);
            $pdf->setXY(12.00125,$y + 6);
            $pdf->Cell(4,4,"2.",0,0,'L');
            $pdf->Cell(10,4,"Biaya uji lokasi (biaya uji, transportasi, dan akomodasi) sesuai dengan Surat Perintah Bayar (SPB) yang telah",0,0,'L');
            $pdf->Ln(4);
            $pdf->Cell(1,4,"",0,0,'L');
            $pdf->Cell(10,4,"diterbitkan oleh TELKOM.",0,0,'L');
            // $pdf->Cell(4, 4, "", 1, 0);
            $y = $pdf->getY();
            $pdf->Ln(5);
            $pdf->SetFont('helvetica','',10);
            $pdf->setXY(12.00125,$y + 6);
            // $pdf->Cell(4, 4, "", 1, 0);
            $pdf->Cell(4,4,"3.",0,0,'L');
            $pdf->Cell(10,4,"Pelanggan memahami dan menentukan referensi uji yang akan digunakan, memahami item uji, dan konfigurasi uji.",0,0,'L');
            $y = $pdf->getY();
            $pdf->Ln(5);
            $pdf->SetFont('helvetica','',10);
            $pdf->setXY(12.00125,$y + 6);
            // $pdf->Cell(4, 4, "", 1, 0);
            $pdf->Cell(4,4,"4.",0,0,'L');
            $pdf->Cell(10,4,"Pelanggan akan menerima laporan hasil uji dan/atau sertifikat.",0,0,'L');
            $y = $pdf->getY();
            $pdf->Ln(5);
            $pdf->SetFont('helvetica','',10);
            $pdf->setXY(12.00125,$y + 6);
            // $pdf->Cell(4, 4, "", 1, 0);
            $pdf->Cell(4,4,"5.",0,0,'L');
            $pdf->Cell(10,4,"Pembayaran biaya uji lokasi sesuai SPB, dilakukan oleh pelanggan paling lambat 3 (tiga) hari kalender sebelum",0,0,'L');
            $pdf->Ln(4);
            $pdf->Cell(1,4,"",0,0,'L');
            $pdf->Cell(10,4,"pelaksanaan uji lokasi. Apabila pada tenggang waktu tersebut, pelanggan tidak melakukan pembayaran, kontrak ini",0,0,'L');
            $pdf->Ln(4);
            $pdf->Cell(1,4,"",0,0,'L');
            $pdf->Cell(10,4,"dinyatakan tidak berlaku.",0,0,'L');
            $y = $pdf->getY();
            $pdf->Ln(5);
            $pdf->SetFont('helvetica','',10);
            $pdf->setXY(12.00125,$y + 6);
            $pdf->Cell(4,4,"6.",0,0,'L');
            $pdf->Cell(10,4,"Pelanggan menyatakan bahwa perangkat yang didaftarkan dalam kontrak ini adalah sama dengan sampel uji.",0,0,'L');
            $y = $pdf->getY();
            $pdf->Ln(5);
            $pdf->SetFont('helvetica','',10);
            $pdf->setXY(12.00125,$y + 6);
            $pdf->Cell(4,4,"7.",0,0,'L');
            $pdf->Cell(10,4,"Pelanggan menyatakan bahwa lingkungan (laboratorium, teknisi, sampel uji, dan alat ukur) uji lokasi sudah siap.",0,0,'L');
            $y = $pdf->getY();
            $pdf->Ln(5);
            $pdf->SetFont('helvetica','',10);
            $pdf->setXY(12.00125,$y + 6);
            $pdf->Cell(4,4,"8.",0,0,'L');
            $pdf->Cell(10,4,"Kekeliruan pada penamaan perangkat dan referensi uji yang digunakan pada laporan hasil uji bukan tanggung jawab",0,0,'L');
            $pdf->Ln(4);
            $pdf->Cell(1,4,"",0,0,'L');
            $pdf->Cell(10,4,"TELKOM.*",0,0,'L');
            $y = $pdf->getY();
            $pdf->Ln(5);
            $pdf->SetFont('helvetica','',10);
            $pdf->setXY(12.00125,$y + 6);
            $pdf->Cell(4,4,"9.",0,0,'L');
            $pdf->Cell(10,4,"Pelanggan tidak berkeberatan jika data pelanggan dan data perangkat yang telah lulus uji QA dipublikasikan melalui",0,0,'L');
            $pdf->Ln(4);
            $pdf->Cell(1,4,"",0,0,'L');
            $pdf->Cell(10,4,"web www.telkomtesthouse.co.id.",0,0,'L');
        }else{		
            $pdf->Cell(4,4,"1.",0,0,'L');
            $pdf->Cell(10,4,"Biaya uji sesuai Surat Perintah Bayar (SPB) yang telah diterbitkan oleh TELKOM",0,0,'L');
            $y = $pdf->getY();
            $pdf->Ln(5);
            $pdf->SetFont('helvetica','',10);
            $pdf->setXY(12.00125,$y + 6);
            // $pdf->Cell(4, 4, "", 1, 0);
            $pdf->Cell(4,4,"2.",0,0,'L');
            $pdf->Cell(10,4,"Pelanggan memahami dan menentukan referensi uji yang akan digunakan, memahami item uji, dan konfigurasi uji.",0,0,'L');
            $y = $pdf->getY();
            $pdf->Ln(5);
            $pdf->SetFont('helvetica','',10);
            $pdf->setXY(12.00125,$y + 6);
            // $pdf->Cell(4, 4, "", 1, 0);
            $pdf->Cell(4,4,"3.",0,0,'L');
            $pdf->Cell(10,4,"Pelanggan akan menerima laporan hasil uji dan/atau sertifikat.",0,0,'L');
            $y = $pdf->getY();
            $pdf->Ln(5);
            $pdf->SetFont('helvetica','',10);
            $pdf->setXY(12.00125,$y + 6);
            // $pdf->Cell(4, 4, "", 1, 0);
            $pdf->Cell(4,4,"4.",0,0,'L');
            $pdf->Cell(10,4,"Pelanggan harus mengambil kembali sampel uji, paling lambat 30 (tiga puluh) hari kalender setelah proses uji selesai.",0,0,'L');
            $y = $pdf->getY();
            $pdf->Ln(5);
            $pdf->SetFont('helvetica','',10);
            $pdf->setXY(12.00125,$y + 6);
            // $pdf->Cell(4, 4, "", 1, 0);
            $pdf->Cell(4,4,"5.",0,0,'L');
            $pdf->Cell(10,4,"Laporan hasil uji dan/atau sertifikat Quality Assurance (QA) test diberikan apabila sampel uji sudah diambil oleh",0,0,'L');
            $pdf->Ln(4);
            $pdf->Cell(1,4,"",0,0,'L');
            $pdf->Cell(10,4,"pelanggan. Setelah menerima laporan hasil uji dan/atau sertifikat QA test, pelanggan telah memahami hasil uji",0,0,'L');
            $y = $pdf->getY();
            $pdf->Ln(5);
            $pdf->SetFont('helvetica','',10);
            $pdf->setXY(12.00125,$y + 6);
            // $pdf->Cell(4, 4, "", 1, 0);
            $pdf->Cell(4,4,"6.",0,0,'L');
            $pdf->Cell(10,4,"Pembayaran biaya uji sesuai SPB, dilakukan oleh pelanggan paling lambat 14 (empat belas) hari kalender setelah",0,0,'L');
            $pdf->Ln(4);
            $pdf->Cell(1,4,"",0,0,'L');
            $pdf->Cell(10,4,"penerbitan SPB. Apabila pada tenggang waktu tersebut, pelanggan tidak melakukan pembayaran, kontrak ini",0,0,'L');
            $pdf->Ln(4);
            $pdf->Cell(1,4,"",0,0,'L');
            $pdf->Cell(10,4,"dinyatakan tidak berlaku.",0,0,'L');
            $y = $pdf->getY();
            $pdf->Ln(5);
            $pdf->SetFont('helvetica','',10);
            $pdf->setXY(12.00125,$y + 6);
            $pdf->Cell(4,4,"7.",0,0,'L');
            $pdf->Cell(10,4,"Pelanggan menyatakan bahwa perangkat yang didaftarkan dalam kontrak ini adalah sama dengan sampel uji.",0,0,'L');
            $y = $pdf->getY();
            $pdf->Ln(5);
            $pdf->SetFont('helvetica','',10);
            $pdf->setXY(12.00125,$y + 6);
            $pdf->Cell(4,4,"8.",0,0,'L');
            $pdf->Cell(10,4,"Kekeliruan pada penamaan perangkat dan referensi uji yang digunakan pada laporan hasil uji bukan tanggung jawab",0,0,'L');
            $pdf->Ln(4);
            $pdf->Cell(1,4,"",0,0,'L');
            $pdf->Cell(10,4,"TELKOM.",0,0,'L');
            $y = $pdf->getY();
            $pdf->Ln(5);
            $pdf->SetFont('helvetica','',10);
            $pdf->setXY(12.00125,$y + 6);
            $pdf->Cell(4,4,"9.",0,0,'L');
            $pdf->Cell(10,4,"Pelanggan tidak berkeberatan jika data pelanggan dan data perangkat yang telah lulus uji QA dipublikasikan melalui",0,0,'L');
            $pdf->Ln(4);
            $pdf->Cell(1,4,"",0,0,'L');
            $pdf->Cell(10,4,"web www.telkomtesthouse.co.id.",0,0,'L');
        }
        $y = $pdf->getY();
        $pdf->Ln(5);
        $pdf->SetFont('helvetica','',10);
        $pdf->setXY(12.00125,$y + 6);
        $pdf->Cell(50,4,"*)  Untuk Pengujian TA",0,0,'L');
        $pdf->Cell(4,4,"**)  Coret salah satu",0,0,'L');
        
        $pdf->Ln(6);
        $pdf->setX(10.00125);
    /*End Data Pemohon*/
    
    /*Footer Manual*/
        $pdf->SetFont('helvetica','',10);
        $pdf->Cell(126,5,"",0,0,'L');
        $pdf->Cell(63,5,"Bandung, ".$data[0]['contract_date'],0,0,'C');
        $pdf->Ln(5);
        $pdf->setX(10.00125);
        $pdf->Cell(63, 4, 'Pelanggan', 1, 0, 'C');
        $pdf->Cell(63, 4, 'Manager Laboratorium QA', 1, 0, 'C');
        $pdf->Cell(63, 4, 'Manager UREL', 1, 1, 'C');
        $pdf->setX(10.00125);

        $pdf->drawTextBox('('.$data[0]['pic'].')', 63, 23, 'C', 'B', 1);
        $pdf->setXY(73.00125,$pdf->getY()-23);
        $pdf->drawTextBox('('.$data[0]['manager_lab'].')', 63, 23, 'C', 'B', 1);
        $pdf->setXY(136.00125,$pdf->getY()-23);
        if($data[0]['is_poh'] == '1'){
            $pdf->drawTextBox('POH ('.$data[0]['manager_urel'].')', 63, 23, 'C', 'B', 1);
        }else{
            $pdf->drawTextBox('('.$data[0]['manager_urel'].')', 63, 23, 'C', 'B', 1);
        }
        
        $pdf->Ln(5);
        $pdf->setX(10.00125);
        $pdf->SetFont('','U');
        $pdf->Cell(10,5,"Telkom Test House, Telp. (+62) 812-2483-7500",0,0,'L');
        $pdf->Ln(4);
        $pdf->setX(10.00125);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"Telkom Test House, Phone. (+62) 812-2483-7500",0,0,'L');
        $pdf->Ln();
        $pdf->Ln();
    /*End Footer Manual*/
        $pdf->Output();
        exit;
    }
}
