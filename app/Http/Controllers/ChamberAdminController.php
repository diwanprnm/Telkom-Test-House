<?php
namespace App\Http\Controllers;

// Laravel
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;

// Services
use App\Services\Querys\QueryFilter;

// Models
use App\Chamber;

class ChamberAdminController extends Controller
{

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
        return DB::table('chamber')
            ->join('companies', 'companies.id', '=', 'chamber.company_id')
            ->select('chamber.start_date as start_date', 'chamber.invoice as invoice', 'chamber.total as total', 'chamber.payment_status as payment_status', 'companies.name as company_name')
            ->where('chamber.payment_status', $paymentStatus)
        ;
    }

}
