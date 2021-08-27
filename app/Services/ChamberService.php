<?php
namespace App\Services;

// SELF
use App\Chamber;
use App\Chamber_detail;

class ChamberService
{
    public function createPdf($id, $method = null)
    {   
        //Get Data
        $chamber = Chamber::select(
                'chamber.id as id',
                'chamber.invoice',
                'companies.name as companyName',
                'companies.email as email',
                'users.name as userName',
                'start_date',
                'end_date',
                'price', 'tax', 'total'
            )
            ->join("users","users.id","=","chamber.user_id")
            ->join("companies","companies.id","=",'users.company_id')
            ->where('chamber.id',$id)
            ->first()
        ;
            
        // Setup PDF Data formating
        $monthNameId = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        $start_date = date_create($chamber->start_date);
        $chamber->printDate = date('d') .' '. $monthNameId[(int)date('m')-1] .' '. date('Y')  ;
        $chamber->startDate = date_format($start_date, 'd') .' '. $monthNameId[(int)date_format($start_date, 'm')-1] .' '. date_format($start_date, 'Y');
        if ($chamber->duration > 1)
        {
            $end_date = date_create($chamber->end_date);
            $chamber->endDate = date_format($end_date, 'd') .' '. $monthNameId[(int)date_format($end_date, 'm')-1] .' '. date_format($end_date, 'Y');
            $chamber->startDate .= ', dan';
        }

        /** SETUP PDF RETURN METHOD
         * null         => send pdf to browser
         * 'getSteam'   => return pdf stream
         **/ 
        $chamber->method = $method;

        $PDF = new \App\Services\PDF\PDFService();
		return $PDF->cetakTiketChamber($chamber);
    }
}