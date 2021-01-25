<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\STELSales;
use App\Services\FileService;

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
                        $res_invoice = $client->request('GET', 'v3/invoices/'.$INVOICE_ID);
                        $invoice = json_decode($res_invoice->getBody());
                        
                        if($invoice && $invoice->status == true){
                            $status_invoice = $invoice->data->status_invoice;
                            $status_faktur = $invoice->data->status_faktur;
                            if($status_invoice == "approved" && $status_faktur == "received"){
                                /*
                                 * SAVE FAKTUR PAJAK
                                 * Ket: Pengaplikasian upload ke minio dari stream API
                                 * Tgs: Belum ditest
                                 */
                                $name_file = 'faktur_stel_'.$INVOICE_ID.'.pdf';
                                $path_file = 'stel/'.$data->id;
                                $response = $client->request('GET', 'v3/invoices/'.$INVOICE_ID.'/taxinvoice/pdf');
                                $stream = (String)$response->getBody();

                                $fileService = new FileService();
                                $fileProperties = array(
                                    'path' => $path_file,
                                    'fileName' => $name_file
                                );
                                $isUploaded = $fileService->uploadFromStream($stream, $fileProperties);

                                if($isUploaded){
                                    $STELSales->faktur_file = $name_file;
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
        // $this->line('Display this on the screen')
        // $this->error('Something went wrong!')
    }
}
