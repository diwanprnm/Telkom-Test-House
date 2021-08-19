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
        $chamber = Chamber::find($id);
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
