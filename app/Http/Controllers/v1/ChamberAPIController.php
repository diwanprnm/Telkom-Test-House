<?php

namespace App\Http\Controllers\v1;
 
//LARAVEL
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use Response;

//3rd Party
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Events\Notification;
use App\NotificationTable;
use App\Services\NotificationService;
use App\Services\Logs\LogService;
use App\Services\FileService;

//SELF
use App\Chamber;
use App\Chamber_detail;
use App\Services\ChamberService;

class ChamberAPIController extends AppBaseController
{
    
    public function getDateRented()
    {
      $rentedDates = DB::table('chamber_detail')
        ->join('chamber', 'chamber.id', '=', 'chamber_detail.chamber_id')
        ->select('chamber_id as id', 'date')
        ->whereDate('chamber_detail.date', '>=', Carbon::now()->startOfMonth())
        ->get()
      ;
      return response()->json($rentedDates);
    }

    public function checkBillingTPN()
    {
      $main_chamber = Chamber::where('payment_status', 0)->whereNotNull('BILLING_ID')->whereNull('INVOICE_ID')->get();
      if(count($main_chamber)>0){
        $updated_count = 0;
        foreach ($main_chamber as $data) {
            $chamber = Chamber::find($data->id);
            $oldStel = $chamber;
            
            $data_invoices = [
                "billing_id" => $data->BILLING_ID,
                "created" => [
                    "by" => "SUPERADMIN UREL",
                    "reference_id" => "1"
                ]
            ];

            $billing = $this->api_billing($data->BILLING_ID);
            if($billing && $billing->status == true && $billing->data->status_paid == 'paid'){
                $chamber->cust_price_payment = $billing->data->draft->final_price;
                $chamber->payment_status = 1;
                $chamber->pay_date = $billing->data->paid->at;
                $chamber->approved_date = $billing->data->paid->at;

                $invoice = $this->api_invoice($data_invoices);
                $chamber->INVOICE_ID = $invoice && $invoice->status == true ? $invoice->data->_id : null;

                $updated_count = $invoice && $invoice->status == true ? $updated_count += 1 : $updated_count;

                if($chamber->save()){
                    $data = array( 
                        "from"=>"admin-digimon",
                        "to"=>$chamber->created_by,
                        "message"=>"Pembayaran Sewa Chamber Telah diterima",
                        "url"=>"chamber_history",
                        "is_read"=>0,
                        "created_at"=>date("Y-m-d H:i:s"),
                        "updated_at"=>date("Y-m-d H:i:s")
                    );

                    // $notificationService = new NotificationService();
                    // $data['id'] = $notificationService->make($data);
                    // event(new Notification($data));

                    // $logService = new LogService();
                    // $logService->createLog('Update Status Pembayaran Chamber', 'Chamber', $oldStel );
                }else{
                    $updated_count -= 1;
                }
            }
        }
        return 'checkBillingCHMBTPN Command Run successfully! '.$updated_count.'/'.count($main_chamber).' updated.';
      }else{
        return 'checkBillingCHMBTPN Command Run successfully! Nothing to update.';
      }
    }

    public function api_billing($id_billing){
      $client = new Client([
          'headers' => ['Authorization' => config("app.gateway_tpn_3")],
          'base_uri' => config("app.url_api_tpn"),
          'timeout'  => 60.0,
          'http_errors' => false
      ]);
      try {
          $res_billing = $client->get("v3/billings/".$id_billing."")->getBody()->getContents();
          $billing = json_decode($res_billing);

          return $billing;
      } catch(Exception $e){
          return null;
      }
    }

    public function api_invoice($data_invoices){
      $client = new Client([
          'headers' => ['Authorization' => config("app.gateway_tpn_3")],
          'base_uri' => config("app.url_api_tpn"),
          'timeout'  => 60.0,
          'http_errors' => false
      ]);
      try {
          $param_invoices['json'] = $data_invoices;
          $res_invoices = $client->post("v3/invoices", $param_invoices)->getBody()->getContents();
          $invoice = json_decode($res_invoices);

          return $invoice;
      } catch(Exception $e){
          return null;
      }
    }

