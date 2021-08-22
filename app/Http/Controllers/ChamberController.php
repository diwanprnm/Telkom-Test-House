<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Http\Requests;
use App\Chamber;
use App\Chamber_detail;
use App\Services\ExaminationService;
use App\Services\ChamberService;
use Auth;
use Session;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class ChamberController extends Controller
{
    public function __construct()
    {
		parent::__construct();
		$this->middleware('client', ['only' => [
            'index',
			'purchase_history',
			'pembayaran',
			'payment_confirmation',
            'store'
        ]]);
	}
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = "pengujian";
        return view('client.process.rent_chamber')
            ->with('page', $page);
    }

    public function purchase_history(Request $request)
    {
        $currentUser = Auth::user();
        if(!$currentUser){ return redirect('login');}
        $paginate = 10; 
        $tab = $request->input('tab') ? $request->input('tab') : 'unpaid';
        $select = array("chamber.*","users.name"); 
        $query = Chamber::select($select)->distinct('chamber.id')
            ->join("chamber_detail","chamber.id","=","chamber_detail.chamber_id")
            ->join("users","users.id","=","chamber.user_id")
            ->join("companies","companies.id","=",'users.company_id')
            ->where('users.company_id',$currentUser->company_id)
        ;
        $query_unpaid = Chamber::select($select)->distinct('chamber.id')
            ->join("chamber_detail","chamber.id","=","chamber_detail.chamber_id")
            ->join("users","users.id","=","chamber.user_id")
            ->join("companies","companies.id","=",'users.company_id')
            ->where('users.company_id',$currentUser->company_id)
            ->where("chamber.payment_status", 0)
        ;
        $query_paid = Chamber::select($select)->distinct('chamber.id')
            ->join("chamber_detail","chamber.id","=","chamber_detail.chamber_id")
            ->join("users","users.id","=","chamber.user_id")
            ->join("companies","companies.id","=",'users.company_id')
            ->where('users.company_id',$currentUser->company_id)
            ->where("chamber.payment_status", 1)
        ;
        $query_delivered = Chamber::select($select)->distinct('chamber.id')
            ->join("chamber_detail","chamber.id","=","chamber_detail.chamber_id")
            ->join("users","users.id","=","chamber.user_id")
            ->join("companies","companies.id","=",'users.company_id')
            ->where('users.company_id',$currentUser->company_id)
            ->where("chamber.payment_status", 3)
        ;

        $data = $query->orderBy("chamber.created_at", 'desc')->paginate($paginate);
        $data_unpaid = $query_unpaid->orderBy("chamber.created_at", 'desc')->paginate($paginate, ['*'], 'pageUnpaid');
        $data_paid = $query_paid->orderBy("chamber.created_at", 'desc')->paginate($paginate, ['*'], 'pagePaid');
        $data_delivered = $query_delivered->orderBy("chamber.created_at", 'desc')->paginate($paginate, ['*'], 'pageDelivered');

        $page = "chamber_history";
        return view('client.chamber.purchase_history') 
        ->with('tab', $tab)
        ->with('page', $page)
        ->with('data', $data)
        ->with('data_unpaid', $data_unpaid)
        ->with('data_paid', $data_paid)
        ->with('data_delivered', $data_delivered);
    } 

    public function pembayaran($id, Request $request)
    {
		$examinationService = new ExaminationService();
		$currentUser = Auth::user();
		$user_id = ''.$currentUser['attributes']['id'].'';
		$company_id = ''.$currentUser['attributes']['company_id'].'';
        if ($currentUser){
			$chamber = Chamber::find($id);
			if($chamber->payment_method != 0){
				return redirect('payment_confirmation_chamber/'.$chamber->id);
			}
            $message = null;
			
            return view('client.chamber.pembayaran')
                ->with('message', $message)
                ->with('data', $chamber)
                ->with('id', $id)
				->with('payment_method', $examinationService->api_get_payment_methods())
                ->with('user_id', $user_id);
        }else{
			return redirect('login');
		}
    }

    public function doCheckout(Request $request){
        $currentUser = Auth::user();
    	$chamber = Chamber::with('user')->with('company')->where('id', $request->input('hide_id'))->first();
        if($currentUser){ 
        	$mps_info = explode('||', $request->input("payment_method"));
           	$chamber->include_pph = $request->has('is_pph') ? 1 : 0;
           	$chamber->payment_method = $mps_info[2] == "atm" ? 1 : 2;

            if($chamber){
    			$data = [
                    "draft_id" => $chamber->PO_ID,
                    "include_pph" => $request->has('is_pph') ? true : false,
                    "created" => [
                        "by" => $currentUser->name,
                        "reference_id" => '1'
                    ],
                    "config" => [
                        "kode_wapu" => "01",
                        "afiliasi" => "non-telkom",
                        "tax_invoice_text" => $chamber->start_date,
                        "payment_method" => $mps_info[2] == "atm" ? "internal" : "mps",
                    ],
                    "mps" => [
                        "gateway" => $mps_info[0],
                        "product_code" => $mps_info[1],
                        "product_type" => $mps_info[2],
                        "manual_expired" => 1440
                    ]
                ];

                $billing = $this->api_billing($data);
                // dd($billing);

                $chamber->BILLING_ID = $billing && $billing->status == true ? $billing->data->_id : null;
                if($mps_info[2] != "atm"){
                	$chamber->VA_name = $mps_info ? $mps_info[3] : null;
                    $chamber->VA_image_url = $mps_info ? $mps_info[4] : null;
                    $chamber->VA_number = $billing && $billing->status == true ? $billing->data->mps->va->number : null;
                    $chamber->VA_amount = $billing && $billing->status == true ? $billing->data->mps->total_amount : null;
                    $chamber->VA_expired = $billing && $billing->status == true ? $billing->data->mps->va->expired : null;
                }

                if(!$chamber->VA_number){
                    Session::flash('error', 'Failed to generate '.$mps_info[3].', please choose another bank!');
                    $chamber->PO_ID = $this->regeneratePO($chamber);
                    $chamber->BILLING_ID = null;
					$chamber->include_pph = 0;
					$chamber->payment_method = 0;
					$chamber->VA_name = null;
					$chamber->VA_image_url = null;
					$chamber->VA_number = null;
					$chamber->VA_amount = null;
					$chamber->VA_expired = null;
                    $chamber->save();
                    return back();
                }
            }

            try{
                $chamber->save();
                return redirect('payment_confirmation_chamber/'.$chamber->id);
            } catch(\Illuminate\Database\QueryException $e){
                dd($e);
                Session::flash('error', 'Failed To Checkout');
                return back();
            }
        }else{
           return back();
        } 
        
    }

    public function payment_confirmation($id)
    { 
        $currentUser = Auth::user();

        if($currentUser){
            $chamber = Chamber::where('id', $id)->get();
            if($chamber[0]->payment_method == 0){
				return redirect('chamber_history/'.$id.'/pembayaran');
			}
            return view('client.chamber.payment_confirmation') 
            ->with('data', $chamber);
        }else{
           return redirect("login");
        }
        
    } 

    public function api_billing($data){
        $client = new Client([
            'headers' => ['Content-Type' => 'application/json', 
                            'Authorization' => config("app.gateway_tpn_3")
                        ],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
        ]);
        try {
            $params['json'] = $data;
            $res_billing = $client->post("v3/billings", $params)->getBody();
            $billing = json_decode($res_billing);

            return $billing;
        } catch(Exception $e){
            return null;
        }
    }

    public function api_resend_va($id){
        $chamber = Chamber::find($id);
        $client = new Client([
            'headers' => ['Content-Type' => 'application/json', 
                            'Authorization' => config("app.gateway_tpn_3")
                        ],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
        ]);
        try {
            $res_resend = $client->post("v3/billings/mps/resend/".$chamber->BILLING_ID)->getBody();
            $resend = json_decode($res_resend);
            if($resend){
                $chamber->VA_number = $resend && $resend->status == true ? $resend->data->mps->va->number : null;
                $chamber->VA_amount = $resend && $resend->status == true ? $resend->data->mps->total_amount : null;
                $chamber->VA_expired = $resend && $resend->status == true ? $resend->data->mps->va->expired : null;
                
                $chamber->save();
            }
                        
            return redirect('/payment_confirmation_chamber/'.$id);
        } catch(Exception $e){
            return null;
        }
    }

    public function api_cancel_va($id){
    	$currentUser = Auth::user();

        if($currentUser){
	        $chamber = Chamber::find($id);
	        if($chamber->BILLING_ID){
				$data_cancel_billing = [
	            	"canceled" => [
						"message" => "-",
						"by" => $currentUser->name,
	                	"reference_id" => '1'
					]
	            ];
				$this->api_cancel_billing($chamber->BILLING_ID, $data_cancel_billing);
			}

			$chamber->PO_ID = $this->regeneratePO($chamber);
	        $chamber->BILLING_ID = null;
			$chamber->include_pph = 0;
			$chamber->payment_method = 0;
			$chamber->VA_name = null;
			$chamber->VA_image_url = null;
			$chamber->VA_number = null;
			$chamber->VA_amount = null;
			$chamber->VA_expired = null;

			$chamber->save();

	        Session::flash('message', "Please choose another bank. If you leave or move to another page, your process will not be saved!");
	        return redirect('chamber_history/'.$id.'/pembayaran');
		}
    }

    public function api_cancel_billing($BILLING_ID,$data){
        $client = new Client([
            'headers' => ['Content-Type' => 'application/json', 
                            'Authorization' => config("app.gateway_tpn_3")
                        ],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false
        ]);
        try {
            $params['json'] = $data;
            $res_cancel_billing = $client->put("v3/billings/".$BILLING_ID."/cancel", $params)->getBody();
            $cancel_billing = json_decode($res_cancel_billing);

            return $cancel_billing;
        } catch(Exception $e){
            return null;
        }
    }

    public function regeneratePO($chamber){
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

        return $purchase && $purchase->status ? $purchase->data->_id : null;
    }

    public function api_purchase($data){
        $client = new Client([
            'headers' => ['Content-Type' => 'application/json', 
                            'Authorization' => config("app.gateway_tpn_3")
                        ],
            'base_uri' => config("app.url_api_tpn"),
            'timeout'  => 60.0,
            'http_errors' => false,
            'verify' => false
        ]);
        try {
            
            $params['json'] = $data;
            $res_purchase = $client->post("v3/draftbillings", $params)->getBody();
            $purchase = json_decode($res_purchase);

            return $purchase;
        } catch(Exception $e){
            return null;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pricePerDay = 5000000;
        $dates = (json_decode( $request->dates));
        $currentUser = Auth::user();
        if (!$currentUser){ return redirect('login');}
        $serializeDates = [];
        
        foreach ($dates as $date){ array_push($serializeDates, Carbon::createFromFormat('Ymd', $date)->format('Y-m-d'));}
        $response = [
            'success' => false,
            'message' => 'The chamber has been booked'
        ];

        $isChamberBooked = Chamber_detail::whereIn('date', $serializeDates)->count();
        if ($isChamberBooked){
            return $response;
        }
        $price = $pricePerDay*count($serializeDates);

        $chamber = new Chamber;
        $chamber->id = Uuid::uuid4();
        $chamber->user_id = $currentUser->id;
        $chamber->company_id = $currentUser->company_id;
        $chamber->invoice = $this->getNextInvoice();
        $chamber->start_date = $serializeDates[0];
        $chamber->end_date = end($serializeDates);
        $chamber->duration = count($serializeDates);
        $chamber->price = $price;
        $chamber->tax = $price*0.1;
        $chamber->total = $price*1.1;
        $chamber->created_by = $currentUser->id;
        $chamber->save();

        $chamberDetails = [];
        foreach ($serializeDates as $date){
            $chamberDetail = new Chamber_detail;
            $chamberDetail->date = $date;
            $chamberDetail->chamber_id = $chamber->id;
            $chamberDetail->save();
        }

        $response['success'] = true;
        $response['message'] = "You have successfully rent the chamber";

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cetakTiket($id)
    {
        $chamberService = new ChamberService();
        $chamberService->createPdf($id);
    }


    private function getNextInvoice()
    {
        $record = 1;
        $year = date("Y");        
        $months = ['','I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $romanstMonth = $months[(int) date('m')];

        $lastChamber = Chamber::orderBy('created_at', 'desc')->first();
        if ( $lastChamber && $year == substr($lastChamber->invoice, -4) ){
            $record = (int) substr($lastChamber->invoice, 5,4)+1;
        }

        //format = CHMB 0001/VII/2021
        return "CHMB ".sprintf('%04d', $record)."/$romanstMonth/$year";
    }
}
