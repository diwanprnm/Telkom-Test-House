<?php

namespace App\Services\PDF;

class CetakPermohonan
{

    public function makePDF($data, $pdf)
    {
        $pdf->jns_pengujian($data[0]['initPengujian'],$data[0]['initPengujian']);
        $kop = '';
        $subHeader = '';

        if($data[0]['initPengujian'] == 'QA'){
            $kop = 'UJI MUTU';
            $subHeader = 'Quality Assurance (QA)';
        }
        else if($data[0]['initPengujian'] == 'TA'){
            $kop = 'UJI TIPE';
            $subHeader = 'Type Approval (TA)';
        }
        else if($data[0]['initPengujian'] == 'VT'){
            $kop = 'UJI PESANAN';
            $subHeader = 'Voluntary Testing (VT)';
        }
        else if($data[0]['initPengujian'] == 'KAL'){
            $kop = 'KALIBRASI';
            $subHeader = 'Calibration';
        }
       
        $pdf->data_param(
			'PERMOHONAN '.$kop,
			$subHeader.' Application',
			$data[0]['kotaPerusahaan'],
            $data[0]['date'],
            $data[0]['nama_pemohon']
        );

        //Initial Halaman
        $pdf->AliasNbPages();
        $pdf->AddPage();

        /*
         * SECTION DATA PEMOHON
         */
        $pdf->SetFont('helvetica','B',9);
        $pdf->Cell(190,1,"No. Reg ".$data[0]['no_reg'],0,0,'R');
        $pdf->Ln(1);
        $pdf->SetFont('helvetica','B',11);
        $pdf->Cell(27.5,5,"Data Pemohon ",0,0,'L');
        $pdf->SetFont('helvetica','',11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"/Applicant's Data",0,0,'L');

        /*Nama Pemohon*/
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','U',10);
        $pdf->setXY(10.00125,$y + 6);
        $pdf->Cell(10,5,"Nama Pemohon",0,0,'L');
        $pdf->SetWidths(array(0.00125,40,45,145));
        $pdf->Row(array("","",":",$data[0]['nama_pemohon']));
        $y2 = $pdf->getY();
        $pdf->setXY(10.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"Applicant's Name",0,0,'L');
        if(($y2 - $y) > 11){ $yNow = $y2 - 6;
        }else{ $yNow = $y2; }
        $pdf->setXY(10.00125,$yNow);

        /*Alamat Pemohon*/
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','U',10);
        $pdf->setXY(10.00125,$y + 6);
        $pdf->Cell(10,5,"Alamat",0,0,'L');
        $pdf->SetWidths(array(0.00125,40,45,145));
        $pdf->Row(array("","",":",$data[0]['alamat_pemohon']));
        $y2 = $pdf->getY();
        $pdf->setXY(10.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"Address",0,0,'L');
        if(($y2 - $y) > 11){ $yNow = $y2 - 6;
        }else{ $yNow = $y2; }
        $pdf->setXY(10.00125,$yNow);

        /*Nomor Telepon dan Alamat Pemohon*/
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','U',10);
        $pdf->setXY(10.00125,$y + 6);
        $pdf->Cell(10,5,"Nomor HP",0,0,'L');
        $pdf->SetWidths(array(0.00125,40,45,50));
        $pdf->Row(array("","",":",$data[0]['telepon_pemohon']));
        $y2 = $pdf->getY();
        $pdf->setXY(10.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"HP Number",0,0,'L');
        if(($y2 - $y) > 11){ $yNow = $y2 - 6;
        }else{ $yNow = $y2; }
        $pdf->setXY(10.00125,$yNow);

        /*Alamat email (disebelah kanan nomor telepon)*/
        $y2 = $pdf->getY();
        $pdf->setXY(115.00125,$y + 6);
        $pdf->SetFont('helvetica','U',10);
        $pdf->Cell(10,5,"Alamat E-Mail",0,0,'L');
        $pdf->SetWidths(array(0.00125,130,133,70));
        $pdf->Row(array("","",":",$data[0]['email_pemohon']));
        $y2 = $pdf->getY();
        $pdf->setXY(115.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"E-Mail Address",0,0,'L');
        if(($y2 - $y) > 11){ $yNow = $y2 - 6;
        }else{ $yNow = $y2; }
        $pdf->setXY(10.00125,$yNow);
        /*END DATA PEMOHON*/
        
        /*Set New Section*/
        $pdf->Ln(6);
        $pdf->setX(10.00125);
    
        /**
         * SECTION DATA PERUSAHAAN
         */
        /*Sub header section*/
        $pdf->SetFont('helvetica','B',11);
        $pdf->Cell(32,5,"Data Perusahaan ",0,0,'L');
        $pdf->SetFont('helvetica','',11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"/Company's Data",0,0,'L');

        /*Jenis Perusahaan*/
        switch ($data[0]['jns_perusahaan']) {
            case 'Agen':
                $jnsPerusahaan_in = 'Agen/Perwakilan';
                $jnsPerusahaan_en = 'Agent/Distributor';
                break;
            case 'Pabrikan':
                $jnsPerusahaan_in = 'Pabrikan';
                $jnsPerusahaan_en = 'Manufacture';
                break;
            case 'Perorangan':
                $jnsPerusahaan_in = 'Pengguna/Perorangan';
                $jnsPerusahaan_en = 'User/Private';
                break;
            default:
                $jnsPerusahaan_in = 'Tidak Diketahui';
                $jnsPerusahaan_en = 'Unknown';
                break;
        }
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','BU',12);
        $pdf->setXY(10.00125,$y + 6);
        $pdf->Cell(190,5,$jnsPerusahaan_in,0,0,'C');
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','I',12);
        $pdf->setXY(10.00125,$y + 5);
        $pdf->Cell(190,5,$jnsPerusahaan_en,0,0,'C');

        /*Nama Perusahaan*/
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','U',10);
        $pdf->setXY(10.00125,$y + 6);
        $pdf->Cell(10,5,"Nama Perusahaan",0,0,'L');
        $pdf->SetWidths(array(0.00125,40,45,145));
        $pdf->Row(array("","",":",$data[0]['nama_perusahaan']));
        $y2 = $pdf->getY();
        $pdf->setXY(10.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"Company's Name",0,0,'L');
        if(($y2 - $y) > 11){ $yNow = $y2 - 6;
        }else{ $yNow = $y2; }
        $pdf->setXY(10.00125,$yNow);

        /*Alamat Perusahaan*/
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','U',10);
        $pdf->setXY(10.00125,$y + 6);
        $pdf->Cell(10,5,"Alamat",0,0,'L');
        $pdf->SetWidths(array(0.00125,40,45,145));
        $pdf->Row(array("","",":",$data[0]['alamat_perusahaan']));
        $y2 = $pdf->getY();
        $pdf->setXY(10.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"Address",0,0,'L');
        if(($y2 - $y) > 11){ $yNow = $y2 - 6;
        }else{ $yNow = $y2; }
        $pdf->setXY(10.00125,$yNow);

        /**
         * If Jenisa Pengujian = TA (Type Approval)
         */
        if($data[0]['jnsPengujian'] == 2){
            /*PLG_ID dan NIB*/
            $y = $pdf->getY();
            $pdf->Ln(6);
            $pdf->SetFont('helvetica','U',10);
            $pdf->setXY(10.00125,$y + 6);
            $pdf->Cell(10,5,"Nomor PLG_ID",0,0,'L');
            $pdf->SetWidths(array(0.00125,40,45,50));
            $pdf->Row(array("","",":",$data[0]['plg_id_perusahaan']));
            $y2 = $pdf->getY();
            $pdf->setXY(10.00125,$y + 11);
            $pdf->SetFont('','I');
            $pdf->Cell(10,5,"PLG_ID Number",0,0,'L');
            if(($y2 - $y) > 11){ $yNow = $y2 - 6;
            }else{ $yNow = $y2; }
            $pdf->setXY(10.00125,$yNow);


            $pdf->SetFont('helvetica','U',10);
            $pdf->setXY(115.00125,$y + 6);
            $pdf->Cell(10,5,"NIB",0,0,'L');
            $pdf->SetWidths(array(0.00125,130,133,70));
            $pdf->Row(array("","",":",$data[0]['nib_perusahaan']));
            $y2 = $pdf->getY();
            $pdf->setXY(115.00125,$y + 11);
            $pdf->SetFont('','I');
            $pdf->Cell(10,5,"NIB",0,0,'L');
            if(($y2 - $y) > 11){ $yNow = $y2 - 6;
            }else{ $yNow = $y2; }
            $pdf->setXY(10.00125,$yNow);

            $yNow = max($y,$y2);
            $yNow = $yNow - 4;
            $pdf->setXY(10.00125,$yNow);
            $pdf->Ln(4);
        }

        /*Telepon dan Faksimile Perusahaan*/
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','U',10);
        $pdf->setXY(10.00125,$y + 6);
        $pdf->Cell(10,5,"Nomor Telepon",0,0,'L');
        $pdf->SetWidths(array(0.00125,40,45,50));
        $pdf->Row(array("","",":",$data[0]['telepon_perusahaan']));
        $y2 = $pdf->getY();
        $pdf->setXY(10.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"Phone Number",0,0,'L');
        if(($y2 - $y) > 11){ $yNow = $y2 - 6;
        }else{ $yNow = $y2; }
        $pdf->setXY(10.00125,$yNow);

        /*Email Perusahaan*/
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','U',10);
        $pdf->setXY(10.00125,$y + 6);
        $pdf->Cell(10,5,"Alamat E-Mail",0,0,'L');
        $pdf->SetWidths(array(0.00125,40,45,65));
        $pdf->Row(array("","",":",$data[0]['email_perusahaan']));
        $y2 = $pdf->getY();
        $pdf->setXY(10.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"E-Mail Address",0,0,'L');
        if(($y2 - $y) > 11){ $yNow = $y2 - 6;
        }else{ $yNow = $y2; }
        $pdf->setXY(10.00125,$yNow);


        $y2 = $pdf->getY();
        $pdf->setXY(115.00125,$y + 6);
        $pdf->SetFont('','U');
        $pdf->Cell(10,5,"NPWP",0,0,'L');
        $pdf->SetWidths(array(0.00125,130,133,70));
        $pdf->Row(array("","",":",$data[0]['npwp_perusahaan']));
        $y2 = $pdf->getY();
        $pdf->setXY(115.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"NPWP",0,0,'L');
        if(($y2 - $y) > 11){ $yNow = $y2 - 6;
        }else{ $yNow = $y2; }
        $pdf->setXY(10.00125,$yNow);
    /*End Data Perusahaan*/

        $pdf->Ln(8);
    
    /*Data Perangkat*/
        $pdf->setX(10.00125);
        $pdf->SetFont('helvetica','B',11);
        $pdf->Cell(29,5,"Data Perangkat ",0,0,'L');
        $pdf->SetFont('helvetica','',11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"/Equipment Data",0,0,'L');

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
        $pdf->Cell(10,5,"Merek/Pabrik",0,0,'L');
        $pdf->SetWidths(array(0.00125,40,45,50));
        $pdf->Row(array("","",":",$data[0]['merek_perangkat']));
        $y2 = $pdf->getY();
        $pdf->setXY(10.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"Brand/Factory",0,0,'L');
        if(($y2 - $y) > 11){ $yNow = $y2 - 6;
        }else{ $yNow = $y2; }
        $pdf->setXY(10.00125,$yNow);

        $pdf->SetFont('helvetica','U',10);
        $pdf->setXY(115.00125,$y + 6);
        $pdf->Cell(10,5,"Model/Tipe",0,0,'L');
        $pdf->SetWidths(array(0.00125,130,133,70));
        $pdf->Row(array("","",":",$data[0]['model_perangkat']));
        $y2 = $pdf->getY();
        $pdf->setXY(115.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"Model/Type",0,0,'L');
        $y3 = $pdf->getY();
        $yNow = max($y,$y2,$y3);
        $pdf->setXY(10.00125,$yNow+3);

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
        $pdf->setXY(115.00125,$y + 3);
        $pdf->SetFont('','U');
        $pdf->Cell(10,5,"Referensi Uji",0,0,'L');
        $pdf->SetWidths(array(0.00125,130,133,70));
        $pdf->Row(array("","",":",$data[0]['referensi_perangkat']));
        $y3 = $pdf->getY();
        $pdf->setXY(10.00125,$y + 8);
        $pdf->SetFont('','I');
        $pdf->Cell(105,5,"Capacity/Speed",0,0,'L');
        $pdf->Cell(10,5,"Test Reference",0,0,'L');
        $yNow = max($y,$y2,$y3);
        $yNow -= 1;
        $pdf->setXY(10.00125,$yNow);

        /*Negara Pembuat Perangkat*/
        $y = $pdf->getY();
        $pdf->Ln(4);
        $pdf->SetFont('helvetica','',10);
        $pdf->setXY(10.00125,$y + 6);
        $pdf->SetFont('','U');
        $pdf->Cell(10,5,"Negara Pembuat",0,0,'L');
        $pdf->SetWidths(array(0.00125,40,45,145));
        $pdf->Row(array("","",":",$data[0]['pembuat_perangkat']));
        $y2 = $pdf->getY();
        $pdf->setXY(10.00125,$y + 11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"Made In",0,0,'L');
        $pdf->Ln(8);
        $pdf->setX(10.00125);
    /*End Data Perangkat*/
    
    
    if($data[0]['jnsPengujian'] == 4){
        /*Metoda Kalibrasi*/
        $pdf->SetFont('helvetica','B',11);
        $pdf->Cell(32,5,"Metode Kalibrasi",0,0,'L');
        $pdf->SetFont('helvetica','I',11);
        $pdf->Cell(37,5,"/ Calibration Method: ",0,0,'L');
        $pdf->SetFont('helvetica','',11);
        $pdf->Cell(33,5,"Instruksi Kerja (IK)",0,0,'L');
        $pdf->SetFont('helvetica','I',11);
        $pdf->Cell(35,5,"/ Work Instruction (WI)",0,0,'L');
        $pdf->Ln(8);
        $pdf->setX(10.00125);
    /*End Metoda Kalibrasi*/
    }
    /*Pernyataan*/
        $pdf->SetFont('helvetica','B',11);
        $pdf->Cell(21,5,"Pernyataan ",0,0,'L');
        $pdf->SetFont('helvetica','',11);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"/Agreements",0,0,'L');
        $y = $pdf->getY();
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','',10);
        $pdf->setXY(10.00125,$y + 6);
        $pdf->Cell(5,5,"1. ",0,0,'L');
        $pdf->SetFont('','U');
        $pdf->Cell(10,5,"Kami menyatakan bahwa permohonan ini telah diisi dengan keadaan yang sebenarnya.",0,0,'L');
        $pdf->Ln(4);
        $pdf->setX(10.00125);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"     Ensuring that we have filled this application with eligible data.",0,0,'L');
        $pdf->Ln(6);
        $pdf->setX(10.00125);
        $pdf->Cell(5,5,"2. ",0,0,'L');
        $pdf->SetFont('','U');
        $pdf->Cell(10,5,"Kami telah mengetahui dan menyetujui spesifikasi uji tersebut yang digunakan sebagai referensi uji.",0,0,'L');
        $pdf->Ln(4);
        $pdf->setX(10.00125);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"     We had fully known and agreed to the specification as stated above for test reference.",0,0,'L');
        $pdf->Ln(6);
        $pdf->setX(10.00125);
        $pdf->Cell(5,5,"3. ",0,0,'L');
        $pdf->SetFont('','U',9.8);
        $pdf->Cell(10,5,"Kami menjamin bahwa merek/pabrik dan model/tipe perangkat yang kami produksi/pasarkan sama dengan yang diujikan.",0,0,'L');
        $pdf->Ln(4);
        $pdf->setX(10.00125);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"     Ensuring that we reproduce/distribute the similar brand/factory and model/type with the tested item.",0,0,'L');
        $pdf->Ln(6);
        $pdf->setX(10.00125);
        $pdf->Cell(5,5,"4. ",0,0,'L');
        $pdf->SetFont('','U');
        $pdf->Cell(10,5,"Kami menyatakan bahwa perangkat yang akan diuji sesuai dengan dokumen perangkat. Apabila perangkat",0,0,'L'); $pdf->Ln(4);
        $pdf->Cell(5,5,"terbukti tidak benar/tidak sah, maka permohonan dinyatakan batal dan dikenakan sanksi penundaan",0,0,'L'); $pdf->Ln(4);
        $pdf->Cell(5,5,"permohonan registrasi uji berikutnya.",0,0,'L');$pdf->Ln(4);
        
        $pdf->SetFont('','I');
        $pdf->Cell(5,5,"We certified the tested item is in accordance with device's document/data sheet. If the tested item is proven to be",0,0,'L'); $pdf->Ln(4);
        $pdf->Cell(5,5,"incompatible/invalid, it shall thereupon be canceled, in addition we wil be subjected to a postponement of the",0,0,'L');$pdf->Ln(4);
        $pdf->Cell(5 ,5,"application for the next testing registration.",0,0,'L');$pdf->Ln(8);
        $pdf->setX(10.00125);
    /*End Data Pemohon*/
    
    /*Footer Manual*/
        /*$pdf->SetFont('helvetica','',10);
        $pdf->Cell(150,5,"     Bandung,",0,0,'R');
        $pdf->Ln(18);
        $pdf->SetFont('','U');
        $pdf->Cell(185,5,"                                        ",0,0,'R');
        $pdf->SetFont('helvetica','',8);
        $pdf->Ln(6);
        $pdf->SetFont('','U');
        $pdf->Cell(185,5,"NAMA PEMOHON & CAP PERUSAHAAN",0,0,'R');
        $pdf->Ln(4);
        $pdf->SetFont('','I');
        $pdf->Cell(185,5,"APPLICANT'S NAME & COMPANY STAMP",0,0,'R');
        $pdf->Ln(6);
        $pdf->SetFont('','U');
        $pdf->Cell(10,5,"User Relation, Divisi Digital Business, Telp. 62-22-4571050, 4571101 Fax. 62-22-2012255",0,0,'L');
        $pdf->Ln(4);
        $pdf->SetFont('','I');
        $pdf->Cell(10,5,"Divisi Digital Business, User Relation, Phone. 62-22-4571050, 4571101 Fax. 62-22-2012255",0,0,'L');
        $pdf->Ln(6);
        $pdf->SetFont('helvetica','',8);
        if($data[0]['initPengujian'] == 'QA'){
            $pdf->Cell(185,5,"IAS02/F/001 Versi 01",0,0,'R');		
        }
        else if($data[0]['initPengujian'] == 'TA'){
            $pdf->Cell(185,5,"IAS02/F/002 Versi 01",0,0,'R');		
        }
        else if($data[0]['initPengujian'] == 'VT'){
            $pdf->Cell(185,5,"IAS02/F/003 Versi 01",0,0,'R');		
        }
        else if($data[0]['initPengujian'] == 'CAL'){
            $pdf->Cell(185,5,"IAS02/F/004 Versi 01",0,0,'R');		
        }*/
    /*End Footer Manual*/
        $pdf->Output();
        exit;
    }
}

