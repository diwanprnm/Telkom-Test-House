<?php

namespace App\Services\Admin;

class SPKService
{
    public function collectionToArrayAndApendPayment($data)
    {
        $daftarStatus = array(
            '',
            'Draft SPK',
            'SPK dikirim ke Manajer UREL',
            'SPK disetujui Manajer UREL',
            'SPK disetujui SMPIA',
            'SPK disetujui Manager Lab',
            'Proses Uji',
            'SPK ditolak Manajer UREL',
            'SPK ditolak SMPIA',
            'SPK ditolak Manager Lab',
            'TE meminta revisi target uji',
            'Laporan dikirim ke Manajer Lab',
            'Laporan disetujui Manajer Lab',
            'Laporan disetujui SMPIA',
            'Laporan disetujui Manajer UREL',
            'Laporan dikembalikan Manajer Lab',
            'Laporan dikembalikan SMPIA',
            'Laporan dikembalikan Manajer UREL',
            'Laporan ditolak EGM',
            'Selesai SPK',
            'Selesai Uji',
            'Selesai Sidang',
            'Sidang Ditunda',
            'Draft Laporan'
        );

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
          
            if (isset($daftarStatus[$row->FLOW_STATUS])){
                $status = $daftarStatus[$row->FLOW_STATUS];
            }else {
                $status = $daftarStatus[0];
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