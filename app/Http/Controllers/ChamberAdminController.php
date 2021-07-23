<?php
namespace App\Http\Controllers;

// Laravel
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
        $paginate = 1;
        $search = trim(strip_tags($request->input('search','')));
        $statuses = ['unpaid', 'paid', 'delivered'];

        // Get data
        $rentChamber = new \stdClass();
        $rentChamber->unpaid = Chamber::paginate($paginate, ['*'], 'pageUnpaid');
        $rentChamber->paid = Chamber::paginate($paginate, ['*'], 'pagePaid');
        $rentChamber->delivered = Chamber::paginate($paginate, ['*'], 'pageDelivered');
        
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

}
