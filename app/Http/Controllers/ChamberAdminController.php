<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Chamber;

class ChamberAdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index(Request $request)
    {
        //initial var
        $paginate = 1;
        $search = trim(strip_tags($request->input('search','')));
        $statuses = ['unpaid', 'paid', 'delivered'];

        $rentChamber = new \stdClass();
        $rentChamber->unpaid = Chamber::paginate($paginate, ['*'], 'pageUnpaid');
        $rentChamber->paid = Chamber::paginate($paginate, ['*'], 'pagePaid');
        $rentChamber->delivered = Chamber::paginate($paginate, ['*'], 'pageDelivered');
        
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
