<?php
namespace App\Http\Controllers;

// Laravel
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;

// Services
use App\Services\Querys\QueryFilter;
use App\Services\Logs\LogService;

// Models
use App\Chamber;

class ChamberAdminController extends Controller
{

    private $request;

    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index(Request $request)
    {
        // Initial var
        $paginate = 2;
        $search = trim(strip_tags($request->input('search','')));
        $statuses = ['unpaid', 'paid', 'delivered'];
        $this->request = $request;

        // Get data
        $rentChamber = new \stdClass();        
        $rentChamber->unpaid = $this->getChamberByPaymentStatus(0)->paginate($paginate, ['*'], 'pageUnpaid');
        $rentChamber->paid = $this->getChamberByPaymentStatus(2)->paginate($paginate, ['*'], 'pagePaid');
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
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }


    private function getChamberByPaymentStatus($paymentStatus = 0)
    {
        // Initial
        $logService = new LogService();
        $search = $this->request->input('search', '');
        $dataRentChambers = DB::table('chamber')
            ->join('companies', 'companies.id', '=', 'chamber.company_id')
            ->select('chamber.id as id', 'chamber.start_date as start_date', 'chamber.invoice as invoice', 'chamber.total as total', 'chamber.payment_status as payment_status', 'companies.name as company_name')
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
