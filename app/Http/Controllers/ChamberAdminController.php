<?php
namespace App\Http\Controllers;

// Laravel
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use Auth;
use Exception;

// Services
use App\Services\Querys\QueryFilter;
use App\Services\Logs\LogService;
use App\Services\FileService;

// Models
use App\Chamber;
use App\Chamber_detail;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class ChamberAdminController extends Controller
{

    private const HEADERS = 'headers';
	private const CONTENT_TYPE = 'Content-type';
	private const JSON_HEADER = 'application/json';
	private const AUTHORIZATION = 'Authorization';
	private const APP_GATEWAY_TPN_3 = 'app.gateway_tpn_3';
	private const BASE_URI = 'base_uri';
	private const APP_URI_API_TPN = 'app.url_api_tpn';
	private const TIMEOUT = 'timeout';
	private const HTTP_ERRORS = 'http_errors';

    private $request;

    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index(Request $request)
    {
        // Initial var
        $paginate = 10;
        $search = trim(strip_tags($request->input('search','')));
        $statuses = ['unpaid', 'paid', 'delivered'];
        $this->request = $request;

        // Get data
        $rentChamber = new \stdClass();        
        $rentChamber->unpaid = $this->getChamberByPaymentStatus(0)->paginate($paginate, ['*'], 'pageUnpaid');
        $rentChamber->paid = $this->getChamberByPaymentStatus(1)->paginate($paginate, ['*'], 'pagePaid');
        $rentChamber->delivered = $this->getChamberByPaymentStatus(3)->paginate($paginate, ['*'], 'pageDelivered');
        
        // return View
        return view('admin.chamber.index')
            ->with('data', $rentChamber)
            ->with('statuses', $statuses)
        ;
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {   
        // Get Data
        $chambers = DB::table('chamber')
            ->select('chamber.*', 'companies.name as company_name')
            ->join('companies', 'companies.id', '=', 'chamber.company_id')
            ->where('chamber.id', $id)
            ->first()
        ;
        $chamber_details = Chamber_detail::where('chamber_id',$chambers->id)->get();

        // Return View
        return view('admin.chamber.show')
            ->with('data', $chambers)
            ->with('dataDetail', $chamber_details)
            ->with('id_kuitansi', 'INIIDKU')
            ->with('id_sales', 'INIIDSALES')
            ->with('faktur_file', 'faktur_file')
        ;
    }

    public function generateKuitansi(Request $request) {
        $this->validate($request, [
            'INVOICE_ID' => 'required',
        ]);

        $INVOICE_ID = $request->input('INVOICE_ID');
        
        $client = new Client([
            'headers' => ['Authorization' => config("app.gateway_tpn_3")],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
        ]);

        $data = Chamber::where("INVOICE_ID", $INVOICE_ID)->first();
        if(!$data){return "Data Pembelian Tidak Ditemukan!";}

        try {
            $res_invoice = $client->request('GET', 'v3/invoices/'.$INVOICE_ID);
            $invoice = json_decode($res_invoice->getBody());
            
            if($INVOICE_ID && $invoice && $invoice->status){
                $status_invoice = $invoice->data->status_invoice;
                if($status_invoice == "approved"){
                    $status_faktur = $invoice->data->status_faktur;
                    if($status_faktur == "received"){
                        /*SAVE KUITANSI*/
                        $name_file = 'kuitansi_chamber_'.$INVOICE_ID.'.pdf';
                        $path_file = "chamber/".$data->id."/";
                        $response = $client->request('GET', 'v3/invoices/'.$INVOICE_ID.'/exportpdf');
                        $stream = (String)$response->getBody();
                        
                        $fileService = new FileService();
                        $fileProperties = array(
                            'path' => $path_file,
                            'fileName' => $name_file
                        );
        
                        $isUploaded = $fileService->uploadFromStream($stream, $fileProperties);
        
                        if($isUploaded){
                            $data->kuitansi_file = $name_file;
                            $data->save();
                            $result = "Kuitansi Berhasil Disimpan.";
                        }else{
                            $result = "Gagal Menyimpan Kuitansi!";
                        }
                    }else{
                        return $invoice->data->status_faktur;
                    }
                }else{
                    switch ($status_invoice) {
                        case 'invoiced':
                            $result = "Invoice Baru Dibuat.";
                            break;
                        
                        case 'returned':
                            $result = $invoice->data->$status_invoice->message;
                            break;
                        
                        default:
                            $result = "Invoice sudah dikirim ke DJP.";
                            break;
                    }
                }
            }else{
                $result = "Data Invoice Tidak Ditemukan!";        
            }
        } catch(Exception $e){ $result = null;
        }

        return $result;
    }

    public function generateTaxInvoice(Request $request) {
        $this->validate($request, [
            'INVOICE_ID' => 'required',
        ]);

        $INVOICE_ID = $request->input('INVOICE_ID');
        
        $client = new Client([
            'headers' => ['Authorization' => config("app.gateway_tpn_3")],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
        ]);

        $data = Chamber::where("INVOICE_ID", $INVOICE_ID)->first();
        if(!$data){return "Data Pembelian Tidak Ditemukan!";}
        
        try {
            /* GENERATE NAMA FILE FAKTUR */
                $filename = $data->pay_date.'_'.$data->INVOICE_ID;
            /* END GENERATE NAMA FILE FAKTUR */
            $res_invoice = $client->request('GET', 'v3/invoices/'.$INVOICE_ID);
            $invoice = json_decode($res_invoice->getBody());
            
            if($INVOICE_ID && $invoice && $invoice->status){
                $status_invoice = $invoice->data->status_invoice;
                if($status_invoice == "approved"){
                    $status_faktur = $invoice->data->status_faktur;
                    if($status_faktur == "received"){
                        /*SAVE FAKTUR PAJAK*/
                        $name_file = 'faktur_chamber_'.$filename.'.pdf';
                        $path_file = "chamber/".$data->id."/";
                        $response = $client->request('GET', 'v3/invoices/'.$INVOICE_ID.'/exportpdf');
                        $stream = (String)$response->getBody();
        
                        $fileService = new FileService();
                        $fileProperties = array(
                            'path' => $path_file,
                            'fileName' => $name_file
                        );
                        $isUploaded = $fileService->uploadFromStream($stream, $fileProperties);
        
                        if($isUploaded){
                            $data->faktur_file = $name_file;
                            $data->save();
                            $result = "Faktur Pajak Berhasil Disimpan.";
                        }else{
                            $result = "Gagal Menyimpan Faktur Pajak!";
                        }
                    }else{
                        $result = $invoice->data->status_faktur;
                    }
                }else{
                    switch ($status_invoice) {
                        case 'invoiced':
                            $result = "Faktur Pajak belum Tersedia, karena Invoice Baru Dibuat.";
                            break;
                        
                        case 'returned':
                            $result = $invoice->data->$status_invoice->message;
                            break;
                        
                        default:
                        $result = "Faktur Pajak belum Tersedia. Invoice sudah dikirim ke DJP.";
                            break;
                    }
                }
            }else{
                $result = "Data Invoice Tidak Ditemukan!";        
            }
        } catch(Exception $e){
            $result = null;
        }

        return $result;
    }

    public function edit($id)
    {
        // Get Data
        $chambers = DB::table('chamber')
            ->select('chamber.*', 'companies.name as company_name')
            ->join('companies', 'companies.id', '=', 'chamber.company_id')
            ->where('chamber.id', $id)
            ->first()
        ;
        $chamber_details = Chamber_detail::where('chamber_id',$chambers->id)->get();

        // Return View
        return view('admin.chamber.edit')
            ->with('data', $chambers)
            ->with('dataDetail', $chamber_details)
        ;
    }

    public function update(Request $request, $id)
    {
        // Form Vaildation
        $this->validate($request, [
            'start_date' => 'required',
            'price' => 'required',
        ]);

        // Update Record
        $chamber = Chamber::with('user')->with('company')->where('id', $id)->first();
        if (!$chamber){
            Session::flash('error', 'Undefined Data');
            return redirect('/admin/chamber/');
        }

        $chamberOld = clone $chamber;
        $chamberDetail = Chamber_detail::where('chamber_id',$id)->delete();

        $chamber->start_date = $request->input('start_date');
        $chamber->end_date = $chamber->duration == 1 ? $chamber->start_date : $request->input('end_date', $chamber->start_date);
        $chamber->spb_number = $id;
        $chamber->spb_date = $chamber->spb_date ?? $request->input('spb_date');
        $chamber->price = (int) preg_replace("/[^0-9]/", "", $request->input('price', 0) );
        $chamber->tax = $chamber->price * 0.11;
        $chamber->total = $chamber->price * 1.11;
        $chamber->updated_by = Auth::user()->id;
        
        if($chamber->payment_status == 0 && !$chamber->PO_ID){ //jika draft pembayaran belum ada, buatkan draftnya
            /* Kirim Draft ke TPN */
                $details [] = 
                [
                    "item" => 'Biaya Sewa Chamber '.$chamber->company->name,
                    "description" => $chamber->end_date ? $chamber->start_date." & ".$chamber->end_date : $chamber->start_date,
                    "quantity" => 1,
                    "price" => ceil($chamber->price),
                    "total" => ceil($chamber->price)
                ]
            ;

            $data_draft = [
                "from" => [
                    "name" => "PT. TELKOM INDONESIA (PERSERO) Tbk",
                    "address" => "Telkom Indonesia Graha Merah Putih, Jalan Japati No.1 Bandung, Jawa Barat, 40133",
                    "phone" => "(+62) 812-2483-7500",
                    "email" => "cstth@telkom.co.id",
                    "npwp" => "01.000.013.1-093.000"
                ],
                "to" => [
                    "name" => $chamber->company->name ? $chamber->company->name : "-",
                    "address" => $chamber->company->address ? $chamber->company->address : "-",
                    "phone" => $chamber->company->phone_number ? $chamber->company->phone_number : "-",
                    "email" => $chamber->user->email ? $chamber->user->email : "-",
                    "npwp" => $chamber->company->npwp_number ? $chamber->company->npwp_number : "-"
                ],
                "product_id" => config("app.product_id_tth_3"), //product_id TTH untuk Chamber
                "details" => $details,
                "created" => [
                    "by" => $chamber->user->name,
                    "reference_id" => '1'
                ],
                "include_tax_invoice" => true,
                "bank" => [
                    "owner" => "Divisi RisTI TELKOM",
                    "account_number" => "131-0096022712",
                    "bank_name" => "BANK MANDIRI",
                    "branch_office" => "KCP KAMPUS TELKOM BANDUNG"         
                ]
            ];
            $purchase = $this->api_purchase($data_draft);
            $chamber->PO_ID = $purchase && $purchase->status ? $purchase->data->_id : null;
            /* END Kirim Draft ke TPN */
        }
        

        $chamber->save();

        // List Dates
        $chamberDetails = [];
        array_push($chamberDetails, $request->input('start_date'));
        if ($chamber->duration > 1){
            array_push($chamberDetails, $request->input('end_date'));
        }

        // Create detail corresponding to List dates
        foreach ($chamberDetails as $date){
            $chamberDetail = new Chamber_detail;
            $chamberDetail->date = $date;
            $chamberDetail->chamber_id = $chamber->id;
            $chamberDetail->save();
        }

        // Create log of Update
        $logService = new LogService();
        $logService->createAdminLog('Update Data Penyewaan Chamber', 'Chamber', $chamberOld );

        // Redirect to view & set messages
        Session::flash('message', 'Chamber data successfully updated');
        return redirect("admin/chamber/$id/edit");
    }

    public function api_purchase($data){
        $client = new Client([
            self::HEADERS => [self::CONTENT_TYPE => self::JSON_HEADER, 
                            self::AUTHORIZATION => config(self::APP_GATEWAY_TPN_3)
                        ],
            self::BASE_URI => config(self::APP_URI_API_TPN),
            self::TIMEOUT  => 60.0,
            self::HTTP_ERRORS => false,
            'verify' => false
        ]);
        try {
            
            $params['json'] = $data;
            $res_purchase = $client->post("v3/draftbillings", $params)->getBody();
            return json_decode($res_purchase);
        } catch(Exception $e){
            return null;
        }
	}

    public function destroy($id)
    {
        //
    }

    public function deleteChamber($id, $reasonOfDeletion)
    {   
        //Get data
        $chamber = Chamber::find($id);
        
        // Filter and Feedback if no data found
        if (!$chamber){
            Session::flash('error', 'Undefined Data');
            return redirect('/admin/chamber/');
        }

        // Delete the record(s)
        $detailChamber = Chamber_detail::where('chamber_id',$id)->delete();
        $chamber->delete();

        // Create Admin Log
        $logService = new LogService();
        $logService->createAdminLog('Hapus Data Penyewaan Chamber', 'Chamber', $chamber, urldecode($reasonOfDeletion) );

        // Feedback succeed
        Session::flash('message', 'Successfully Delete Data');
        return redirect('/admin/chamber/');
    }


    private function getChamberByPaymentStatus($paymentStatus = 0)
    {
        // Initial
        $logService = new LogService();
        $search = $this->request->input('search', '');
        $dataRentChambers = DB::table('chamber')
            ->join('chamber_detail', 'chamber_detail.chamber_id', '=', 'chamber.id')
            ->join('companies', 'companies.id', '=', 'chamber.company_id')
            ->select(
                'chamber.id as id',
                'chamber.start_date as start_date',
                'chamber.end_date as end_date',
                'chamber.duration as duration',
                'chamber.invoice as invoice',
                'chamber.total as total',
                'chamber.payment_status as payment_status',
                'chamber.created_at as created_at',
                'chamber.payment_method as payment_method',
                'chamber.VA_name as VA_name',
                'companies.name as company_name')
            ->where('chamber.payment_status', $paymentStatus)
            ->distinct()
        ;

        // If search something crate log
        if($search){
            $dataRentChambers = $dataRentChambers->where(function($q) use ($search){
                $q->where('chamber.invoice','like','%'.$search.'%')->orWhere('companies.name', 'like', '%'.$search.'%');
            });
            $logService->createLog('Search Rent Chamber','Chamber',json_encode( ['search' => $search] ));
        }

        // Filter the query then return it
        $queryFilter = new QueryFilter($this->request, $dataRentChambers);
        return $queryFilter
            ->beforeDate(DB::raw('DATE(chamber_detail.date)'))
            ->afterDate(DB::raw('DATE(chamber_detail.date)'))
            ->getSortedAndOrderedData('chamber.created_at','desc')
            ->getQuery()
        ;
    }

}
