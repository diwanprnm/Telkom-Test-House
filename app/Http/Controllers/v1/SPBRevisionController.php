<?php

namespace App\Http\Controllers\v1;
 
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Response;
use Carbon\Carbon;
use App\Jobs\SendSPBRevisionController;


class SPBRevisionController extends AppBaseController
{   
    public function spbRevision(Request $request)
    {
        //get data
        
        /**
         * for each data
         * 
         * // format data
         * $data = $this->setData($data);
         * 
         * //dispatch job
         * 
         */
        
        
    }

    private function setData($data)
    {
        return array(
            'subject' => 'Revisi Surat Pemberitahuan Biaya (SPB) untuk [No. Registrasi]', /*#ini#*/
            'customerName' => 'customerName',
            'customerEmail' => 'customerEmail',
            'registrationNumber' => 'registrationNumber',
            'spbNumber' => 'spbNumber',
            'spbRevisionNumber' => 'spbRevisionNumber',
            'paymentLink' => 'paymentLink',
            'paymentMethod' => 'paymentMethod',
            'howToPay' => 'howToPay'
        );
    }
	 
}