    public function checkKuitansiTPN()
    {
      $chamber = Chamber::whereNull('kuitansi_file')->whereNotNull('INVOICE_ID')->get();
      if(count($chamber)>0){
          $client = new Client([
              'headers' => ['Authorization' => config("app.gateway_tpn_3")],
              'base_uri' => config("app.url_api_tpn"),
              'timeout'  => 60.0,
              'http_errors' => false
          ]);

          $updated_count = 0;
          foreach ($chamber as $data) {
              $Chamber = Chamber::where("id", $data->id)->first();
              if($Chamber){
                  try {
                      $INVOICE_ID = $Chamber->INVOICE_ID;
                      $res_invoice = $client->request('GET', 'v3/invoices/'.$INVOICE_ID);
                      $invoice = json_decode($res_invoice->getBody());
                      
                      if($invoice && $invoice->status == true){
                          $status_invoice = $invoice->data->status_invoice;
                          $status_faktur = $invoice->data->status_faktur;
                          if($status_invoice == "approved" && $status_faktur == "received"){
                              /* 
                                * SAVE KUITANSI
                                * Ket: Pengaplikasian upload ke minio dari stream API
                                * Tgs: Belum ditest
                                */
                              $name_file = "kuitansi_chamber_$INVOICE_ID.pdf";
                              $path_file = "chamber/$data->id/";
                              $response = $client->request('GET', 'v3/invoices/'.$INVOICE_ID.'/exportpdf');
                              $stream = (String)$response->getBody();

                              $fileService = new FileService();
                              $fileProperties = array(
                                  'path' => $path_file,
                                  'fileName' => $name_file
                              );
                              $isUploaded = $fileService->uploadFromStream($stream, $fileProperties);

                              if($isUploaded){
                                  $Chamber->kuitansi_file = $name_file;
                                  $Chamber->save();
                                  $updated_count = $Chamber->save() ? $updated_count += 1 : $updated_count;
                              }
                          }
                      }
                  } catch(Exception $e){
                      return null;
                  }
              }
          }
        return 'checkKuitansiCHMBTPN Command Run successfully! '.$updated_count.'/'.count($chamber).' updated.';
      }else{
        return 'checkKuitansiCHMBTPN Command Run successfully! Nothing to update.';
      }
    }

