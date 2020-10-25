<?php

namespace App\Http\Controllers\v1;
 
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Response;
use App\Company;
use App\User;
use Carbon\Carbon;


use App\Jobs\SendReminderEmail;
/**
 * @resource Content
 *
 * Class CompanyAPIController,
 * list of all api related to company
 * - GET companies
 */
class ReminderController extends AppBaseController
{   
    public function remider7Day(Request $request)
    {
        $date = Carbon::now()->subDays(7)->format('Y-m-d');
        $SPBs = \App\SPB::getSPBFromDate($date);

        //foreach ($SPBs as $SPB){
            $emailContent = $this->setData($SPBs[0], '7');
            return view('emails.reminderSPB')->with('data', $emailContent);
            //$this->dispatch(new SendReminderEmail($emailContent)); -
        //}
    }


    private function api_get_payment_methods(){
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json', 
                'Authorization' => config('app.gateway_tpn')
            ],
            'base_uri' => config('app.url_api_tpn'),
            'timeout'  => 60.0,
            'http_errors' => false,
            'verify' => false
        ]);
        try {
            $res_payment_method = $client->get("v1/products/".config('app.product_id_tth')."/paymentmethods")->getBody();
            return json_decode($res_payment_method);
        } catch(Exception $e){ return null;
        }
    }

    private function setData($SPB, $remain){
        return array(
            'customerName' => $SPB->customerName,
            'SPBNumber' => $SPB->spbNumber,
            'remainingDay' => $remain,
            'dueDate' => Carbon::now()->addDays((14-(int)$remain))->format('Y-m-d'),
            'dueHour' => Carbon::createFromFormat('Y-m-d H:i:s', $SPB->createdAt)->format('H:i:s'),
            'paymentMethod' => $SPB->paymentMethod,
            'price' => "Rp " . number_format($SPB->price,2,',','.'),
            'includePPH' => $SPB->includePPH,
            'howToPay' => $this->api_get_payment_methods()
        );
    }


    /*
     * How To recreate data
     * 
     * ### create date -7 format
     * $date = Carbon\Carbon::now()->subDays(7)->format('Y-m-d');
     * 
     * ### create company, user, examination, spb
     * $company = factory(App\Company::class)->create();
     * factory(App\User::class)->create(['role_id'=> 2, 'company_id' => $company->id]);
     * $examination = factory(\App\Examination::class)->create(['company_id' => $company->id]);
     * factory(\App\SPB::class)->create(['examination_id' => $examination->id,'created_at' => $date]);
     * 
     * ### query data
     * $dbData = DB::table('spb')->select( 'users.name as customerName', 'spb.id as spbNumber', 'spb.created_at as createdAt', 'examinations.payment_method as paymentMethod', 'examinations.price as price', 'examinations.include_pph as includePPH')->join('examinations', 'examinations.id', '=', 'spb.examination_id')->join('companies', 'examinations.company_id', '=', 'companies.id')->join('users', 'users.company_id', '=', 'companies.id')->whereDate('spb.created_at', '=' ,$date)->get();
     * 
    */
	 
}
