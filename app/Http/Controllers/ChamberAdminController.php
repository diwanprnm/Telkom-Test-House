<?php
namespace App\Http\Controllers;

// Laravel
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use Auth;

// Services
use App\Services\Querys\QueryFilter;
use App\Services\Logs\LogService;

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
        $rentChamber->delivered = $this->getChamberByPaymentStatus(2)->paginate($paginate, ['*'], 'pageDelivered');
        
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
        $chamber->end_date = $request->input('end_date', null);
        $chamber->spb_number = $id;
        $chamber->spb_date = $chamber->spb_date ?? $request->input('spb_date');
        $chamber->price = (int) preg_replace("/[^0-9]/", "", $request->input('price', 0) );
        $chamber->tax = $chamber->price * 0.1;
        $chamber->total = $chamber->price * 1.1;
        $chamber->duration = $chamber->end_date ? 2 : 1;
        $chamber->updated_by = Auth::user()->id;
        
        /* 
            Jika Pembayaran Berubah dan ada billing_id, redirect back dan alert tidak bisa mengubah harga
        */

        if($chamber->payment_status == 0){ //jika pembayaran belum paid buatkan draftnya
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
        if ($request->input('end_date')){
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
            ->join('companies', 'companies.id', '=', 'chamber.company_id')
            ->select(
                'chamber.id as id',
                'chamber.start_date as start_date',
                'chamber.end_date as end_date',
                'chamber.duration as duration',
                'chamber.invoice as invoice',
                'chamber.total as total',
                'chamber.payment_status as payment_status',
                'companies.name as company_name')
            ->where('chamber.payment_status', $paymentStatus)
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
            ->beforeDate(DB::raw('DATE(chamber.start_date)'))
            ->afterDate(DB::raw('DATE(chamber.start_date)'))
            ->getSortedAndOrderedData('chamber.created_at','desc')
            ->getQuery()
        ;
    }

}
