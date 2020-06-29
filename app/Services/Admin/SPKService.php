<?php

namespace App\Services\Admin;

class SPKService
{
    public function collectionToArrayAndApendPayment($data)
    {
        $examsArray = []; 

        // Define the Excel spreadsheet headers
        $examsArray = 
            array(
                array(
                    'Tipe Pengujian',
                    'Tanggal SPK',
                    'Nomor SPK',
                    'Nama Perusahaan',
                    'Nama Perangkat',
                    'Status')
            ); 

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($data as $row) {
            switch ($row->FLOW_STATUS) {
                case 1:
                    $status = 'Draft SPK';
                    break;
                case 2:
                    $status = 'SPK dikirim ke Manajer UREL';
                    break;
                case 3:
                    $status = 'SPK disetujui Manajer UREL';
                    break;
                case 4:
                    $status = 'SPK disetujui SMPIA';
                    break;
                case 5:
                    $status = 'SPK disetujui Manager Lab';
                    break;
                case 6:
                    $status = 'Proses Uji';
                    break;
                case 7:
                    $status = 'SPK ditolak Manajer UREL';
                    break;
                case 8:
                    $status = 'SPK ditolak SMPIA';
                    break;
                case 9:
                    $status = 'SPK ditolak Manager Lab';
                    break;
                case 10:
                    $status = 'TE meminta revisi target uji';
                    break;
                case 11:
                    $status = 'Laporan dikirim ke Manajer Lab';
                    break;
                case 12:
                    $status = 'Laporan disetujui Manajer Lab';
                    break;
                case 13:
                    $status = 'Laporan disetujui SMPIA';
                    break;
                case 14:
                    $status = 'Laporan disetujui Manajer UREL';
                    break;
                case 15:
                    $status = 'Laporan dikembalikan Manajer Lab';
                    break;
                case 16:
                    $status = 'Laporan dikembalikan SMPIA';
                    break;
                case 17:
                    $status = 'Laporan dikembalikan Manajer UREL';
                    break;
                case 18:
                    $status = 'Laporan ditolak EGM';
                    break;
                case 19:
                    $status = 'Selesai SPK';
                    break;
                case 20:
                    $status = 'Selesai Uji';
                    break;
                case 21:
                    $status = 'Selesai Sidang';
                    break;
                case 22:
                    $status = 'Sidang Ditunda';
                    break;
                case 23:
                    $status = 'Draft Laporan';
                    break;
                
                default:
                    $status = '';
                    break;
            }
            
            $examsArray[] = [
                $row->TESTING_TYPE,
                $row->spk_date,
                $row->SPK_NUMBER,
                $row->COMPANY_NAME,
                $row->DEVICE_NAME,
                $status
            ];

            

            
        }
        return $examsArray;
    }
}