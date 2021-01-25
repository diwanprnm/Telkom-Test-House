<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\STELSales;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class CheckBillingTPN extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkBillingTPN';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Billing Payment Status from Purchase of STEL(s) in TPN';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $stel = STELSales::where('payment_status', 0)->whereNotNull('BILLING_ID')->get();
        if(count($stel)>0){
            $updated_count = 0;
            foreach ($stel as $data) {
                $STELSales = STELSales::find($data->id);

                $data_invoices = [
                    "billing_id" => $data->BILLING_ID,
                    "created" => [
                        "by" => "SUPERADMIN UREL",
                        "reference_id" => "1"
                    ]
                ];

                $invoice = $this->api_invoice($data_invoices);
                $STELSales->INVOICE_ID = $invoice && $invoice->status == true ? $invoice->data->_id : null;
                $STELSales->payment_status = $invoice && $invoice->status == true ? 1 : 0;

                $updated_count = $invoice && $invoice->status == true ? $updated_count += 1 : $updated_count;
                $updated_count = $STELSales->save() ? $updated_count : $updated_count -= 1;
            }
            $this->info('checkBillingTPN Command Run successfully! '.$updated_count.'/'.count($stel).' updated.');
        }else{
            $this->info('checkBillingTPN Command Run successfully! Nothing to update.');
        }
        // $this->line('Display this on the screen');
        // $this->error('Something went wrong!');
    }

    public function api_invoice($data_invoices){
        $client = new Client([
            'headers' => ['Authorization' => config("app.gateway_tpn")],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
        ]);
        try {
            $param_invoices['json'] = $data_invoices;
            $res_invoices = $client->post("v3/invoices", $param_invoices)->getBody()->getContents();
            $invoice = json_decode($res_invoices);

            /*get
                $invoice->status; //if true lanjut, else panggil lagi API ny
                $invoice->data->_id; //INVOICE_ID
            */

            return $invoice;
        } catch(Exception $e){
            return null;
        }
    }
}