    public function checkTaxInvoiceTPN()
    {
      $chamber = Chamber::whereNull('faktur_file')->whereNotNull('INVOICE_ID')->get();
      if(count($chamber)>0){
        $client = new Client([
            'headers' => ['Authorization' => config("app.gateway_tpn_3")],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
        ]);

        $updated_count = 0;
        foreach ($chamber as $data) {
            $Chamber = Chamber::where("id", $data->id)->first();

            if($Chamber){
                /* GENERATE NAMA FILE FAKTUR */
                    $filename = $Chamber->pay_date.'_'.$Chamber->INVOICE_ID;
                /* END GENERATE NAMA FILE FAKTUR */
                try {
                    $INVOICE_ID = $Chamber->INVOICE_ID;
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
                            $name_file = "faktur_chamber_$filename.pdf";
                            $path_file = "chamber/$data->id/";
                            $response = $client->request('GET', 'v3/invoices/'.$INVOICE_ID.'/taxinvoice/pdf');
                            $stream = (String)$response->getBody();

                            $fileService = new FileService();
                            $fileProperties = array(
                                'path' => $path_file,
                                'fileName' => $name_file
                            );
                            $isUploaded = $fileService->uploadFromStream($stream, $fileProperties);

                            if($isUploaded){
                                $Chamber->faktur_file = $name_file;
                                $updated_count = $Chamber->save() ? $updated_count += 1 : $updated_count;
                            }
                        }
                    }
                } catch(Exception $e){
                    return null;
                }
            }
        }
        return 'checkTaxInvoiceCHMBTPN Command Run successfully! '.$updated_count.'/'.count($chamber).' updated.';
      }else{
        return 'checkTaxInvoiceCHMBTPN Command Run successfully! Nothing to update.';
      }
    }

    public function checkReturnedTPN()
    {
      $main_chamber = Chamber::whereNull('faktur_file')->whereNotNull('INVOICE_ID')->get();
      if(count($main_chamber)>0){
          $client = new Client([
              'headers' => ['Authorization' => config("app.gateway_tpn_3")],
              'base_uri' => config("app.url_api_tpn"),
              'timeout'  => 60.0,
              'http_errors' => false
          ]);

          $updated_count = 0;
          foreach ($main_chamber as $data) {
              $Chamber = Chamber::where("id", $data->id)->first();

              if($Chamber){
                  try {
                      $INVOICE_ID = $Chamber->INVOICE_ID;
                      $res_invoice = $client->request('GET', 'v3/invoices/'.$INVOICE_ID);
                      $invoice = json_decode($res_invoice->getBody());
                      
                      if($invoice && $invoice->status == true){
                          $status_invoice = $invoice->data->status_invoice;
                          if($status_invoice == "returned"){
                              $res_billing = $client->request('GET', 'v3/invoices?filterobjid-billing._id='.$Chamber->BILLING_ID);
                              $billing = json_decode($res_billing->getBody());
                              foreach ($billing->data as $data_billing) {
                                  if($data_billing->status_faktur == "received"){
                                      $Chamber->INVOICE_ID = $data_billing->_id;
                                      $updated_count = $Chamber->save() ? $updated_count += 1 : $updated_count;
                                  }
                              }
                          }
                      }
                  } catch(Exception $e){
                      return null;
                  }
              }
          }
          return 'checkReturnedCHMBTPN Command Run successfully! '.$updated_count.'/'.count($main_chamber).' updated.';
      }else{
          return 'checkReturnedCHMBTPN Command Run successfully! Nothing to update.';
      }
    }

    public function checkDeliveredTPN()
    {
      // Jika sudah dibayar dan tanggal sewa = now() - 1
      $main_chamber = Chamber::whereNotNull('spb_date')
          ->where('payment_status', 1)
          ->whereDate('end_date', '=',Carbon::now()->subDays(1)->format('Y-m-d'))
          ->get()
      ;

      if(count($main_chamber)>0){
          $chamberService = new ChamberService();
          $client = new Client([
              'headers' => ['Authorization' => config("app.gateway_tpn_3")],
              'base_uri' => config("app.url_api_tpn"),
              'timeout'  => 60.0,
              'http_errors' => false
          ]);

          $updated_count = 0;
          $notificationService = new NotificationService();
          $logService = new LogService();
          foreach ($main_chamber as $data) {
              $Chamber = Chamber::with('company')->with('user')->where("id", $data->id)->first();
              if($Chamber){
                  try {
                    //dd("Ticket Chamber ".$Chamber->company->name, $chamberService->createPdf($data->id, 'getStream')); //uncomment untuk cek nama file dan stream
                    // Array Data Ticket Chamber
                    $data_delivered [] = [
                        'name' => "file",
                        'contents' => $chamberService->createPdf($Chamber->id, 'getStream'), //stream generate tiket chamber
                        'filename' => "Ticket Chamber ".$Chamber->company->name //Ticket Chamber PT APA
                    ];

                    /*TPN api_upload*/
                    if($Chamber->BILLING_ID != null){
                      $data_delivered [] = array(
                          'name'=>"delivered",
                          'contents'=>json_encode(['by'=>'Admin', "reference_id" => '1']),
                      );
                      $data_delivered [] = array(
                          'name'=>"type",
                          'contents'=>"bast",
                      );
                      $this->api_upload($data_delivered,$Chamber->BILLING_ID);
                    }

                    //Update Chamber payment Status
                    $Chamber->updated_by = 1; 
                    $Chamber->payment_status = 3; //delivered

                    if($Chamber->save()){
                      $data_notif['id'] = $notificationService->make(array(
                          "from"=> 'admin',
                          "to"=>$Chamber->created_by,
                          "message"=>"Tiket telah diupload",
                          "url"=>'chamber_history',
                          "is_read"=>0,
                      ));
                      // event(new Notification($data_notif));
                      
                      $updated_count++;
                      // Create log
                      $logService->createLog('Upload Tiket Penyewaan Chamber', "Chamber", null);
                    }
                  } catch(Exception $e){
                      return null;
                  }
              }
          }
          return 'checkDeliveredCHMBTPN Command Run successfully! '.$updated_count.'/'.count($main_chamber).' updated.';
      }else{
          return 'checkDeliveredCHMBTPN Command Run successfully! Nothing to update.';
      }
    }

    public function api_upload($data, $BILLING_ID){
      $client = new Client([
          'headers' => ['Authorization' => config("app.gateway_tpn_3")],
          'base_uri' => config("app.url_api_tpn"),
          'timeout'  => 60.0,
          'http_errors' => false
      ]);
      try {
          $params['multipart'] = $data;
          $res_upload = $client->post("v3/billings/".$BILLING_ID."/deliver", $params)->getBody(); //BILLING_ID
          return json_decode($res_upload);

      } catch(Exception $e){
          return null;
      }
    }

    public function cronDeleteChamber()
    {
        //get list of tobe deleted chamber
        $chamberNotCreatingVa = $this->getChamberNotCreatingVa();
        $chamberVaExpired = $this->getChamberVaExpired();
        $chamberIdList = array_unique(array_merge($chamberNotCreatingVa,$chamberVaExpired));

        //delete chamber & chamber_detail in list
        Chamber_detail::whereIn('chamber_id', $chamberIdList)->delete();
        Chamber::whereIn('id', $chamberIdList)->delete();

        return count($chamberIdList) > 0 ? 'cronDeleteChamber Command Run successfully! '.count($chamberIdList) : 'cronDeleteChamber Command Run successfully! Nothing to'.' deleted.';
    }

    private function getChamberNotCreatingVa()
    {
        //get list of chamber that not yet creating va after 2 days (2x24) of confirmation date by admin
        $chamberIdList = [];
        $chambers = Chamber::where('payment_status', 0)
            ->whereNull('VA_number')
            ->whereDate('spb_date', '<', Carbon::now()->subDays(2)->format('Y-m-d'))
            ->get()
        ;
        foreach ($chambers as $chamber){
            $chamberIdList[] = $chamber->id;
        }
        return $chamberIdList;
    }

    private function getChamberVaExpired()
    {
        //get list of chamber that va is expired after 1 day (1x24h) from VA_expired date
        $chamberIdList = [];
        $chambers = Chamber::where('payment_status', 0)
            ->whereNotNull('VA_number')
            ->whereDay('VA_expired', '<', Carbon::now()->subDays(1)->format('Y-m-d'))
            ->get()
        ;
        foreach ($chambers as $chamber){
            $chamberIdList[] = $chamber->id;
        }
        return $chamberIdList;
    }
}
