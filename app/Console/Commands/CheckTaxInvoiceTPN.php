<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\STELSales;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class CheckTaxInvoiceTPN extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkTaxInvoiceTPN';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Tax Invoice from Purchase of STEL(s) in TPN';

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
        $stel = STELSales::where('faktur_file', '')->whereNotNull('INVOICE_ID')->get();
        if(count($stel)>0){
            $client = new Client([
                'headers' => ['Authorization' => config("app.gateway_tpn")],
                'base_uri' => config("app.url_api_tpn"),
                'timeout'  => 60.0,
                'http_errors' => false
            ]);

            $updated_count = 0;
            foreach ($stel as $data) {
                $STELSales = STELSales::where("id", $data->id)->first();

                if($STELSales){
                    try {
                        $INVOICE_ID = $STELSales->INVOICE_ID;
                        $res_invoice = $client->request('GET', 'v1/invoices/'.$INVOICE_ID);
                        $invoice = json_decode($res_invoice->getBody());
                        
                        if($invoice && $invoice->status == true){
                            $status_invoice = $invoice->data->status_invoice;
                            $status_faktur = $invoice->data->status_faktur;
                            if($status_invoice == "approved" && $status_faktur == "received"){
                                /*SAVE FAKTUR PAJAK*/
                                $name_file = 'faktur_stel_'.$INVOICE_ID.'.pdf';

                                $path_file = public_path().'/media/stel/'.$data->id;
                                if (!file_exists($path_file)) {
                                    mkdir($path_file, 0775);
                                }

                                $response = $client->request('GET', 'v1/invoices/'.$INVOICE_ID.'/taxinvoice/pdf');
                                $stream = (String)$response->getBody();

                                if(file_put_contents($path_file.'/'.$name_file, "Content-type: application/octet-stream;Content-disposition: attachment ".$stream)){
                                    $STELSales->faktur_file = $name_file;
                                    // $STELSales->save();
                                    $updated_count = $STELSales->save() ? $updated_count += 1 : $updated_count;
                                }
                            }
                        }
                    } catch(Exception $e){
                        return null;
                    }
                }
            }
            $this->info('checkTaxInvoiceTPN Command Run successfully! '.$updated_count.'/'.count($stel).' updated.');
        }else{
            $this->info('checkTaxInvoiceTPN Command Run successfully! Nothing to update.');
        }
        // $this->line('Display this on the screen');
        // $this->error('Something went wrong!');
    }
}
